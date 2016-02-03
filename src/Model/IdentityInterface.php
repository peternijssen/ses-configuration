<?php

namespace PeterNijssen\Ses\Model;

/**
 * Interface for identity types
 */
interface IdentityInterface
{
    /**
     * Get the domain for this identity
     *
     * @return string
     */
    public function getDomain();

    /**
     * Get the identity. If correct, this is either an email address or domain
     *
     * @return string
     */
    public function getIdentity();
}
