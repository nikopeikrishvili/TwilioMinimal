<?php

namespace NikoPeikrishvili\TwilioMinimal;

use NikoPeikrishvili\TwilioMinimal\Exceptions\InvalidTwilioConfigException;
use PHPUnit\Framework\TestCase;

class TwilioConfigTest extends TestCase
{
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new TwilioConfig();
    }

    public function testSetClientSid()
    {
        $this->config->setClientSid(getenv("TWILIO_SID"));
        $this->assertEquals(getenv("TWILIO_SID"), $this->config->getClientSid());
    }

    public function testSetAuthToken()
    {
        $this->config->setAuthToken(getenv("TWILIO_AUTH_TOKEN"));
        $this->assertEquals(getenv("TWILIO_AUTH_TOKEN"), $this->config->getAuthToken());
    }

    public function testSetApiUrl()
    {
        $this->config->setApiUrl(getenv("TWILIO_API_URL"));
        $this->assertEquals(getenv("TWILIO_API_URL"), $this->config->getApiUrl());
    }

    public function testSetVersion()
    {
        $this->config->setVersion(getenv("TWILIO_VERSION"));
        $this->assertEquals(getenv("TWILIO_VERSION"), $this->config->getVersion());
    }

    public function testSetSenderNumber()
    {
        $this->config->setSenderNumber(getenv("TWILIO_NUMBER"));
        $this->assertEquals(getenv("TWILIO_NUMBER"), $this->config->getSenderNumber());
    }

    public function testCheckConfigException()
    {
        $this->expectException(InvalidTwilioConfigException::class);
        $this->config->checkConfig();
    }

    public function testCheckConfig()
    {
        $this->config->setAuthToken(getenv("TWILIO_AUTH_TOKEN"));
        $this->config->setClientSid(getenv("TWILIO_SID"));
        $this->config->setApiUrl(getenv("TWILIO_API_URL"));
        $this->config->setVersion(getenv("TWILIO_VERSION"));
        $this->config->setSenderNumber(getenv("TWILIO_NUMBER"));

        $this->assertIsBool($this->config->checkConfig());
        $this->assertTrue($this->config->checkConfig());
    }
}
