<?php

namespace App\Services;

use App\Contracts\ScrapedDataTransformerServiceInterface;

class ScrapedDataTransformerService implements ScrapedDataTransformerServiceInterface
{
    public function transform(array $data): array
    {
        return [
            'results' => $this->transformData($data),
            'total' => $this->getTotal($data)
        ];
    }

    protected function transformData(array $data): array
    {
        $items = [];

        foreach ($data as $key => $product) {
            if(isset($data[$key]['title'])) {
                $items[$key]['title'] = $product['title'];
            }

            if(isset($data[$key]['unit_price'])) {
                $items[$key]['unit_price'] = $product['unit_price'];
            }

            if(isset($data[$key]['description'])) {
                $items[$key]['description'] = $this->getDescription($product['description']);
            }

            if(isset($data[$key]['size'])) {
                $items[$key]['size'] = $this->appendUnitTo($product['size']);
            }
        }

        return $items;
    }

    /**
     * @param array $data
     * @return float
     */
    protected function getTotal(array $data): float
    {
        if(!count($data)) {
            return 0.0;
        }

        return array_sum(array_column($data, 'unit_price'));
    }

    /**
     * @param array $descriptionArray
     * @return string
     */
    protected function getDescription(array $descriptionArray): string
    {
        $filteredDescriptionArray = array_filter($descriptionArray);
        $trimmedAndFilteredDescriptionArray = array_map('trim', $filteredDescriptionArray);

        return implode(' - ', $trimmedAndFilteredDescriptionArray);
    }

    /**
     * @param float $size
     * @return string
     */
    protected function appendUnitTo(float $size): string
    {
        return round($size, 2) . 'kb';
    }
}
