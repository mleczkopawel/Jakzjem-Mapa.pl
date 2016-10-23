<?php
/**
 * User: Paweł
 * Date: 24.07.2016
 * Time: 21:45
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Localization' => 'Api\Controller\LocalizationController',
            'Api\Controller\User' => 'Api\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'api' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Localization',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'localization' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/localization',
                            'defaults' => array(
                                'controller' => 'Api\Controller\Localization',
                            ),
                        ),
                    ),
                    'user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/user',
                            'defaults' => array(
                                'controller' => 'Api\Controller\User',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);