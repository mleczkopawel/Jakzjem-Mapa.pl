<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.09.2016
 * Time: 10:04
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class StatsSearch
 * @package Application\Entity
 *
 * @ORM\Table(name="search_stats")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StatsSearch")
 */
class StatsSearch
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return StatsSearch
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return StatsSearch
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     * @return StatsSearch
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

}