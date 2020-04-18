<?php

namespace NikoPeikrishvili\TwilioMinimal;

use GuzzleHttp\Psr7\Response;
use NikoPeikrishvili\TwilioMinimal\Exceptions\InvalidTextSizeException;
use NikoPeikrishvili\TwilioMinimal\Exceptions\UnableToSendMessageException;
use PHPUnit\Framework\TestCase;

class TwilioMinimalTest extends TestCase
{

    protected $config;
    protected $twilioMinimal;

    public function setUp(): void
    {
        parent::setUp(); //
        $this->config = new TwilioConfig();
        $this->config->setAuthToken(getenv("TWILIO_AUTH_TOKEN"));
        $this->config->setClientSid(getenv("TWILIO_SID"));
        $this->config->setApiUrl(getenv("TWILIO_API_URL"));
        $this->config->setVersion(getenv("TWILIO_VERSION"));
        $this->config->setSenderNumber(getenv("TWILIO_NUMBER"));

        $this->twilioMinimal = new TwilioMinimal($this->config);
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    public function testValidateResponseSuccess()
    {
        $string = json_encode(['status' => 'sent']);
        $response = new Response(200, ['Content-type' => 'application/json', 'accept' => 'application/json'], $string);
        $state = $this->invokeMethod($this->twilioMinimal, 'validateResponse', [$response]);
        $this->assertTrue($state);
    }

    public function testValidateResponseFail()
    {
        $this->expectException(UnableToSendMessageException::class);
        $this->expectExceptionMessage("failed error_message");
        $string = json_encode(['status' => 'failed', 'error_message' => 'error_message']);
        $response = new Response(200, ['Content-type' => 'application/json', 'accept' => 'application/json'], $string);
        $state = $this->invokeMethod($this->twilioMinimal, 'validateResponse', [$response]);
    }

    public function testSendFail()
    {
        $this->expectException(UnableToSendMessageException::class);
        $result = $this->twilioMinimal->send(getenv("TWILIO_ERROR_NUMBER"), "1234");
        $this->assertTrue($result);
    }

    public function testGetParams()
    {
        $params = [
            'form_params' => [
                "Body" => "text",
                "To" => "+100000000000",
                "From" => $this->config->getSenderNumber()
            ],
            'auth' => [
                $this->config->getClientSid(),
                $this->config->getAuthToken()
            ]
        ];
        $state = $this->invokeMethod($this->twilioMinimal, 'getParams', ['+100000000000', 'text']);
        $this->assertSame($params, $state);
    }

    public function testGetBaseUriValid()
    {
        $valid = "https://api.twilio.com/" . $this->config->getVersion() . "/Accounts/";
        $state = $this->invokeMethod($this->twilioMinimal, 'getBaseUri');
        $this->assertSame($valid, $state);
    }

    public function testGetSmsSendUriValid()
    {
        $valid = $this->config->getClientSid() . '/Messages.json';
        $state = $this->invokeMethod($this->twilioMinimal, 'getSmsSendUri');
        $this->assertSame($valid, $state);
    }

    public function testValidateTextMaxCharacters()
    {
        $text = str_repeat("A", 1601);
        $this->expectException(InvalidTextSizeException::class);
        $state = $this->invokeMethod($this->twilioMinimal, 'validateText', array($text));
    }

    public function testValidateTextMinCharacters()
    {
        $this->expectException(InvalidTextSizeException::class);
        $state = $this->invokeMethod($this->twilioMinimal, 'validateText', array(''));
    }

    public function testNormalizeNumberWithPlus()
    {
        $number = '+100000000000';
        $state = $this->invokeMethod($this->twilioMinimal, 'normalizeNumber', array($number));
        $this->assertSame('+100000000000', $state);
    }

    public function testNormalizeNumberWithoutPlus()
    {
        $number = '100000000000';
        $state = $this->invokeMethod($this->twilioMinimal, 'normalizeNumber', array($number));
        $this->assertSame('+100000000000', $state);
    }

    public function testValidateTextSuccess()
    {
        $state = $this->invokeMethod($this->twilioMinimal, 'validateText', array('small text'));
        $this->assertSame("small text", $state);
    }
}
