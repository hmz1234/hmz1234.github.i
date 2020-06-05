<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php Mirages::initTheme($this); ?>
<?php if(!Utils::isPjax() || !PJAX_ENABLED):?>
<!DOCTYPE HTML>
<html class="no-js" <?php Mirages::$options->rootFontSizeStyle()?>>
<head>
<?php
    $this->need('component/head.php');
    $this->need('component/headfix.php');
?>
</head>
<body class="<?php Mirages::$options->bodyClass() ?>">
<?php if(Mirages::$options->disableAutoNightTheme <= 0 && COMMENT_SYSTEM !== Mirages_Const::COMMENT_SYSTEM_DISQUS && THEME_CLASS != Mirages_Const::THEME_MIRAGES_DARK && NIGHT_SHIFT_BTN_CLASS != "sunset-mode"):?>
    <script>
        loadPrefersDarkModeState();
        if (LocalConst.USE_MIRAGES_DARK || (LocalConst.AUTO_NIGHT_SHIFT && LocalConst.PREFERS_DARK_MODE)) {
            var body = document.querySelector("body");
            body.classList.remove('theme-white');
            body.classList.add('theme-dark');
            body.classList.add('dark-mode');

            if (LocalConst.USE_MIRAGES_DARK) {
                body.classList.remove('dark-mode');
            } else if (LocalConst.AUTO_NIGHT_SHIFT && LocalConst.PREFERS_DARK_MODE) {
                body.classList.add('os-dark-mode');
            }
        }
    </script>
<?php else:?>
    <script type="text/javascript">
        loadPrefersDarkModeState();
    </script>
<?php endif;?>
<!--[if lt IE 9]>
<div class="browse-happy" role="dialog"><?php _me('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="%s">升级你的浏览器</a>.', 'http://browsehappy.com/'); ?></div>
<![endif]-->
<div class="sp-progress"></div>
<?php if (Utils::hasValue(Mirages::$options->blogNotice)):?>
<div class="blog-notice">
    <a class="blog-notice-close" href="javascript:void(0)" title="关闭"></a>
    <p><?php Mirages::$options->blogNotice()?></p>
</div>
<?php endif?>
<div id="wrap">
    <span id="backtop" class="waves-effect waves-button"><i class="fa fa-angle-up"></i></span>
    <header>
    <?php
        $this->need('component/sidebar.php');
        if (Mirages::$options->navbarStyle == 1) {
            $this->need('component/navbar.php');
        }
    ?>
    </header>
    <?php else:?>
    <title><?php $this->archiveTitle(array(
            'category'  =>  _mt('%s'),
            'search'    =>  _mt('包含关键字 %s 的文章'),
            'tag'       =>  _mt('%s'),
            'author'    =>  _mt('%s 发布的文章')
        ), '', ' - '); ?><?php Mirages::$options->title(); ?></title>

