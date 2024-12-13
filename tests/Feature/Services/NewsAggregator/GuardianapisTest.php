<?php

use App\Services\NewsAggregator\Guardianapis;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
    $this->base_url = config('services.guardianapis.base_url') . '/search*';
});

it('Fetches news from Guardianapis', function () {
    Http::fake([
        $this->base_url => Http::response([
            'response' => [
                'results' => [
                    [
                        'webTitle'           => 'Test Title',
                        'webUrl'             => 'https://example.com',
                        'webPublicationDate' => now()->toIso8601String(),
                    ],
                ],
            ],
        ]),
    ]);

    $guardianapis = new Guardianapis;

    $news = $guardianapis->getNews();

    expect($news)->toBeArray();
    expect($news)->toHaveCount(1);
    expect($news[0])->toHaveKeys(['title', 'description', 'link', 'source', 'published_at']);
});

it('Handles empty news response from Guardianapis', function () {
    Http::fake([
        $this->base_url => Http::response([
            'response' => [
                'results' => [],
            ],
        ]),
    ]);

    $guardianapis = new Guardianapis;

    $news = $guardianapis->getNews();

    expect($news)->toBeArray();
    expect($news)->toHaveCount(0);
});

it('Throws exception on failed request to Guardianapis', function () {
    Http::fake([
        $this->base_url => Http::response(null, 500),
    ]);

    $guardianapis = new Guardianapis;

    expect(fn () => $guardianapis->getNews())->toThrow(\Exception::class, 'Guardianapis: Failed to fetch news');
});
