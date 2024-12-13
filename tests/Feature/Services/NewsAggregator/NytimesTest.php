<?php

use App\Services\NewsAggregator\Nytimes;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
    $this->base_url = config('services.nytimes.base_url') . '/articlesearch.json';
});

it('Fetches news from Nytimes', function () {
    Http::fake([
        $this->base_url => Http::response([
            'response' => [
                'docs' => [
                    [
                        'headline' => [
                            'main' => 'Test Title',
                        ],
                        'abstract' => 'Test Description',
                        'web_url'  => 'https://example.com',
                        'pub_date' => now()->toIso8601String(),
                    ],
                ],
            ],
        ]),
    ]);

    $nytimes = new Nytimes;

    $news = $nytimes->getNews();

    expect($news)->toBeArray();
    expect($news)->toHaveCount(1);
    expect($news[0])->toHaveKeys(['title', 'description', 'link', 'source', 'published_at']);
});

it('Handles empty news response from Nytimes', function () {
    Http::fake([
        $this->base_url => Http::response([
            'response' => [
                'docs' => [],
            ],
        ]),
    ]);

    $nytimes = new Nytimes;

    $news = $nytimes->getNews();

    expect($news)->toBeArray();
    expect($news)->toHaveCount(0);
});

it('Throws exception on failed request to Nytimes', function () {
    Http::fake([
        $this->base_url => Http::response(null, 500),
    ]);

    $nytimes = new Nytimes;

    expect(fn () => $nytimes->getNews())->toThrow(\Exception::class, 'Nytimes: Failed to fetch news');
});
