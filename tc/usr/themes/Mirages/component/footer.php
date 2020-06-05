<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
</div>
</div>
<?php if($this->is('post') && !Device::isPhone() && (Utils::hasValue(Mirages::$options->postQRCodeURL) || Utils::hasValue(Mirages::$options->rewardQRCodeURL))):?>
<?php
    $postQRCodeURL = Utils::replaceStaticPath(Mirages::$options->postQRCodeURL);
    $postQRCodeURL = str_replace("{{%BASE64_LINK_WITHOUT_SLASH}}", str_replace('/', '-', base64_encode($this->permalink)), $postQRCodeURL);
    $postQRCodeURL = str_replace("{{%BASE64_LINK}}", base64_encode($this->permalink), $postQRCodeURL);
    $postQRCodeURL = str_replace("{{%LINK}}", $this->permalink, $postQRCodeURL);
?>
<div id="qr-box">
    <div class="post-qr-code-box">
        <img src="<?php echo $postQRCodeURL;?>" width="250" height="250" alt="<?php _me('本页链接的二维码')?>"/>
    </div>
    <div class="reward-qr-code-box">
        <img src="<?php echo Utils::replaceStaticPath(Mirages::$options->rewardQRCodeURL);?>" height="250" alt="<?php _me('打赏二维码')?>"/>
    </div>
</div>
<?php endif?>
<div id="body-bottom">
<?php if($this->is('post') || ($this->is('page') && ($this->allow('comment') || Mirages::$options->showHistoryCommentEvenClosed__isTrue)) || $this->is('attachment')):?>
<div class="container">
    <?php if($this->is('post')):?>
        <div class="post-near">
            <nav>
                <span class="prev"><?php Content::theNext($this, $this->created, $this->options->gmtTime, $this->type, _mt('没有更多了')); ?></span>
                <span class="next"><?php Content::thePrev($this, $this->created, $this->type, _mt('没有更多了')); ?></span>
            </nav>
        </div>
    <?php endif?>
    <?php if(Mirages::$options->commentDisabled__isFalse && ($this->allow('comment') || Mirages::$options->showHistoryCommentEvenClosed__isTrue)):?>
    <?php $this->need('component/comments.php'); ?>
    <?php endif?>
</div>
<?php endif?>
</div>
<?php if($this->is('index') || $this->is('category') || $this->is('tag')):?>
    <?php if(COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_DISQUS):?>
        <script type="text/javascript">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = '<?php Mirages::$options->disqusShortName()?>'; // required: replace example with your forum shortname
            window.DISQUSWIDGETS = undefined;
            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function () {
                var s = document.createElement('script'); s.async = true;
                s.type = 'text/javascript';
                s.src = '//' + disqus_shortname + '.disqus.com/count.js';
                (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
            }());
        </script>
    <?php endif?>
<?php endif?>
<?php
if(($this->is('post') || $this->is('page')) && Utils::hasValue($this->fields->js)) {
    echo "<script type=\"text/javascript\">\n";
    echo $this->fields->js;
    echo "\n</script>\n";
}
?>
</div><!-- end #body -->
<?php if(!Utils::isPjax() || !PJAX_ENABLED):?>
</div><!-- end #wrap -->
<?php
    $copyright = 'Copyright &copy; '. date('Y') .' <a href="'. Mirages::$options->siteUrl .'">'. Mirages::$options->title .'</a>'; if (!Device::isPhone()) {$theme = "";}$theme = (Device::isPhone() ? '': 'Powered by <a href="http://typecho.org" target="_blank">Typecho</a> • ') . 'Theme <a href="https://get233.com/archives/mirages-intro.html" target="_blank">Mirages</a>';
    if (Utils::hasValue(Mirages::$options->beian)) {
        $beiAn = "<a href=\"http://beian.miit.gov.cn\" target='_blank'> ".Mirages::$options->beian . "</a>";
        $copyright = "<p>{$copyright}</p><p>{$beiAn} • {$theme}</p>";
    } else {
        $copyright = "<p>{$copyright} • {$theme}</p>";
    }
?>
<footer id="footer" role="contentinfo">
    <div class="container"  data-index="<?php echo (Mirages::$options->timeValid - intval(Mirages::$options->timeValid / 1170) * 1170)?>">
        <?php echo $copyright;?>
    </div>
</footer>

<?php if (Mirages::$options->pjaxLoadStyle == Mirages_Const::PJAX_LOAD_STYLE_CIRCLE):?>
<div id="loader-wrapper">
    <div class="sk-circle">
        <div class="sk-circle1 sk-child"></div>
        <div class="sk-circle2 sk-child"></div>
        <div class="sk-circle3 sk-child"></div>
        <div class="sk-circle4 sk-child"></div>
        <div class="sk-circle5 sk-child"></div>
        <div class="sk-circle6 sk-child"></div>
        <div class="sk-circle7 sk-child"></div>
        <div class="sk-circle8 sk-child"></div>
        <div class="sk-circle9 sk-child"></div>
        <div class="sk-circle10 sk-child"></div>
        <div class="sk-circle11 sk-child"></div>
        <div class="sk-circle12 sk-child"></div>
    </div>
</div>
<?php endif;?>

<?php if(!Mirages::$options->loadJQueryInHead__isTrue):?>
    <script src="<?php echo STATIC_PATH ?>static/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>
