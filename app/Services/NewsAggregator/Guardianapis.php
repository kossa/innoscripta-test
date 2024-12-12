<?php

namespace App\Services\NewsAggregator;

use App\Data\ArticleData;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class Guardianapis implements NewsAggregatorInterface
{
    public $http;

    public $defaultParams = [];

    public function __construct()
    {
        $this->http = Http::baseUrl(config('services.guardianapis.base_url'));

        $this->defaultParams = [
            'api-key'    => config('services.guardianapis.api_key'),
            'from'       => today()->subDay()->toDateString(),
            'q'          => 'general',
            'page-size'  => 200,
        ];
    }

    public function getNews(): array
    {
        $response = $this->http->get('search', $this->defaultParams);

        if ($response->failed()) {
            throw new \Exception('Guardianapis: Failed to fetch news');
        }

        return $this->format($response->json());
    }

    public function format(array $response): array
    {
        $articles = [];

        foreach ($response['response']['results'] as $article) {
            $articles[] = (new ArticleData(
                title: $article['webTitle'],
                description: null, // There is no description in the response
                link: $article['webUrl'],
                source: 'guardianapis',
                published_at: CarbonImmutable::parse($article['webPublicationDate']),
            ))->toArray();
        }

        return $articles;
    }
}
