<?php
/**
 * User: Paweł
 * Date: 24.07.2016
 * Time: 21:48
 */

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class LocalizationController extends AbstractRestfulController
{

    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em = null;
    private $salt = 'ksIdhdbB8^*)';

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');

        return parent::dispatch($request, $response);
    }

    public function getList()
    {
        return new JsonModel(array(
            'result' => 'jej!',
        ));
    }

    public function create($data)
    {
        if ($this->auth($data['auth'])) {
            if ($data['search']) {
                $local = $this->em->getRepository('Application\Entity\LocalCenter')->findOneBy(array('name' => $data['local']));
                $loc = $this->em->getRepository('Application\Entity\MapLocalization')->getSearch($data['search'], $local);
                $i = 0;

                $center = array(
                    'lat' => $local->getLat(),
                    'lon' => $local->getLon(),
                );

                foreach ($loc as $item) {
                    $dataa[$i]['id'] = $item->getIdloc();
                    $dataa[$i]['lat'] = $item->getLat();
                    $dataa[$i]['lon'] = $item->getLon();
                    $dataa[$i]['nazwa'] = $item->getNazwa();
                    $dataa[$i]['tagi'] = $item->getTagi();
                    $dataa[$i]['adres'] = $item->getAdres();
                    $i++;
                }

                if ($loc) {
                    $message = array(
                        'code' => 3,
                        'type' => 'success',
                        'title' => 'Jej!',
                        'context' => 'Znaleziono ' . count($dataa) . ' elementów',
                        'data' => $dataa,
                        'center' => $center,
                    );

                    return new JsonModel($message);
                } else {
                    $message = array(
                        'code' => 2,
                        'type' => 'danger',
                        'title' => 'Żle!',
                        'context' => 'Nie udało się znaleźć \'' . $data['search'] . '\'',
                        'data' => $dataa,
                    );

                    return new JsonModel($message);
                }
            } else if ($data['edit']) {

                $message = array(
                    'code' => 1,
                    'type' => 'info',
                    'title' => 'Info!',
                    'message' => 'Edycja'
                );

                return new JsonModel($message);
            } else if ($data['all']) {
                $local = $this->em->getRepository('Application\Entity\LocalCenter')->findOneBy(array('name' => $data['local']));
                $loc = $this->em->getRepository('Application\Entity\MapLocalization')->findBy(array('isActive' => 1, 'town' => $local));
                $i = 0;

                $center = array(
                    'lat' => $local->getLat(),
                    'lon' => $local->getLon(),
                );

                foreach ($loc as $item) {
                    $dataa[$i]['id'] = $item->getIdloc();
                    $dataa[$i]['lat'] = $item->getLat();
                    $dataa[$i]['lon'] = $item->getLon();
                    $dataa[$i]['nazwa'] = $item->getNazwa();
                    $dataa[$i]['tagi'] = $item->getTagi();
                    $dataa[$i]['adres'] = $item->getAdres();
                    $i++;
                }

                $message = array(
                    'code' => 3,
                    'type' => 'success',
                    'title' => 'Jej!',
                    'context' => 'Znaleziono ' . count($dataa) . ' elementów',
                    'data' => $dataa,
                    'center' => $center
                );

                return new JsonModel($message);
            } else {
                $message = array(
                    'code' => 1,
                    'type' => 'info',
                    'title' => 'Info!',
                    'message' => 'Nic nie robisz!'
                );

                return new JsonModel($message);
            }
        } else {
            $message = array(
                'code' => 0,
                'type' => 'error',
                'title' => 'ERROR!',
                'context' => 'Nie powiodło się uwierzytelnienie',
            );

            return new JsonModel($message);
        }
    }

    public function auth($auth)
    {
        $token = md5($this->salt);

        if ($token == $auth)
            return true;
        else
            return false;
    }
}