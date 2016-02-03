<?php

namespace PeterNijssen\Ses\Model;

use PeterNijssen\Ses\Exception\InvalidIdentityException;

/**
 * Domain identity
 */
class DomainIdentity extends SesIdentity implements IdentityInterface
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
        if (!preg_match('/^((\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,32}|[0-9]{1,32})(\]?))$/', $identity)
        ) {
            throw new InvalidIdentityException("The identity is not a valid domain");
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
        return $this->identity;
    }
}
