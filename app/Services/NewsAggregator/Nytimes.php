<?php

namespace App\Services\NewsAggregator;

use App\Data\ArticleData;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class Nytimes implements NewsAggregatorInterface
{
    public $http;

    public $defaultParams = [];

    public function __construct()
    {
        $this->http = Http::baseUrl(config('services.nytimes.base_url'));

        $this->defaultParams = [
            'api-key'    => config('services.nytimes.api_key'),
            'begin_date' => today()->subDay()->toDateString(),
            'q'          => 'general',
        ];
    }

    public function getNews(): array
    {
        $response = $this->http->get('articlesearch.json', $this->defaultParams);

        if ($response->failed()) {
            throw new \Exception('Nytimes: Failed to fetch news');
        }

        return $this->format($response->json());
    }

    public function format(array $response): array
    {
        $articles = [];

        foreach ($response['response']['docs'] as $article) {
            $articles[] = (new ArticleData(
                title: $article['headline']['main'],
                description: $article['abstract'],
                link: $article['web_url'],
                source: 'nytimes',
                published_at: CarbonImmutable::parse($article['pub_date']),
            ))->toArray();
        }

        return $articles;
    }
}
