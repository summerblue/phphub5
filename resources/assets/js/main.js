
(function($){

    var original_title = document.title;
    var nCount = 0;

    var PHPHub = {
        init: function(){
            var self = this;
            $(document).pjax('a:not(a[target="_blank"])', 'body', {
                timeout: 1600,
                maxCacheLength: 500
            });
            $(document).on('pjax:start', function() {
                NProgress.start();
            });
            $(document).on('pjax:end', function() {
                NProgress.done();
                self.siteBootUp();
            });
            $(document).on('pjax:complete', function() {
                original_title = document.title;
                NProgress.done();
                self._resetTitle();
            });
            // Exclude links with a specific class
            $(document).on("pjax:click", "a.no-pjax", false);

            self.siteBootUp();
            self.initLightBox();
            self.initNotificationsCount();
            window.showMsg = self.showMsg;
            window.showPluginDownload = self.showPluginDownload;

            if (ShowCrxHint === 'yes') {
                showPluginDownload();
            }

            Messenger.options = {
                extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
                theme: 'flat'
            }
        },

        /*
        * Things to be execute when normal page load
        * and pjax page load.
        */
        siteBootUp: function(){
            var self = this;
            self.initExternalLink();
            self.initTimeAgo();
            self.initEmoji();
            // self.initAutocompleteAtUser();
            self.initScrollToTop();
            self.initPopup();
            self.initTextareaAutoResize();
            self.initHeightLight();
            self.initLocalStorage();
            self.initEditorPreview();
            self.initReplyOnPressKey();
            self.initDeleteForm();
            self.initInlineAttach();
            // self.snowing();
            self.forceImageDataType();
            self.initToolTips();
            self.initAjax();
            self.initLogin();
            self.initEditBtnAnimated();
            self.initAnchorific();
        },

        initAnchorific: function(){
            $('div.entry-content').anchorific({
                navigation: '.anchorific', // position of navigation
                speed: 200, // speed of sliding back to top
                anchorClass: 'anchorific', // class of anchor links
                anchorText: '#', // prepended or appended to anchor headings
                top: '.top', // back to top button or link class
                spy: true, // scroll spy
                position: 'append', // position of anchor text
                spyOffset: 0 // specify heading offset for spy scrolling
            });
        },
        /**
         * Open External Links In New Window
         */
        initExternalLink: function(){
            $('.topics-show a[href^="http://"], .topics-show a[href^="https://"]').each(function() {
               var a = new RegExp('/' + window.location.host + '/');
               if(!a.test(this.href) ) {
                   $(this).click(function(event) {
                       event.preventDefault();
                       event.stopPropagation();
                       window.open(this.href, '_blank');
                   });
               }
            });
        },

        /**
         * Automatically transform any Date format to human
         * friendly format, all you need to do is add a
         * `.timeago` class.
         */
        initTimeAgo: function(){
            moment.lang('zh-cn');
            $('.timeago').each(function(){
                var time_str = $(this).text();
                if(moment(time_str, "YYYY-MM-DD HH:mm:ss", true).isValid()) {
                    $(this).text(moment(time_str).fromNow());
                }

                $(this).addClass('popover-with-html');
                $(this).attr('data-content', time_str);
            });
        },

        /**
         * Enable emoji everywhere.
         */
        initEmoji: function(){
            emojify.setConfig({
                img_dir : Config.cdnDomain + 'assets/images/emoji',
                ignored_tags : {
                    'SCRIPT'  : 1,
                    'TEXTAREA': 1,
                    'A'       : 1,
                    'PRE'     : 1,
                    'CODE'    : 1
                }
            });
            emojify.run();

            $('#reply_content').textcomplete([
                { // emoji strategy
                    match: /\B:([\-+\w]*)$/,
                    search: function (term, callback) {
                        callback($.map(emojies, function (emoji) {
                            return emoji.indexOf(term) === 0 ? emoji : null;
                        }));
                    },
                    template: function (value) {
                        return '<img src="' + Config.cdnDomain + 'assets/images/emoji/' + value + '.png"></img>' + value;
                    },
                    replace: function (value) {
                        return ':' + value + ': ';
                    },
                    index: 1,
                    maxCount: 5
                }
            ]);
        },

        /**
         * Autocomplete @user
         */
        initAutocompleteAtUser: function() {
            var at_users = Config.following_users,
                  user;
            $users = $('.topic .meta a.author, .media-heading a.author');
            for (var i = 0; i < $users.length; i++) {
                user = $users.eq(i).text().trim();
                if ($.inArray(user, at_users) == -1) {
                    at_users.push(user);
                };
            };

            $('textarea').textcomplete([{
                mentions: at_users,
                match: /\B@(\S*)$/,
                search: function(term, callback) {
                    callback($.map(this.mentions, function(mention) {
                        console.log(term + ' -> '+ mention.indexOf(term) + ' -> ' + mention);
                        return (mention.indexOf(term) >= 0 // 中文
                                || mention.indexOf(term.toUpperCase()) >= 0 // 大写
                                || mention.indexOf(term.toLowerCase()) >= 0 // 小写
                                ) ? mention : null;
                    }));
                },
                index: 1,
                replace: function(mention) {
                    return '@' + mention + ' ';
                }
            }], {
                appendTo: 'body',
                onKeydown: function(e, commands){
                    console.log(commands);
                }
            });

        },

        /**
         * Autoresizing the textarea when you typing.
         */
        initTextareaAutoResize: function(){
            $('textarea').autosize();
        },

        /**
         * Scroll to top in one click.
         */
        initScrollToTop: function(){
            $.scrollUp.init();
        },

        /**
         * Scroll to top in one click.
         */
        initPopup: function(){
            // Popover with html
            $('.popover-with-html').popover({
				 html : true,
				 trigger : 'hover',
                 container: 'body',
				 placement: 'auto top',
			 });
        },

        /**
         * Code heightlight.
         */
        initHeightLight: function(){
            Prism.highlightAll();
        },

        /**
         * lightbox
         */
        initLightBox: function(){
            $(document).delegate('.content-body img:not(.emoji)', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox({
                    onShown: function() {
                        if (window.console) {
                            // return console.log('Checking our the events huh?');
                        }
                    }
                });
            });
            $(document).delegate('.appends-container img:not(.emoji)', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox({
                    onShown: function() {
                        if (window.console) {
                            // return console.log('Checking our the events huh?');
                        }
                    }
                });
            });
        },

        /**
         * do content preview
         */
        runPreview: function() {
            var replyContent = $("#reply_content");
            var oldContent = replyContent.val();

            if (oldContent) {
                marked(oldContent, function (err, content) {
                  $('#preview-box').html(content);
                  emojify.run(document.getElementById('preview-box'));
                });
            }
        },

        /**
         * Init post content preview
         */
        initEditorPreview: function() {
            var self = this;
            $("#reply_content").focus(function(event) {
                // $("#reply_notice").addClass('animated pulse');
                $("#preview-box").fadeIn(1500);
                $("#preview-lable").fadeIn(1500);

                // Scroll to bottom
                // if (!$("#reply_content").val()) {
                //     $("html, body").animate({ scrollTop: $(document).height()}, 1800);
                // }
            });
            // $("#reply_content").blur(function(event) {
            //     $("#reply_notice").removeClass('animated swing');
            // });
            $('#reply_content').keyup(function(){
                self.runPreview();
            });
        },

        /**
         * Notify user unread notifications when they stay on the
         * page for too long.
         */
        initNotificationsCount: function(argumen) {
            var self = this;
            if (Config.user_id > 0 && Config.environment != 'local') {
                function scheduleGetNotification(){
                    $.get( Config.routes.notificationsCount, function( data ) {

                        nCount = parseInt(data);
                        self._resetTitle();
                        setTimeout(scheduleGetNotification, 15000);
                    });
                };
                setTimeout(scheduleGetNotification, 15000);
            }
        },

        _resetTitle: function() {

            if(window.location.href.indexOf("notifications") > -1) {
               nCount = 0;
            }

            if (nCount > 0) {
                $('#notification-count').text(nCount);
                $('#notification-count').hasClass('badge-important') || $('#notification-count').addClass('badge-important');
                document.title = '(' + nCount + ') '+ original_title;
            } else {
                document.title =  original_title;
                $('#notification-count').text(0);
                $('#notification-count').addClass('badge-fade');
                $('#notification-count').removeClass('badge-important');
            }
        },

        /*
         * Use Ctrl + Enter for reply
         */
        initReplyOnPressKey: function() {
            $(document).on("keydown", "#reply_content", function(e)
            {
                if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey && $('#reply-create-submit').is(':enabled')) {
                    $(this).parents("form").submit();
                    return false;
                }
            });
        },

        /*
         * Construct a form when using the following code, makes more clean code.
         *   {{ link_to_route('tasks.destroy', 'D', $task->id, ['data-method'=>'delete']) }}
         * See this answer: http://stackoverflow.com/a/23082278/689832
         */
        initDeleteForm: function() {
            $('[data-method]').append(function(){
                return "\n"+
                "<form action='"+$(this).attr('data-url')+"' method='POST' style='display:none'>\n"+
                "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
                "   <input type='hidden' name='_token' value='"+Config.token+"'>\n"+
                "</form>\n"
                })
                .attr('style','cursor:pointer;')
                .click(function() {
                    var that = $(this);
                    if ($(this).attr('data-method') == 'post') {
                        $(this).find("form").submit();
                    }
                    if ($(this).attr('data-method') == 'delete') {
                        swal({
                            title: "",
                            text: "你确定要删除此内容吗？",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            confirmButtonText: "删除"
                        }, function() {
                            that.find("form").submit();
                        });
                    }
                });
           // attr('onclick',' if (confirm("Are you sure want to proceed?")) { $(this).find("form").submit(); };');
        },

        /**
         * Local Storage
         */
        initLocalStorage: function() {
            var self = this;
            $("#reply_content").focus(function(event) {

                // Topic Title ON Topic Creation View
                localforage.getItem('topic-title', function(err, value) {
                    if ($('.topic_create #topic-title').val() == '' && !err) {
                        $('.topic_create #topic-title').val(value);
                    };
                });
                $('.topic_create #topic-title').keyup(function(){
                    localforage.setItem('topic-title', $(this).val());
                });

                // Topic Content ON Topic Creation View
                localforage.getItem('topic_create_content', function(err, value) {
                    if ($('.topic_create #reply_content').val() == '' && !err) {
                        $('.topic_create #reply_content').val(value);
                        self.runPreview();
                    }
                });
                $('.topic_create #reply_content').keyup(function(){
                    localforage.setItem('topic_create_content', $(this).val());
                });

                // Reply Content ON Topic Detail View
                localforage.getItem('reply_content', function(err, value) {
                    if ($('.topics-show #reply_content').val() == '' && !err) {
                        $('.topics-show #reply_content').val(value);
                        self.runPreview();
                    }
                });
                $('.topics-show #reply_content').keyup(function(){
                    localforage.setItem('reply_content', $(this).val());
                });
            })

            // Clear Local Storage on submit
            $("#topic-create-form").submit(function(event){
                localforage.removeItem('topic_create_content');
                localforage.removeItem('topic-title');

                $("#topic-create-submit").val('提交中...').addClass('disabled').prop('disabled', true);

            });
            $("#reply-form").submit(function(event){
                localforage.removeItem('reply_content');
            });
        },

        /**
         * Upload image
         */
        initInlineAttach: function() {
            var self = this;
            $('#reply_content').inlineattach({
                uploadUrl: Config.routes.upload_image,
                extraParams: {
                  '_token': Config.token,
                },
                onUploadedFile: function(response) {
                    setTimeout(self.runPreview, 200);
                },
            });
        },

        /**
         * Snowing around the world
         */
        snowing: function() {

            // only show snow in Christmas
            var today = new Date()
            var christmas = new Date(today.getFullYear(), 11, 25) // Month is 0-11 in JavaScript
            if (today.getMonth() == 11 && (today.getDate() > 22 && today.getDate() < 30))
            {
                $('#body').snowfall({
                    flakeCount: 100,
                    minSize: 2,
                    maxSize:5,
                    round: true
                });
            }
        },

        /**
         * Force image data type, fixing the "Could not detect remote target type..."
         * problem.
         */
        forceImageDataType: function() {
          $('.content-body img:not(.emoji)').each(function(){
              $(this).attr('data-type', 'image');
          });
        },

        initToolTips: function() {
          $('[data-toggle="tooltip"]').tooltip();
        },

        initAjax: function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            this.initDataAjax();
            this.initAppendsModal();
            this.initComment();
        },

        initComment: function() {
            var self = this;
            var form = $('#reply-form');
            var submitBtn = form.find('input[type=submit]');
            var submitBtnVal = submitBtn.val();
            var replies = $('.replies .list-group');
            var preview = $('#preview-box');
            var emptyBlock = $('#replies-empty-block');
            var count = 0;

            form.on('submit', function() {
                var tpl = '';
                var delTpl = '';
                var voteTpl = '';
                var introTpl = '';
                var total = $('.replies .total b');

                count = replies.find('li').length + 1;
                comment = $(this).find('textarea');
                commentText = comment.val();

                if ($.trim(commentText) !== '') {
                    submitBtn.val('提交中...').addClass('disabled').prop('disabled', true);
                    $.ajax({
                        method: 'POST',
                        url: $(this).attr('action'),
                        data: {
                            body: commentText,
                            topic_id: $('[name=topic_id]').val()
                        },
                    }).done(function(data) {
                        if (data.status === 200) {
                            if (data.manage_topics === 'yes') {
                                delTpl = '<a class="" id="reply-delete-' + data.reply.id + '" data-ajax="delete" href="javascript:void(0);" data-url="/replies/delete/' + data.reply.id + '" title="删除"><i class="fa fa-trash-o"></i></a><span> •  </span>';
                            }

                            if (data.reply.user.introduction) {
                                introTpl = '，' + data.reply.user.introduction;
                            }

                            tpl = '<li class="list-group-item media" style="margin-top: 0px;">\
                                <div class="avatar pull-left">\
                                    <a href="/users/' + data.reply.user_id + '"><img class="media-object img-thumbnail avatar" alt="' + data.reply.user.name + '" src="' + data.reply.user.image_url + '" style="width:48px;height:48px;"></a>\
                                </div>\
                                <div class="infos">\
                                    <div class="media-heading">\
                                        <a href="/users/' + data.reply.user_id + '" title="' + data.reply.user.name + '" class="remove-padding-left author">' + data.reply.user.name + '</a>\
                                        <span class="introduction">' + introTpl + '</span>\
                                        <span class="operate pull-right">' + delTpl + '<a class="fa fa-reply" href="javascript:void(0)" onclick="replyOne(\'' + data.reply.user.name + '\');" title="回复 ' + data.reply.user.name + '"></a>\
                                        </span>\
                                        <div class="meta">\
                                            <a name="reply' + count + '" class="anchor" href="#reply' + count + '" aria-hidden="true">#' + count + '</a>\
                                            <span> •  </span>\
                                            <abbr class="timeago" title="' + data.reply.created_at + '">' + data.reply.created_at + '</abbr>\
                                        </div>\
                                    </div>\
                                    <div class="media-body markdown-reply content-body">' + data.reply.body + '</div>\
                                </div>\
                            </li>';
                        }

                        $(tpl).hide().appendTo(replies).slideDown();
                        total.html(parseInt(total.html()) + 1);
                        emptyBlock.addClass('hide');
                        comment.val('');
                        localforage.removeItem('reply_content');
                        preview.html('');
                        location.href = location.href.split('#')[0] + '#reply' + count;
                        self.initTimeAgo();
                        self.showPluginDownload();
                        emojify.run();
                    }).always(function() {
                        submitBtn.val(submitBtnVal).removeClass('disabled').prop('disabled', false);
                    });
                }

                return false;
            });
        },

        initAppendsModal: function() {
            var self = this;
            var appendsContainer = $('.topic .appends-container');
            var appendText = appendsContainer.data('lang-append');
            var modal = $('#exampleModal');
            var submitBtn = modal.find('button[type=submit]');
            var count = 0;
            var appendMsg = '';

            modal.find('form').on('submit', function() {
                var tpl = '';
                count = appendsContainer.find('.appends').length;
                appendMsg = $(this).find('textarea').val();

                if ($.trim(appendMsg) !== '') {
                    submitBtn.html('提交中...').addClass('disabled').prop('disabled', true);
                    $.ajax({
                        method: 'POST',
                        url: $(this).attr('action'),
                        data: {
                            content: appendMsg
                        },
                    }).done(function(data) {
                        if (data.status === 200) {
                            tpl += '<div class="appends">';
                            tpl +=     '<span class="meta">' + appendText + ' ' + count + ' &nbsp;·&nbsp; <abbr title="' + data.append.created_at + '" class="timeago">' + data.append.created_at + '</abbr></span>';
                            tpl +=     '<div class="sep5"></div>';
                            tpl +=     '<div class="markdown-reply append-content">';
                            tpl +=         data.append.content;
                            tpl +=     '</div>';
                            tpl += '</div>';
                        }

                        $(tpl).hide().appendTo(appendsContainer).slideDown();
                        self.initTimeAgo();
                        modal.modal('hide');
                    });
                }

                return false;
            });

            modal.on('hidden.bs.modal', function() {
                $(this).find('textarea').val('');
                submitBtn.html('提交').removeClass('disabled').prop('disabled', false);
            });
        },

        initDataAjax: function() {
            var self = this;
            $(document).on('click', '[data-ajax]', function() {
                var that = $(this);
                var method = that.data('ajax');
                var url = that.data('url');
                var active = that.is('.active');
                var cancelText = that.data('lang-cancel');
                var isRecomend = that.is('#topic-recomend-button');
                var isWiki = that.is('#topic-wiki-button');
                var ribbonContainer = $('.topic .ribbon-container');
                var ribbon = $('.topic .ribbon');
                var excellent = $('.topic .ribbon-excellent');
                var wiki = $('.topic .ribbon-wiki');
                var total = $('.replies .total b');
                var voteCount = $('#vote-count');
                var upVote = $('#up-vote');
                var isVote = that.is('.vote');
                var isUpVote = that.is('#up-vote');
                var isCommentVote= that.is('.comment-vote');
                var commenVoteCount= that.find('.vote-count');
                var emptyBlock = $('#replies-empty-block');
                var originUpVoteActive = upVote.is('.active');

                if (Config.user_id === 0) {
                    swal({
                        title: "",
                        text: '需要登录以后才能执行此操作。',
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "取消",
                        confirmButtonText: "前往登录"
                    }, function() {
                        location.href = '/login-required';
                    });
                }

                if (method === 'delete') {
                    swal({
                        title: "",
                        text: "Are you sure want to proceed?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "取消",
                        confirmButtonText: "删除"
                    }, function() {
                        that.closest('.list-group-item').slideUp();
                        $.ajax({
                            method: method,
                            url: url
                        }).done(function(data) {
                            if (data.status === 200) {
                                that.closest('.list-group-item').remove();
                                total.html(parseInt(total.html()) - 1);
                                if (parseInt(total.html()) === 0) {
                                    emptyBlock.removeClass('hide');
                                }
                            }
                        }).fail(function() {
                            that.closest('.list-group-item').show();
                        });
                    });

                    return;
                }

                if (that.is('.ajax-loading')) return;
                that.addClass('ajax-loading');

                if (active) {
                    that.removeClass('active');
                    that.removeClass('animated rubberBand');

                    if (isRecomend) {
                        excellent.hide();
                    } else if (isWiki) {
                        wiki.hide();
                    }

                    if (isVote) {
                        // @CJ 如果是点赞，并且是已经点过赞的点赞，那就是去除点赞
                        $('.user-lists').find("a[data-userId='"+Config.user_id+"']").fadeOut('slow/400/fast', function() {
                            $(this).remove();
                        });
                    }
                } else {
                    that.addClass('active');
                    that.addClass('animated rubberBand');

                    if (cancelText) {
                        that.find('span').html(cancelText);
                        self.showPluginDownload();
                    }

                    if (isRecomend) {
                        var excellentText = ribbonContainer.data('lang-excellent');
                        if (excellent.length) {
                            excellent.show();
                        } else {
                            if (ribbon.length) {
                                ribbon.prepend('<div class="ribbon-excellent"><i class="fa fa-trophy"></i> ' + excellentText + ' </div>');
                            } else {
                                ribbonContainer.prepend('<div class="ribbon"><div class="ribbon-excellent"><i class="fa fa-trophy"></i> ' + excellentText + ' </div></div>');
                            }
                        }
                    } else if (isWiki) {
                        var wikiText = ribbonContainer.data('lang-wiki');
                        if (wiki.length) {
                            wiki.show();
                        } else {
                            if (ribbon.length) {
                                ribbon.append('<div class="ribbon-wiki"><i class="fa fa-graduation-cap"></i> ' + wikiText + ' </div>');
                            } else {
                                ribbonContainer.append('<div class="ribbon"><div class="ribbon-wiki"><i class="fa fa-graduation-cap"></i> ' + wikiText + ' </div></div>');
                            }
                        }
                    }

                    if (isVote && Config.user_id > 0) {
                        // @CJ 如果是点赞，并且是没有点过赞的
                        var newContent = $('.voted-template').clone();
                        newContent.attr('data-userId', Config.user_id);
                        newContent.attr('href', Config.user_link);
                        newContent.find('img').attr('src', Config.user_avatar);

                        newContent.prependTo('.user-lists').show('fast', function() {
                            $(this).addClass('animated swing');
                        });

                        $('.vote-hint').hide();
                    }
                }

                $.ajax({
                    method: method,
                    url: url
                }).done(function(data) {
                    if (data.status === 200) {
                        if (isCommentVote) {
                            var num = parseInt(commenVoteCount.html());
                            num = isNaN(num) ? 0 : num;

                            if (data.type === 'sub') {
                                commenVoteCount.html(num - 1 < 1 ? '' : num - 1);
                            } else if (data.type === 'add') {
                                commenVoteCount.html(num + 1);
                            }
                        }
                    }
                }).fail(function() {
                    if (!active) {
                        that.removeClass('active');

                        if (isRecomend) {
                            excellent.hide();
                        } else if (isWiki) {
                            wiki.hide();
                        }
                    } else {
                        that.addClass('active');

                        if (cancelText) {
                            that.find('span').html(cancelText);
                        }

                        if (isRecomend) {
                            excellent.show();
                        } else if (isWiki) {
                            wiki.show();
                        }
                    }

                    if (isVote) {
                        if (originUpVoteActive) {
                            upVote.addClass('active');
                        } else {
                            upVote.removeClass('active');
                        }
                    }
                })
                .always(function() {
                    that.removeClass('ajax-loading');
                });
            });
        },

        showMsg: function(msg, myobj) {
            if (!msg) return;
            Messenger().post(msg);
        },

        showPluginDownload: function() {
            this.showMsg('操作成功', {
                type: 'success',
                timer: 8000
            });
        },

        initLogin: function() {
            $('#login-out').on('click', function(e) {
                var langText = $(this).data('lang-loginout');
                var href = $(this).attr('href');

                swal({
                    title: "",
                    text: langText,
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonText: "退出"
                }, function() {
                    location.href = href;
                });

                return false;
            });
        },
        initEditBtnAnimated: function() {
            $('.topic-author-box .panel-body').hover(function() {
                  $('.edit-btn').toggleClass('infinite');
            });

            $(".bootstrap-switch").bootstrapSwitch();
        },

    };
    window.PHPHub = PHPHub;
})(jQuery);

$(document).ready(function()
{
    PHPHub.init();
});

// reply a reply
function replyOne(username){
    replyContent = $("#reply_content");
    oldContent = replyContent.val();
    prefix = "@" + username + " ";
    newContent = ''
    if(oldContent.length > 0){
        if (oldContent != prefix) {
            newContent = oldContent + "\n" + prefix;
        }
    } else {
        newContent = prefix
    }
    replyContent.focus();
    replyContent.val(newContent);
    moveEnd($("#reply_content"));
}

var moveEnd = function(obj){
  obj.focus();

  var len = obj.value === undefined ? 0 : obj.value.length;

  if (document.selection) {
    var sel = obj.createTextRange();
    sel.moveStart('character',len);
    sel.collapse();
    sel.select();
  } else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
    obj.selectionStart = obj.selectionEnd = len;
  }
}
