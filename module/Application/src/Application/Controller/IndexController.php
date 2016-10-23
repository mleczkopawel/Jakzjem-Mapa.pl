<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Entity\MapLocalization;
use Application\Entity\StatsSearch;
use Application\Form\LoginForm;
use Application\Form\PointForm;
use Application\Form\RegisterForm;
use Application\Functions\OtherFunctions;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Google_Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em = null;
    protected $translate;
    protected $otherFunctions;

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');
        $this->otherFunctions = new OtherFunctions();
        return parent::dispatch($request, $response);
    }

    public function indexAction()
    {
        $clientInfo = $this->otherFunctions->getSocial()['front'];

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

//        MAP
        $_SESSION['translate_echo'] = $_SESSION['translate_echo'] ? $_SESSION['translate_echo'] : 'ENG';
        $_SESSION['translate'] = $_SESSION['translate'] ? $_SESSION['translate'] : 'pl_PL';
        $this->translate = $this->getServiceLocator()->get('Translator');
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $params = $this->params()->fromPost('search');
        $local = $this->params()->fromRoute('local');
        $loginForm = new LoginForm();
        $registerForm = new RegisterForm();
        if (!$local)
            $local = 'krakow';

        $_SESSION['locale'] = $local;
        $_SESSION['show_locale'] = $this->em->getRepository('Application\Entity\LocalCenter')->findOneBy(array('name' => $local))->getShowName();

        $local2 = $this->em->getRepository('Application\Entity\LocalCenter')->findOneBy(array('name' => $_SESSION['locale']));
        $lat = $local2->getLat();
        $lon = $local2->getLon();
        $localId = $local2->getId();

        if ($this->request->isXmlHttpRequest()) {

            if ($params != NULL) {
                $loc = $this->em->getRepository('Application\Entity\MapLocalization')->getSearch($params, $local2);
            } elseif ($params == NULL) {
                $loc = $this->em->getRepository('Application\Entity\MapLocalization')->findBy(array('town' => $local2, 'isActive' => 1));
            }
            $i = 0;

            $dataa = array();

            foreach ($loc as $item) {
                if ($item->getTown()->getId() == $localId) {
                    $dataa[$i]['id'] = $item->getIdloc();
                    $dataa[$i]['lat'] = $item->getLat();
                    $dataa[$i]['lon'] = $item->getLon();
                    $dataa[$i]['nazwa'] = $item->getNazwa();
                    $dataa[$i]['datasm'] = $item->getDatasm();
                    $dataa[$i]['tagi'] = $item->getTagi();
                    $i++;
                }
            }

            if ($loc) {
                $viewModel = new ViewModel(array(
                    'points' => $loc,
                ));
                $viewModel->setTemplate('application/index/partial/found-partial.phtml');
                $html = $viewRender->render($viewModel);
                return new JsonModel(array(
                    'message' => array(
                        'type' => 'success',
                        'title' => $this->translate->translate('Yey!', 'default', $_SESSION['translate']),
                        'context' => $this->translate->translate('I can find', 'default', $_SESSION['translate']) . ' <b>\'' . $params . '\'</b>',
                        'error' => 0,
                    ),
                    'data' => $dataa,
                    'html' => $html,
                ));
            } else {
                return new JsonModel(array(
                    'message' => array(
                        'type' => 'danger',
                        'title' => $this->translate->translate('Bad!', 'default', $_SESSION['translate']),
                        'context' => $this->translate->translate('I can\'t find', 'default', $_SESSION['translate']) . ' <b>\'' . $params . '\'</b>',
                        'error' => 1,
                    ),
                    'data' => $dataa,
                ));
            }
        } else {
            $message = array(
                'type' => 'info',
                'title' => 'Info!',
                'context' => $this->translate->translate('I do not nothing...', 'default', $_SESSION['translate']),
                'error' => 1,
            );

            $message = json_encode($message);

            return new ViewModel(array(
                'form' => new PointForm(),
                'message' => $message,
                'lat' => $lat,
                'lon' => $lon,
                'localId' => $localId,
                'loginForm' => $loginForm,
                'registerForm' => $registerForm,
                'social' => array(
                    'google' => $googleLoginUrl,
                    'facebook' => $facebookLoginUrl,
                ),
            ));
        }
    }

    public function translateAction()
    {
        if ($_SESSION['translate'] == 'pl_PL') {
            $_SESSION['translate'] = 'en_US';
            $_SESSION['translate_echo'] = 'PL';
        } else {
            $_SESSION['translate'] = 'pl_PL';
            $_SESSION['translate_echo'] = 'ENG';
        }

        $this->redirect()->toRoute('app');
    }

    public function getByLocaleAction()
    {
        $locale = $this->params()->fromPost('locale');
        $local = $this->em->getRepository('Application\Entity\LocalCenter')->find($locale);

        $names = $this->em->getRepository('Application\Entity\MapLocalization')->findBy(array('town' => $local, 'isActive' => 1));

        foreach ($names as $nameAdr) {
            $pointName[] = $nameAdr->getNazwa() . ', ' . $nameAdr->getAdres();
        }

        foreach ($names as $name) {
            $nameN[] = $name->getNazwa();
        }

        $nameN = array_unique($nameN);

        foreach ($nameN as $name)
            $pointName[] = $name;

        foreach ($names as $tags) {
            $expName = explode(',', $tags->getTagi());
            foreach ($expName as $name)
                $tagName[] = $name;
        }

        $tagName = array_unique($tagName);

        foreach ($tagName as $tag)
            $pointName[] = $tag;

        return new JsonModel($pointName);
    }

    public function getPointAction()
    {
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $pointId = $this->params()->fromPost('point');
        $point = $this->em->getRepository('Application\Entity\MapLocalization')->find($pointId);

        $viewModel = new ViewModel(array(
            'point' => $point,
        ));
        $viewModel->setTemplate('application/index/partial/checked-partial.phtml');
        $html = $viewRender->render($viewModel);

        return new JsonModel(array(
            'html' => $html
        ));
    }

    public function setToSessionAction()
    {
        $lat = $this->params()->fromPost('lat');
        $lon = $this->params()->fromPost('lon');

        $_SESSION['pos_local'] = array(
            'lat' => $lat,
            'lon' => $lon
        );

        return new JsonModel();
    }

    public function addNewPointAction()
    {
        $data = array(
            'name' => $this->params()->fromPost('name'),
            'tags' => $this->params()->fromPost('tags'),
            'adres' => $this->params()->fromPost('adres'),
            'url' => $this->params()->fromPost('url'),
            'phone' => $this->params()->fromPost('phone'),
            'lat' => $this->params()->fromPost('lat'),
            'lon' => $this->params()->fromPost('lon'),
            'town' => $this->params()->fromPost('town'),
        );
        $date = date('d.m.Y h:i:s');

        $data['town'] = $this->em->getRepository('Application\Entity\LocalCenter')->findOneBy(array('lat' => $data['town']['lat'], 'lon' => $data['town']['lon']));

        $point = new MapLocalization();
        $point->setNazwa($data['name']);
        $point->setLat($data['lat']);
        $point->setLon($data['lon']);
        $point->setTagi($data['tags']);
        $point->setAdres($data['adres']);
        $point->setUrl($data['url']);
        $point->setTelephone($data['phone']);
        $point->setTown($data['town']);
        $point->setDateAdd(new \DateTime($date));
        $point->setIsActive(0);

        $this->em->persist($point);
        $this->em->flush();

        return new JsonModel(array(
            'code' => 1,
            'name' => $data['name'],
        ));
    }

    public function sendToStatsAction()
    {
        $value = $this->params()->fromPost('value');
        $stats = $this->em->getRepository('Application\Entity\StatsSearch')->findOneBy(array('name' => $value));
        if (!$stats) {
            $stats = new StatsSearch();
            $stats->setName($value);
            $stats->setCount(1);
        } else {
            $stats->setCount($stats->getCount() + 1);
        }

        $this->em->persist($stats);
        $this->em->flush();

        return new JsonModel();
    }
}
