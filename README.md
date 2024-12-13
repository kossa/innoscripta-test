# News aggregator website using Laravel
Building a Laravel website to get data from aggregators and return them in api.

## Installation
```shell
git clone https://github.com/kossa/innoscripta-test.git
cd innoscripta-test
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate # make sure you have a database and update .env file
php artisan app:load-news # Load news from aggregators
php artisan serve
```

Visit `http://localhost:8000/api/articles` in your browser.


### Filtering and sorting
You can filter and sort the articles by using the following query parameters:
- `filter[title]`: Filter articles by title.
- `filter[id]`: Filter articles by id.
- `filter[source]`: Filter articles by source.
- `filter[title, source]`: Filter articles by title and source.
- `sort=-id`: Sort articles by id in descending order.
- `sort=id`: Sort articles by id in ascending order.
- `sort=-published_at`: Sort articles by published_at in descending order.


### Integrated aggregators
- [News API](https://newsapi.org/)
- [The guardianapis](https://open-platform.theguardian.com/)
- [New York Times](https://developer.nytimes.com/)


### Third party packages
- [spatie/laravel-data](https://github.com/spatie/laravel-data/): Usually I use Laravel [resources](https://laravel.com/docs/11.x/eloquent-resources) to transform models into JSON, and [form validation](https://laravel.com/docs/11.x/validation#form-request-validation) to validate incoming requests. But I wanted to try this package to see how it works.
- [spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder): This package is used to filter, sort and include data in the response. It's very useful when you have a lot of data and you want to filter it based on some criteria.


### License
The MIT License (MIT).
