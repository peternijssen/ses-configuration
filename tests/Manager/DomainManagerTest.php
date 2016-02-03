<?php

namespace PeterNijssen\Ses\Tests\Manager;

use PeterNijssen\Ses\Manager\DomainManager;
use PeterNijssen\Ses\Model\DomainIdentity;

class DomainManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $identity = new DomainIdentity("test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->getMock();

        $manager = new DomainManager($sesClient, $identity);

        $this->assertInstanceOf("PeterNijssen\Ses\Manager\DomainManager", $manager);
    }

    public function testCreate()
    {
        $identity = new DomainIdentity("test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['verifyDomainIdentity'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('verifyDomainIdentity')
            ->with($this->equalTo(['Domain' => $identity->getIdentity()]));

        $manager = new DomainManager($sesClient, $identity);

        $manager->create();
    }

    public function testVerifyDkim()
    {
        $identity = new DomainIdentity("test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['verifyDomainDkim'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('verifyDomainDkim')
            ->with($this->equalTo(['Domain' => $identity->getDomain()]));

        $manager = new DomainManager($sesClient, $identity);

        $manager->verifyDkim();
    }

    public function testEnableDkim()
    {
        $identity = new DomainIdentity("test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['setIdentityDkimEnabled'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('setIdentityDkimEnabled')
            ->with(
                $this->equalTo(
                    [
                        'DkimEnabled' => true,
                        'Identity' => $identity->getIdentity(),
                    ]
                )
            );

        $manager = new DomainManager($sesClient, $identity);

        $manager->enableDkim();
    }

    public function testDisableDkim()
    {
        $identity = new DomainIdentity("test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['setIdentityDkimEnabled'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('setIdentityDkimEnabled')
            ->with($this->equalTo([
                'DkimEnabled' => false,
                'Identity' => $identity->getIdentity(),
            ]));

        $manager = new DomainManager($sesClient, $identity);

        $manager->disableDkim();
    }
}
