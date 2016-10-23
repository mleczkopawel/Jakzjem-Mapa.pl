<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 14:49
 */

namespace Admin\Form;

use Zend\Form\Form;

class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'type' => 'Hidden',
            'attributes' => array(
                'class' => 'js-hiden-login',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'loginSubmit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Login',
                'class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent js-login-submit',
                'style' => 'width:100%',
                'disabled' => true,
            ),
        ));
    }

}