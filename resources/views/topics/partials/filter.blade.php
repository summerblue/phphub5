<ul class="pull-right list-inline remove-margin-bottom topic-filter">

    <li>
        <a {!! app(App\Models\Topic::class)->present()->topicFilter('recent') !!}>
            <i class="glyphicon glyphicon-time"></i> {{ lang('Recent') }}
        </a>
        <span class="divider"></span>
    </li>

    <li>
        <a {!! app(App\Models\Topic::class)->present()->topicFilter('excellent') !!}>
            <i class="glyphicon glyphicon-ok"> </i> {{ lang('Excellent') }}
        </a>
        <span class="divider"></span>
    </li>

    <li>
        <a {!! app(App\Models\Topic::class)->present()->topicFilter('vote') !!}>
            <i class="glyphicon glyphicon-thumbs-up"> </i> {{ lang('Vote') }}
        </a>
        <span class="divider"></span>
    </li>

    <li>
        <a {!! app(App\Models\Topic::class)->present()->topicFilter('noreply') !!}>
            <i class="glyphicon glyphicon-eye-open"></i> {{ lang('Noreply') }}
        </a>
    </li>
</ul>
