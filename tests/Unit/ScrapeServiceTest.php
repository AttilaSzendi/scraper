<?php

namespace Tests\Unit;

use App\Services\GoutteScraperService;
use App\Services\UnavailableScraperService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class ScrapeServiceTest extends TestCase
{
    /** @var Client */
    protected $guzzle;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzle = $this->app->make(Client::class);
    }

    /**
     * @throws GuzzleException
     * @test
     */
    public function it_throws_exception_if_the_sainsburys_api_service_is_down()
    {
        $this->expectException(ConnectException::class);
        $class = new UnavailableScraperService($this->guzzle);
        $class->scrape();
    }

    /**
     * @throws GuzzleException
     * @test
     */
    public function it_returns_the_products_array()
    {
        $class = new GoutteScraperService($this->guzzle);
        $result = $class->scrape();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result[0]);
        $this->assertArrayHasKey('description', $result[0]);
        $this->assertArrayHasKey('unit_price', $result[0]);
        $this->assertArrayHasKey('size', $result[0]);
    }
}
