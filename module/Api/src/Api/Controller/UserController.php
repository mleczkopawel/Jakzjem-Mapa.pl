<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 09.10.2016
 * Time: 18:12
 */

namespace Api\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;

class UserController extends AbstractRestfulController
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

    public function create($data)
    {
        var_dump($data);die;
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