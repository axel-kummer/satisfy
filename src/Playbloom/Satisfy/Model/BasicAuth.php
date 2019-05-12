<?php


namespace Playbloom\Satisfy\Model;


class BasicAuth
{
    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var string
     */
    private $username = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return BasicAuth
     */
    public function setDomain(string $domain): BasicAuth
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return BasicAuth
     */
    public function setUsername(string $username): BasicAuth
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return BasicAuth
     */
    public function setPassword(string $password): BasicAuth
    {
        $this->password = $password;
        return $this;
    }
}

