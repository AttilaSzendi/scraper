<?php

namespace App\Services;

class LocalScraperService extends GoutteScraperService
{
    const MOCK_FILE = "sainsburys-mock.html";

    public function getUrl(): string
    {
        return asset(static::MOCK_FILE);
    }
}
