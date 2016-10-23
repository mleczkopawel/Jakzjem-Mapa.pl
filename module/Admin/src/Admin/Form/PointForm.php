<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 19.09.2016
 * Time: 19:11
 */

namespace Admin\Form;

use Zend\Form\Form;

class PointForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('point');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'lat',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'lon',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'datasm',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'databg',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'tags',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'vote',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'adres',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent',
                'style' => 'width: 90%; margin: 0 5%',
            ),
        ));
    }

}