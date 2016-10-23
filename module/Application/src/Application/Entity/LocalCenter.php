<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 14.09.2016
 * Time: 10:53
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LocalCenter
 * @package Application\Entity
 *
 * @ORM\Table(name="center")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\LocalCenter")
 */
class LocalCenter
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255, nullable=true)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lon", type="string", length=255, nullable=true)
     */
    private $lon;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="show_name", type="string", length=255, nullable=true)
     */
    private $showName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LocalCenter
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param string $lat
     * @return LocalCenter
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return string
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @param string $lon
     * @return LocalCenter
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return LocalCenter
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getShowName()
    {
        return $this->showName;
    }

    /**
     * @param string $showName
     * @return LocalCenter
     */
    public function setShowName($showName)
    {
        $this->showName = $showName;
        return $this;
    }

}