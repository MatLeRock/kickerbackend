<?php

namespace Kicker\Controller;

use Zend\View\Model\JsonModel;
//use Kicker\Model\Entity\Game;
use Kicker\Entity\Game;
use Kicker\Entity\GameLink;
use Swagger\Annotations as SWG;

/**
 * @SWG\Resource(
 *     apiVersion="0.1",
 *     resourcePath="/game") 
 *  
 */
class GameController extends \Kicker\CorsRestfulController {

    protected $_gamesTable;
    protected $_users2gamesTable;

    public function getGamesTable() {
        if (!$this->_gamesTable) {
            $sm = $this->getServiceLocator();
            $this->_gamesTable = $sm->get('Kicker\Model\GamesTable');
        }
        return $this->_gamesTable;
    }

    public function getUsers2GamesTable() {
        if (!$this->_users2gamesTable) {
            $sm = $this->getServiceLocator();
            $this->_users2gamesTable = $sm->get('Kicker\Model\Users2GamesTable');
        }
        return $this->_users2gamesTable;
    }

    /**
     *
     * @SWG\Api(
     *   path="/game",
     *   description="Operations about pets",
     *   @SWG\Operation(
     *      method="GET", 
     *      summary="Fetch games", 
     *      notes="Returns list of games. Paginated with index and size",
     *      type="List", 
     *      nickname="getList",
     *      @SWG\ResponseMessage(code=404, message="Pet not found"),
     *      @SWG\Parameter(
     *              paramType="query",
     *              name="index",
     *              description="ID of pet that needs to be fetched",
     *              required=false,
     *              type="integer"
     *          )       
     *      )
     *  )
     */
    public function getList() {
        
        $index = (int) $this->request->getQuery()->get("index", 0);
        $size = (int) $this->request->getQuery()->get("size", 10);

        $em = $this->getEntityManager();

        $repository = $em->getRepository('Kicker\Entity\Game');

        $games = $repository->findBy(array(), array('created' => 'DESC'), $size, $index * $size);
        $total = count($repository->findAll());



        $gamesArray = array();
        foreach ($games as $game) {
            $gameArray = $game->toArray();
            $gamesArray[] = $gameArray;
        }
//        var_dump($gamesArray);
        $data = array(
            "games" => $gamesArray,
            "total" => $total
        );
        return new JsonModel(array('data' => $data));
    }

    public function get($id) {
        $em = $this->getEntityManager();
        $game = $em->find('Kicker\Entity\Game', $id);

        return new JsonModel(array(
            'data' => $game->toArray(),
        ));
    }

     /**
     *
     * @SWG\Api(
     *   @SWG\Operation(
     *      method="POST", 
     *      summary="Create game", 
     *      notes="Returns list of games. Paginated with index and size",
     *      type="List", 
     *      @SWG\ResponseMessage(code=404, message="Pet not found"),
     *      @SWG\Parameter(
     *              paramType="body",
     *              name="data",
     *              description="ID of pet that needs to be fetched",
     *              required=true,
     *              type="array"
     *          )       
     *      )
     *  )
     */
    public function create($data) {

        if (!$this->isAuthenticated()) {
            return new JsonModel(array('data' => 'Unauthorized'));
        }

        $em = $this->getEntityManager();

        $team1 = array();
        foreach ($data["team1"] as $key => $value) {
            $user = $em->find('Kicker\Entity\User', (int) $value["id"]);
            if ($user == null) {
                return null;
            } else {
                $team1[] = $user;
            }
        }

        $team2 = array();
        foreach ($data["team2"] as $key => $value) {
            $user = $em->find('Kicker\Entity\User', (int) $value["id"]);
            if ($user == null) {
                return null;
            } else {
                $team2[] = $user;
            }
        }

        $game = new Game();
        if (isset($data["team1_result"])) {
            $game->setTeam1_result($data["team1_result"]);
        }

        if (isset($data["team2_result"])) {
            $game->setTeam2_result($data["team2_result"]);
        }

        $game->setCreated(time());
        $em->persist($game);
        $em->flush();

        $game_id = $game->getId();

        foreach ($team1 as $teamUser) {
            $gLink = new GameLink();
            $gLink->setId($game_id);
            $gLink->setTeam_num(1);
            $gLink->setUser($teamUser);
            $game->addGameLink($gLink);
        }

        foreach ($team2 as $teamUser) {
            $gLink = new GameLink();
            $gLink->setId($game_id);
            $gLink->setTeam_num(2);
            $gLink->setUser($teamUser);
            $game->addGameLink($gLink);
        }

        $em->flush();

        return new JsonModel(array(
            'data' => $game_id
        ));
    }

    public function update($id, $data) {

        if (!$this->isAuthenticated()) {
            return new JsonModel(array('data' => 'Unauthorized'));
        }

        $em = $this->getEntityManager();
        $game = $em->find('Kicker\Entity\Game', $id);

        if (isset($data["team1_result"])) {
            $game->setTeam1_result($data["team1_result"]);
        }

        if (isset($data["team2_result"])) {
            $game->setTeam2_result($data["team2_result"]);
        }

        $team1 = array();
        foreach ($data["team1"] as $key => $value) {
            $user = $em->find('Kicker\Entity\User', (int) $value["id"]);
            if ($user == null) {
                return null;
            } else {
                $team1[] = $user;
            }
        }

        $team2 = array();
        foreach ($data["team2"] as $key => $value) {
            $user = $em->find('Kicker\Entity\User', (int) $value["id"]);
            if ($user == null) {
                return null;
            } else {
                $team2[] = $user;
            }
        }

        $game_id = $game->getId();

        $gameLinks = $game->getGameLinks();
        foreach ($gameLinks as $gamelink) {
            $game->removeGameLink($gamelink);
        }


        foreach ($team1 as $teamUser) {
            $gLink = new GameLink();
            $gLink->setId($game_id);
            $gLink->setTeam_num(1);
            $gLink->setUser($teamUser);
            $game->addGameLink($gLink);
        }

        foreach ($team2 as $teamUser) {
            $gLink = new GameLink();
            $gLink->setId($game_id);
            $gLink->setTeam_num(2);
            $gLink->setUser($teamUser);
            $game->addGameLink($gLink);
        }

        $em->persist($game);
        $em->flush();
        return new JsonModel(array(
            'data' => $game->toArray()
        ));
    }

    public function delete($id) {

        if (!$this->isAuthenticated()) {
            return new JsonModel(array('data' => 'Unauthorized'));
        }

        $em = $this->getEntityManager();
        $game = $em->find('Kicker\Entity\Game', $id);
        $message = '';
        if ($game !== null) {

            // Delete GameLinks? No we wanna keep games even if players are deleted. Deleted players will be displayed as "Deleted Player" 
//            $gameLinks = $user->getGameLinks();
//            foreach ($gameLinks as $gameLink){
//                $user->removeGameLink($gameLink);
//            }

            $em->remove($game);
            $em->flush();

            if ($game->getId() === null) {
                $message .= 'game was succesfully deleted';
            } else {
                $message .= 'some error happened';
            }
        } else {
            $message .= 'game could not be found';
        }
        return new JsonModel(array(
            'data' => null,
            'message' => $message
        ));
    }

}
