<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 03.10.2016
 * Time: 16:30
 */

namespace Application\Controller;


use Application\Entity\File;
use Application\Entity\User;
use Application\Form\RegisterForm;
use Application\Functions\Generator;
use Application\Functions\OtherFunctions;
use Application\Functions\UserPassword;
use Application\Model\RegisterModel;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Google_Client;
use Google_Service_Plus;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em = null;
    protected $storage;
    protected $authService;
    protected $otherFunctions;
    protected $translate;

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');
        $this->otherFunctions = new OtherFunctions();
        $this->translate = $this->getServiceLocator()->get('Translator');
        $this->loggerLocation = $this->getEvent()->getRouteMatch()->getParams()['controller'] . '|' . $this->getEvent()->getRouteMatch()->getParams()['action'] . ': ';

        return parent::dispatch($request, $response);
    }

    public function callbackGoogleAction()
    {
        $clientInfo = $this->otherFunctions->getSocial()['front'];
//        GOOGLE
        $clientGoogle = new Google_Client();
        $clientGoogle->setClientId($clientInfo['Google']['id']);
        $clientGoogle->setClientSecret($clientInfo['Google']['secret']);
        $clientGoogle->setRedirectUri($clientInfo['Google']['redirect']);
        $clientGoogle->setScopes('email');

        $plus = new Google_Service_Plus($clientGoogle);

        if (isset($_GET['code'])) {
            $clientGoogle->authenticate($_GET['code']);
            $_SESSION['google']['access_token'] = $clientGoogle->getAccessToken();
            $this->redirect()->refresh();
        }

        if (isset($_SESSION['google']['access_token']) && $_SESSION['google']['access_token']) {
            $clientGoogle->setAccessToken($_SESSION['google']['access_token']);
            $user = $plus->people->get('me');

            $this->registerSocial($user['displayName'], $user['emails'][0]['value'], $user->getImage()->getUrl(), 'google');
        }
    }

    public function registerSocial($name, $email, $image, $provider)
    {
        $code = new Generator();
        $dbUser = $this->em->getRepository('Application\Entity\User')->findOneBy(array('name' => $name, 'email' => $email));
        $hash = $code->string(20);
        $token = $code->string(30);
        if (!$dbUser) {
            $userPassword = new UserPassword();
            $dbUser = new User();
            $dbUser->setName($name);
            $dbUser->setEmail($email);
            $dbUser->setAddDate(new \DateTime(date('d.m.Y H:i:s')));
            $dbUser->setToken($token);
            $dbUser->setIsAccepted(1);
            if ($provider == 'google') {
                $dbUser->setGoogle(1);
            } elseif ($provider == 'facebook') {
                $dbUser->setFacebook(1);
            } else {
                $dbUser->setLocal(1);
            }

            $this->em->persist($dbUser);
            $this->em->flush();

            $dbUser = $this->em->getRepository('Application\Entity\User')->findOneBy(array('name' => $name, 'email' => $email));
            $avatar = new File();
            $avatar->setAuthor($dbUser);
            $avatar->setName($dbUser->getName() . '_img');
            $avatar->setPath($image);
            $avatar->setDateAdd(new \DateTime(date('d.m.Y H:i:s')));
            $avatar->setHash($hash);
            $this->em->persist($avatar);
            $this->em->flush();
            $avatar = $this->em->getRepository('Application\Entity\File')->findOneBy(array('path' => $image));
            $dbUser->setAvatarId($avatar);
            $dbUser->setPassword($userPassword->create($dbUser->getId()));
            $this->em->persist($dbUser);
            $this->em->flush();
        } else {
            $dbUser->setEditDate(new \DateTime(date('d.m.Y H:i:s')));
            $dbUser->setToken($token);
            if ($provider == 'google') {
                $dbUser->setGoogle(1);
            } elseif ($provider == 'facebook') {
                $dbUser->setFacebook(1);
            } else {
                $dbUser->setLocal(1);
            }

            $this->em->persist($dbUser);
            $this->em->flush();
        }
        $dbUser = $this->em->getRepository('Application\Entity\User')->findOneBy(array('name' => $name, 'email' => $email));
        $this->auth($dbUser->getId(), $dbUser->getName(), $dbUser, $provider, null);
    }

    public function auth($pass, $name, $user, $provider, $ajax = null)
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $userPassword = new UserPassword();
        $encryptPass = $userPassword->create($pass);

        $adapter = $authService->getAdapter();
        $adapter->setIdentity($name);
        $adapter->setCredential($encryptPass);
        $authResult = $authService->authenticate();
        if ($authResult->isValid()) {
            $user->setLastLoginDate(new \DateTime(date('d.m.Y H:i:s')));
            if ($user->getIsAdmin() == 1) {
                $session = new Container('Admin');
                $session->offsetSet('admin', $name);
                $session->offsetSet('provider', $provider);
            } else {
                $session = new Container('User');
                $session->offsetSet('user', $name);
                $session->offsetSet('provider', $provider);
            }
        } else {
            if($ajax) {
                return false;
            } else {
                return $this->redirect()->toRoute('app');
            }
        }
        $this->em->persist($user);
        $this->em->flush();
        if($ajax) {
            return true;
        } else {
            return $this->redirect()->toRoute('app');
        }
    }

    public function callbackFacebookAction()
    {
        $clientInfo = $this->otherFunctions->getSocial()['front'];

        $clientFacebook = new Facebook(array(
            'app_id' => $clientInfo['Facebook']['id'],
            'app_secret' => $clientInfo['Facebook']['secret'],
            'default_graph_version' => 'v2.5',
        ));
        $helper = $clientFacebook->getRedirectLoginHelper();

        try {
            $_SESSION['facebook']['access_token'] = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            die('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            die('Facebook SDK returned an error ' . $e->getMessage());
        }

        if (isset($_SESSION['facebook']['access_token'])) {
            $clientFacebook->setDefaultAccessToken($_SESSION['facebook']['access_token']);
            $response = $clientFacebook->get('/me?fields=email,name');
            $user = $response->getGraphUser();
            $picture = $clientFacebook->get('/me/picture?type=large&redirect=false');
            $picture = $picture->getGraphObject();

            $this->registerSocial($user->getName(), $user->getEmail(), $picture['url'], 'facebook');
        }
    }

    public function logoutAction()
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authService->clearIdentity();

        $session = new Container('admin');
        $session2 = new Container('user');
        $session->getManager()->getStorage()->clear('Admin');
        $session2->getManager()->getStorage()->clear('User');

        $_SESSION['google'] = null;
        $_SESSION['facebook'] = null;

        return $this->redirect()->toRoute('app');
    }

    public function registerAction() {
        $data = $this->params()->fromPost();
        $code = new Generator();
        $cryptPass = new UserPassword();
        $token = $code->string(30);
        $form = new RegisterForm();
        $model = new RegisterModel();

        $form->setInputFilter($model->getInputFilter());
        $form->setData($data);
        if($form->isValid()) {
            $data['password'] = $cryptPass->create($data['password']);

            $user = new User();

            $user->setName($data['login']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setToken($token);
            $user->setLocal(1);
            $user->setAddDate(new \DateTime(date('d.m.Y H:i:s')));

//            $this->em->persist($user);
//            $this->em->flush();

            $mail = new Message();
            $mail->setEncoding("UTF-8");
            $mail->setBody('Registered user ' . $data['name'] . ' click into this url to end register ' . $this->host() . 'admin/auth/check/' . $token)
                ->setFrom('admin@mleczkop.nazwa.pl')
                ->addTo($data['email'])
                ->setSubject('Witamy');
            $transport = new Sendmail('admin@mleczkop.nazwa.pl');
            $transport->send($mail);

            $response = array(
                'code' => 1,
                'toast' => array(
                    'type' => 'success',
                    'title' => $this->translate->translate('Success!', 'default', $_SESSION['translate']),
                    'message' => $this->translate->translate('To given email address was sent an activation link.', 'default', $_SESSION['translate'])
                )
            );
        } else {
            $response = array(
                'code' => 0,
                'toast' => array(
                    'type' => 'warning',
                    'title' => $this->translate->translate('ERROR!', 'default', $_SESSION['translate']),
                    'message' => $this->translate->translate('Not correct values.', 'default', $_SESSION['translate'])
                )
            );
        }
        return new JsonModel($response);
    }

    public function loginAction() {
        $data = $this->params()->fromPost();

        $user = $this->em->getRepository('Application\Entity\User')->findOneBy(array('name' => $data['login'], 'local' => 1));
        $auth = $this->auth($data['password'], $data['login'], $user, 'local', true);
        if ($auth) {
            $viewRender = $this->getServiceLocator()->get('ViewRenderer');
            $viewModel = new ViewModel();
            $viewModel->setTemplate('application/index/partial/account-partials/loged-partial.phtml');
            $html = $viewRender->render($viewModel);

            $response = array(
                'html' => $html,
                'toast' => array(
                    'type' => 'success',
                    'title' => $this->translate->translate('Success!', 'default', $_SESSION['translate']),
                    'message' => $this->translate->translate('Able to login', 'default', $_SESSION['translate']),
                ),
            );
        } else {
            $response = array(
                'toast' => array(
                    'type' => 'danger',
                    'title' => $this->translate->translate('ERROR!', 'default', $_SESSION['translate']),
                    'message' => $this->translate->translate('Unable to login', 'default', $_SESSION['translate']),
                ),
            );
        }

        $response['auth'] = $auth;

        return new JsonModel($response);
    }
}