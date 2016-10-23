<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 23.08.2016
 * Time: 08:41
 */

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class LoginModel implements InputFilterAwareInterface
{

    public $name;
    public $password;
    protected $inputFilter;

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags',
                    ),
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags',
                    ),
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
}