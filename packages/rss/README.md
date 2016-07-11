# RSS

RSS builder for Laravel 4

[![Build Status](https://travis-ci.org/thujohn/rss-l4.png?branch=master)](https://travis-ci.org/thujohn/rss-l4)


## Installation

Add `thujohn/rss` to `composer.json`.

    "thujohn/rss": "~1.0"
    
Run `composer update` to pull down the latest version of RSS.

Now open up `app/config/app.php` and add the service provider to your `providers` array.

    'providers' => array(
        'Thujohn\Rss\RssServiceProvider',
    )

Now add the alias.

    'aliases' => array(
        'Rss' => 'Thujohn\Rss\RssFacade',
    )


## Usage

Returns the feed

	Route::get('/', function()
	{
		$feed = Rss::feed('2.0', 'UTF-8');
		$feed->channel(array('title' => 'Channel\'s title', 'description' => 'Channel\'s description', 'link' => 'http://www.test.com/'));
		for ($i=1; $i<=5; $i++){
			$feed->item(array('title' => 'Item '.$i, 'description|cdata' => 'Description '.$i, 'link' => 'http://www.test.com/article-'.$i));
		}

		return Response::make($feed, 200, array('Content-Type' => 'text/xml'));
	});

Save the feed

	Route::get('/', function()
	{
		$feed = Rss::feed('2.0', 'UTF-8');
		$feed->channel(array('title' => 'Channel\'s title', 'description' => 'Channel\'s description', 'link' => 'http://www.test.com/'));
		for ($i=1; $i<=5; $i++){
			$feed->item(array('title' => 'Item '.$i, 'description|cdata' => 'Description '.$i, 'link' => 'http://www.test.com/article-'.$i));
		}

		$feed->save('test.xml');
	});
