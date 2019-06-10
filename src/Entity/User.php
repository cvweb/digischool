<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="users",
*    uniqueConstraints={@ORM\UniqueConstraint(name="user_mail_unique",columns={"mail"})})
*/

class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $mail;

    /**
     * @ORM\Column(type="string")
     */
    protected $birth;

    /**
     * @ORM\Column(type="string")
     */
    protected $register;


    public function getUsername(){
        return $this->username;
    }

    public function getMail(){
        return $this->mail;
    }

    public function getBirth(){
        return $this->birth;
    }

    public function getRegister(){
        return $this->register;
    }

    public function setUsername($username){
        $this->username = $username;
        return $this;
    }

    public function setMail($mail){
        $this->mail = $mail;
        return $this;
    }

    public function setBirth($birth){
        $this->birth = $birth;
        return $this;
    }

    public function setRegister($register){
        $this->register = $register;
        return $this;
    }



}