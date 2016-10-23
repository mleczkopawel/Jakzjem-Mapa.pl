<?php

/**
 * User: Paweł
 * Date: 24.07.2016
 * Time: 21:44
 */

namespace Api;

class Module
{

    public function __construct()
    {
//        error_reporting(0);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'EntityManager' => function ($sm) {
                    $em = $sm->get('doctrine.entitymanager.orm_default');
                    return $em;
                },
            )
        );
    }
}