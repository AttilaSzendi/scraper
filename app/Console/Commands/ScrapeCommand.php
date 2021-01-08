<?php

namespace App\Console\Commands;

use App\Contracts\ScrapedDataTransformerServiceInterface;
use App\Contracts\ScraperServiceInterface;
use Illuminate\Console\Command;

class ScrapeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'scraper:scrape';

    /**
     * @var string
     */
    protected $description = 'It scrapes the Sainsbury\'s website ripe food page';

    /**
     * @var ScraperServiceInterface
     */
    protected $scraperService;

    /**
     * @var ScrapedDataTransformerServiceInterface
     */
    protected $dataTransformerService;

    /**
     * @param ScraperServiceInterface $scraperService
     * @param ScrapedDataTransformerServiceInterface $dataTransformerService
     * @return void
     */
    public function __construct(
        ScraperServiceInterface $scraperService,
        ScrapedDataTransformerServiceInterface $dataTransformerService
    ) {
        parent::__construct();

        $this->scraperService = $scraperService;
        $this->dataTransformerService = $dataTransformerService;
    }

    public function handle()
    {
        $data = $this->scraperService->scrape();

        $transformedData = $this->dataTransformerService->transform($data);

        $json_string = json_encode($transformedData, JSON_PRETTY_PRINT);

        $this->info($json_string);
    }
}
