<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{

    public function __construct()
    {
//        error_reporting(0);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'EntityManager' => function ($sm) {
                    $em = $sm->get('doctrine.entitymanager.orm_default');
                    return $em;
                },
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'beforeDispatch',
        ), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'afterDispatch',
        ), -100);
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator->addTranslationFile('phpArray', 'vendor/zendframework/zend-i18n-resources/languages/pl/Zend_Validate.php', 'default', 'pl_PL');
    }

    function beforeDispatch(MvcEvent $event)
    {
        $response = $event->getResponse();
        $whiteList = array(
//            AUTH-BACK
            'Admin\Controller\Auth-login',
            'Admin\Controller\Auth-register',
            'Admin\Controller\Auth-checkName',
            'Admin\Controller\Auth-check',
            'Admin\Controller\Auth-callbackGoogle',
            'Admin\Controller\Auth-callbackFacebook',
//            INDEX
            'Application\Controller\Index-index',
            'Application\Controller\Index-getByLocale',
            'Application\Controller\Index-getPoint',
            'Application\Controller\Index-setToSession',
            'Application\Controller\Index-init',
//            AUTH-FRONT
            'Application\Controller\Auth-callbackGoogle',
            'Application\Controller\Auth-callbackFacebook',
            'Application\Controller\Auth-register',
            'Application\Controller\Auth-login',
//            API
            'Api\Controller\Localization-',
            'Api\Controller\User-',
        );

        $controller = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');
        $requestedResource = $controller . '-' . $action;


        $session = new Container('Admin');
        $session2 = new Container('User');

        if ($controller = 'Admin\Controller\Index') {
            if ($session->offsetExists('admin')) {
            } elseif ($session2->offsetExists('user')) {
            } else {
                if ($requestedResource != 'Admin\Controller\Auth-login' && !in_array($requestedResource, $whiteList)) {
                    $url = 'admin/auth/login';
                    $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                    $response->setStatusCode(302);
                }
                $response->sendHeaders();
            }
        }
    }

    function afterDispatch(MvcEvent $event)
    {

    }
}
