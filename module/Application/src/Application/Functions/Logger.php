<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 23.10.2016
 * Time: 20:00
 */

namespace Application\Functions;


use Application\Entity\File;
use Zend\Log\Writer\Stream;

class Logger
{
    private $logger;

    /**
     * Logger constructor.
     * @param $date
     * @param $user
     * @param $em
     * @param $path
     * @param $parent
     */
    public function __construct($date, $user, $em, $path, $parent) {
        $this->logger = new \Zend\Log\Logger();
        $log = $em->getRepository('Application\Entity\File')->findOneBy(array('name' => $date . '.log', 'parent' => $parent));
        if (!$log) {
            $log = new File();
            $log->setName($date . '.log');
            $log->setDateAdd(new \DateTime($date));
            $log->setAuthor($user);
            $log->setPath($path . '/' . $date . '.log');
            $log->setParent($em->getRepository('Application\Entity\File')->findOneByName($parent));
            $log->setHash(md5($date . $date . $user->getName()));
            $log->setIsFile(true);

            $em->persist($log);
            $em->flush();

            touch($log->getPath());
            chmod($log->getPath(), 0777);
        }
        $stream = new Stream($log->getPath());
        $this->logger->addWriter($stream);
    }

    public function log($context, $status) {
        if ($status == 0) {
            $stat = \Zend\Log\Logger::ALERT;
        } else {
            $stat = \Zend\Log\Logger::INFO;
        }
        $this->logger->log($stat, $context);
    }
}