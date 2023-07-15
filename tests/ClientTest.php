<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Ownage\Approvedeny\Client;
use Ownage\Approvedeny\Exceptions\ClientException;

it('creates an instance of approvedeny client', function () {
    $client = new Client('__api_key__');
    $this->assertInstanceOf(Client::class, $client);
    $this->assertNotEmpty($client->getGuzzleClient());
});

it('throws an exception when an invalid API key is provided', function () {
    new Client('');
})->throws(ClientException::MSG_INVALID_API_KEY);

it('returns an instance Guzzle client', function () {
    $client = new Client('__api_key__');
    $this->assertInstanceOf(GuzzleClient::class, $client->getGuzzleClient());
});

it('can set a custom Guzzle client instance', function () {
    $client = new Client('__api_key__');
    $mockClient = $this->createMock(GuzzleClient::class);
    $client->setGuzzleClient($mockClient);
    $this->assertEquals($mockClient, $client->getGuzzleClient());
});

it('can get a single check request', function () {
    $client = new Client('__api_key__');
    $expectedResponse = json_decode(file_get_contents('tests/stubs/get_a_check_request.json'), true);

    $mock = new MockHandler([
        new Response(200, [], json_encode($expectedResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
    $client->setGuzzleClient($guzzleClient);

    $response = $client->getCheckRequest('check-id');

    expect($response)->toBe($expectedResponse);
});

it('can create a new check request', function () {
    $client = new Client('__api_key__');
    $expectedResponse = json_decode(file_get_contents('tests/stubs/create_check_request.json'), true);

    $mock = new MockHandler([
        new Response(200, [], json_encode($expectedResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
    $client->setGuzzleClient($guzzleClient);

    $response = $client->createCheckRequest('check-id', [
        'description' => 'Test Description',
        'metadata' => [
            'key' => 'value',
        ]]
    );

    expect($response)->toBe($expectedResponse);
});

it('can get a single check request response', function () {
    $client = new Client('__api_key__');
    $expectedResponse = json_decode(file_get_contents('tests/stubs/get_a_check_request_response.json'), true);

    $mock = new MockHandler([
        new Response(200, [], json_encode($expectedResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
    $client->setGuzzleClient($guzzleClient);

    $response = $client->getCheckRequestResponse('check-response-id');

    expect($response)->toBe($expectedResponse);
});

it('returns true for a valid signature', function () {
    $client = new Client('__api_key__');
    $encryptionKey = 'my_encryption_key';
    $payload = ['foo' => 'bar'];
    $signature = hash_hmac('sha256', json_encode($payload), $encryptionKey);

    expect($client->isValidWebhookSignature($encryptionKey, $signature, $payload))->toBeTrue();
});

it('returns false for an invalid signature', function () {
    $client = new Client('__api_key__');
    $encryptionKey = 'my_encryption_key';
    $payload = ['foo' => 'bar'];


    expect($client->isValidWebhookSignature($encryptionKey, 'invalid_signature', $payload))->toBeFalse();
});
