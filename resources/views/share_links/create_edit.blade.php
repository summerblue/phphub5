@extends('layouts.default')

@section('title')
{{ isset($topic) ? '编辑链接' : '分享链接' }}_@parent
@stop

@section('content')

<div class="topic_create share-link">

    <h1 class="header"><i class="fa fa-link"></i> 分享</h1>

  <div class="col-md-8 main-col">

    <div class="reply-box form box-block">

      @include('layouts.partials.errors')

      @if (isset($topic))
        <form method="POST" action="{{ route('topics.update', $topic->id) }}" accept-charset="UTF-8" id="topic-edit-form" class="topic-form">
        <input name="_method" type="hidden" value="PATCH">
      @else
        <form method="POST" action="{{ route('topics.store') }}" accept-charset="UTF-8" id="topic-create-form" class="topic-form">
      @endif
        {!! csrf_field() !!}

        <input name="category_id" type="hidden" value="{{ config('phphub.hunt_category_id') }}">

        <div class="form-group">
            <input class="form-control" id="topic-title" placeholder="{{ lang('Please write down a topic') }}" name="title" type="text" value="{{ !isset($topic) ? '' : $topic->title }}" required="require">
        </div>

        <div class="form-group">
            <input class="form-control" id="topic-link" placeholder="分享的链接" name="link" type="text" value="{{ !isset($topic) ? '' : $topic->share_link->link }}" required="require">
        </div>

        <div class="form-group">
            <textarea class="form-control" rows="20" style="overflow:hidden" id="reply_content" placeholder="描述，可不写！" name="body" cols="30">{{ !isset($topic) ? '' : $topic->body_original }}</textarea>
        </div>

        <div class="form-group status-post-submit">
            <button class="btn btn-primary submit-btn" id="topic-submit" type="submit"><i class="fa fa-paper-plane"></i> 分享链接</button>
        </div>

    </form>

    </div>
  </div>

  <div class="col-md-4 side-bar">

    <div class="panel panel-default corner-radius help-box">
      <div class="panel-heading text-center">
        <h3 class="panel-title">{{ lang('This kind of topic is not allowed.') }}</h3>
      </div>
      <div class="panel-body">
        <ul class="list">
          <li>请传播美好的事物，拒绝低俗、诋毁、谩骂等相关信息</li>
          <li>请尽量分享技术相关的话题，谢绝社会, 政治等新闻</li>
          <li>无论任何情况下，请保持友善</li>
          <li>绝对不讨论有关盗版软件、音乐、电影如何获得的问题</li>
      </div>
    </div>


  </div>
</div>

<script>
    Config.topic_id = '{{ isset($topic) ? $topic->id : 0 }}';
</script>

@stop

@section('scripts')

<link rel="stylesheet" href="{{ cdn(elixir('assets/css/editor.css')) }}">
<script src="{{ cdn(elixir('assets/js/editor.js')) }}"></script>

<script type="text/javascript">

    $(document).ready(function()
    {
        @if ( ! isset($topic))
            localforage.getItem('topic-title', function(err, value) {
                if ($('#topic-title').val() == '' && !err) {
                    $('#topic-title').val(value);
                };
            });
            $('#topic-title').keyup(function(){
                localforage.setItem('topic-title', $(this).val());
            });
        @endif

        $('#category-select').on('change', function() {
            var current_cid = $(this).val();
            $('.category-hint').hide();
            $('.category-'+current_cid).fadeIn();
        });

        var simplemde = new SimpleMDE({
            spellChecker: false,
            autosave: {
                enabled: true,
                delay: 5000,
                unique_id: "topic_content{{ isset($topic) ? $topic->id . '_' . str_slug($topic->updated_at) : '' }}"
            },
            forceSync: true,
            tabSize: 4,
            toolbar: [
                "bold", "italic", "heading", "|", "quote", "code", "table",
                "horizontal-rule", "unordered-list", "ordered-list", "|",
                "link", "image", "|",  "side-by-side", 'fullscreen', "|",
                {
                    name: "guide",
                    action: function customFunction(editor){
                        var win = window.open('https://github.com/riku/Markdown-Syntax-CN/blob/master/syntax.md', '_blank');
                        if (win) {
                            //Browser has allowed it to be opened
                            win.focus();
                        } else {
                            //Browser has blocked it
                            alert('Please allow popups for this website');
                        }
                    },
                    className: "fa fa-info-circle",
                    title: "Markdown 语法！",
                },
                {
                    name: "publish",
                    action: function customFunction(editor){
                        $('#topic-submit').click();
                    },
                    className: "fa fa-paper-plane",
                    title: "发布文章",
                }
            ],
        });

        inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, {
            uploadUrl: Config.routes.upload_image,
            extraParams: {
              '_token': Config.token,
            },
            onFileUploadResponse: function(xhr) {
                var result = JSON.parse(xhr.responseText),
                filename = result[this.settings.jsonFieldName];

                if (result && filename) {
                    var newValue;
                    if (typeof this.settings.urlText === 'function') {
                        newValue = this.settings.urlText.call(this, filename, result);
                    } else {
                        newValue = this.settings.urlText.replace(this.filenameTag, filename);
                    }
                    var text = this.editor.getValue().replace(this.lastValue, newValue);
                    this.editor.setValue(text);
                    this.settings.onFileUploaded.call(this, filename);
                }
                return false;
            }
        });
    });



</script>
@stop
