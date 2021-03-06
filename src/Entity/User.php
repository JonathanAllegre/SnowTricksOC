<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email déjà enregistré")
 * @UniqueEntity(fields="username", message="Utilisateur déjà enregistré")
 * @ORM\Table(name="app_users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\Column(type="smallint")
     */
    private $active;

    /**
     * @ORM\Column(type="string", name="user_picture", nullable=true)
     */
    private $userPicture;

    /**
     * User constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->roles  = array('ROLE_USER');
        $this->token  = $this->generateToken();
        $this->active = 0;
        $this->userPicture = 'default.svg';
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username):self
    {
        $this->username = $username;
        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password): self
    {
        $this->plainPassword = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param $active
     * @return User
     */
    public function setActive($active): self
    {
        $this->active = $active;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function isEnabled()
    {
        return (bool) $this->active;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateToken()
    {
        return md5(random_bytes(40));
    }

    /**
     * @return mixed
     */
    public function getUserPicture()
    {
        return $this->userPicture;
    }

    /**
     * @param mixed $userPicture
     */
    public function setUserPicture($userPicture): void
    {
        $this->userPicture = $userPicture;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
