<?php
namespace Tests\Feature;

use DeepSeek\DeepSeekClient;
use DeepSeek\DeepSeekClientOptions;
use DeepSeek\Enums\Requests\HTTPState;
use PHPUnit\Framework\TestCase;

class HandelResultDeepseekTest extends TestCase
{
    private DeepSeekClientOptions $clientOptions;

    function __construct(string $name)
    {
        parent::__construct($name);

        $this->clientOptions = new DeepSeekClientOptions();
    }

    protected string $apiKey;
    protected string $expiredApiKey;
    protected function setUp():void
    {
        $this->apiKey = "valid-api-key";
        $this->expiredApiKey = "expired-api-key";
    }
    public function test_ok_response()
    {
        $this->clientOptions->apiKey = $this->apiKey;

        $query = DeepSeekClient::build($this->clientOptions)
            ->query('Hello DeepSeek, how are you today?')
            ->withTemperature(1.5);

        $result = $query->run();

        $this->assertNotEmpty($result);
        $this->assertEquals(HTTPState::OK->value, $result->getStatusCode());
    }
    public function test_can_not_access_with_api_expired_payment()
    {
        $this->clientOptions->apiKey = $this->expiredApiKey;

        $query = DeepSeekClient::build($this->clientOptions)
            ->query('Hello Deepseek, how are you today?')
            ->withTemperature(1.5);

        $result = $query->run();

        $this->assertNotEmpty($result);
        $this->assertEquals(HTTPState::PAYMENT_REQUIRED->value, $result->getStatusCode());
    }
    public function test_access_with_wrong_api_key()
    {
        $this->clientOptions->apiKey = "wrong-api-key";

        $query = DeepSeekClient::build($this->clientOptions)
            ->query('Hello Deepseek, how are you today?')
            ->withTemperature(1.5);

        $result = $query->run();

        $this->assertNotEmpty($result);
        $this->assertEquals(HTTPState::UNAUTHORIZED->value, $result->getStatusCode());
    }
}
