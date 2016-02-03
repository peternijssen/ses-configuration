<?php

namespace PeterNijssen\Ses\Tests\Manager;

use PeterNijssen\Ses\Manager\EmailManager;
use PeterNijssen\Ses\Model\EmailIdentity;

class EmailManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->getMock();

        $manager = new EmailManager($sesClient, $identity);

        $this->assertInstanceOf("PeterNijssen\Ses\Manager\EmailManager", $manager);
    }

    public function testCreate()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['verifyEmailIdentity'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('verifyEmailIdentity')
            ->with($this->equalTo(['EmailAddress' => $identity->getIdentity()]));

        $manager = new EmailManager($sesClient, $identity);

        $manager->create();
    }

    public function testVerifyDkim()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['verifyDomainDkim'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('verifyDomainDkim')
            ->with($this->equalTo(['Domain' => $identity->getDomain()]));

        $manager = new EmailManager($sesClient, $identity);

        $manager->verifyDkim();
    }

    public function testEnableDkim()
    {
        $identity = new EmailIdentity("test@test.com");
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

        $manager = new EmailManager($sesClient, $identity);

        $manager->enableDkim();
    }

    public function testDisableDkim()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['setIdentityDkimEnabled'])
            ->getMock();

        $sesClient->expects($this->once())
            ->method('setIdentityDkimEnabled')
            ->with(
                $this->equalTo(
                    [
                        'DkimEnabled' => false,
                        'Identity' => $identity->getIdentity(),
                    ]
                )
            );

        $manager = new EmailManager($sesClient, $identity);

        $manager->disableDkim();
    }
}
