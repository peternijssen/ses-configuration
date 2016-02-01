<?php

namespace PeterNijssen\Ses\Manager;

/**
 * This manager handles email identities
 */
class EmailManager extends GeneralManager implements ManagerInterface
{
    /**
     * Create the identity within SES
     */
    public function create()
    {
        $this->sesClient->verifyEmailIdentity(
            [
                'EmailAddress' => $this->sesIdentity->getIdentity(),
            ]
        );
    }
}
