<?php

namespace PeterNijssen\Ses\Tests\Model;

use PeterNijssen\Ses\Exception\InvalidIdentityException;
use PeterNijssen\Ses\Model\SesIdentity;

class SesIdentityTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstance()
    {
        $this->assertInstanceOf("PeterNijssen\Ses\Model\SesIdentity", new SesIdentity("test@test.com"));
        $this->assertInstanceOf("PeterNijssen\Ses\Model\SesIdentity", new SesIdentity("test.com"));

        $this->assertInstanceOf("PeterNijssen\Ses\Model\SesIdentity", new SesIdentity("test@test.photography"));
        $this->assertInstanceOf("PeterNijssen\Ses\Model\SesIdentity", new SesIdentity("test.photography"));

        $this->assertInstanceOf("PeterNijssen\Ses\Model\SesIdentity", new SesIdentity("test@test.co.uk"));
        $this->assertInstanceOf("PeterNijssen\Ses\Model\SesIdentity", new SesIdentity("test.co.uk"));

        foreach (["test", "123", "@test.com"] as $id) {
            try {
                new SesIdentity("peternijssen");
                $this->fail("Invalid identity passed");
            } catch (InvalidIdentityException $e) {
                // Cool :)
            }
        }
    }
}
