<?php namespace Phphub\Presenters;

use Laracasts\Presenter\Presenter;
use App\Models\Site;

class SitePresenter extends Presenter
{
    public function linkWithUTMSource()
    {
        $append = 'utm_source=phphub.org';
        return strpos($this->link, '?') === false
                    ? $this->link . '?' . $append
                    : $this->link . '&' . $append;
    }
}
