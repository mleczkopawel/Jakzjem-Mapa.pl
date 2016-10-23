<?php
/**
 * User: Paweł
 * Date: 01.07.2016
 * Time: 19:51
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RestaurantMenu
 * @package Application\Entity
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\RestaurantMenu")
 */
class RestaurantMenu
{
    /**
     * @var int
     *
     * @ORM\Column(name="idmenu", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idmenu;

    /**
     * @var int
     *
     * @ORM\Column(name="idloc", type="integer")
     */
    private $idloc;

    /**
     * @var string
     *
     * @ORM\Column(name="nazwa", type="string", length=100)
     */
    private $nazwa;

    /**
     * @var string
     *
     * @ORM\Column(name="skladniki", type="string", length=255)
     */
    private $skladniki;

    /**
     * @var string
     *
     * @ORM\Column(name="cena1", type="string", length=255)
     */
    private $cena1;
    /**
     * @var string
     *
     * @ORM\Column(name="cena2", type="string", length=255)
     */
    private $cena2;
    /**
     * @var string
     *
     * @ORM\Column(name="cena3", type="string", length=255)
     */
    private $cena3;
    /**
     * @var string
     *
     * @ORM\Column(name="cena4", type="string", length=255)
     */
    private $cena4;
    /**
     * @var string
     *
     * @ORM\Column(name="cena5", type="string", length=255)
     */
    private $cena5;

    /**
     * @var int
     *
     * @ORM\Column(name="ocena", type="integer", length=5)
     */
    private $ocena;

    /**
     * @var string
     *
     * @ORM\Column(name="rozmiar1", type="string", length=255)
     */
    private $rozmiar1;

    /**
     * @var string
     *
     * @ORM\Column(name="rozmiar2", type="string", length=255)
     */
    private $rozmiar2;

    /**
     * @var string
     *
     * @ORM\Column(name="rozmiar3", type="string", length=255)
     */
    private $rozmiar3;

    /**
     * @var string
     *
     * @ORM\Column(name="rozmiar4", type="string", length=255)
     */
    private $rozmiar4;

    /**
     * @var string
     *
     * @ORM\Column(name="rozmiar5", type="string", length=255)
     */
    private $rozmiar5;

    /**
     * @return int
     */
    public function getIdmenu()
    {
        return $this->idmenu;
    }

    /**
     * @param int $idmenu
     * @return RestaurantMenu
     */
    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdloc()
    {
        return $this->idloc;
    }

    /**
     * @param int $idloc
     * @return RestaurantMenu
     */
    public function setIdloc($idloc)
    {
        $this->idloc = $idloc;
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
     * @return RestaurantMenu
     */
    public function setNazwa($nazwa)
    {
        $this->nazwa = $nazwa;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkladniki()
    {
        return $this->skladniki;
    }

    /**
     * @param string $skladniki
     * @return RestaurantMenu
     */
    public function setSkladniki($skladniki)
    {
        $this->skladniki = $skladniki;
        return $this;
    }

    /**
     * @return string
     */
    public function getCena1()
    {
        return $this->cena1;
    }

    /**
     * @param string $cena1
     * @return RestaurantMenu
     */
    public function setCena1($cena1)
    {
        $this->cena1 = $cena1;
        return $this;
    }

    /**
     * @return string
     */
    public function getCena2()
    {
        return $this->cena2;
    }

    /**
     * @param string $cena2
     * @return RestaurantMenu
     */
    public function setCena2($cena2)
    {
        $this->cena2 = $cena2;
        return $this;
    }

    /**
     * @return string
     */
    public function getCena3()
    {
        return $this->cena3;
    }

    /**
     * @param string $cena3
     * @return RestaurantMenu
     */
    public function setCena3($cena3)
    {
        $this->cena3 = $cena3;
        return $this;
    }

    /**
     * @return string
     */
    public function getCena4()
    {
        return $this->cena4;
    }

    /**
     * @param string $cena4
     * @return RestaurantMenu
     */
    public function setCena4($cena4)
    {
        $this->cena4 = $cena4;
        return $this;
    }

    /**
     * @return string
     */
    public function getCena5()
    {
        return $this->cena5;
    }

    /**
     * @param string $cena5
     * @return RestaurantMenu
     */
    public function setCena5($cena5)
    {
        $this->cena5 = $cena5;
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
     * @return RestaurantMenu
     */
    public function setOcena($ocena)
    {
        $this->ocena = $ocena;
        return $this;
    }

    /**
     * @return string
     */
    public function getRozmiar1()
    {
        return $this->rozmiar1;
    }

    /**
     * @param string $rozmiar1
     * @return RestaurantMenu
     */
    public function setRozmiar1($rozmiar1)
    {
        $this->rozmiar1 = $rozmiar1;
        return $this;
    }

    /**
     * @return string
     */
    public function getRozmiar2()
    {
        return $this->rozmiar2;
    }

    /**
     * @param string $rozmiar2
     * @return RestaurantMenu
     */
    public function setRozmiar2($rozmiar2)
    {
        $this->rozmiar2 = $rozmiar2;
        return $this;
    }

    /**
     * @return string
     */
    public function getRozmiar3()
    {
        return $this->rozmiar3;
    }

    /**
     * @param string $rozmiar3
     * @return RestaurantMenu
     */
    public function setRozmiar3($rozmiar3)
    {
        $this->rozmiar3 = $rozmiar3;
        return $this;
    }

    /**
     * @return string
     */
    public function getRozmiar4()
    {
        return $this->rozmiar4;
    }

    /**
     * @param string $rozmiar4
     * @return RestaurantMenu
     */
    public function setRozmiar4($rozmiar4)
    {
        $this->rozmiar4 = $rozmiar4;
        return $this;
    }

    /**
     * @return string
     */
    public function getRozmiar5()
    {
        return $this->rozmiar5;
    }

    /**
     * @param string $rozmiar5
     * @return RestaurantMenu
     */
    public function setRozmiar5($rozmiar5)
    {
        $this->rozmiar5 = $rozmiar5;
        return $this;
    }


}