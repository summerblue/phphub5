@extends('layouts.default')

@section('title')
{{ $topic->id > 0 ? '编辑文章' : '创作文章' }} | @parent
@stop

@section('content')

<div class="blog-pages">

  <div class="col-md-12 panel">

      <div class="panel-body">

            <h2 class="text-center"> {{ $topic->id > 0 ? '编辑文章' : '创作文章' }}</h2>
            <hr>

            @include('layouts.partials.errors')

            @if ($topic->id > 0)
            <form method="POST" action="{{ route('topics.update', $topic->id) }}" accept-charset="UTF-8" id="article-edit-form">
                <input name="_method" type="hidden" value="PATCH">
            @else
                <form method="POST" action="{{ route('articles.store') }}" accept-charset="UTF-8" id="article-create-form">
            @endif
                {!! csrf_field() !!}

                <input name="category_id" type="hidden" value="{{ config('phphub.blog_category_id') }}">

                @if (isset($blog))
                    <input name="blog_id" type="hidden" value="{{ $blog->id }}">
                @endif

                <div class="form-group">
                    <input class="form-control" id="article-title" placeholder="{{ lang('Please write down a topic') }}" name="title" type="text" value="{{ old('title') ?: $topic->title }}" required="require">
                </div>

                @include('topics.partials.composing_help_block', ['without_box' => false])

                <div class="form-group">
                  <textarea required="require" class="form-control" rows="20" style="overflow:hidden" id="reply_content" placeholder="{{ lang('Please using markdown.') }}" name="body" cols="50">{{ old('body') ?: $topic->body_original }}</textarea>
                </div>

                <div class="form-group status-post-submit">
                  <button class="btn btn-primary submit-btn" type="submit" name="subject" value="publish" id="{{ $topic->is_draft == 'yes' ? 'publish-hint' : '' }}">{{ lang('Publish') }}</button>

                    @if($topic->is_draft == 'yes' || empty($topic->id))
                        &nbsp;&nbsp;or&nbsp;&nbsp;
                        <button class="btn btn-basic" type="submit" name="subject" value="draft">保存草稿</button>
                    @endif
                </div>
            </form>
      </div>

  </div>
</div>

<script>
    Config.article_id = '{{ isset($article) ? $article->id : 0 }}';
</script>

@stop

@section('scripts')

<link rel="stylesheet" href="{{ cdn(elixir('assets/css/editor.css')) }}">
<script src="{{ cdn(elixir('assets/js/editor.js')) }}"></script>

<script type="text/javascript">

    $(document).ready(function()
    {
        @if (empty($topic->id))
            localforage.getItem('article-title', function(err, value) {
                if ($('#article-title').val() == '' && !err) {
                    $('#article-title').val(value);
                };
            });
            $('#article-title').keyup(function(){
                localforage.setItem('article-title', $(this).val());
            });
        @endif

        $('#publish-hint').click(function(event) {
            event.preventDefault();
            swal({
                title: "",
                text: "确定要将发布草稿？",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "取消",
                confirmButtonText: "发布"
            }).then(function () {
                $('<input />').attr('type', 'hidden')
                      .attr('name', "subject")
                      .attr('value', "publish")
                      .appendTo('#article-edit-form');
                      $("#article-edit-form").submit();
            }).catch(swal.noop);
        });

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
                unique_id: "article_content{{ $topic->id ? $topic->id . '_' . str_slug($topic->updated_at) : '' }}"
            },
            forceSync: true,
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
                        $('.submit-btn').click();
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
