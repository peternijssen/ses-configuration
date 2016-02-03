<?php

namespace PeterNijssen\Ses\Tests\Model;

use PeterNijssen\Ses\Exception\InvalidIdentityException;
use PeterNijssen\Ses\Model\EmailIdentity;

class EmailIdentityTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstance()
    {
        $this->assertInstanceOf("PeterNijssen\Ses\Model\EmailIdentity", new EmailIdentity("test@test.com"));

        $this->assertInstanceOf("PeterNijssen\Ses\Model\EmailIdentity", new EmailIdentity("test@test.photography"));

        $this->assertInstanceOf("PeterNijssen\Ses\Model\EmailIdentity", new EmailIdentity("test@test.co.uk"));

        foreach (["test", "123", "@test.com"] as $id) {
            try {
                new EmailIdentity($id);
                $this->fail("Invalid identity passed");
            } catch (InvalidIdentityException $e) {
                // Cool :)
            }
        }
    }

    public function testGetIdentity()
    {
        $sesIdentity = new EmailIdentity("test@test.com");
        $this->assertEquals("test@test.com", $sesIdentity->getIdentity());
    }

    public function testGetDomain()
    {
        $sesIdentity = new EmailIdentity("test@test.com");
        $this->assertEquals("test.com", $sesIdentity->getDomain());
    }
}
