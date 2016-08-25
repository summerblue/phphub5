

@if (count($topics))

<ul class="list-group row topic-list">
    @foreach ($topics as $topic)

         <li class="list-group-item media" style="margin-top: 0px;">

             <a class="reply_last_time hidden-xs" href="{{route('topics.show', [$topic->id])}}">
                 @if ($topic->reply_count > 0 && count($topic->lastReplyUser))
                 <img class="user_small_avatar avatar-circle" src="{{ $topic->lastReplyUser->present()->gravatar }}">
                 @else
                 <img class="user_small_avatar avatar-circle" src="{{ $topic->user->present()->gravatar }}">
                 @endif

                 <span class="timeago">{{ $topic->updated_at }}</span>
              </a>

            <div class="avatar pull-left">
                <a href="{{ route('users.show', [$topic->user_id]) }}">
                    <img class="media-object img-thumbnail avatar avatar-middle" alt="{{{ $topic->user->name }}}" src="{{ $topic->user->present()->gravatar }}"/>
                </a>
            </div>

            <div class="reply_count_area hidden-xs" >
                <div class="count_of_votes" title="投票数">
                  {{ $topic->vote_count }}
              </div>
                <div class="count_set">
                    <span class="count_of_replies" title="回复数">
                      {{ $topic->reply_count }}
                    </span>
                    <span class="count_seperator">/</span>
                    <span class="count_of_visits" title="查看数">
                      {{ $topic->view_count }}
                    </span>
                </div>
            </div>

            <div class="infos">

              <div class="media-heading">

                @if ($topic->order > 0 && !Input::get('filter') && Route::currentRouteName() != 'home' )
                    <span class="hidden-xs label label-warning">{{ lang('Stick') }}</span>
                @else
                    <span class="hidden-xs label label-{{ ($topic->is_excellent == 'yes' && Route::currentRouteName() != 'home') ? 'success' : 'default' }}">{{{ $topic->category->name }}}</span>
                @endif

                <a href="{{ route('topics.show', [$topic->id]) }}" title="{{{ $topic->title }}}">
                    {{{ $topic->title }}}
                </a>
              </div>

            </div>

        </li>
    @endforeach
</ul>

@else
   <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
@endif
