<?php

namespace Kicker;

use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\Adapter\Http\FileResolver;
use Zend\Authentication\Adapter\Http;

class CorsRestfulController extends AbstractRestfulController {

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Sets the EntityManager
     *
     * @param EntityManager $em
     * @access protected
     * @return PostController
     */
    protected function setEntityManager(EntityManager $em) {
        $this->entityManager = $em;
        return $this;
    }

    /**
     * Returns the EntityManager
     *
     * Fetches the EntityManager from ServiceLocator if it has not been initiated
     * and then returns it
     *
     * @access protected
     * @return EntityManager
     */
    protected function getEntityManager() {
        if (null === $this->entityManager) {
            $this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
        }
        return $this->entityManager;
    }

    /**
     * Set Header for Cross Origin Requests
     * TODO: At the moment this is pretty insecure; -> Add Allow-Origin URL
     * 
     * @param Request $request
     * @param Response $response
     * @return type
     *
     */
    public function dispatch(Request $request, Response $response = NULL) {
        $response->getHeaders()->addHeaders(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Expose-Headers' => 'WWW-Authenticate',
//            'Access-Control-Allow-Credentials' => 'true', 
//            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, WWW-Authenticate'
        ));
        return parent::dispatch($request, $response);
    }

    /**
     * OPTIONS Method id needed for Cross Origin Requests
     * TODO: At the moment this is pretty insecure; -> Add Allow-Origin URL 
     */
    public function options() {
        $this->response->getHeaders()->addHeaders(array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'OPTIONS, HEAD, INDEX, GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'accept, content-type, authorization, www-authenticate'
        ));
//        $this->getResponse()->setBody(null);//?
    }

    public function isAuthenticated() {

        $config = array(
            'accept_schemes' => 'digest',
            'realm' => 'realm',
            'digest_domains' => '/',
            'nonce_timeout' => 3600,
        );

        $adapter = new Http($config);
        $resolver = new FileResolver();
        $resolver->setFile('./files/passwords');
        $adapter->setDigestResolver($resolver);

        $request = $this->getRequest();
        $response = $this->getResponse();
        $adapter->setRequest($request);
        $adapter->setResponse($response);

        $result = $adapter->authenticate();
        if ($result->isValid()) {
            return true;
        }
        return false;
    }

}
