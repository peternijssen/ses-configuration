<?php

namespace PeterNijssen\Ses\Manager;
use Aws\Ses\SesClient;
use PeterNijssen\Ses\Model\EmailIdentity;

/**
 * This manager handles email identities
 */
class EmailManager extends SesManager
{
    /**
     * Constructor
     *
     * @param SesClient     $sesClient
     * @param EmailIdentity $identity
     */
    public function __construct(SesClient $sesClient, EmailIdentity $identity)
    {
        parent::__construct($sesClient, $identity);
    }

    /**
     * Create the identity within SES
     */
    public function create()
    {
        $this->sesClient->verifyEmailIdentity(
            [
                'EmailAddress' => $this->identity->getIdentity(),
            ]
        );
    }
}
