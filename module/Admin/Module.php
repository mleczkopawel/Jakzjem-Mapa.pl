<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 08:14
 */

namespace Admin;

class Module
{

    public function __construct()
    {
        error_reporting(0);
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
                'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ),
        );
    }
}