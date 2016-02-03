<?php

namespace PeterNijssen\Ses\Model;

/**
 * Main SES identity
 */
abstract class SesIdentity
{
    /**
     * @var string
     */
    protected $identity;

    /**
     * Get the identity. If correct, this is either an email address or domain
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
