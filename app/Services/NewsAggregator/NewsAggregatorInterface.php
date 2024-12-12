<?php

namespace App\Services\NewsAggregator;

interface NewsAggregatorInterface
{
    public function getNews(): array;

    public function format(array $response): array;
}
