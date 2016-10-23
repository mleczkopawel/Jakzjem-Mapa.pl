<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.09.2016
 * Time: 14:48
 */

namespace Application\Functions;


class OtherFunctions
{
    public function moveUploadedFile($file, $hash)
    {
        $newPath = ROOT_PATH . '/csv_files/' . $hash . '.csv';
        move_uploaded_file($file['file']['tmp_name'], $newPath);

        return '/csv_files/' . $hash . '.csv';
    }

    public function validate($data)
    {
        if ($data[0] == NULL)
            return array(
                'error' => true,
                'type' => 0,
            );
        elseif ($data[1] == NULL) {
            return array(
                'error' => true,
                'type' => 1,
            );
        } else
            return array(
                'error' => false,
            );
    }

    public function getSocial()
    {
        $config = array(
            'back' => array(
                "Google" => array(
                    "id" => "197140539370-oi3la13u48ebp5hfoepjahdsfb2n4q16.apps.googleusercontent.com",
                    "secret" => "NliXUMXpnCQs44WaupY6IrEU",
                    'redirect' => 'https://jakzjem-mapa.pl/admin/auth/callbackGoogle',
                ),
                'Facebook' => array(
                    'id' => '1782719245331307',
                    'secret' => 'd1a7f4f3bd2660607a0ebb6f8e4fe099',
                    'redirect' => 'https://jakzjem-mapa.pl/admin/auth/callbackFacebook',
                ),
            ),
            'front' => array(
                "Google" => array(
                    "id" => "197140539370-oi3la13u48ebp5hfoepjahdsfb2n4q16.apps.googleusercontent.com",
                    "secret" => "NliXUMXpnCQs44WaupY6IrEU",
                    'redirect' => 'https://jakzjem-mapa.pl/app/auth/callbackGoogle',
                ),
                'Facebook' => array(
                    'id' => '1782719245331307',
                    'secret' => 'd1a7f4f3bd2660607a0ebb6f8e4fe099',
                    'redirect' => 'https://jakzjem-mapa.pl/app/auth/callbackFacebook',
                ),
            )
        );

        return $config;
    }
}