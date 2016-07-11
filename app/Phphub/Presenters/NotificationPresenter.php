<?php namespace Phphub\Presenters;

use Laracasts\Presenter\Presenter;
use Route;

class NotificationPresenter extends Presenter
{
    public function lableUp()
    {
        switch ($this->type) {
            case 'new_reply':
            $lable = lang('Your topic have new reply:');
                break;
            case 'attention':
                $lable = lang('Attented topic has new reply:');
                break;
            case 'at':
                $lable = lang('Mention you At:');
                break;
            case 'topic_favorite':
                $lable = lang('Favorited your topic:');
                break;
            case 'topic_attent':
                $lable = lang('Attented your topic:');
                break;
            case 'topic_upvote':
                $lable = lang('Up Vote your topic');
                break;
            case 'reply_upvote':
                $lable = lang('Up Vote your reply');
                break;
            case 'topic_mark_wiki':
                $lable = lang('has mark your topic as wiki:');
                break;
            case 'topic_mark_excellent':
                $lable = lang('has recomended your topic:');
                break;
            case 'comment_append':
                $lable = lang('Commented topic has new update:');
                break;
            case 'attention_append':
                $lable = lang('Attented topic has new update:');
                break;

            default:
                break;
        }
        return $lable;
    }
}
