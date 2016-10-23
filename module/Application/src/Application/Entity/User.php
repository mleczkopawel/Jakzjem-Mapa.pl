<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 13:55
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Application\Entity
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\User")
 */
class User
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
     * @var int
     *
     * @ORM\Column(name="is_admin", type="integer")
     */
    private $isAdmin = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="add_date", type="datetime")
     */
    private $addDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="edit_date", type="datetime", nullable=true)
     */
    private $editDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login_date", type="datetime", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\File")
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id")
     */
    private $avatarId;

    /**
     * @var int
     *
     * @ORM\Column(name="is_accepted", type="integer")
     */
    private $isAccepted = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="google", type="integer", nullable=true)
     */
    private $google;

    /**
     * @var int
     *
     * @ORM\Column(name="facebook", type="integer", nullable=true)
     */
    private $facebook;

    /**
     * @var int
     *
     * @ORM\Column(name="local", type="integer", nullable=true)
     */
    private $local;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param int $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
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
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * @param \DateTime $addDate
     * @return User
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatarId()
    {
        return $this->avatarId;
    }

    /**
     * @param mixed $avatarId
     * @return User
     */
    public function setAvatarId($avatarId)
    {
        $this->avatarId = $avatarId;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * @param int $isAccepted
     * @return User
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEditDate()
    {
        return $this->editDate;
    }

    /**
     * @param mixed $editDate
     * @return User
     */
    public function setEditDate($editDate)
    {
        $this->editDate = $editDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    /**
     * @param \DateTime $lastLoginDate
     * @return User
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProviders()
    {
        $providers = null;
        if ($this->getLocal() != '' || $this->getLocal() != NULL) {
            $providers[] = 'local';
        }
        if ($this->getFacebook() != '' || $this->getFacebook() != NULL) {
            $providers[] = 'facebook';
        }
        if ($this->getGoogle() != '' || $this->getGoogle() != NULL) {
            $providers[] = 'google';
        }
        return $providers;
    }

    /**
     * @return int
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param int $local
     * @return User
     */
    public function setLocal($local)
    {
        $this->local = $local;
        return $this;
    }

    /**
     * @return int
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param int $facebook
     * @return User
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogle()
    {
        return $this->google;
    }

    /**
     * @param mixed $google
     * @return User
     */
    public function setGoogle($google)
    {
        $this->google = $google;
        return $this;
    }

    /**
     * @param null $local
     * @param null $facebook
     * @param null $google
     * @return User
     */
    public function setProviders($local = null, $facebook = null, $google = null)
    {
        if ($local != null) {
            $this->setLocal($local);
        }
        if ($facebook != null) {
            $this->setFacebook($facebook);
        }
        if ($google != null) {
            $this->setGoogle($google);
        }

        return $this;
    }

}