<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 14:14
 */

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Form\RegisterForm;
use Admin\Model\LoginModel;
use Admin\Model\RegisterModel;
use Application\Entity\File;
use Application\Entity\User;
use Application\Functions\Generator;
use Application\Functions\OtherFunctions;
use Application\Functions\UserPassword;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Google_Client;
use Google_Service_Plus;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
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

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');
        $this->otherFunctions = new OtherFunctions();
        $this->loggerLocation = $this->getEvent()->getRouteMatch()->getParams()['controller'] . '|' . $this->getEvent()->getRouteMatch()->getParams()['action'] . ': ';

        return parent::dispatch($request, $response);
    }

    public function indexAction()
    {
        $this->template();

        $this->redirect()->toRoute('admin/auth/login');

        return new ViewModel();
    }

    public function template()
    {
        $this->layout('login/layout');
    }

    public function loginAction()
    {
        $clientInfo = $this->otherFunctions->getSocial()['back'];

//        GOOGLE
        $clientGoogle = new Google_Client();
        $clientGoogle->setClientId($clientInfo['Google']['id']);
        $clientGoogle->setClientSecret($clientInfo['Google']['secret']);
        $clientGoogle->setRedirectUri($clientInfo['Google']['redirect']);
        $clientGoogle->setScopes('email');

        $googleLoginUrl = $clientGoogle->createAuthUrl();

//        FACEBOOK
        $clientFacebook = new Facebook(array(
            'app_id' => $clientInfo['Facebook']['id'],
            'app_secret' => $clientInfo['Facebook']['secret'],
            'default_graph_version' => 'v2.5',
        ));
        $facebookRedirect = $clientInfo['Facebook']['redirect'];
        $helper = $clientFacebook->getRedirectLoginHelper();

        try {
            $_SESSION['facebook']['access_token'] = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            die('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            die('Facebook SDK returned an error ' . $e->getMessage());
        }

        if (!isset($_SESSION['facebook']['access_token'])) {
            $permission = ['email'];
            $facebookLoginUrl = $helper->getLoginUrl($facebookRedirect, $permission);
        }
//        LOCALE
        $_SESSION['count_in_admin'] = 0;
        $this->template();
        $request = $this->getRequest();
        $form = new LoginForm('user');
        $filter = new LoginModel();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();

                $user = $this->em->getRepository('Application\Entity\User')->findOneBy(array('name' => $data['name']));
                if ($user->getIsAccepted() == 1) {
                    $this->auth($data['password'], $data['name'], $user, 'local');
                } else {
                    return $this->redirect()->toRoute('admin');
                }
            } else {
            }
        } else {
        }
//        END LOCALE
        return new ViewModel(array(
            'form' => $form,
            'social' => array(
                'google' => $googleLoginUrl,
                'facebook' => $facebookLoginUrl,
            ),
        ));
    }

    public function auth($pass, $name, $user, $provider)
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
            return $this->redirect()->toRoute('app');
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->redirect()->toRoute('admin');
    }

    public function registerAction()
    {
        $this->template();
        $form = new RegisterForm();
        $model = new RegisterModel();
        $request = $this->getRequest();
        $code = new Generator();
        $cryptPass = new UserPassword();

        if ($request->isPost()) {
            $data = $request->getPost();

            $form->setInputFilter($model->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                $data['password'] = $cryptPass->create($data['password']);
                $token = $code->string(30);

                $user = new User();

                $user->setAddDate(new \DateTime(date('d.m.Y H:i:s')));
                $user->setName($data['login']);
                $user->setEmail($data['email']);
                $user->setPassword($data['password']);
                $user->setToken($token);
                $user->setLocal(1);

                $this->em->persist($user);
                $this->em->flush();

                $mail = new Message();
                $mail->setEncoding("UTF-8");
                $mail->setBody('Registered user ' . $data['name'] . ' click into this url to end register ' . $this->host() . 'admin/auth/check/' . $token)
                    ->setFrom('admin@mleczkop.nazwa.pl')
                    ->addTo($data['email'])
                    ->setSubject('Witamy');
                $transport = new Sendmail('admin@mleczkop.nazwa.pl');
                $transport->send($mail);

                return $this->redirect()->toRoute('app');
            }
        }
        return new ViewModel(array(
            'form' => $form,
        ));
    }

    private function host()
    {
        $host = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $baseurl = "http://" . $host . $path . "/";

        return $baseurl;
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

        return $this->redirect()->toRoute('admin/auth/login');
    }

    public function checkNameAction()
    {
        $login = $this->params()->fromPost('name');
        $email = $this->params()->fromPost('email');

        if ($login)
            $names = $this->em->getRepository('Application\Entity\User')->findNameOrEmail($login);
        elseif ($email)
            $emails = $this->em->getRepository('Application\Entity\User')->findOneBy(array('email' => $email));
        if ($names) {
            return new JsonModel(array(
                'code' => 1,
                'name' => $names[0],
            ));
        } elseif ($emails) {
            return new JsonModel(array(
                'code' => 1,
                'name' => $emails,
            ));
        } else {
            return new JsonModel(array(
                'code' => 0
            ));
        }
    }

    public function checkAction()
    {
        $this->template();

        $token = $this->params()->fromRoute('token');

        $user = $this->em->getRepository('Application\Entity\User')->findOneBy(array('token' => $token));
        if ($user) {
            $user->setIsAccepted('1');
            $this->em->persist($user);
            $this->em->flush();
            $mess = 'Email potwierdzony.';
        } else {
            $mess = 'Coś pomyliłeś.';
        }

        return new ViewModel(array(
            'mess' => $mess,
        ));
    }

    public function callbackGoogleAction()
    {
        $clientInfo = $this->otherFunctions->getSocial()['back'];

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
        $token = $code->string(30);
        $hash = $code->string(20);
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
        $this->auth($dbUser->getId(), $dbUser->getName(), $dbUser, $provider);
    }

    public function callbackFacebookAction()
    {
        $clientInfo = $this->otherFunctions->getSocial()['back'];

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




    public function json() {
        $data = array(
            'nazwa' => 'ja',
            'wiek' => 20,
            'szkoły' => array(
                'pierwsza' => 'coś tam',
                'druga' => 'coś innego',
            ),
        );

        $data = json_encode($data);


        echo $data;

        return $data;
    }
}