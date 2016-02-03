<?php

namespace PeterNijssen\Ses\Manager;

use Aws\Ses\SesClient;
use PeterNijssen\Ses\Exception\InvalidFetchStatusException;
use PeterNijssen\Ses\Model\Dns;
use PeterNijssen\Ses\Model\IdentityInterface;

/**
 * This service manages AWS SES
 */
abstract class SesManager
{
    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * @var IdentityInterface
     */
    protected $identity;

    /**
     * Constructor
     *
     * @param SesClient         $sesClient
     * @param IdentityInterface $identity
     */
    public function __construct(SesClient $sesClient, IdentityInterface $identity)
    {
        $this->sesClient = $sesClient;
        $this->identity = $identity;
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
                'Identities' => [$this->identity->getIdentity()],
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
                'Identities' => [$this->identity->getIdentity()],
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
                'Identities' => [$this->identity->getIdentity()],
            ]
        );

        $records = [];
        $results = $result->search("DkimAttributes.*.DkimTokens");
        if (is_array($results)) {
            foreach (current($results) as $result) {
                $name = $result."._domainkey.".$this->identity->getDomain();
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
                'Domain' => $this->identity->getDomain(),
            ]
        );
    }

    /**
     * Request AWS to enable DKIM
     *
     * @return bool
     */
    public function enableDkim()
    {
        try {
            $this->sesClient->setIdentityDkimEnabled(
                [
                    'DkimEnabled' => true,
                    'Identity' => $this->identity->getIdentity(),
                ]
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Request AWS To disable DKIM
     *
     * @return bool
     */
    public function disableDkim()
    {
        try {
            $this->sesClient->setIdentityDkimEnabled(
                [
                    'DkimEnabled' => false,
                    'Identity' => $this->identity->getIdentity(),
                ]
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
