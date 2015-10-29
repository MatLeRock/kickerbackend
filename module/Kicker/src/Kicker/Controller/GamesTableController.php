<?php

namespace Kicker\Controller;

//use Kicker\Model\Entity\Game;


use Kicker\CorsRestfulController;
use Zend\View\Model\JsonModel;
//use Zend\Authentication\Adapter\Digest as AuthAdapter;
use Zend\Authentication\Adapter\Http\FileResolver;
use Zend\Authentication\Adapter\Http;

class GamesTableController extends CorsRestfulController {

    protected $_gamesTable;
    protected $_usersTable;
    protected $_users2gamesTable;
    private $_pointsPerGame = 3;

    public function getList() {
        
//        return new JsonModel(array('data' => array()));
        
//
//           if(!$this->isAuthenticated()){
//            return new JsonModel(array('data' => 'Unauthorized'));
//        }
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Kicker\Entity\Game');

//        $games = $repository->findBy(array(), array('created' => 'DESC'), $size, ($index) * $size);
        $games = $repository->findAll();


        $games_table = array();
        foreach ($games as $key => $game) {
            $team1IDs = array();
            $team2IDs = array();
            $team1Users = array();
            $team2Users = array();
            $users = $game->getUsersAsArray(); // getUserIds?
            foreach ($users as $user) {
                if ($user["team"] == 1) {
                    $team1IDs[] = $user["id"];
                    $team1Users[] = $user;
                } else {
                    $team2IDs[] = $user["id"];
                    $team2Users[] = $user;
                }
            }

            sort($team1IDs);
            sort($team2IDs);
            $team1String = implode("|", $team1IDs);
            $team2String = implode("|", $team2IDs);
            if ($game->getTeam1_result() > $game->getTeam2_result()) {
                if (!isset($games_table[$team1String])) {
                    $games_table[$team1String] = array(
                        "team" => $team1Users,
                        "won" => 1,
                        "lost" => 0
                    );
                } else {
                    $games_table[$team1String]["won"] ++;
                }

                if (!isset($games_table[$team2String])) {
                    $games_table[$team2String] = array(
                        "team" => $team2Users,
                        "won" => 0,
                        "lost" => 1
                    );
                } else {
                    $games_table[$team2String]["lost"] ++;
                }
            } else {

                if (!isset($games_table[$team1String])) {
                    $games_table[$team1String] = array(
                        "team" => $team1Users,
                        "won" => 0,
                        "lost" => 1
                    );
                } else {
                    $games_table[$team1String]["lost"] ++;
                }

                if (!isset($games_table[$team2String])) {
                    $games_table[$team2String] = array(
                        "team" => $team2Users,
                        "won" => 1,
                        "lost" => 0
                    );
                } else {
                    $games_table[$team2String]["won"] ++;
                }
            }
        }


        uasort($games_table, function($a, $b) {
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
        foreach ($games_table as $key => $value) {
            $value["points"] = $this->_pointsPerGame * $value["won"];
            $clean_array[] = $value;
        }

        return new JsonModel(array('data' => $clean_array));
    }

    public function get($id) {
//        $user = $this->getGamesTable()->getGame($id);
//
//        return new JsonModel(array(
//            'data' => $user->convert2Array(),
//        ));
    }

}
