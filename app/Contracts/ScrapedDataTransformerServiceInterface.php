<?php

namespace App\Contracts;

interface ScrapedDataTransformerServiceInterface
{
    public function transform(array $data);
}
