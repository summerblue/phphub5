

@if (count($topics))

<ul class="list-group row topic-list">
    @foreach ($topics as $topic)
        <li class="list-group-item media col-md-6" style="margin-top: 0px;">

             <a class="reply_last_time hidden-xs meta" href="{{ $topic->link() }}">
                 {{ $topic->vote_count }} {{ lang('Up Votes') }}
                 <span> ⋅ </span>
                 {{ $topic->reply_count }} {{ lang('Replies') }}
              </a>

            <div class="avatar pull-left">
                <a href="{{ route('users.show', [$topic->user_id]) }}">
                    <img class="media-object img-thumbnail avatar avatar-middle" alt="{{{ $topic->user->name }}}" src="{{ $topic->user->present()->gravatar }}"/>
                </a>
            </div>

            <div class="infos">

              <div class="media-heading">

                @if ($topic->order > 0 && !Input::get('filter') && Route::currentRouteName() != 'home' )
                    <span class="hidden-xs label label-warning">{{ lang('Stick') }}</span>
                @else
                    <span class="hidden-xs label label-{{ ($topic->is_excellent == 'yes' && Route::currentRouteName() != 'home') ? 'success' : 'default' }}">{{{ $topic->category->name }}}</span>
                @endif

                <a href="{{ $topic->link() }}" title="{{ $topic->title }}">
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
