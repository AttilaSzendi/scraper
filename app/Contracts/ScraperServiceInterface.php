<?php

namespace App\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface ScraperServiceInterface
{
    /**
     * @return array
     */
    public function scrape(): array;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $productSlug
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function callApi(string $productSlug): ResponseInterface;
}
