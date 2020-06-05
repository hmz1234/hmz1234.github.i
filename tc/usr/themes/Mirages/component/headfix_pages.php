<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<style type="text/css">
    /** 页面样式调整 */
    <?php if ($this->is("index") || $this->is("archive")):?>
    @media(max-width: 767px) {
        body.card #index, body.card #archive {
            padding: 4rem 3rem 3.5rem;
        }
        body.card .container {
            max-width: 710px;
        }
        body.card #index article, body.card #archive article {
            padding: .9375rem 0 1.25rem;
        }
        body.card #index article .post-card-mask, body.card #archive article .post-card-mask {
            height: 12.5rem;
        }
        body.card #index article .post-card-container, body.card #archive article .post-card-container {
            padding: 1rem 1rem;
        }
        .page-navigator {
            margin-top: 2rem;
        }
    }
    @media(max-width: 336px) {
        body.card #index article .post-card-mask, body.card #archive article .post-card-mask {
            height: 10.5rem;
        }
        a.btn, .btn>a {
            padding: .4375rem 2.25rem;
        }
        .page-navigator {
            margin-top: 1.5rem;
        }
    }
    @media screen and (min-width: 768px) and (max-width: 1301px) {
        body.card .container {
            max-width: 720px;
        }
    }
    @media screen and (min-width: 1302px) and (max-width: 1599px) {
        body.card .container {
            max-width: 864px;
        }
    }
    @media screen and (min-width: 1600px) and (max-width: 1799px){
        body.card .container {
            max-width: 896px;
        }
    }
    @media screen and (min-width: 1800px) and (max-width: 1999px){
        body.card .container {
            max-width: 960px;
        }
    }
    @media screen and (min-width: 2000px) and (max-width: 2399px) {
        body.card .container {
            max-width: 992px;
        }
    }
    @media screen and (min-width: 2400px) {
        body.card .container {
            max-width: 1024px;
        }
    }
    <?php endif;?>
    <?php if(Mirages::$options->showBanner && Mirages::$options->showBannerCurveStyle == 1 && !FULL_BANNER_DISPLAY):?>
    #masthead::after {
        content: '';
        width: 150%;
        height: 4.375rem;
        background: #fff;
        left: -25%;
        bottom: -1.875rem;
        border-radius: 100%;
        position: absolute;
        z-index: -1;
    }
    .inner::after {
        content: '';
        width: 150%;
        height: 4.375rem;
        background-color: #fff;
        left: -25%;
        bottom: -1.875rem;
        border-radius: 100%;
        position: absolute;
    }
    @media screen and (max-width: 25rem){
        #masthead::after, .inner::after {
            width: 250%;
            left: -75%;
            bottom: -3.875rem;
        }
    }
    @media screen and (min-width: 25.0625rem) and (max-width: 37.4375rem){
        #masthead::after, .inner::after {
            width: 200%;
            left: -50%;
            bottom: -2.875rem;
        }
    }
    body.theme-dark #masthead {
        box-shadow: none;
        -webkit-box-shadow: none;
    }
    body.theme-dark #masthead::after {
        background-color: #2c2a2a;
    }
    body.theme-dark .inner::after {
        background-color: #2c2a2a;
    }
    body.theme-sunset .inner::after, body.theme-sunset #masthead::after{
        background-color: #F8F1E4;
    }
    #post article {
        margin-top: -0.625rem;
    }
    #index {
        padding-top: 0.375rem;
    }
    <?php endif?>
    <?php if($this->is('post')):?>
    div#comments{
        margin-top: 0;
    }
    <?php endif?>
    <?php if(!$this->is('post') || Device::isPhone()):?>
    #qr-box {
        background-color: transparent;
    }
    <?php endif?>
    <?php if(!($this->is('post') && !Device::isPhone() && (Utils::hasValue(Mirages::$options->postQRCodeURL) || Utils::hasValue(Mirages::$options->rewardQRCodeURL)))):?>
    .post-buttons, #qr-box {
        display: none;
    }
    #body-bottom {
        margin-top: 0;
    }
    <?php endif?>
    <?php if($this->is('page','links')): ?>
    #body .container {
        margin-top: 3.125rem;
    }
    .row{
        margin-left: 0;
        margin-right: 0;
    }
    <?php endif ?>
    <?php if($this->is('post') || $this->is('page')):?>
    #footer{
        padding: 1.25rem 0;
    }
    <?php if(Utils::hasValue($this->fields->contentWidth)):?>
    @media(min-width: 62rem) {
        .container {
            max-width: <?php echo is_numeric($this->fields->contentWidth)?($this->fields->contentWidth."px"):$this->fields->contentWidth?>;
        }
    }
    <?php endif?>
    <?php endif?>

    <?php if($this->fields->textAlign == 'left' || $this->fields->textAlign == 'center' || $this->fields->textAlign == 'right' || $this->fields->textAlign == 'justify' || Mirages::$options->textAlign__hasValue):?>
    .post-content p,.post-content blockquote,.post-content ul,.post-content ol,.post-content dl,.post-content table,.post-content pre {
        text-align: <?php Utils::hasValue($this->fields->textAlign) ? $this->fields->textAlign() : Mirages::$options->textAlign() ?>;
    }
    <?php endif?>
    <?php if (Mirages::$options->showBanner && (Utils::isTrue($this->fields->headTitle) || (intval($this->fields->headTitle) >= 0 && Mirages::$options->headTitle__isTrue) || Mirages::$options->blogIntro__hasValue || (!$this->is('index') && (Utils::hasValue($this->fields->mastheadTitle || Utils::hasValue($this->fields->mastheadSubtitle)))))):?>
    <?php if (!Utils::isTrue($this->fields->disableDarkMask)):?>
    .inner {
        background-color: rgba(0,0,0,0.25);
    }
    <?php endif?>
    #masthead {
        min-height: 12.5rem;
    }
    <?php else:?>
    @media screen and (max-width: 40rem) {
        #post article {
            margin-top: 2.6rem;
        }
    }
    <?php endif?>
    <?php if (Mirages::$options->showBanner && $this->is('page', 'about')):?>
