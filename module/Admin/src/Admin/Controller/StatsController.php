<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.09.2016
 * Time: 10:26
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StatsController extends AbstractActionController
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

        $stats = $this->em->getRepository('Application\Entity\StatsSearch')->findAll();

        return new ViewModel(array(
            'stats' => $stats,
        ));
    }

    public function template()
    {
        $this->layout('admin/layout');
    }

}