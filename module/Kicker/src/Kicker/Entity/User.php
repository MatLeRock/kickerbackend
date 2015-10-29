<?php

namespace Kicker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users",options={"collate"="utf8_general_ci"})
 */
class User {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nickname = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $avatar = null;

    /**
     * @ORM\Column(type="integer")
     */
    protected $created;

    /**
     * Gamelinks will exist even if a user will be deleted6
     * 
     * @ORM\OneToMany(targetEntity="GameLink", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $gamelinks;

    public function __construct() {
        $this->gamelinks = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getFirstname() {
        return $this->firstname;
    }

    function getLastname() {
        return $this->lastname;
    }

    function getNickname() {
        return ($this->nickname === null) ? $this->firstname : $this->nickname;
    }

    function getCreated() {
        return $this->created;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    function getAvatar() {
        return $this->avatar;
    }

    function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    function setCreated($created) {
        $this->created = $created;
    }

    function toArray() {
        return array(
            "id" => $this->getId(),
            "firstname" => $this->getFirstname(),
            "lastname" => $this->getLastname(),
            "nickname" => $this->getNickname(),
            "avatar" => $this->getAvatar(),
            "created" => $this->getCreated(),
        );
    }

    public function getGameLinks() {
        return $this->gamelinks->toArray();
    }

    public function addGameLink(GameLink $gLink) {
        if (!$this->gamelinks->contains($gLink)) {
            $this->gamelinks->add($gLink);
            $gLink->setUser($this);
        }

        return $this;
    }

    public function removeGameLink(GameLink $gLink) {
        if ($this->gamelinks->contains($gLink)) {
            $this->gamelinks->removeElement($gLink);
            $gLink->setUser(null);
        }

        return $this;
    }

    public function getGames() {
        return array_map(
                function ($gLink) {
            return $gLink->getGame();
        }, $this->gamelinks->toArray()
        );
    }

}
