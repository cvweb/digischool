<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="choices")
*/

class Choice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $userId;

    /**
     * @ORM\Column(type="string")
     */
    protected $filmId;

    /**
     * @ORM\Column(type="string")
     */
    protected $filmName;

    /**
     * @ORM\Column(type="string")
     */
    protected $filmScreen;

    public function getUserId(){
        return $this->userId;
    }

    public function getFilmId(){
        return $this->filmId;
    }

    public function getFilmName(){
        return $this->filmName;
    }

    public function getFilmScreen(){
        return $this->filmScreen;
    }

    public function setUserId($userId){
        $this->userId = $userId;
        return $this;
    }

    public function setFilmId($filmId){
        $this->filmId = $filmId;
        return $this;
    }

    public function setFilmName($filmName){
        $this->filmName = $filmName;
        return $this;
    }

    public function setFilmScreen($filmScreen){
        $this->filmScreen = $filmScreen;
        return $this;
    }

}