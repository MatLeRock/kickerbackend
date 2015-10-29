<?php
namespace Kicker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamelinks")
 */
class GameLink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="gamelinks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="gamelinks")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $game;
    
     /**
     * @ORM\Column(type="integer")
     */
    protected $team_num;

    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getGame() {
        return $this->game;
    }

    function getTeam_num() {
        return $this->team_num;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setGame($game) {
        $this->game = $game;
    }

    function setTeam_num($team_num) {
        $this->team_num = $team_num;
    }

   function toArray() {
        return get_object_vars($this);
    }

}