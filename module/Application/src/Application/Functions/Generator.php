<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 10.09.2016
 * Time: 18:40
 */

namespace Application\Functions;

class Generator
{
    public function string($len)
    {
        $str['str'] = array(true, true, true, true);
        $letters = 'abcdefghijklmnouprstuwvxyz';
        $values = '';
        $rando = '';

        if ($str['str'][0]) {
            $values .= '0123456789';
        }

        if ($str['str'][1]) {
            $values .= $letters;
        }

        if ($str['str'][2]) {
            $values .= strtoupper($letters);
        }

        for ($i = 0, $length = (strlen($values) - 1); $i < $len; $i++) {
            $rando .= substr($values, mt_rand(0, $length), 1);
        }
        return $rando;
    }
}