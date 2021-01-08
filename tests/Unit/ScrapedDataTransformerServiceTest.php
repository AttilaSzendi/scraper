<?php

namespace Tests\Unit;

use App\Services\ScrapedDataTransformerService;
use ReflectionClass;
use Tests\TestCase;

class ScrapedDataTransformerServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_wraps_in_results()
    {
        $data = [];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $expectedResult = [
            'results' => $data,
            'total' => 0.0
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function it_appends_total_to_the_result_as_zero_if_empty_array_was_provided()
    {
        $data = [];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $expectedResult = [
            'results' => $data,
            'total' => 0.0
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function it_appends_total_to_the_result_calculated_from_the_provided_array()
    {
        $data = [
            ['unit_price' => 1.34],
            ['unit_price' => 2.66],
        ];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $this->assertEquals(4.0, $result['total']);
    }

    /**
     * @test
     */
    public function it_calculates_total_from_items_unit_price()
    {
        $data = [
            ['unit_price' => 1.34],
            ['unit_price' => 2.66],
        ];

        $classInstance = new ScrapedDataTransformerService();

        $reflection = new ReflectionClass($classInstance);
        $method = $reflection->getMethod('getTotal');
        $method->setAccessible(true);
        $result = $method->invokeArgs($classInstance, [$data]);

        $this->assertEquals(4.0, $result);
    }

    /**
     * @test
     */
    public function it_transforms_description_array_to_string()
    {
        $data = [
            ['description' => ['foo', 'bar']],
            ['description' => ['baz', 'qux']],
        ];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $expectedResult = [
            ['description' => 'foo - bar'],
            ['description' => 'baz - qux'],
        ];

        $this->assertEquals($expectedResult, $result['results']);
    }

    /**
     * @test
     */
    public function it_trim_every_description_item()
    {
        $data = [
            ['description' => ['  foo', '   bar   ']],
            ['description' => ['baz  ', 'qux  ']],
        ];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $expectedResult = [
            ['description' => 'foo - bar'],
            ['description' => 'baz - qux'],
        ];

        $this->assertEquals($expectedResult, $result['results']);
    }

    /**
     * @test
     */
    public function it_removes_empty_description_items()
    {
        $data = [
            ['description' => ['foo', '', 'bar']],
            ['description' => ['baz', '', 'qux']],
        ];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $expectedResult = [
            ['description' => 'foo - bar'],
            ['description' => 'baz - qux'],
        ];

        $this->assertEquals($expectedResult, $result['results']);
    }

    /**
     * @test
     */
    public function it_transforms_size_to_string_by_appending_the_kb_unit()
    {
        $data = [
            ['size' => 20.2412341],
            ['size' => 242.2345626],
        ];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $this->assertEquals('20.24kb', $result['results'][0]['size']);
        $this->assertEquals('242.23kb', $result['results'][1]['size']);
    }

    /**
     * @test
     */
    public function it_appends_kb_to_float_numbers()
    {
        $data = 10.23;

        $classInstance = new ScrapedDataTransformerService();

        $reflection = new ReflectionClass($classInstance);
        $method = $reflection->getMethod('appendUnitTo');
        $method->setAccessible(true);
        $result = $method->invokeArgs($classInstance, [$data]);

        $this->assertEquals('10.23kb', $result);
    }

    /**
     * @test
     */
    public function it_rounds_float_numbers_to_maximum_two_decimal_units()
    {
        $data = 10.2323452345;

        $classInstance = new ScrapedDataTransformerService();

        $reflection = new ReflectionClass($classInstance);
        $method = $reflection->getMethod('appendUnitTo');
        $method->setAccessible(true);
        $result = $method->invokeArgs($classInstance, [$data]);

        $this->assertEquals('10.23kb', $result);
    }

    /**
     * @test
     */
    public function it_contains_every_data()
    {
        $data = [
            [
                'title' => 'foo',
                'description' => ['bar', 'biz'],
                'unit_price' => 12.32,
                'size' => 212.32434,
            ]
        ];

        $class = new ScrapedDataTransformerService();
        $result = $class->transform($data);

        $this->assertArrayHasKey('title', $result['results'][0]);
        $this->assertArrayHasKey('description', $result['results'][0]);
        $this->assertArrayHasKey('unit_price', $result['results'][0]);
        $this->assertArrayHasKey('size', $result['results'][0]);
    }
}
