<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 19.09.2016
 * Time: 19:17
 */

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class PointModel implements InputFilterAwareInterface
{

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
                'name' => 'lat',
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
                'name' => 'lon',
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
        // TODO: Implement setInputFilter() method.
    }
}