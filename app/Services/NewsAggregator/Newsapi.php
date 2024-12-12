<?php

namespace App\Services\NewsAggregator;

use App\Data\ArticleData;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class Newsapi implements NewsAggregatorInterface
{
    public $http;

    public $defaultParams = [];

    public function __construct()
    {
        $this->http = Http::baseUrl(config('services.newsapi.base_url'));

        $this->defaultParams = [
            'apiKey'   => config('services.newsapi.api_key'),
            'from'     => today()->subDay()->toDateString(),
            'category' => 'general',
        ];
    }

    public function getNews(): array
    {
        $response = $this->http->get('top-headlines', $this->defaultParams);

        if ($response->failed()) {
            throw new \Exception('Newsapi: Failed to fetch news');
        }

        return $this->format($response->json());
    }

    public function format(array $response): array
    {
        $articles = [];

        foreach ($response['articles'] as $article) {
            $articles[] = (new ArticleData(
                title: $article['title'],
                description: $article['description'],
                link: $article['url'],
                source: 'newsapi',
                published_at: CarbonImmutable::parse($article['publishedAt']),
            ))->toArray();
        }

        return $articles;
    }
}
