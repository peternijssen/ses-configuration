<?php

namespace PeterNijssen\Ses\Manager;

use Aws\Ses\SesClient;
use PeterNijssen\Ses\Model\Dns;
use PeterNijssen\Ses\Model\DomainIdentity;

/**
 * This manager handles domain identities
 */
class DomainManager extends SesManager
{
    /**
     * Constructor
     *
     * @param SesClient      $sesClient
     * @param DomainIdentity $identity
     */
    public function __construct(SesClient $sesClient, DomainIdentity $identity)
    {
        parent::__construct($sesClient, $identity);
    }

    /**
     * Create the identity within SES
     */
    public function create()
    {
        $this->sesClient->verifyDomainIdentity(
            [
                'Domain' => $this->identity->getIdentity(),
            ]
        );
    }

    /**
     * Return the DNS record for the domain
     *
     * @return Dns
     */
    public function fetchRecord()
    {
        $result = $this->sesClient->verifyDomainIdentity(
            [
                'Domain' => $this->identity->getIdentity(),
            ]
        );

        $value = $result->search("VerificationToken");
        $name = "_amazonses.".$this->identity->getDomain();

        return new Dns("TXT", $name, $value);
    }
}
