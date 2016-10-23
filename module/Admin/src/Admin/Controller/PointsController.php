<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 19.09.2016
 * Time: 18:37
 */

namespace Admin\Controller;

use Admin\Form\PointForm;
use Admin\Model\PointModel;
use Application\Entity\File;
use Application\Entity\MapLocalization;
use Application\Functions\OtherFunctions;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PointsController extends AbstractActionController
{

    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em = null;

    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
    {
        $this->em = $this->getServiceLocator()->get('EntityManager');

        return parent::dispatch($request, $response);
    }

    public function showAllAction()
    {
        $this->template();
        $limit = 10;

        if ($this->request->isXmlHttpRequest()) {
            $page = $this->params()->fromPost('page', 1);
        } else {
            $page = $this->params()->fromRoute('page', 1);
        }

        $offset = !$page ? 0 : ($page - 1) * $limit;

        if ($this->request->isXmlHttpRequest()) {
            $points = $this->em->getRepository('Application\Entity\MapLocalization')->getAll($offset, $limit);
            $viewRender = $this->getServiceLocator()->get('ViewRenderer');
            $viewModel = new ViewModel(array(
                'page' => $page,
                'limit' => $limit,
                'points' => $points
            ));
            $viewModel->setTemplate('admin/points/partial/list-partial.phtml');
            $html = $viewRender->render($viewModel);

            $result = array(
                'success' => 1,
                'html' => $html,
            );

            return new JsonModel($result);
        } else {
            $points = $this->em->getRepository('Application\Entity\MapLocalization')->getAll($offset, $limit);
        }

        return new ViewModel(array(
            'points' => $points,
            'page' => $page,
            'limit' => $limit
        ));
    }

    public function template()
    {
        $this->layout('admin/layout');
    }

    public function uploadFileAction()
    {
        $fileFunctions = new OtherFunctions();
        $logger = new Logger();
        $write = new Stream('./logs/points_logs.log');
        $logger->addWriter($write);
        $files = $this->params()->fromFiles();
        $date = date('d.m.Y h:i:s');
        $hash = md5($date . $files['file']['name'] . $files['file']['size']);
        $path = $fileFunctions->moveUploadedFile($files, $hash);
        $i = 0;
        $bad = 0;
        $good = 0;
        $result = array();
        if (strpos($files['file']['name'], '.csv')) {
            $handle = fopen(ROOT_PATH . $path, "r");
            $logger->log(Logger::INFO, '---------------------POCZĄTEK DODAWANIA---------------------');
            $logger->log(Logger::INFO, 'UserId: ' . $this->identity()->getId());
            while (($data = fgetcsv($handle, 100000, ';')) == true) {
                if ($i == 0) {
                } else {
                    $validate = $fileFunctions->validate($data);
                    if ($validate['error']) {
                        $bad++;
                    } else {

                        $local = explode(',', $data[0]);

                        $mapParams = $this->em->getRepository('Application\Entity\MapLocalization')->findOneBy(array('nazwa' => $data[1], 'lat' => $local[0], 'lon' => $local[1]));
                        $localCenter = $this->em->getRepository('Application\Entity\LocalCenter')->find($data[6]);

                        if (!$mapParams)
                            $mapParams = new MapLocalization();
                        $mapParams->setDateAdd(new \DateTime($date));
                        $mapParams->setLat($local[0]);
                        $mapParams->setLon($local[1]);
                        $mapParams->setNazwa(iconv(mb_detect_encoding($data[1]), 'utf-8', ucwords($data[1])));
                        $mapParams->setTagi(iconv(mb_detect_encoding($data[1]), 'utf-8', ucwords($data[2])));
                        $mapParams->setAdres(iconv('', 'utf-8', ucwords($data[3])));
                        $mapParams->setUrl($data[4]);
                        if ($data[5])
                            $mapParams->setTelephone($data[5]);
                        $mapParams->setTown($localCenter);
                        $mapParams->setIsActive(1);

                        $this->em->persist($mapParams);
                        $this->em->flush();
                        $good++;

                        $logger->log(Logger::INFO, 'Dodano punkt o ID: ' . $mapParams->getIdloc() . ' o nazwie: ' . iconv(mb_detect_encoding($data[1]), 'utf-8', ucwords($data[1])));
                    }
                }
                $i++;
            }
            $logger->log(Logger::INFO, '---------------------KONIEC DODAWANIA---------------------');

            $file = $this->em->getRepository('Application\Entity\File')->findOneBy(array('name' => $files['file']['name'], 'hash' => $hash));
            if (!$file)
                $file = new File();
//
            $file->setName($files['file']['name']);
            $file->setAuthor($this->identity());
            $file->setDateAdd(new \DateTime($date));
            $file->setHash($hash);
            $file->setPath($path);

            $this->em->persist($file);
            $this->em->flush();

            $viewRender = $this->getServiceLocator()->get('ViewRenderer');
            $viewModel = new ViewModel(array(
                'file' => array(
                    'name' => $files['file']['name'],
                    'size' => $files['file']['size'],
                    'good' => $good,
                    'bad' => $bad,
                ),
            ));

            $viewModel->setTemplate('admin/points/partial/up-table-upload.phtml');
            $html_up = $viewRender->render($viewModel);
            $result = array(
                'success' => 1,
                'html_up' => $html_up,
                'bad' => $bad,
            );
        }
        return new JsonModel($result);
    }

    public function importFrameAction()
    {
        $this->layout('iframe/layout');

        return new ViewModel();
    }

    public function editAction()
    {
        $this->template();
        $id = $this->params()->fromRoute('id');
        $point = $this->em->getRepository('Application\Entity\MapLocalization')->find($id);
        $date = date('d.m.Y h:i:s');

        $form = new PointForm();
        $model = new PointModel();

        $form->setInputFilter($model->getInputFilter());

        $data = array(
            'name' => $point->getNazwa(),
            'lat' => $point->getLat(),
            'lon' => $point->getLon(),
            'datasm' => $point->getDatasm(),
            'databg' => $point->getDatabg(),
            'tags' => $point->getTagi(),
            'vote' => $point->getOcena(),
            'adres' => $point->getAdres(),
            'url' => $point->getUrl(),
            'phone' => $point->getTelephone(),
        );
        $form->setData($data);
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $point->setNazwa($data['name']);
                $point->setLat($data['lat']);
                $point->setLon($data['lon']);
                $point->setTagi($data['tags']);
                $point->setAdres($data['adres']);
                $point->setUrl($data['url']);
                $point->setTelephone($data['phone']);
                $point->setDatabg($data['databg']);
                $point->setDateEdit(new \DateTime($date));

                $this->em->persist($point);
                $this->em->flush();
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));

    }

    public function deactivateAction()
    {
        $id = $this->params()->fromRoute('id');
        $point = $this->em->getRepository('Application\Entity\MapLocalization')->find($id);

        if ($point->getIsActive() == 0) {
            $point->setIsActive(1);
            $active = 1;
        } else {
            $point->setIsActive(0);
            $active = 0;
        }

        $this->em->persist($point);
        $this->em->flush();

        return new JsonModel(array(
            'name' => $point->getNazwa(),
            'active' => $active,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $point = $this->em->getRepository('Application\Entity\MapLocalization')->find($id);
        $name = $point->getNazwa();

        $this->em->remove($point);
        $this->em->flush();

        return new JsonModel(array(
            'name' => $name,
        ));
    }

    public function refreshAction()
    {
        $limit = 10;
        $offset = 0;
        $points = $this->em->getRepository('Application\Entity\MapLocalization')->getAll($offset, $limit);

        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $viewModel = new ViewModel(array(
            'page' => 1,
            'limit' => 10,
            'points' => $points,
        ));
        $viewModel->setTemplate('admin/points/partial/table-partial.phtml');

        $html = $viewRender->render($viewModel);

        return new JsonModel(array(
            'html' => $html,
        ));
    }
}