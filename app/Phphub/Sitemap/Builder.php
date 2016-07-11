<?php namespace Phphub\Sitemap;

use Roumen\Sitemap\Sitemap;
use Illuminate\Config\Repository;

class Builder
{
    /**
     * The type of sitemap to build.
     *
     * @var string
     */
    protected $type = 'xml';

    /**
     * Config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * The sitemap generator instance.
     *
     * @var \Roumen\Sitemap\Sitemap
     */
    protected $sitemap;

    /**
     * The data provider instance.
     *
     * @var \Phphub\Sitemap\DataProvider
     */
    protected $provider;

    /**
     * Create a new sitemap builder instance.
     *
     * @param  \Roumen\Sitemap\Sitemap                $sitemap
     * @param  \Phphub\Sitemap\DataProvider  $provider
     * @param  \Illuminate\Config\Repository          $config
     * @return void
     */
    public function __construct(Sitemap $sitemap, DataProvider $provider, Repository $config)
    {
        $this->sitemap = $sitemap;
        $this->provider = $provider;
        $this->config = $config;
    }

    /**
     * Set the type of sitemap to build.
     *
     * @param  string  $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = strtolower($type);
    }

    /**
     * Build the sitemap.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if (!$this->sitemap->isCached()) {
            $this->addStaticPages();

            foreach ($this->getTypes()['custom'] as $type => $config) {
                $this->addDynamicData($type, $config);
            }
        }

        return $this->sitemap->render($this->type);
    }

    /**
     * Add the static pages to the sitemap
     *
     * @return void
     */
    protected function addStaticPages()
    {
        $pages = $this->provider->getStaticPages();

        foreach ($pages as $page) {
            $this->sitemap->add($page['url'], null, $page['priority'], $page['freq']);
        }
    }

    /**
     * Get the dynamic data types.
     *
     * @return array
     */
    protected function getTypes()
    {
        return $this->config->get('sitemap');
    }

    /**
     * Add the dynamic data of the given type to the sitemap.
     *
     * @param  string  $type
     * @param  array   $config
     * @return void
     */
    protected function addDynamicData($type, $config)
    {
        foreach ($this->getItems($type) as $item) {
            $url     = $this->getItemUrl($item, $type);
            $lastMod = $item->{$config['lastMod']};

            $this->sitemap->add($url, $lastMod, $config['priority'], $config['freq']);
        }
    }

    /**
     * Get the dynamic items from the data provider.
     *
     * @param  string  $type
     * @return \Illuminate\Support\Collection
     */
    protected function getItems($type)
    {
        $method = $this->getDataMethodName($type);
        return $this->provider->$method();
    }

    /**
     * Get the name of the data method.
     *
     * @param  string  $type
     * @return string
     */
    protected function getDataMethodName($type)
    {
        return 'get' . studly_case($type);
    }

    /**
     * Get the url of the given item.
     *
     * @param  mixed   $item
     * @param  string  $type
     * @return string
     */
    protected function getItemUrl($item, $type)
    {
        $method = $this->getUrlMethodName($type);

        return $this->provider->$method($item);
    }

    /**
     * Get the name of the url method.
     *
     * @param  string  $type
     * @return string
     */
    protected function getUrlMethodName($type)
    {
        return 'get' . studly_case(str_singular($type)) . 'Url';
    }
}
