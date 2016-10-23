<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/[:local]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                        'locale' => 'pl_PL',
                        'local' => 'krakow'
                    ),
                    'constrains' => array(
                        'locale' => '[a-zA-Z]{2}_[a-zA-Z]{2}',
                    ),
                ),
            ),
            'app' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/app',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                    'translate' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/translate',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'translate',
                            ),
                        ),
                    ),
                    'addNewPoint' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/addNewPoint',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'addNewPoint',
                            ),
                        ),
                    ),
                    'sendToStats' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/sendToStats',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'sendToStats',
                            ),
                        ),
                    ),
                    'getByLocale' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/getByLocale',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'getByLocale',
                            ),
                        ),
                    ),
                    'getPoint' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/getPoint',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'getPoint',
                            ),
                        ),
                    ),
                    'setToSession' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/setToSession',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'setToSession',
                            ),
                        ),
                    ),
                    'auth' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/auth',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Auth',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'login' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/login',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'login',
                                    ),
                                ),
                            ),
                            'register' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/register',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'register',
                                    ),
                                ),
                            ),
                            'checkName' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/checkName',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'checkName',
                                    ),
                                ),
                            ),
                            'logout' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/logout',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'logout',
                                    ),
                                ),
                            ),
                            'check' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/check/:token',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'check',
                                    ),
                                ),
                            ),
                            'callbackGoogle' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/callbackGoogle',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'callbackGoogle',
                                    ),
                                ),
                            ),
                            'callbackFacebook' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/callbackFacebook',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Auth',
                                        'action' => 'callbackFacebook',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => ROOT_PATH . '/module/language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Auth' => 'Application\Controller\AuthController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layoutload' => __DIR__ . '/../view/layout/load-layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'layout' => 'layout/layout',
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(),
        ),
    ),
);
