<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 19.09.2016
 * Time: 19:11
 */

namespace Application\Form;

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
                'class' => 'mdl-textfield__input js-add-name',
            ),
        ));

        $this->add(array(
            'name' => 'lat',
            'type' => 'hidden',
            'attributes' => array(
                'class' => 'js-add-lat',
            ),
        ));

        $this->add(array(
            'name' => 'lon',
            'type' => 'hidden',
            'attributes' => array(
                'class' => 'js-add-lon',
            ),
        ));

        $this->add(array(
            'name' => 'tags',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input js-add-tags',
            ),
        ));

        $this->add(array(
            'name' => 'adres',
            'type' => 'text',
            'attributes' => array(
                'class' => 'mdl-textfield__input js-add-adres',
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'type' => 'url',
            'attributes' => array(
                'class' => 'mdl-textfield__input js-add-url',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'number',
            'attributes' => array(
                'class' => 'mdl-textfield__input js-add-phone',
            ),
        ));
    }

}