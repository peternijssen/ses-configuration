<?php

namespace PeterNijssen\Ses\Tests\Manager;

use PeterNijssen\Ses\Manager\EmailManager;
use PeterNijssen\Ses\Model\Dns;
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

    public function testFetchStatus()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['getIdentityVerificationAttributes'])
            ->getMock();

        $awsResult = $this->getMockBuilder('\Aws\Result')
            ->setMethods(['search'])
            ->getMock();

        $awsResult->expects($this->once())
            ->method('search')
            ->willReturn(["success"]);

        $sesClient->expects($this->once())
            ->method('getIdentityVerificationAttributes')
            ->with($this->equalTo(['Identities' => [$identity->getIdentity()]]))
            ->willReturn($awsResult);

        $manager = new EmailManager($sesClient, $identity);

        $this->assertEquals("success", $manager->fetchStatus());
    }

    public function testFetchDkimStatus()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['getIdentityDkimAttributes'])
            ->getMock();

        $awsResult = $this->getMockBuilder('\Aws\Result')
            ->setMethods(['search'])
            ->getMock();

        $awsResult->expects($this->once())
            ->method('search')
            ->willReturn(["success"]);

        $sesClient->expects($this->once())
            ->method('getIdentityDkimAttributes')
            ->with($this->equalTo(['Identities' => [$identity->getIdentity()]]))
            ->willReturn($awsResult);

        $manager = new EmailManager($sesClient, $identity);

        $this->assertEquals("success", $manager->fetchDkimStatus());
    }

    public function testFetchDkimRecords()
    {
        $identity = new EmailIdentity("test@test.com");
        $sesClient = $this->getMockBuilder('\Aws\Ses\SesClient')
            ->disableOriginalConstructor()
            ->setMethods(['getIdentityDkimAttributes'])
            ->getMock();

        $awsResult = $this->getMockBuilder('\Aws\Result')
            ->setMethods(['search'])
            ->getMock();

        $awsResult->expects($this->once())
            ->method('search')
            ->willReturn([0 => ["token1", "token2"]]);

        $sesClient->expects($this->once())
            ->method('getIdentityDkimAttributes')
            ->with($this->equalTo(['Identities' => [$identity->getIdentity()]]))
            ->willReturn($awsResult);

        $manager = new EmailManager($sesClient, $identity);

        $records = [];
        $records[] = new Dns("CNAME", "token1._domainkey.test.com", "token1.dkim.amazonses.com");
        $records[] = new Dns("CNAME", "token2._domainkey.test.com", "token2.dkim.amazonses.com");

        $this->assertEquals($records, $manager->fetchDkimRecords());
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
