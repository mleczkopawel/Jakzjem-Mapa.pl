<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 09.09.2016
 * Time: 14:03
 */

namespace Admin\Form;

use Zend\Form\Form;

class RegisterForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name = 'register');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'login',
            'type' => 'Hidden',
            'attributes' => array(
                'class' => 'js-register-login',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'attributes' => array(
                'class' => 'mdl-textfield__input js-register-email',
            ),
        ));

        $this->add(array(
            'name' => 'confirm_email',
            'type' => 'email',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
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
            'name' => 'confirm_password',
            'type' => 'password',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'registerSubmit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Register',
                'class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent js-register-submit',
                'style' => 'width:100%',
                'disabled' => true,
            ),
        ));
    }
}