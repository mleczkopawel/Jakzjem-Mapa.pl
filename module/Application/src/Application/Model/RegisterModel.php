<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 04.10.2016
 * Time: 11:30
 */

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RegisterModel implements InputFilterAwareInterface
{

    public $name;
    public $password;
    public $confirmPassword;
    public $email;
    public $confirmEmail;

    protected $inputFilter;

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
            $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
            $isIdentical = \Zend\Validator\Identical::NOT_SAME;

            $inputFilter->add(array(
                'name' => 'login',
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => 'Name can not be empty',
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => 'Email can not be empty',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                $invalidEmail => 'Enter Valid Email Address.'
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'confirm_email',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => 'Email can not be empty',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'email',
                            'messages' => array(
                                $isIdentical => 'Emails must be the same',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                $invalidEmail => 'Enter Valid Email Address.'
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 6,
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => 'Password can not be empty',
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'confirm_password',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 6,
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => 'Password can not be empty',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                $isIdentical => 'Passwords must be the same',
                            ),
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