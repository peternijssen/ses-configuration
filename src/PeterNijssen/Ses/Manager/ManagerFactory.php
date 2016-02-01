<?php

namespace PeterNijssen\Ses\Manager;

use Aws\Ses\SesClient;
use PeterNijssen\Ses\Model\SesIdentity;

/**
 * Factory to generate a manager which fits the identity type
 */
class ManagerFactory
{
    /**
     * Create a manager for the identity
     *
     * @param SesClient   $sesClient
     * @param SesIdentity $sesIdentity
     *
     * @return ManagerInterface
     */
    public function createManager(SesClient $sesClient, SesIdentity $sesIdentity)
    {
        $className = "PeterNijssen\\Ses\\Manager\\".ucfirst($sesIdentity->getType())."Manager";
        if (class_exists($className)) {
            return new $className($sesClient, $sesIdentity);
        }
        throw new \InvalidArgumentException('Unknown manager: '.$sesIdentity->getType());
    }
}
