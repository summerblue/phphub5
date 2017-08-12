

@if (count($topics))

<ul class="list-group row topic-list">
    @foreach ($topics as $topic)

         <li class="list-group-item ">

             <a class="reply_count_area hidden-xs pull-right" href="{{ $topic->link() }}">
                 <div class="count_set">
                     <span class="count_of_replies" title="回复数">
                       <i class="fa fa-comment-o"></i> {{ $topic->reply_count }}
                     </span>

                    <span class="count_seperator">/</span>

                     <span class="count_of_visits" title="查看数">
                       <i class="fa fa-eye"></i> {{ number_shorten($topic->view_count) }}
                     </span>
                     <span class="count_seperator">|</span>

                    于 <abbr title="创建时间 {{ $topic->created_at }}" class="timeago">{{ $topic->created_at }}</abbr>
                 </div>
             </a>

            <div class="avatar pull-left ">
                <div class="vote-count-wrap">
                    <a class="vote-count vote" href="javascript:void(0);" data-url="{{ route('topics.upvote', [$topic->id]) }}" data-ajax="post">
                        <span>{{ $topic->vote_count }}</span>
                    </a>
                </div>
            </div>


            <div class="infos">

              <div class="media-heading">

                @if ($topic->order > 0 && !Input::get('filter') && Route::currentRouteName() != 'home' )
                    <span class="hidden-xs label label-warning">{{ lang('Stick') }}</span>
                @endif

                <a href="{{ $topic->link() }}" title="{{{ $topic->title }}}">
                    {{{ $topic->title }}} <span class="share-link-site" data-link="{{ $topic->share_link->link }}">({{ $topic->share_link->site }})</span>
                </a>

              </div>

            </div>

        </li>
    @endforeach
</ul>

@else
   <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
@endif
