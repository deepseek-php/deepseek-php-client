<?php
namespace DeepSeekPhp\Tests;


use DeepSeekPhp\Enums\Requests\HTTPState;
use PHPUnit\Framework\TestCase;
use DeepSeekPhp\DeepseekClient;

class HandelResultDeepseekTest extends TestCase
{
    protected $apiKey;
    protected $expiredApiKey;
    protected function setUp():void
    {
        $this->apiKey = "valid-api-key";
        $this->expiredApiKey = "expired-api-key";
    }
    public function test_ok_response()
    {
        $deepseek = DeepseekClient::build($this->apiKey)
            ->query('Hello Deepseek, how are you today?')
            ->setTemperature(1.5);
        $response = $deepseek->run();
        $result = $deepseek->getResult();

        $this->assertNotEmpty($response);
        $this->assertEquals(HTTPState::OK->value, $result->getStatusCode());
    }
    public function test_can_not_access_with_api_expired_payment()
    {
        $deepseek = DeepseekClient::build($this->expiredApiKey)
            ->query('Hello Deepseek, how are you today?')
            ->setTemperature(1.5);
        $response = $deepseek->run();
        $result = $deepseek->getResult();

        $this->assertNotEmpty($response);
        if(!$result->isSuccess())
        {
            $this->assertEquals(HTTPState::PAYMENT_REQUIRED->value, $result->getStatusCode());
        }
    }
    public function test_access_with_wrong_api_key()
    {
        $deepseek = DeepseekClient::build($this->apiKey."wrong-api-key")
            ->query('Hello Deepseek, how are you today?')
            ->setTemperature(1.5);
        $response = $deepseek->run();
        $result = $deepseek->getResult();
        
        $this->assertNotEmpty($response);
        $this->assertEquals(HTTPState::UNAUTHORIZED->value, $result->getStatusCode());
    }
}
