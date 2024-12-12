<?php

namespace App\Console\Commands;

use App\Services\NewsAggregator\Newsapi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LoadNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load news from aggregator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $aggregators = [
            'newsapi' => new Newsapi,
        ];

        $bar = $this->output->createProgressBar(count($aggregators));

        $bar->start();

        try {
            foreach ($aggregators as $aggregator_name => $aggregator) {

                $articles = $aggregator->getNews();

                info('Fetched ' . count($articles) . ' articles from ' . $aggregator_name);

                DB::table('articles')->insert($articles);

                $bar->advance();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $bar->finish();

        $this->newLine()
                ->newLine()
                ->info('News loaded successfully');
    }
}
