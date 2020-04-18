<?php

namespace NikoPeikrishvili\TwilioMinimal;

use NikoPeikrishvili\TwilioMinimal\Exceptions\InvalidTwilioConfigException;

final class TwilioConfig
{
    protected $apiUrl = 'https://api.twilio.com/';
    protected $version = '2010-04-01';
    protected $clientSid;
    protected $authToken;
    protected $senderNumber;

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getClientSid(): string
    {
        return $this->clientSid;
    }

    /**
     * @param string $clientSid
     */
    public function setClientSid(string $clientSid)
    {
        $this->clientSid = $clientSid;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken(string $authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * @return string
     */
    public function getSenderNumber(): string
    {
        return $this->senderNumber;
    }

    /**
     * @param string $senderNumber
     */
    public function setSenderNumber(string $senderNumber)
    {
        if ($senderNumber['0'] !== '+') {
            $senderNumber = "+" . $senderNumber;
        }
        $this->senderNumber = $senderNumber;
    }


    /**
     * @return bool
     * @throws \ReflectionException
     * @throws InvalidTwilioConfigException
     */
    public function checkConfig(): bool
    {
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($props as $prop) {
            $prop->setAccessible(true);
            if (empty($prop->getValue($this))) {
                throw new InvalidTwilioConfigException("Config param " . $prop->getName() . " is empty");
            }
        }
        return true;
    }
}