<?php endif?>
<script src="<?php echo Content::jsUrl('mirages.main.min.js')?>" type="text/javascript"></script>
<?php $this->footer(); ?>
<?php if (file_exists(Mirages::themeUsrDir("highlight-ext.min.js"))):?>
    <script src="<?php echo STATIC_PATH ?>usr/highlight-ext.min.js" type="text/javascript"></script>
<?php endif;?>
<script type="text/javascript">Mirages.highlightCodeBlock();</script>

<?php if (Mirages::$options->enableMathJax): ?>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
          <?php if (Mirages::$options->texOptions__useDollarForInline): ?>
          tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]},
          <?php endif;?>
          skipStartupTypeset: true
        });
    </script>
    <script src="<?php echo STATIC_PATH ?>static/mathjax/2.7.5/MathJax.js" type="text/javascript"></script>
    <script src="<?php echo STATIC_PATH ?>static/mathjax/2.7.5/config/TeX-AMS-MML_SVG.js" type="text/javascript"></script>
<?php endif;?>

<script type="text/javascript">pangu.spacingElementByClassName('container');</script>
<script type="text/javascript">Waves.init();</script>
<?php if (Mirages::$options->enableFlowChat): ?>
    <script src="<?php echo STATIC_PATH ?>static/raphael/2.2.7/raphael.min.js"></script>
    <script src="<?php echo STATIC_PATH ?>static/flowchart/1.10.0/flowchart.min.js"></script>
    <script type="text/javascript">Mirages.renderFlowChart();</script>
<?php endif;?>

<?php if (Mirages::$options->enableMermaid): ?>
    <script src="<?php echo STATIC_PATH ?>static/mermaid/8.0.0/mermaid.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            Mirages.renderMermaid();
        });
    </script>
<?php endif;?>
<?php if(Mirages::$options->otherOptions__enableSSCROnWindows && !Device::isMobile() && Device::isWindowsAboveVista() && Device::is('Chrome', 'Edge')):?>
<script src="<?php echo STATIC_PATH ?>static/smoothscroll/1.4.9/SmoothScroll.min.js" type="text/javascript" async></script>
<?php endif?>
<?php echo Utils::injectCustomJS();?>
<script type="text/javascript">
    (function ($) {
        <?php if (COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_EMBED): ?>
        if (typeof registCommentEvent === 'function') {
            registCommentEvent();
        }
        <?php endif;?>
        <?php if(Mirages::$options->enableLazyLoad__isTrue):?>
        Mirages.setupLazyLoadImage();
        <?php endif?>
        <?php if(Mirages::$options->qiniuOptions__qiniuOptimize):?>
        Mirages.setupCDNImageOptimize();
        <?php endif?>
        Mirages.setupPage();
        <?php if(PJAX_ENABLED):?>
        $('body').pjax('a[href^="<?php Mirages::$options->rootUrl()?>"]:not(a[target="_blank"], a[no-pjax])', {
                container: '#body',
                fragment: '#body',
                timeout: 8000
            }
        ).on('pjax:click', function() {
            window.asyncBannerLoadNum = 0;
            window.asyncBannerLoadCompleteNum = 0;
            Mirages.doPJAXClickAction();
            <?php Mirages::$options->pjaxClickAction()?>
        }).on('pjax:send', function() {
            Mirages.doPJAXSendAction();
            <?php Mirages::$options->pjaxSendAction()?>
        }).on('pjax:complete', function() {
            <?php if (COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_EMBED): ?>
            if (typeof registCommentEvent === 'function') {
                registCommentEvent();
            }
            <?php elseif (COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_GENTIE):?>
            <?php endif;?>
            Mirages.doPJAXCompleteAction();
            <?php Mirages::$options->pjaxCompleteAction()?>
        });
        <?php endif?>
    })(jQuery);
    <?php if(PJAX_ENABLED):?>
    function ExSearchCall(item){
        if (item && item.length) {
            // 关闭搜索框
            $('.ins-close').click();
            $.pjax({
                url: item.attr('data-url'),
                container: '#body',
                fragment: '#body',
                timeout: 8000
            });
        }
    }
    <?php endif?>
</script>
<?php
    $needLoadWebFont = USE_GOOGLE_FONTS || USE_SERIF_FONTS;
    $families = '';
    if (USE_SERIF_FONTS) {
        $families .= '"Noto Serif SC:400,700&amp;subset=chinese-simplified,japanese"';
    }
    if (USE_GOOGLE_FONTS) {
        if (strlen($families) > 0) {
            $families .= ", ";
        }
        $families .= '"Open Sans:300,400,700:latin,latin-ext", "Lora:400,700"';
    }

?>
<?php if ($needLoadWebFont):?>
<script>
    WebFontConfig = {
        google: {
            families: [<?php echo $families ?>]
        },
        timeout: 3000
    };

    (function(d) {
        var wf = d.createElement('script'), s = d.scripts[0];
        wf.src = '<?php echo STATIC_PATH ?>static/webfont/1.6.24/webfontloader.js';
        s.parentNode.insertBefore(wf, s);
    })(document);
</script>
<?php endif?>
<?php echo Utils::replaceStaticPath(Mirages::$options->beforeBodyClose); ?>

</body>
</html>
<?php endif?>