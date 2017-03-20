<div class="panel panel-default corner-radius" style="padding-bottom: 20px;">

    <div class="panel-heading text-center">
        <h3 class="panel-title">专栏作者</h3>
    </div>

    <div class="panel-body text-center topic-author-box blog-info text-center">

          @foreach ($authors as $index => $author)
              @if ($author->is_banned != 'yes')
                 <a class="" href="{{ route('users.show', [$author->id]) }}" title="{{{ $author->name . ($author->introduction ? ' - ' . $author->introduction : '')}}}">
                    <img class=" img-thumbnail avatar avatar-middle" alt="{{{ $author->name }}}" src="{{ $author->present()->gravatar }}"/>
                </a>
              @endif
          @endforeach
    </div>

</div>