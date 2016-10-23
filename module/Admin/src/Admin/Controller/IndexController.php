<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 08:13
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em = null;
    protected $translate;

    private $typesBugs = array(
        0 => 'Empty Locations',
        1 => 'Empty Name',
    );

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');

        return parent::dispatch($request, $response);
    }

    public function indexAction()
    {
        $this->template();

        return new ViewModel();
    }

    public function template()
    {
        $this->layout('admin/layout');
    }

    public function exportAction()
    {
        $this->template();

        return new ViewModel();
    }

    public function usersAction()
    {
        $this->template();

        return new ViewModel();
    }

    public function profileSettingsAction()
    {
        $this->template();

        return new ViewModel();
    }

    public function settingsAction()
    {
        $this->template();

        return new ViewModel();
    }

    public function translateAction()
    {
        if ($_SESSION['translate'] == 'pl_PL') {
            $_SESSION['translate'] = 'en_US';
            $_SESSION['translate_echo'] = 'PL';
        } else {
            $_SESSION['translate'] = 'pl_PL';
            $_SESSION['translate_echo'] = 'ENG';
        }

        $this->redirect()->toRoute('admin');
    }
}