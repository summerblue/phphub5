@extends('layouts.default')

@section('title')
{{ lang('About Us') }}_@parent
@stop

@section('content')

    <div class="panel">
        <div class="panel-body">

        <div class="markdown-body" id="emojify">


<h1 id="toc_0">关于 PHPHub</h1>

<h2 id="toc_1">说明</h2>

<p>PHPHub 是积极向上的 PHP &amp; Laravel 开发者社区</p>
<p>我们是一个公益组织, 致力于推动 Laravel, php-fig 等国外 PHP 新技术, 新理念在中国的发展.</p>

<p>在这里没有商业广告, 没有灌水, 没有千篇一律新手问答, 有的是关于新技术的讨论和分享. </p>

<p>名字的灵感来自伟大的 Github, <b>hub</b> 有 "中心", "集线器" 的意思, 意味着 PHPer 们齐聚一堂, 互相交换着信息流. </p>

<p>本项目基于 MIT Licence 开源, <a href="https://github.com/summerblue/phphub">源代码在此 at Github</a>, 欢迎贡献代码, Feature Request 和 Bug Report <a href="https://github.com/summerblue/phphub/issues">请到此发表</a>.</p>

<h2 id="toc_2">关于新手问题</h2>

<p>这个社区不是用来问新手问题的, 如果你是新手, 请先看 <a href="http://www.phptherightway.com/">PHP The Right Way</a> , 里面有中文版本, 此文档能为你构建一个合理的 PHP 基础知识体系. </p>

<p>第一次接触 Laravel? 这里是 <a href="http://laravel.com/docs/quick">官方文档</a>, 中文翻译见 <a href="http://laravel-china.org/">Laravel 中文网</a>, Laravel 的出名, 也得益于这份撰写精炼的文档, 值得多读几遍.</p>

<p>在学习上遇到问题的时候, 请先 Google. </p>

<p>如果觉得你的问题比较独占, 需要单独提问的话, 请到 <a href="http://segmentfault.com/t/php">Segmentfault 的 PHP 节点下</a> 发表你的问题, 谢谢. </p>

<h2 id="toc_3">愿景 Vision</h2>

<blockquote>
<p>下面是 phphub.org 创建的初衷, 与君共勉. </p>
</blockquote>

<h3 id="toc_4">在这里的我们</h3>

<ul>
<li>热爱开源, 尊重开源;</li>
<li>热爱技术, 为最新最潮的技术狂热;</li>
<li>热爱学习, 热爱互联网;</li>
<li>热爱 PHP &amp; Laravel;</li>
</ul>

<h3 id="toc_5">在这里我们可以</h3>

<ul>
<li>分享生活见闻, 分享知识</li>
<li>接触新技术</li>
<li>为自己的创业项目找合伙人</li>
<li>讨论技术解决方案</li>
<li>自发线下聚会</li>
<li>遇见志同道合的人</li>
<li>发现更好工作机会</li>
<li>甚至是开始另一个神奇的开源项目</li>
<li>... </li>
</ul>

<h3 id="toc_6">在这里我们不可以</h3>

<ul>
<li>这里绝对不讨论任何有关盗版软件、音乐、电影如何获得的问题 </li>
<li>这里绝对不会全文转载任何文章，而只会以链接方式分享 </li>
<li>这里感激和崇尚美的事物 </li>
<li>这里尊重原创 </li>
<li>这里反对中文互联网上的无信息量习惯如“顶”，“沙发”，“前排”，“留名”，“路过” </li>
</ul>

<h2 id="toc_7">Laravel?</h2>

<p>为什么是 Laravel? 为什么不是 CI, Symfony, CakePHP, ThinkPHP ... </p>

<blockquote>
<p>Because Laravel is amazing and It is the future.</p>
</blockquote>

<p><a href="http://www.zhihu.com/question/19558755/answer/23062110">知乎上的答案 - 最好的 PHP 框架是什么？为什么？</a></p>

<p>需要补充的一句是, <code>绝不排斥</code> 别的框架的讨论, 因为 <code>PHPHub</code> 是 <code>PHP</code> 社区多一点, 并且我们很开放.</p>

<h2 id="toc_8">Logo 和 icon</h2>

<p><img src="{{ cdn('assets/images/banner_transparent.png') }}" alt=""></p>

<p><img src="{{ cdn('assets/images/favicon.png') }}" alt=""></p>



        </div>

        </div>
    </div>

@stop
