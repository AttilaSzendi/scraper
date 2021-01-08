<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface;

class UnavailableScraperService extends GoutteScraperService
{
    const MOCK_FILE = "sainsburys-mock.html";
    const NOT_EXISTING_URI = "http://not-exists.hu/jatekok";

    public function getUrl(): string
    {
        return asset(static::MOCK_FILE);
    }

    public function callApi(string $productSlug): ResponseInterface
    {
        return $this->guzzle->request('GET', static::NOT_EXISTING_URI);
    }
}
