<?php

namespace Kicker;

use Zend\Authentication\Adapter\Http\ResolverInterface;

/**
 * This Class enables HTTP authentication via Environment Variables.
 */
class EnvVarsResolver implements ResolverInterface {

    private $password;
    private $username;
    private $realm;

    public function __construct() {
        $this->password = getenv('API_AUTH_PASS');
        $this->username = getenv('API_AUTH_USER');
        $this->realm = getenv('API_AUTH_REALM');
    }

    public function isReady() {
        if (!$this->password && !$this->username && !$this->realm) {
            return false;
        }
        return true;
    }

    public function resolve($username, $realm, $password = null) {
        if (empty($username)) {
            throw new Exception\InvalidArgumentException('Username is required');
        } elseif (!ctype_print($username) || strpos($username, ':') !== false) {
            throw new Exception\InvalidArgumentException(
            'Username must consist only of printable characters, excluding the colon'
            );
        }
        if (empty($realm)) {
            throw new Exception\InvalidArgumentException('Realm is required');
        } elseif (!ctype_print($realm) || strpos($realm, ':') !== false) {
            throw new Exception\InvalidArgumentException(
            'Realm must consist only of printable characters, excluding the colon.'
            );
        }

        if ($this->username == $username && $this->realm == $realm) {
            $password = $this->password;
            return $password;
        }
        return false;
    }

}
