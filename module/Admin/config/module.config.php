<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 08:19
 */
//var_dump(__DIR__.'/../../Application/language');die;
return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'import' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/import',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action' => 'import',
                            ),
                        ),
                    ),
                    'export' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/export',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action' => 'export',
                            ),
                        ),
                    ),
                    'settings' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/settings',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action' => 'settings',
                            ),
                        ),
                    ),
                    'profile-settings' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/profile-settings',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action' => 'profileSettings',
                            ),
                        ),
                    ),
                    'users' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/users',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Users',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Users',
                                        'action' => 'edit',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:id',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Users',
                                        'action' => 'delete',
                                    ),
                                ),
                            ),
                            'addAdmin' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/addAdmin/:id',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Users',
                                        'action' => 'addAdmin',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'stats' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/stats',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Stats',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'points' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/points[/page[/:page]]',
                            'constrains' => array(
                                'page' => '[0-9]*'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Points',
                                'action' => 'showAll',
                                'page' => 1,
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'import-frame' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/import-frame',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Points',
                                        'action' => 'import-frame',
                                    ),
                                ),
                            ),
                            'upload-file' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/upload-file',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Points',
                                        'action' => 'uploadFile',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'constraints' => array(),
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Points',
                                        'action' => 'edit',
                                    ),
                                ),
                            ),
                            'deactivate' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/deactivate/:id',
                                    'constraints' => array(),
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Points',
                                        'action' => 'deactivate',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:id',
                                    'constraints' => array(),
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Points',
                                        'action' => 'delete',
                                    ),
                                ),
                            ),
                            'refresh' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/refresh',
                                    'constraints' => array(),
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Points',
                                        'action' => 'refresh',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'translate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/translate',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action' => 'translate',
                            ),
                        ),
                    ),
                    'auth' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/auth',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Auth',
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
                                        'controller' => 'Admin\Controller\Auth',
                                        'action' => 'login',
                                    ),
                                ),
                            ),
                            'register' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/register',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Auth',
                                        'action' => 'register',
                                    ),
                                ),
                            ),
                            'checkName' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/checkName',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Auth',
                                        'action' => 'checkName',
                                    ),
                                ),
                            ),
                            'logout' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/logout',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Auth',
                                        'action' => 'logout',
                                    ),
                                ),
                            ),
                            'check' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/check/:token',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Auth',
                                        'action' => 'check',
                                    ),
                                ),
                            ),
                            'callbackGoogle' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/callbackGoogle',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Auth',
                                        'action' => 'callbackGoogle',
                                    ),
                                ),
                            ),
                            'callbackFacebook' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/callbackFacebook',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Auth',
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
    'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\User',
                'identity_property' => 'name',
                'credential_property' => 'password',
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'pl_PL',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => ROOT_PATH . '/module/language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            'Admin\Controller\Points' => 'Admin\Controller\PointsController',
            'Admin\Controller\Stats' => 'Admin\Controller\StatsController',
            'Admin\Controller\Users' => 'Admin\Controller\UsersController',
            'Admin\Controller\SocialAuth' => 'Admin\Controller\SocialAuthController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'admin/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'login/layout' => __DIR__ . '/../view/layout/login.phtml',
            'iframe/layout' => __DIR__ . '/../view/layout/iframe.phtml',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'paginationHelper' => 'Admin\View\Helper\PaginationHelper',
        ),
    ),
);
