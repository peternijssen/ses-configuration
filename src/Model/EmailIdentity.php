<?php

namespace PeterNijssen\Ses\Model;
use PeterNijssen\Ses\Exception\InvalidIdentityException;

/**
 * Email identity
 */
class EmailIdentity extends SesIdentity implements IdentityInterface
{
    /**
     * Domain identity constructor.
     *
     * @param string $identity domain
     *
     * @throws InvalidIdentityException
     */
    public function __construct($identity)
    {
        if (!preg_match('/^.+\@\S+\.\S+$/', $identity)) {
            throw new InvalidIdentityException("The identity is not a valid email address");
        }

        $this->identity = $identity;
    }

    /**
     * Get the domain for this identity
     *
     * @return string
     */
    public function getDomain()
    {
        if (preg_match("/@([^\s]+)/iu", $this->identity, $info)) {
            return $info[1];
        }
    }
}

