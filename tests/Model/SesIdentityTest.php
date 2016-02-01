<?php

namespace PeterNijssen\Ses\Model;


use PeterNijssen\Ses\Exception\InvalidIdentityException;

class SesIdentityTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstance()
    {
        $this->assertInstanceOf(SesIdentity::class, new SesIdentity("test@test.com"));
        $this->assertInstanceOf(SesIdentity::class, new SesIdentity("test.com"));

        $this->assertInstanceOf(SesIdentity::class, new SesIdentity("test@test.photography"));
        $this->assertInstanceOf(SesIdentity::class, new SesIdentity("test.photography"));

        $this->assertInstanceOf(SesIdentity::class, new SesIdentity("test@test.co.uk"));
        $this->assertInstanceOf(SesIdentity::class, new SesIdentity("test.co.uk"));

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
