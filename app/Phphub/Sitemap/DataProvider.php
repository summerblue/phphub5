<?php namespace Phphub\Sitemap;

use Illuminate\Routing\UrlGenerator;
use App\Models\Topic;
use App\Models\Category;

class DataProvider
{
    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    /**
     * Topic Eloquent Model
     *
     * @var \Topic
     */
    protected $topics;

    /**
     * Catebory Eloquent Model
     *
     * @var \Category
     */
    protected $categories;

    /**
     * Create a new data provider instance.
     *
     * @param  \Illuminate\Routing\UrlGenerator  $url
     * @param  \Topic                            $topics
     * @param  \Category                         $categories
     * @return void
     */
    public function __construct(UrlGenerator $url, Topic $topics, Category $categories)
    {
        $this->url    = $url;
        $this->topics = $topics;
        $this->categories  = $categories;
    }

    /**
     * Get all the topic to include in the sitemap.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Topic[]
     */
    public function getTopics()
    {
        return $this->topics->recent()->get();
    }

    /**
     * Get the url for the given topic.
     *
     * @param  \Topic  $topic
     * @return string
     */
    public function getTopicUrl($topic)
    {
        return $topic->link();
    }

    /**
     * Get all the Categories to include in the sitemap.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Category[]
     */
    public function getCategories()
    {
        return $this->categories->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get the url for the given category.
     *
     * @param  \Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        return $this->url->route('categories.show', $category->id);
    }

    /**
     * Get all the static pages to include in the sitemap.
     *
     * @return array
     */
    public function getStaticPages()
    {
        $static = [];

        $static[] = $this->getPage('home', 'daily', '1.0');
        $static[] = $this->getPage('topics.index', 'daily', '1.0');
        $static[] = $this->getPage('users.index', 'weekly', '0.8');
        $static[] = $this->getPage('about', 'monthly', '0.7');

        return $static;
    }

    /**
     * Get the data for the given page.
     *
     * @param  string  $route
     * @param  string  $freq
     * @param  string  $priority
     * @return array
     */
    protected function getPage($route, $freq, $priority)
    {
        $url = $this->url->route($route);

        return compact('url', 'freq', 'priority');
    }
}
