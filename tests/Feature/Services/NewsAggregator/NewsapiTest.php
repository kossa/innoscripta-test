<?php

use App\Services\NewsAggregator\Newsapi;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
    $this->base_url = config('services.newsapi.url') . '/top-headlines*';
});

it('Fetches news from Newsapi', function () {
    Http::fake([
        $this->base_url => Http::response([
            'articles' => [
                [
                    'title'       => 'Test Title',
                    'description' => 'Test Description',
                    'url'         => 'https://example.com',
                    'publishedAt' => now()->toIso8601String(),
                ],
            ],
        ]),
    ]);

    $newsapi = new Newsapi;

    $news = $newsapi->getNews();

    expect($news)->toBeArray();
    expect($news)->toHaveCount(1);
    expect($news[0])->toHaveKeys(['title', 'description', 'link', 'source', 'published_at']);
});

it('Handles empty news response from Newsapi', function () {
    Http::fake([
        $this->base_url => Http::response([
            'articles' => [],
        ]),
    ]);

    $newsapi = new Newsapi;

    $news = $newsapi->getNews();

    expect($news)->toBeArray();
    expect($news)->toHaveCount(0);
});

it('Throws exception on failed request to Newsapi', function () {
    Http::fake([
        $this->base_url => Http::response(null, 500),
    ]);

    $newsapi = new Newsapi;

    expect(fn () => $newsapi->getNews())->toThrow(\Exception::class, 'Newsapi: Failed to fetch news');
});
