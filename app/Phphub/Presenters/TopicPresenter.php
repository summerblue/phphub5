<?php namespace Phphub\Presenters;

use Laracasts\Presenter\Presenter;
use Input;
use URL;
use Request;
use Config;

class TopicPresenter extends Presenter
{
    public function topicFilter($filter)
    {
       $category_id = Request::segment(2);
       $category_append = '';
       if (Request::is('categories*') && $category_id) {
           $link = url('categories', $category_id) . '?filter=' . $filter;
       } else {
           $query_append = '';
           $query = Input::except('filter', '_pjax');
           if ($query) {
               $query_append = '&'.http_build_query($query);
           }
           $link = URL::to('topics') . '?filter=' . $filter . $query_append . $category_append;
       }
       $selected = Input::get('filter') ?
                               (Input::get('filter') == $filter ? ' class="active"':'')
                               : ($filter == 'default' ? ' class="active"':'');
       return 'href="' . $link . '"' . $selected;

    }

    public function voteState($vote_type)
    {
        if ($this->votes()->ByWhom(Auth::id())->WithType($vote_type)->count()) {
            return 'active';
        } else {
            return;
        }
    }

    public function replyFloorFromIndex($index)
    {
        $index += 1;
        $current_page = Input::get('page') ?: 1;
        return ($current_page - 1) * Config::get('phphub.replies_perpage') + $index;
    }
}
