<?php

namespace PeterNijssen\Ses\Tests\Model;

use PeterNijssen\Ses\Model\Dns;

class DnsTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstance()
    {
        $this->assertInstanceOf("PeterNijssen\Ses\Model\Dns", new Dns("TXT", "test.com", "1234"));
    }

    public function testGetType()
    {
        $dns = new Dns("TXT", "test.com", "1234");
        $this->assertEquals("TXT", $dns->getType());
    }

    public function testGetName()
    {
        $dns = new Dns("TXT", "test.com", "1234");
        $this->assertEquals("test.com", $dns->getName());
    }

    public function testGetValue()
    {
        $dns = new Dns("TXT", "test.com", "1234");
        $this->assertEquals("1234", $dns->getValue());
    }
}
