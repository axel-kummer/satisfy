<?php


namespace Playbloom\Satisfy\Model;


class OAuth
{
    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var string
     */
    private $token = '';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return OAuth
     */
    public function setType(string $type): OAuth
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return OAuth
     */
    public function setDomain(string $domain): OAuth
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return OAuth
     */
    public function setToken(string $token): OAuth
    {
        $this->token = $token;
        return $this;
    }
}
