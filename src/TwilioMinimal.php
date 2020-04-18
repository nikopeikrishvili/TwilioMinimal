<?php

namespace NikoPeikrishvili\TwilioMinimal;

use GuzzleHttp\Client;
use NikoPeikrishvili\TwilioMinimal\Exceptions\InvalidTextSizeException;
use NikoPeikrishvili\TwilioMinimal\Exceptions\UnableToSendMessageException;
use Psr\Http\Message\ResponseInterface;

class TwilioMinimal
{

    /**
     * @var TwilioConfig
     */
    private $config;

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * TwilioMinimal constructor.
     * @param TwilioConfig $config
     */
    public function __construct(TwilioConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $receiver
     * @param string $text
     * @return bool
     * @throws UnableToSendMessageException
     */
    public function send(string $receiver, string $text): bool
    {
        try {
            $response = $this->getClient()->post($this->getSmsSendUri(), $this->getParams($receiver, $text));
            return $this->validateResponse($response);
        } catch (\Throwable $exception) {
            throw new UnableToSendMessageException($exception->getMessage());
        }
    }

    protected function validateResponse(ResponseInterface $response)
    {
        $result = $response->getBody()->getContents();
        $object = json_decode($result);
        if (in_array($object->status, $this->getSuccessfullySendCodes())) {
            return true;
        }
        throw new UnableToSendMessageException($object->status . " " . $object->error_message);
    }

    /**
     * Get Guzzle Http Client
     * kind of Singleton implementation
     * @return GuzzleHttp\Client
     */
    private function getClient()
    {
        if (!$this->client instanceof Client) {
            $this->client = new Client([
                "base_uri" => $this->getBaseUri()
            ]);
        }
        return $this->client;
    }

    /**
     * Get Request Params for Guzzle Request
     * @param string $receiver
     * @param string $text
     * @return array|array[]
     */
    private function getParams(string $receiver, string $text): array
    {
        return [
            'form_params' => [
                "Body" => $this->validateText($text),
                "To" => $this->normalizeNumber($receiver),
                "From" => $this->config->getSenderNumber()
            ],
            'auth' => [
                $this->config->getClientSid(),
                $this->config->getAuthToken()
            ]
        ];
    }

    /**
     * Get URI string injected with version number
     * @return string
     */
    private function getBaseUri(): string
    {
        return $this->config->getApiUrl() . $this->config->getVersion() . "/Accounts/";
    }

    private function getSmsSendUri(): string
    {
        return $this->config->getClientSid() . "/Messages.json";
    }

    /**
     * Normalize number in case if number starts without "+" sign
     * @param string $phoneNumber
     * @return string
     */
    private function normalizeNumber(string $phoneNumber): string
    {
        if ('+' !== $phoneNumber['0']) {
            $phoneNumber = '+' . $phoneNumber;
        }
        return $phoneNumber;
    }

    /**
     * @return array|string[]
     */
    private function getSuccessfullySendCodes(): array
    {
        return ['queued', 'sent'];
    }

    /**
     * Validate SMS message
     * @param string $text
     * @return string
     * @throws InvalidTextSizeException
     */
    private function validateText(string $text): string
    {
        if (empty($text) || strlen($text) > 1600) {
            throw new InvalidTextSizeException("Text size must be > 0 < 1600 characters");
        }
        return $text;
    }
}
