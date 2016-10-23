<?php
/**
 * User: Paweł
 * Date: 01.07.2016
 * Time: 19:45
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MapLocalization
 * @package Application\Entity
 *
 * @ORM\Table(name="localization")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\MapLocalization")
 */
class MapLocalization
{
    /**
     * @var int
     *
     * @ORM\Column(name="idloc", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idloc;

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
     * @ORM\Column(name="nazwa", type="string", length=255, nullable=true)
     */
    private $nazwa;
    /**
     * @var string
     *
     * @ORM\Column(name="datasm", type="string", length=150, nullable=true)
     */
    private $datasm;

    /**
     * @var string
     *
     * @ORM\Column(name="databg", type="string", length=255, nullable=true)
     */
    private $databg;

    /**
     * @var string
     *
     * @ORM\Column(name="tagi", type="string", length=255, nullable=true)
     */
    private $tagi;

    /**
     * @var int
     *
     * @ORM\Column(name="ocena", type="integer", length=5, nullable=true)
     */
    private $ocena;

    /**
     * @var string
     *
     * @ORM\Column(name="adres", type="string", length=255, nullable=true)
     */
    private $adres;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=12, nullable=true)
     */
    private $telephone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=false)
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    private $dateEdit;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\LocalCenter")
     * @ORM\JoinColumn(name="town", referencedColumnName="id")
     */
    private $town;

    /**
     * @var int
     *
     * @ORM\Column(name="is_active", type="integer", length=1, nullable=true)
     */
    private $isActive = 0;

    /**
     * @return int
     */
    public function getIdloc()
    {
        return $this->idloc;
    }

    /**
     * @param int $idloc
     * @return MapLocalization
     */
    public function setIdloc($idloc)
    {
        $this->idloc = $idloc;
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
     * @return MapLocalization
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
     * @return MapLocalization
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
        return $this;
    }

    /**
     * @return string
     */
    public function getNazwa()
    {
        return $this->nazwa;
    }

    /**
     * @param string $nazwa
     * @return MapLocalization
     */
    public function setNazwa($nazwa)
    {
        $this->nazwa = $nazwa;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatasm()
    {
        return $this->datasm;
    }

    /**
     * @param string $datasm
     * @return MapLocalization
     */
    public function setDatasm($datasm)
    {
        $this->datasm = $datasm;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabg()
    {
        return $this->databg;
    }

    /**
     * @param string $databg
     * @return MapLocalization
     */
    public function setDatabg($databg)
    {
        $this->databg = $databg;
        return $this;
    }

    /**
     * @return string
     */
    public function getTagi()
    {
        return $this->tagi;
    }

    /**
     * @param string $tagi
     * @return MapLocalization
     */
    public function setTagi($tagi)
    {
        $this->tagi = $tagi;
        return $this;
    }

    /**
     * @return int
     */
    public function getOcena()
    {
        return $this->ocena;
    }

    /**
     * @param int $ocena
     * @return MapLocalization
     */
    public function setOcena($ocena)
    {
        $this->ocena = $ocena;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdres()
    {
        return $this->adres;
    }

    /**
     * @param string $adres
     * @return MapLocalization
     */
    public function setAdres($adres)
    {
        $this->adres = $adres;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return MapLocalization
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * @param \DateTime $dateAdd
     * @return MapLocalization
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    /**
     * @return int
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param int $telephone
     * @return MapLocalization
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param mixed $town
     * @return MapLocalization
     */
    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param int $isActive
     * @return MapLocalization
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * @param mixed $dateEdit
     * @return MapLocalization
     */
    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;
        return $this;
    }


}