<?php

namespace Kicker\Controller;

use Zend\View\Model\JsonModel;
use Kicker\Entity\User;

class UserController extends \Kicker\CorsRestfulController {

    public function getList() {
        
        
//           return new JsonModel(array(
//            'data' => array(),
//        ));
        
        $em = $this->getEntityManager();

        $repository = $em->getRepository('Kicker\Entity\User');
        $users = $repository->findAll();

        $usersArray = array();
        foreach ($users as $user) {
            $usersArray[] = $user->toArray();
        }

        return new JsonModel(array('data' => $usersArray));
    }

    public function get($id) {
        $em = $this->getEntityManager();
        $user = $em->find('Kicker\Entity\User', $id);

        return new JsonModel(array(
            'data' => $user->toArray(),
        ));
    }

    public function create($data) {

        if (!$this->isAuthenticated()) {
            return new JsonModel(array('data' => 'Unauthorized'));
        }

        $em = $this->getEntityManager();

        $user = new User();
        if (isset($data["firstname"])) {
            $user->setFirstname($data["firstname"]);
        }

        if (isset($data["lastname"])) {
            $user->setLastname($data["lastname"]);
        }

        if (isset($data["nickname"])) {
            $user->setNickname($data["nickname"]);
        }

        if (isset($data["avatar"])) {
            $user->setAvatar($data["avatar"]);
        }

        $user->setCreated(time());

        $em->persist($user);
        $em->flush();
        return $this->get($user->getId());
    }

    public function update($id, $data) {

        if (!$this->isAuthenticated()) {
            return new JsonModel(array('data' => 'Unauthorized'));
        }


        $em = $this->getEntityManager();
        $user = $em->find('Kicker\Entity\User', $id);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setNickname($data['nickname']);
        $user->setAvatar($data['avatar']);

        $em->flush();

        return new JsonModel(array(
            'data' => $user->toArray(),
            'message' => ''
        ));
    }

    public function delete($id) {

        if (!$this->isAuthenticated()) {
            return new JsonModel(array('data' => 'Unauthorized'));
        }


        $em = $this->getEntityManager();
        $user = $em->find('Kicker\Entity\User', $id);
        $message = '';
        if ($user !== null) {

            // Delete GameLinks? No we wanna keep games even if players are deleted. Deleted players will be displayed as "Deleted Player" 
            $games = $user->getGames();
            foreach ($games as $game) {
                $em->remove($game);
            }

            $em->remove($user);
            $em->flush();

            if ($user->getId() === null) {
                $message .= 'user ' . $user->getFirstname() . ' was succesfully deleted';
            } else {
                $message .= 'some error happened';
            }
        } else {
            $message .= 'user could not be found';
        }
        return new JsonModel(array(
            'data' => null,
            'message' => $message
        ));
    }

}
