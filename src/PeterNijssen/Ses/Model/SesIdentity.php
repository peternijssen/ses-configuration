<?php

namespace PeterNijssen\Ses\Model;

use PeterNijssen\Ses\Exception\InvalidIdentityException;

/**
 * SES identity
 */
class SesIdentity
{
    /**
     * @var string
     */
    private $identity;

    /**
     * Identity constructor.
     *
     * @param string $identity emailAddress|domain
     *
     * @throws InvalidIdentityException
     */
    public function __construct($identity)
    {
        if (!preg_match('/^.+\@\S+\.\S+$/', $identity)
            && !preg_match('/^((\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,32}|[0-9]{1,32})(\]?))$/', $identity)) {
            throw new InvalidIdentityException("The identity is not a valid domain or email address");
        }


        $this->identity = $identity;
    }

    /**
     * Get the identity. If correct, this is either an email address or domain
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Get the type for this identity
     *
     * @return string
     */
    public function getType()
    {
        if (preg_match('/@/', $this->identity)) {
            return "email";
        }

        return "domain";
    }

    /**
     * Get the domain for this identity
     *
     * @return string
     */
    public function getDomain()
    {
        if(preg_match("/@([^\s]+)/iu", $this->identity, $info)) {
            return $info[1];
        }

        return $this->identity;
    }
}