<!--    --><?php //if (preg_match('/[a-zA-Z0-9\-\]/'))?>
    .blog-title {
        font-size: 2.5rem;
    }
    <?php if (Mirages::$options->showBannerCurveStyle == 1):?>
    #masthead {
        min-height: 21.875rem;
    }
    <?php else:?>
    #masthead {
        min-height: 18.75rem;
    }
    h1.blog-title {
        margin-bottom: -1.25rem;
    }
    <?php endif?>
    <?php endif?>
    <?php if (Mirages::$options->needHideToggleTOCBtn):?>
    /*喵喵喵*/
    /*喵喵喵*/
    /*喵喵喵*/
    /*喵喵喵*/

    @media screen and (min-width: 63rem) and (max-width: 6300rem) {
        #toggle-menu-tree, h1>span.toc, h2>span.toc, h3>span.toc, h4>span.toc, h5>span.toc, h6>span.toc{
            display: none !important;
        }
    }
    <?php endif;?>
    <?php if (Mirages::$options->showTOCAtLeft):?>
    #post-menu {
        right: initial;
        left: -17.5rem;
        border-left: none;
        border-right: .0625rem solid #f0f0f0;
    }
    body.theme-dark #post-menu {
        border-right: none;
    }
    a#toggle-menu-tree {
        right: initial;
        left: 0;
        margin-right: 0;
        margin-left: -5rem;
        padding-right: .35rem;
        text-align: right;
    }
    #wrap #toggle-menu-tree i {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        transform: rotate(180deg)
    }
    #wrap.display-menu-tree #toggle-menu-tree i {
        -webkit-transform: rotate(0);
        -moz-transform: rotate(0);
        -ms-transform: rotate(0);
        -o-transform: rotate(0);
        transform: rotate(0)
    }
    #wrap.display-menu-tree #toggle-menu-tree {
        padding-right: 0;
        margin-left: -1.5625rem;
    }

    a#toggle-menu-tree:hover {
        -webkit-transform: translateX(1.25rem);
        -moz-transform: translateX(1.25rem);
        -ms-transform: translateX(1.25rem);
        -o-transform: translateX(1.25rem);
        transform: translateX(1.25rem);
    }
    #wrap.display-menu-tree a#toggle-menu-tree, #wrap.display-menu-tree #post-menu {
        -webkit-transform: translateX(17.5rem);
        -moz-transform: translateX(17.5rem);
        -ms-transform: translateX(17.5rem);
        -o-transform: translateX(17.5rem);
        transform: translateX(17.5rem)
    }
    #wrap.display-menu-tree #backtop, #wrap.display-menu-tree.display-nav #body {
        -webkit-transform: translateX(0);
        -moz-transform: translateX(0);
        -ms-transform: translateX(0);
        -o-transform: translateX(0);
        transform: translateX(0)
    }
    #wrap.display-menu-tree #site-navigation {
        margin-left: -17.5rem;
    }
    body.display-menu-tree #wrap.display-menu-tree {
        margin-left: 17.5rem;
    }
    body.display-menu-tree #footer {
        margin-left: 17.5rem !important;
    }
    #wrap.display-menu-tree.display-nav #toggle-nav {
        -webkit-transform: translateX(12.625rem);
        -moz-transform: translateX(12.625rem);
        -ms-transform: translateX(12.625rem);
        -o-transform: translateX(12.625rem);
        transform: translateX(12.625rem);
    }
    #wrap.display-menu-tree a#toggle-nav {
        left: 2.5rem;
    }
    #toc-wrap {
        right: 0;
    }
    #toc-content {
        margin-right: 0;
        padding-top: 3.65rem;
    }
    a#toggle-menu-tree.hide {
        right: inherit;
        left: -5.3125rem;
    }
    @media screen and (min-width: 1440px) {
        body.use-navbar.display-menu-tree .navbar.fixed-top {
            left: 17.5rem;
            right: 0;
        }
        body.desktop.chrome #wrap.display-menu-tree {
            right: -10px;
        }
        body.use-navbar #toc-content {
            padding-top: .5rem;
        }
    }
    <?php endif;?>
</style>
<style type="text/css">
    <?php
        if(($this->is('post') || $this->is('page')) && Utils::hasValue($this->fields->css)) {
            echo $this->fields->css;
        }
    ?>
</style>