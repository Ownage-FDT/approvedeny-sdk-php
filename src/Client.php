<?php

declare(strict_types=1);

namespace Ownage\Approvedeny;

use GuzzleHttp\Client as GuzzleHttpClient;
use Ownage\Approvedeny\Exceptions\ClientException;

final class Client
{
    /**
     * @var string Approvedeny server base URL
     */
    private string $baseUrl = 'https://api.approvedeny.com';

    /**
     * @var GuzzleHttpClient|null Guzzle client instance
     */
    private ?GuzzleHttpClient $client = null;

    /**
     * Create a new Approvedeny client instance.
     *
     * @param  string  $apiKey  Your API key
     * @throws \Ownage\Approvedeny\Exceptions\ClientException
     *
     * @example $client = new Client('api_key');
     */
    public function __construct(protected string $apiKey)
    {
        if ($this->apiKey === '') {
            throw ClientException::invalidApiKey();
        }

        $this->connect();
    }

    /**
     * Connect to the Approvedeny server.
     */
    private function connect(): void
    {
        $this->client = new GuzzleHttpClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'approvedeny-php/1.0.0',
            ],
            'http_errors' => false,
        ]);
    }

    /**
     * Get a single check request.
     *
     * @param  string  $checkRequestId  The ID of the check request
     * @return array  The check request data
     *
     * @example $checkRequest = $client->getCheckRequest('check_request_id');
     */
    public function getCheckRequest(string $checkRequestId): array
    {
        $response = $this->client->get('/v1/requests/'.$checkRequestId);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Create a new check request.
     *
     * @param  string  $checkId  The ID of the check to create a request for
     * @param  array  $payload  The payload of the check request
     * @return array  The check request data
     *
     * @example $checkRequest = $client->createCheckRequest('check_id', [
     *    'description' => 'A description for the check request',
     *    'metadata' => [
     *      'key' => 'value',
     *     ],
     * ]);
     */
    public function createCheckRequest(string $checkId, array $payload): array
    {
        $response = $this->client->post('/v1/checks/'.$checkId, ['json' => $payload]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get a single check request response.
     *
     * @param  string  $checkRequestId  The ID of the check request
     * @return array  The check request response data
     *
     * @example $check = $client->getCheckRequestResponse('check_request_id');
     */
    public function getCheckRequestResponse(string $checkRequestId): array
    {
        $response = $this->client->get('/v1/requests/'.$checkRequestId.'/response');

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Check if a webhook signature is valid.
     *
     * @param  string  $encryptionKey The encryption key used for webhook signature validation
     * @param  string  $signature  The webhook signature
     * @param  array  $payload  The webhook payload
     * @return bool
     *
     * @example $isValidSignature = $client->isValidWebhookSignature('encryption-key','signature', ['key' => 'value']);
     */
    public function isValidWebhookSignature(string $encryptionKey, string $signature, array $payload): bool
    {

        $calculatedSignature = hash_hmac('sha256', json_encode($payload), $encryptionKey);

        return hash_equals($calculatedSignature, $signature);
    }

    /**
     * Get the Guzzle client instance.
     *
     * @return GuzzleHttpClient
     */
    public function getGuzzleClient(): GuzzleHttpClient
    {
        return $this->client;
    }

    /**
     * Set a custom Guzzle client instance.
     *
     * @param  GuzzleHttpClient  $client
     * @return void
     */
    public function setGuzzleClient(GuzzleHttpClient $client): void
    {
        $this->client = $client;
    }
}
