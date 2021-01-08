<?php

namespace App\Services;

use App\Contracts\ScraperServiceInterface;
use Goutte\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class GoutteScraperService implements ScraperServiceInterface
{
    const WEBSITE_URL = 'https://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true';
    const API_URL = "https://www.sainsburys.co.uk/groceries-api/gol-services/product/v1/product?filter[product_seo_url]=gb%2Fgroceries%2Fripe---ready%2F";

    /** @var Client */
    protected $client;

    /**
     * @var ClientInterface
     */
    protected $guzzle;

    /** @var array */
    private $result = [];

    public function __construct(ClientInterface $guzzle)
    {
        $this->client = app()->make(Client::class);
        $this->guzzle = $guzzle;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function scrape(): array
    {
        $crawler = $this->client->request('GET', $this->getUrl());

        $crawler->filter('.productInfo h3 a')->each(function (Crawler $node) {

            $link = $node->link()->getUri();

            $slug = $this->getSlugFromLink($link);

            $response = $this->callApi($slug);

            $responseContent = $response->getBody()->getContents();

            $product = json_decode($responseContent)->products[0];

            $this->result[] = [
                'title' => $product->name,
                'description' => $product->description,
                'unit_price' => $product->retail_price->price,
                'size' => $this->getSize($responseContent)
            ];
        });

        return $this->result;
    }

    public function getUrl(): string
    {
        return static::WEBSITE_URL;
    }

    /**
     * @param string $productSlug
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function callApi(string $productSlug): ResponseInterface
    {
        return $this->guzzle->request('GET', static::API_URL . $productSlug);
    }

    /**
     * @param string $link
     * @return string
     */
    protected function getSlugFromLink(string $link): string
    {
        $linkArray = explode('/', $link);

        return end($linkArray);
    }

    /**
     * @param string $responseContent
     * @return float
     */
    protected function getSize(string $responseContent): float
    {
        return (strlen($responseContent) * 8) / 1000;
    }
}
