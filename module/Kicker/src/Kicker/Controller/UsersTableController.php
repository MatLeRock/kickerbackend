<?php

namespace Kicker\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Kicker\Model\Entity\Game;
use Kicker\Controller\GameController;

class UsersTableController extends \Kicker\CorsRestfulController {

    protected $_gamesTable;
    private $_pointsPerGame = 3;

    public function getGamesTable() {
        if (!$this->_gamesTable) {
            $sm = $this->getServiceLocator();
            $this->_gamesTable = $sm->get('Kicker\Model\GamesTable');
        }
        return $this->_gamesTable;
    }

    public function getList() {
        $em = $this->getEntityManager();

        $repository = $em->getRepository('Kicker\Entity\Game');

//        $games = $repository->findBy(array(), array('created' => 'DESC'), $size, ($index) * $size);
        $games = $repository->findAll();
        $users_table = array();
        foreach ($games as $key => $game) {

            $users = $game->getUsersAsArray();

            foreach ($users as $user) {
                $user_id = $user["id"];
                if (!isset($users_table[$user_id])) {
                    $users_table[$user_id] = array(
                        "firstname" => $user["firstname"],
                        "lastname" => $user["lastname"],
                        "nickname" => $user["nickname"],
                        "won" => 0,
                        "lost" => 0
                    );
                }

                if ($game->getTeam1_result() > $game->getTeam2_result()) {

                    if ($user["team"] == 1) {
                        $users_table[$user_id]["won"] ++;
                    } else {
                        $users_table[$user_id]["lost"] ++;
                    }
                } else {
                    if ($user["team"] == 2) {
                        $users_table[$user_id]["won"] ++;
                    } else {
                        $users_table[$user_id]["lost"] ++;
                    }
                }
            }



//            $user_id = $game["user"]["id"];
//            $team = $game["user"]["team"];
        }


        uasort($users_table, function($a, $b) {
            if ($a["won"] == $b["won"]) {
                if ($a["lost"] < $b["lost"]) {
                    return -1;
                } elseif ($a["lost"] > $b["lost"]) {
                    return 1;
                }
                return 0;
            }
            return ($a["won"] < $b["won"]) ? 1 : -1;
        });

        $clean_array = array();
        foreach ($users_table as $key => $value) {
            $value["points"] = $this->_pointsPerGame * $value["won"];
            $clean_array[] = $value;
        }

        return new JsonModel(array('data' => $clean_array));
    }

    public function get($id) {
        $user = $this->getGamesTable()->getGame($id);

        return new JsonModel(array(
            'data' => $user->convert2Array(),
        ));
    }

}
