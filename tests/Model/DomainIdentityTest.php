<?php

namespace PeterNijssen\Ses\Tests\Model;

use PeterNijssen\Ses\Exception\InvalidIdentityException;
use PeterNijssen\Ses\Model\DomainIdentity;

class DomainIdentityTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstance()
    {
        $this->assertInstanceOf("PeterNijssen\Ses\Model\DomainIdentity", new DomainIdentity("test.com"));

        $this->assertInstanceOf("PeterNijssen\Ses\Model\DomainIdentity", new DomainIdentity("test.photography"));

        $this->assertInstanceOf("PeterNijssen\Ses\Model\DomainIdentity", new DomainIdentity("test.co.uk"));

        foreach (["test", "123", "@test.com"] as $id) {
            try {
                new DomainIdentity($id);
                $this->fail("Invalid identity passed");
            } catch (InvalidIdentityException $e) {
                // Cool :)
            }
        }
    }

    public function testGetIdentity()
    {
        $sesIdentity = new DomainIdentity("test.com");
        $this->assertEquals("test.com", $sesIdentity->getIdentity());
    }

    public function testGetDomain()
    {
        $sesIdentity = new DomainIdentity("test.com");
        $this->assertEquals("test.com", $sesIdentity->getDomain());
    }
}
