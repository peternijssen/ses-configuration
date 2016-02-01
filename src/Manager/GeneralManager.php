<?php

namespace PeterNijssen\Ses\Manager;

use Aws\Ses\SesClient;
use PeterNijssen\Ses\Exception\InvalidFetchStatusException;
use PeterNijssen\Ses\Model\Dns;
use PeterNijssen\Ses\Model\SesIdentity;

/**
 * This service manages AWS SES
 */
class GeneralManager
{
    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * @var SesIdentity
     */
    protected $sesIdentity;

    /**
     * Constructor
     *
     * @param SesClient $sesClient
     */
    public function __construct(SesClient $sesClient, SesIdentity $sesIdentity)
    {
        $this->sesClient = $sesClient;
        $this->sesIdentity = $sesIdentity;
    }

    /**
     * Fetch the current status
     *
     * @return string
     *
     * @throws InvalidFetchStatusException
     */
    public function fetchStatus()
    {
        $result = $this->sesClient->getIdentityVerificationAttributes(
            [
                'Identities' => [$this->sesIdentity->getIdentity()],
            ]
        );

        $status = $result->search("VerificationAttributes.*.VerificationStatus");

        if (!is_array($status)) {
            throw new InvalidFetchStatusException("unable to fetch the status. Did you create the identity?");
        }

        return current($status);
    }

    /**
     * Fetch the current DKIM status
     *
     * @return string
     *
     * @throws InvalidFetchStatusException
     */
    public function fetchDkimStatus()
    {
        $result = $this->sesClient->getIdentityDkimAttributes(
            [
                'Identities' => [$this->sesIdentity->getIdentity()],
            ]
        );

        $status = $result->search("DkimAttributes.*.DkimVerificationStatus");

        if (!is_array($status)) {
            throw new InvalidFetchStatusException("unable to fetch the DKIM status. Did you create the identity?");
        }

        return current($status);
    }

    /**
     * Return the DNS records for the DKIM settings
     *
     * @return array
     */
    public function fetchDkimRecords()
    {
        $result = $this->sesClient->getIdentityDkimAttributes(
            [
                'Identities' => [$this->sesIdentity->getIdentity()],
            ]
        );

        $records = [];
        $results = $result->search("DkimAttributes.*.DkimTokens");
        if (is_array($results)) {
            foreach (current($results) as $result) {
                $name = $result."._domainkey.".$this->sesIdentity->getDomain();
                $value = $result.".dkim.amazonses.com";

                $records[] = new Dns("CNAME", $name, $value);
            }
        }

        return $records;
    }

    /**
     * Request AWS to verify DKIM
     */
    public function verifyDkim()
    {
        $this->sesClient->verifyDomainDkim(
            [
                'Domain' => $this->sesIdentity->getDomain(),
            ]
        );
    }

    /**
     * Request AWS to enable DKIM
     */
    public function enableDkim()
    {
        $this->sesClient->setIdentityDkimEnabled(
            [
                'DkimEnabled' => true,
                'Identity' => $this->sesIdentity->getIdentity(),
            ]
        );
    }

    /**
     * Request AWS To disable DKIM
     */
    public function disableDkim()
    {
        $this->sesClient->setIdentityDkimEnabled(
            [
                'DkimEnabled' => false,
                'Identity' => $this->sesIdentity->getIdentity(),
            ]
        );
    }
}
