<?php

namespace Kicker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Kicker\Entity\GameLink;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
class Game {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $team1_result;

    /**
     * @ORM\Column(type="integer")
     */
    protected $team2_result;

    /**
     * @ORM\Column(type="integer")
     */
    protected $created;

    /**
     * @ORM\OneToMany(targetEntity="GameLink", mappedBy="game", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $gamelinks;

    public function __construct() {
        $this->gamelinks = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getTeam1_result() {
        return $this->team1_result;
    }

    function getTeam2_result() {
        return $this->team2_result;
    }

    function getCreated() {
        return $this->created;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTeam1_result($team1_result) {
        $this->team1_result = $team1_result;
    }

    function setTeam2_result($team2_result) {
        $this->team2_result = $team2_result;
    }

    function setCreated($created) {
        $this->created = $created;
    }

    function setGamelinks($gamelinks) {
        $this->gamelinks = $gamelinks;
    }

    public function getGameLinks() {
        return $this->gamelinks->toArray();
    }

    public function addGameLink(GameLink $gLink) {
        if (!$this->gamelinks->contains($gLink)) {
            $this->gamelinks->add($gLink);
            $gLink->setGame($this);
        }

        return $this;
    }

    public function removeGameLink(GameLink $gLink) {
        if ($this->gamelinks->contains($gLink)) {
            $this->gamelinks->removeElement($gLink);
            $gLink->setGame(null);
        }

        return $this;
    }

    public function getUsers() {
        return array_map(
                function ($gLink) {
            return $gLink->getUser();
        }, $this->gamelinks->toArray()
        );
    }

    public function getUsersAsArray() {
        return array_map(
                function ($gLink) {
            $user = $gLink->getUser();
            $userArray = ($user != null) ? $user->toArray() : array('id' => null, 'firstname' => null, 'lastname' => null, 'nickname' => null, 'created' => 0);
            $userArray["team"] = $gLink->getTeam_num();
            return $userArray;
        }, $this->gamelinks->toArray()
        );
    }

    function toArray() {
        $team1 = array();
        $team2 = array();

        foreach ($this->gamelinks as $gLink) {
            $user = $gLink->getUser();
            $userArray = ($user != null) ? $user->toArray() : array('id' => null, 'firstname' => null, 'lastname' => null, 'nickname' => null, 'created' => 0);
            if ($gLink->getTeam_num() == 1) {
                $team1[] = $userArray;
            } else {
                $team2[] = $userArray;
            }
        }

        return array(
            "id" => $this->getId(),
            "team1_result" => $this->getTeam1_result(),
            "team2_result" => $this->getTeam2_result(),
            "created" => $this->getCreated(),
            "team1" => $team1,
            "team2" => $team2,
        );
    }

}
