<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.09.2016
 * Time: 15:21
 */

namespace Admin\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
{

    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em = null;
    protected $translate;

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');

        return parent::dispatch($request, $response);
    }

    public function indexAction()
    {
        $this->template();

        $users = $this->em->getRepository('Application\Entity\User')->findAll();

        return new ViewModel(array(
            'users' => $users,
        ));
    }

    public function template()
    {
        $this->layout('admin/layout');
    }

    public function editAction()
    {
        $this->template();
        $id = $this->params()->fromRoute('id');
        $user = $this->em->getRepository('Application\Entity\User')->find($id);

        return new ViewModel();
    }

    public function deleteAction()
    {

        return new JsonModel();
    }

    public function addAdminAction()
    {

        return new JsonModel();
    }

}