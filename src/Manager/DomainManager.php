<?php

namespace PeterNijssen\Ses\Manager;

use PeterNijssen\Ses\Model\Dns;

/**
 * This manager handles domain identities
 */
class DomainManager extends GeneralManager implements ManagerInterface
{
    /**
     * Create the identity within SES
     */
    public function create()
    {
        $this->sesClient->verifyDomainIdentity(
            [
                'Domain' => $this->sesIdentity->getIdentity(),
            ]
        );
    }

    /**
     * Return the DNS record for the domain
     *
     * @return Dns
     */
    public function fetchDnsRecord()
    {
        $result = $this->sesClient->verifyDomainIdentity(
            [
                'Domain' => $this->sesIdentity->getIdentity(),
            ]
        );

        $value = $result->search("VerificationToken");
        $name = "_amazonses.".$this->sesIdentity->getDomain();

        return new Dns("TXT", $name, $value);
    }
}
