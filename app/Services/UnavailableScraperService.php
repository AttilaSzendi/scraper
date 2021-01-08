<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface;

class UnavailableScraperService extends GoutteScraperService
{
    const NOT_EXISTING_URI = "http://not-exists.hu/jatekok";

    public function callApi(string $productSlug): ResponseInterface
    {
        return $this->guzzle->request('GET', static::NOT_EXISTING_URI);
    }
}
