

@if (count($topics))

<ul class="list-group row topic-list">
    @foreach ($topics as $topic)

         <li class="list-group-item ">

             <a class="reply_count_area hidden-xs pull-right" href="{{ $topic->link() }}">
                 <div class="count_set">
                     <span class="count_of_votes" title="投票数">
                       {{ $topic->vote_count }}
                    </span>

                    <span class="count_seperator">/</span>

                     <span class="count_of_replies" title="回复数">
                       {{ $topic->reply_count }}
                     </span>

                    <span class="count_seperator">/</span>

                     <span class="count_of_visits" title="查看数">
                       {{ number_shorten($topic->view_count) }}
                     </span>
                     <span class="count_seperator">|</span>

                      <abbr title="{{ $topic->updated_at }}" class="timeago">{{ $topic->updated_at }}</abbr>
                 </div>
             </a>

            <div class="avatar pull-left">
                <a href="{{ route('users.show', [$topic->user_id]) }}" title="{{{ $topic->user->name }}}">
                    <img class="media-object img-thumbnail avatar avatar-middle" alt="{{{ $topic->user->name }}}" src="{{ $topic->user->present()->gravatar }}"/>
                </a>
            </div>


            <div class="infos">

              <div class="media-heading">

                @if ($topic->order > 0 && !Input::get('filter') && Route::currentRouteName() != 'home' )
                    <span class="hidden-xs label label-warning">{{ lang('Stick') }}</span>
                @else
                    {{-- @if (!Request::is('categories/'.config('phphub.blog_category_id'))) --}}
                        <span class="hidden-xs label label-{{ ($topic->is_excellent == 'yes' && Route::currentRouteName() != 'home') ? 'success' : 'default' }}">{{{ $topic->category->name }}}</span>
                    {{-- @endif --}}
                @endif

                <a href="{{ $topic->link() }}" title="{{{ $topic->title }}}">
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
