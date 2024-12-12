<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;

class ArticleData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public string $link,
        public string $source,
        #[Date]
        public CarbonImmutable $published_at,
    ) {}
}
