<div class="result">
<h2 class="title">

    <a href="{{ $topic->link() }}">{{ $topic->title }}</a>

    <small>by</small>

    <a href="{{ route('users.show', [$topic->user_id]) }}" title="{{ $topic->user->name }}">
        <img class="avatar avatar-small" alt="{{ $topic->user->name }}" src="{{ $topic->user->present()->gravatar }}"/>
        <small>{{ $topic->user->name }}</small>
    </a>

</h2>
<div class="info">
  <span class="url">
        <a href="{{ $topic->link() }}">{{ $topic->link() }}</a>
  </span>
  <span class="date" title="Last Updated At">
      {{ Carbon\Carbon::parse($topic->created_at)->format('Y-m-d') }}

      ⋅
      <i class="fa fa-eye"></i> {{ $topic->view_count }}
      ⋅
      <i class="fa fa-thumbs-o-up"></i> {{ $topic->vote_count }}
      ⋅
      <i class="fa fa-comments-o"></i> {{ $topic->reply_count }}

  </span>

</div>
<div class="desc">
    {{ str_limit($topic->body_original, 250) }}
</div>
<hr>
</div>
