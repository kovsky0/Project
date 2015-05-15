<?php
/**
 * Copyright (C) 2015 David Young
 * 
 * Defines the session middleware
 */
namespace Project\HTTP\Middleware;
use DateTime;
use RDev\Framework\HTTP\Middleware\Session as BaseSession;
use RDev\HTTP\Responses\Cookie;
use RDev\HTTP\Responses\Response;

class Session extends BaseSession
{
    /** @var array|null The config array */
    private $config = null;

    /**
     * Runs garbage collection, if necessary
     */
    protected function gc()
    {
        $this->loadConfig();

        if(rand(1, $this->config["gcTotal"]) <= $this->config["gcChance"])
        {
            $this->sessionHandler->gc($this->config["lifetime"]);
        }
    }

    /**
     * Writes any session data needed in the response
     *
     * @param Response $response The response to write to
     */
    protected function writeToResponse(Response $response)
    {
        $this->loadConfig();
        $sessionCookie = new Cookie(
            $this->session->getName(),
            $this->session->getId(),
            new DateTime("+" . $this->config["lifetime"] . " seconds"),
            $this->config["cookiePath"],
            $this->config["domain"],
            $this->config["isSecure"],
            false
        );
        $response->getHeaders()->setCookie($sessionCookie);
    }

    /**
     * Loads the configuration array
     */
    private function loadConfig()
    {
        if($this->config === null)
        {
            $this->config = require $this->paths["configs"] . "/http/session.php";;
        }
    }
}