<?php endif?>

    <div id="body">
        <?php $this->need('component/headfix_pages.php');?>
        <script type="text/javascript">
            var wrap = document.querySelector('#wrap');
            var navbar = document.querySelector('#navbar');
            wrap.classList.remove('display-menu-tree');
            var body = document.querySelector('body');
            body.classList.remove('display-menu-tree');
            LocalConst.TOC_AT_LEFT = <?php echo Mirages::$options->showTOCAtLeft ? 'true' : 'false'?>;
            LocalConst.ENABLE_MATH_JAX = <?php echo Mirages::$options->enableMathJax ? 'true' : 'false'?>;
            LocalConst.ENABLE_FLOW_CHART = <?php echo Mirages::$options->enableFlowChat ? 'true' : 'false'?>;
            LocalConst.ENABLE_MERMAID = <?php echo Mirages::$options->enableMermaid ? 'true' : 'false'?>;
            <?php if (Utils::hasValue(Mirages::$options->defaultTOCClass)):?>
            if (window.innerWidth >= 1008) {
                wrap.classList.add('no-animation');
                if (navbar) {
                    navbar.classList.add('no-animation');
                }
                wrap.classList.add('<?php Mirages::$options->defaultTOCClass()?>');
                body.classList.add('<?php Mirages::$options->defaultTOCClass()?>');
                setTimeout(function () {
                    wrap.classList.remove('no-animation');
                    if (navbar) {
                        navbar.classList.remove('no-animation');
                    }
                }, 1000);
            }
            <?php endif;?>

            <?php Content::detectBodyClassForPJAX('no-banner', 'body')?>
            <?php Content::detectBodyClassForPJAX('content-lang-en', 'body')?>
            <?php Content::detectBodyClassForPJAX('content-serif', 'body')?>
            LocalConst.SHOW_TOC = false;
            <?php Mirages::$options->bannerCDNType = Mirages::pluginAvailable(100) ? Mirages_Plugin::getCdnType(Mirages::$options->banner) : -1;?>
        </script>
        <?php if(PJAX_ENABLED) Content::outputCommentJS($this, $this->security); ?>
        <?php if (($this->is('post') || $this->is('page')) && (Utils::isTrue($this->fields->showTOC) || Utils::isTrue($this->fields->TOC))):?>
            <a id="toggle-menu-tree" class="revert" href="javascript:void(0);"><i class="fa fa-angle-left"></i></a>
            <div id="post-menu">
                <div id="toc-wrap">
                    <div id="toc-content">
                        <h2 id="post-menu-title"><?php _me('文章目录')?></h2>
                        <?php echo TOC::output($this->content)?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                LocalConst.SHOW_TOC = true;
            </script>
        <?php endif?>
        <?php if(Mirages::$options->showBanner):?>
        <header id="masthead" class="align-center align-middle <?php echo Mirages::$options->noBannerImage__isTrue ? 'no-banner-image': ''?>" style="
            height:
        <?php Mirages::$options->defaultBgHeight();?>;">
            <div class="blog-background"></div>
            <?php if(Mirages::$options->bannerCDNType > 0 && Mirages::$options->noBannerImage__isFalse):?>
                <?php if (Mirages::$options->enableLazyLoad__isTrue):?>
                    <div class="lazyload-container"></div>
                    <script type="text/javascript">
                        <?php
                        $blurBanner = (Utils::isTrue($this->fields->blurBanner) || (Mirages::$options->blurBanner__isTrue && !Utils::hasValue($this->fields->blurBanner)));
                        echo "var blurBanner = " . ($blurBanner ? 'true' : 'false') . ";";
                        ?>
                        if (!blurBanner) registLoadBanner();
                    </script>
                    <img id="lazyload-img" alt="page banner image" src="<?php echo Mirages::$options->banner . Utils::getThumbnailImageAddOn(Mirages::$options->bannerCDNType)?>" style="display: none" onload="javascript:loadBanner(this, '<?php Mirages::$options->banner()?>', '<?php Mirages::$options->bannerPosition()?>', document.querySelector('#masthead'), '<?php Mirages::$options->bannerCDNType()?>', window.screen.availWidth, window.screen.availHeight, blurBanner)">
                <?php else:?>
                    <script type="text/javascript">
                        loadBannerDirect('<?php Mirages::$options->banner()?>', '<?php Mirages::$options->bannerPosition()?>', document.querySelector('#masthead'), '<?php Mirages::$options->bannerCDNType()?>', window.screen.availWidth, window.screen.availHeight);
                    </script>
                <?php endif?>
            <?php endif?>
            <script type="text/javascript">
                var head = document.querySelector("#masthead");
                var bgHeight = getBgHeight(window.innerHeight, '<?php Mirages::$options->bannerHeight()?>', '<?php Mirages::$options->mobileBannerHeight()?>');
                head.style.height = bgHeight + "px";
                <?php if(Mirages::$options->bannerCDNType <= 0 && Mirages::$options->noBannerImage__isFalse):?>
                var banner = '<?php Mirages::$options->banner()?>' + getImageAddon("-1", window.screen.availWidth, window.screen.availHeight);
                head.querySelector(".blog-background").style.backgroundImage = 'url("' + banner + '")';
                <?php endif?>
            </script>
            <?php if($this->is('page','about')):?>
            <div class="inner">
                <div class="container" itemscope itemtype="http://schema.org/Organization">
                    <meta itemprop="url" content="<?php $this->author('url')?>">
                    <div id="about-avatar" itemscope itemprop="logo" itemtype="http://schema.org/ImageObject">
                        <img itemprop="url" class="rotate" src="<?php Mirages::$options->headFaceUrl()?>" alt="Avatar" width="150" height="150"/>
                    </div>
                    <h1 class="blog-title" style="<?php if (Utils::hasValue($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>" itemprop="name"><?php $this->author(); ?>
                        <?php if($this->user->hasLogin()):?>
                            <a class="superscript" href="<?php Mirages::$options->adminUrl()?>write-page.php?cid=<?php $this->cid()?>" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <?php endif?>
                    </h1>
                    <h2 class="blog-description" itemprop="description"><?php echo $this->fields->description; ?></h2>
                </div>
            </div>
            <?php elseif($this->is('page','links')):?>
            <div class="inner">
                <div class="container">
                    <h1 class="blog-title" style="<?php if (Utils::hasValue($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>">
                        <?php
                        if (Utils::hasValue($this->fields->mastheadTitle)) {
                            echo Mirages::parseBiaoqing($this->fields->mastheadTitle);
                        } else {
                            echo Mirages::parseBiaoqing($this->title);
                        }
                        ?>
                        <?php if($this->user->hasLogin()):?>
                            <a class="superscript" href="<?php Mirages::$options->adminUrl()?>write-page.php?cid=<?php $this->cid()?>" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <?php endif?>
                    </h1>
                </div>
            </div>
            <?php elseif($this->is('index')):?>
            <div class="inner">
                <div class="container">
                    <h1 class="blog-title" style="<?php if (Utils::hasValue($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>"><?php Mirages::$options->blogIntro()?></h1>
                    <h2 class="blog-description" style="<?php if (Utils::hasValue($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>"><?php Mirages::$options->blogIntroDesc()?></h2>
                </div>
            </div>
            <?php else:?>
            <div class="inner">
                <div class="container">
                    <h1 class="blog-title" style="<?php if (Utils::hasValue($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>">
                        <?php
                        if (!($this->is('post') || $this->is('page'))) {
                            if ($this->is('archive') && Mirages::$options->showBanner) {
                                $this->archiveTitle(array(
                                    'category'  =>  _mt('%s'),
                                    'search'    =>  _mt('包含关键字 %s 的文章'),
                                    'tag'       =>  _mt('%s'),
                                    'author'    =>  _mt('%s 发布的文章')), '', '');
                            }
                        } elseif (Utils::hasValue($this->fields->mastheadTitle)) {
                            echo Mirages::parseBiaoqing($this->fields->mastheadTitle);
                        } elseif (Utils::isTrue($this->fields->headTitle) || (intval($this->fields->headTitle) >= 0 && Mirages::$options->headTitle__isTrue)) {
                            echo Mirages::parseBiaoqing($this->title);
                        }
                        ?>
                    </h1>
                    <h2 class="blog-description <?php if ($this->is('post') || $this->is('page')) echo 'font-mono'?>" style="<?php if (Utils::hasValue($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>">
                        <?php
                        if (!($this->is('post') || $this->is('page'))) {
                        } elseif (Utils::hasValue($this->fields->mastheadSubtitle)) {
                            echo Mirages::parseBiaoqing($this->fields->mastheadSubtitle);
                        } elseif (Utils::isTrue($this->fields->headTitle) || (intval($this->fields->headTitle) >= 0 && Mirages::$options->headTitle__isTrue)) {
                            if (Mirages::$options->userNum > 1) {
                                echo "<a itemprop=\"name\" href=\""; $this->author->permalink(); echo "\" rel=\"author\">"; $this->author(); echo "</a>", " • ";
                            }
                            $this->date(I18n::dateFormat());
//                            echo " • ", "<a itemprop=\"discussionUrl\" href=\""; $this->permalink(); echo "#comments\">"; $this->commentsNum(_mt('评论'), _mt('1 条评论'), _mt('%d 条评论')); echo "</a>";
                            if(intval($this->viewsNum) > 0) {
                                echo " • ", _mt("阅读: %d", $this->viewsNum);
                            }
                            if (!$this->is("page")) {
                                echo " • "; $this->category(',');
                            }
                            if (Mirages::$options->hideReadSettings__isFalse && Mirages::$options->navbarStyle != 1) {
                                echo " • ", "<a href=\"javascript:void(0)\" id=\"page-read-setting-toggle\">", _mt('阅读设置'), "</a>";
                            }
                            if($this->user->hasLogin()) {
                                if ($this->is('page')) {
                                    echo " • ", "<a href=\"", Mirages::$options->adminUrl, "write-page.php?cid={$this->cid}\" target=\"_blank\">"; _me('编辑'); echo "</a>";
                                } else {
                                    echo " • ", "<a href=\"", Mirages::$options->adminUrl, "write-post.php?cid={$this->cid}\" target=\"_blank\">"; _me('编辑'); echo "</a>";
                                }
                            }
                        }
                        ?>
                    </h2>
                </div>

            </div>
            <?php endif?>
        </header>
    <?php endif?>
        <?php if($this->is("archive") && !$this->is("archive", "404") && !Mirages::$options->showBanner):?>
        <h2 class="archive-title"><?php $this->archiveTitle(array(
                'category'  =>  _mt('分类 %s 下的文章'),
                'search'    =>  _mt('包含关键字 %s 的文章'),
                'tag'       =>  _mt('标签 %s 下的文章'),
                'author'    =>  _mt('%s 发布的文章')), '', ''); ?></h2>
        <?php endif?>
        <div class="container">
            <div class="row">



