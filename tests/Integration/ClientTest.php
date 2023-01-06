<?php

namespace Netflie\WhatsAppCloudApi\Tests\Integration;

use Netflie\WhatsAppCloudApi\Client;
use Netflie\WhatsAppCloudApi\Message\TextMessage;
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Tests\WhatsAppCloudApiTestConfiguration;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
final class ClientTest extends TestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = new Client(WhatsAppCloudApi::DEFAULT_GRAPH_VERSION);
    }

    public function test_send_text_message()
    {
        $message = new TextMessage(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es',
            true
        );
        $request = new Request\MessageRequest\RequestTextMessage(
            $message,
            WhatsAppCloudApiTestConfiguration::$access_token,
            WhatsAppCloudApiTestConfiguration::$from_phone_number_id
        );

        $response = $this->client->sendMessage($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
    }

    public function test_upload_media()
    {
        $request = new Request\MediaRequest\UploadMediaRequest(
            'tests/Support/netflie.png',
            WhatsAppCloudApiTestConfiguration::$from_phone_number_id,
            WhatsAppCloudApiTestConfiguration::$access_token
        );

        $response = $this->client->uploadMedia($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
        $this->assertArrayHasKey('id', $response->decodedBody());
    }
}
