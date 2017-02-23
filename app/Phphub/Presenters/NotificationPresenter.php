<?php namespace Phphub\Presenters;

use Laracasts\Presenter\Presenter;
use Route;

class NotificationPresenter extends Presenter
{
    public function lableUp()
    {
        switch ($this->type) {
            case 'new_topic_from_subscribe':
                $lable = "在你订阅的专栏中发布了";
                break;
            case 'mentioned_in_topic':
                $lable = "在话题中提及你";
                break;
            case 'new_topic_from_following':
                $lable = "你关注的用户发布了新话题";
                break;
            case 'new_reply':
                $lable = lang('Your topic have new reply:');
                break;
            case 'attention':
                $lable = lang('Attented topic has new reply:');
                break;
            case 'attented_append':
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
            case 'vote_append':
                $lable = lang('Attented topic has new update:');
                break;
            case 'follow':
                $lable = lang('Someone following you');
            default:
                break;
        }
        return $lable;
    }

    // for API
    public function message()
    {
        $message = $this->fromUser->name . ' ⋅ ' . $this->lableUp();

        if (count($this->topic)) {
            $message .= ' ⋅ ' . $this->topic->title;
        }
        return $message;
    }
}
