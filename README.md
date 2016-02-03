# SES Configuration

[![Author](http://img.shields.io/badge/author-@ptnijssen-blue.svg?style=flat-square)](https://twitter.com/ptnijssen)
[![Build Status](https://img.shields.io/travis/peternijssen/ses-configuration/master.svg?style=flat-square)](https://travis-ci.org/peternijssen/ses-configuration)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/peternijssen/ses-configuration.svg?style=flat-square)](https://scrutinizer-ci.com/g/peternijssen/ses-configuration/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/peternijssen/ses-configuration.svg?style=flat-square)](https://scrutinizer-ci.com/g/peternijssen/ses-configuration)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/peternijssen/ses-configuration.svg?style=flat-square)](https://packagist.org/packages/peternijssen/ses-configuration)
[![Total Downloads](https://img.shields.io/packagist/dt/peternijssen/ses-configuration.svg?style=flat-square)](https://packagist.org/packages/peternijssen/ses-configuration)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bbce450d-a28d-4659-b7cf-0153d4484904/small.png)](https://insight.sensiolabs.com/projects/bbce450d-a28d-4659-b7cf-0153d4484904)

Package to manage SES configuration. It does not send actual emails!

## Install

Using Composer:

~~~
$ composer require peternijssen/ses-configuration
~~~

## Testing

To run all unit tests, use the locally installed PHPUnit:

~~~
$ ./vendor/bin/phpunit
~~~

## Usage

### AWS SES Client

You have to begin with creating a SesClient

~~~
$sesClient = new \Aws\Ses\SesClient([
    'region' => 'us-west-2',
    'version' => 'latest',
    'credentials' => [
        'key' => 'key',
        'secret' => 'secret',
    ],
]);
~~~

**warning: It's not recommended to store your AWS credentials within the application itself. Please make sure your server has access through [policies](http://docs.aws.amazon.com/ses/latest/DeveloperGuide/control-user-access.html).**

### Identities

First you have to determine you are using an [Email identity or Domain identity](http://docs.aws.amazon.com/ses/latest/DeveloperGuide/verify-addresses-and-domains.html). You can then use the appropriate object;

~~~
$identity = new DomainIdentity("peternijssen.nl");
~~~

or

~~~
$identity = new EmailIdentity("peter@peternijssen.nl");
~~~

### Manager

Next you have to use the correct manager;

~~~
$manager = new DomainManager($sesClient, $identity);
~~~

or

~~~
$manager = new EmailManager($sesClient, $identity);
~~~

From here, you can do several requests;

**Create the new identity within SES**

~~~
$manager->create();
~~~

**Fetch the status (Pending|Success|Failed|TemporaryFailure|NotStarted)**

~~~
$manager->fetchStatus();
~~~

**Fetch the DKIM status (Pending|Success|Failed|TemporaryFailure|NotStarted)**

~~~
$manager->fetchDkimStatus();
~~~

**Fetch the DNS changes (Domain only!)**

~~~
$manager->fetchRecord();
~~~

**Fetch the DKIM DNS changes**

~~~
$manager->fetchDkimRecords();
~~~

**Request to verify the DKIM changes**

~~~
$manager->verifyDkim();
~~~

**Request to Enable DKIM**

~~~
$manager->enableDkim();
~~~

**Request to Disable DKIM**

~~~
$manager->disableDkim();
~~~
