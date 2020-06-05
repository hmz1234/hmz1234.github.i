<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<meta charset="<?php Mirages::$options->charset(); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="renderer" content="webkit">
<meta name="HandheldFriendly" content="true">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
<?php echo Content::exportDNSPrefetch(); ?>
<title><?php $this->archiveTitle(array(
        'category' => _mt('%s'),
        'search'   => _mt('包含关键字 %s 的文章'),
        'tag'      => _mt('%s'),
        'author'   => _mt('%s 发布的文章')
    ), '', ' - '); ?><?php Mirages::$options->title(); ?></title>
<?php echo Content::exportHeader($this)?>
<?php $this->header(Content::exportGeneratorRules($this));?>
<?php echo Utils::replaceStaticPath(Mirages::$options->customHTMLInHeadTitle); ?>
<?php if (USE_EMBED_FONTS) $this->need(Utils::hasValue(Mirages::$options->customFontFace)? ('usr/' . Mirages::$options->customFontFace) : 'component/head_font.php');?>
<link rel="stylesheet" href="<?php echo Content::cssUrl('mirages.min.css') ?>">
<script type="text/javascript">
    window['LocalConst'] = {
        THEME_VERSION: "<?php echo Mirages::$versionTag?>",
        BUILD: <?php echo intval(Mirages::$options->timeValid / 1170 / 1000)?>,
        BASE_SCRIPT_URL: "<?php echo STATIC_PATH ?>",
        IS_MOBILE: <?php echo Device::isMobile()?'true':'false'?>,
        IS_PHONE: <?php echo Device::isPhone()?'true':'false'?>,
        IS_TABLET: <?php echo Device::isTablet()?'true':'false'?>,
        HAS_LOGIN: <?php echo $this->user->hasLogin()?'true':'false'?>,
        IS_HTTPS: <?php echo IS_HTTPS?'true':'false'?>,
        ENABLE_PJAX: <?php echo  PJAX_ENABLED?'true':'false'?>,
        ENABLE_WEBP: <?php echo (Mirages::$options->qiniuOptions__enableWebP && Device::canEnableWebP())?'true':'false'?>,
        SHOW_TOC: false,
        ENABLE_IMAGE_SIZE_OPTIMIZE: <?php echo Mirages::$options->qiniuOptions__useQiniuImageResize?'true':'false'?>,
        THEME_COLOR: '<?php echo Utils::isHexColor(Mirages::$options->themeColor)?Mirages::$options->themeColor:'#1abc9c'?>',
        DISQUS_SHORT_NAME: '<?php Mirages::$options->disqusShortName()?>',
        COMMENT_SYSTEM: <?php echo COMMENT_SYSTEM ?>,
        OWO_API: '<?php echo Mirages::$options->owoApi?>',
        COMMENT_SYSTEM_DISQUS: <?php echo Mirages_Const::COMMENT_SYSTEM_DISQUS ?>,
        COMMENT_SYSTEM_DUOSHUO: <?php echo Mirages_Const::COMMENT_SYSTEM_DUOSHUO ?>,
        COMMENT_SYSTEM_EMBED: <?php echo Mirages_Const::COMMENT_SYSTEM_EMBED ?>,
        PJAX_LOAD_STYLE: <?php echo intval(Mirages::$options->pjaxLoadStyle)?>,
        PJAX_LOAD_STYLE_SIMPLE: <?php echo Mirages_Const::PJAX_LOAD_STYLE_SIMPLE ?>,
        PJAX_LOAD_STYLE_CIRCLE: <?php echo Mirages_Const::PJAX_LOAD_STYLE_CIRCLE ?>,
        AUTO_NIGHT_SHIFT: <?php echo (Mirages::$options->disableAutoNightTheme <= 0 && COMMENT_SYSTEM !== Mirages_Const::COMMENT_SYSTEM_DISQUS && Mirages::$options->baseTheme != Mirages_Const::THEME_MIRAGES_DARK && NIGHT_SHIFT_BTN_CLASS == 'auto-mode') ? 'true' : 'false'?>,
        USE_MIRAGES_DARK: false,
        PREFERS_DARK_MODE: false,
        LIGHT_THEME_CLASS: "<?php echo LIGHT_THEME_CLASS ?>",
        TOC_AT_LEFT: <?php echo Mirages::$options->showTOCAtLeft?'true':'false'?>,
        SERIF_LOAD_NOTICE: '<?php _me('加载 Serif 字体可能需要 10 秒钟左右，请耐心等待')?>',
        ROOT_FONT_SIZE: '<?php Mirages::$options->rootFontSize() ?>',
        BIAOQING_PAOPAO_PATH: '',
        BIAOQING_ARU_PATH: '',
        CDN_TYPE_OTHERS: -1,
        CDN_TYPE_QINIU: 1,
        CDN_TYPE_UPYUN: 2,
        CDN_TYPE_LOCAL: 3,
        CDN_TYPE_ALIYUN_OSS: 4,
        CDN_TYPE_QCLOUD_CI: 5,
        KEY_CDN_TYPE: '',
        UPYUN_SPLIT_TAG: '!',
        COMMENTS_ORDER: '<?php echo strtoupper(Mirages::$options->commentsOrder);?>',
        ENABLE_MATH_JAX: false,
        MATH_JAX_USE_DOLLAR: <?php echo Mirages::$options->texOptions__useDollarForInline ? 'true' : 'false'?>,
        ENABLE_FLOW_CHART: false,
        ENABLE_MERMAID: false,
        HIDE_CODE_LINE_NUMBER: <?php echo Mirages::$options->codeBlockOptions__hideLineNumber ? 'true' : 'false'?>,
        TRIM_LAST_LINE_BREAK_IN_CODE_BLOCK: <?php echo (Mirages::$options->disableTrimLastLineBreakInCodeBlock__isFalse) ? 'true':'false'?>
    };
    var Mlog = function (message) {
        <?php if(Mirages::$options->devMode__isTrue || Mirages::$options->showLog__isTrue):?>
        var time = new Date().toISOString().replace('T', ' ').replace('Z', '');
        console.log("[" + time + "] " + message);
        <?php endif?>
    };
    <?php if (Mirages::pluginAvailable(100) && method_exists("Mirages_Plugin", "biaoqingRootPath")):?>
        <?php $biaoqingRootPath = Mirages_Plugin::biaoqingRootPath();?>
        LocalConst.BIAOQING_PAOPAO_PATH = '<?php echo $biaoqingRootPath['paopao']?>';
        LocalConst.BIAOQING_ARU_PATH = '<?php echo $biaoqingRootPath['aru']?>';
        var BIAOQING_PAOPAO_PATH = LocalConst.BIAOQING_PAOPAO_PATH;
        var BIAOQING_ARU_PATH = LocalConst.BIAOQING_ARU_PATH;
    <?php endif?>
    <?php if (Mirages::pluginAvailable(102) && method_exists("Mirages_Plugin", "UPYunSplitTag")):?>
        LocalConst.KEY_CDN_TYPE = '<?php echo Mirages_Plugin::KEY_CDN_TYPE ?>';
        LocalConst.UPYUN_SPLIT_TAG = '<?php echo Mirages_Plugin::UPYunSplitTag()?>';
    <?php endif?>
</script>
<?php if (Mirages::$options->disableAutoNightTheme <= 0 && COMMENT_SYSTEM !== Mirages_Const::COMMENT_SYSTEM_DISQUS && THEME_CLASS != Mirages_Const::THEME_MIRAGES_DARK && NIGHT_SHIFT_BTN_CLASS != "day-mode" && NIGHT_SHIFT_BTN_CLASS != "sunset-mode"): ?>
    <script>
        var hour = new Date().getHours();
        if (hour <= 5 || hour >= 22) {
            LocalConst.USE_MIRAGES_DARK = true;
        }
    </script>
<?php endif ?>

<?php echo Utils::injectCustomCSS();?>
<?php if (strlen(Mirages::$options->shortcutIcon) > 5): ?>
    <link rel="shortcut icon" href="<?php echo Utils::replaceStaticPath(Mirages::$options->shortcutIcon)?>">
<?php else: ?>
    <link rel="shortcut icon" href="<?php echo rtrim(Mirages::$options->siteUrl, "/") ?>/favicon.ico">
<?php endif ?>

<?php if(Mirages::$options->loadJQueryInHead__isTrue):?>
    <script src="<?php echo STATIC_PATH ?>static/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>
<?php endif;?>
<?php echo "\n"; ?>

<script type="text/javascript">
    var autoHideElements = {};
    var CSS = function (css) {
        var link = document.createElement('link');
        link.setAttribute('rel', 'stylesheet');
        link.href = css;
        document.head.appendChild(link);
    };
    var STYLE = function (style, type) {
        type = type || 'text/css';
        var s = document.createElement('style');
        s.type = type;
        s.textContent = style;
        document.head.appendChild(s);
    };
    var JS = function (js, async) {
        async = async || false;
        var sc = document.createElement('script'), s = document.scripts[0];
        sc.src = js; sc.async = async;
        s.parentNode.insertBefore(sc, s);
    };
    var registAutoHideElement = function (selector) {
        var tmp = autoHideElements[selector];
        if (typeof(tmp) !== 'undefined') {
            return;
        }
        var element = document.querySelector(selector);
        if (element && typeof(Headroom) !== "undefined") {
            var headroom = new Headroom(element, {
                tolerance: 5,
                offset : 5,
                classes: {
                    initial: "show",
                    pinned: "show",
                    unpinned: "hide"
                }
            });
            headroom.init();
            autoHideElements[selector] = headroom;
        }
    };
    var getImageAddon = function (cdnType, width, height) {
        if (!LocalConst.ENABLE_IMAGE_SIZE_OPTIMIZE) {
            return "";
        }
        if (cdnType == LocalConst.CDN_TYPE_LOCAL || cdnType == LocalConst.CDN_TYPE_OTHERS) {
            return "";
        }
        var addon = "?";
        if (cdnType == LocalConst.CDN_TYPE_UPYUN) {
            addon = LocalConst.UPYUN_SPLIT_TAG;
        }
        var ratio = window.devicePixelRatio || 1;
        width = width || window.innerWidth;
        height = height || window.innerHeight;
        width = width || 0;
        height = height || 0;
        if (width == 0 && height == 0) {
            return "";
        }
        var format = "";
        if (LocalConst.ENABLE_WEBP) {
            if (cdnType == LocalConst.CDN_TYPE_ALIYUN_OSS) {
                format = "/format,webp"
            } else {
                format = "/format/webp";
            }
        }
        if (width >= height) {
            if (cdnType == LocalConst.CDN_TYPE_UPYUN) {
                addon += "/fw/" + parseInt(width * ratio) + "/quality/75" + format;
            } else if(cdnType == LocalConst.CDN_TYPE_ALIYUN_OSS) {
                addon += "x-oss-process=image/resize,w_" + parseInt(width * ratio) + "/quality,Q_75" + format;
            } else {
                addon += "imageView2/2/w/" + parseInt(width * ratio) + "/q/75" + format;
            }
        } else {
            if (cdnType == LocalConst.CDN_TYPE_UPYUN) {
                addon += "/fh/" + parseInt(width * ratio) + "/quality/75" + format;
            } else if(cdnType == LocalConst.CDN_TYPE_ALIYUN_OSS) {
                addon += "x-oss-process=image/resize,h_" + parseInt(width * ratio) + "/quality,Q_75" + format;
            } else {
                addon += "imageView2/2/h/" + parseInt(height * ratio) + "/q/75" + format;
            }
        }
        return addon;
    };
    var getBgHeight = function(windowHeight, bannerHeight, mobileBannerHeight){
        windowHeight = windowHeight || 560;
        if (windowHeight > window.screen.availHeight) {
            windowHeight = window.screen.availHeight;
        }
        bannerHeight = bannerHeight.trim();
        mobileBannerHeight = mobileBannerHeight.trim();
        if (window.innerHeight > window.innerWidth) {
            bannerHeight = parseFloat(mobileBannerHeight);
        } else {
            bannerHeight = parseFloat(bannerHeight);
        }
        bannerHeight = Math.round(windowHeight * bannerHeight / 100);
        return bannerHeight;
    };
    var registLoadBanner = function () {
        if (window.asyncBannerLoadNum >= 0) {
            window.asyncBannerLoadNum ++;
            Mlog("Loading Banner: " + window.asyncBannerLoadNum);
        }
    };
    var remove = function (element) {
        if (element) {
            if (typeof element['remove'] === 'function') {
                element.remove();
            } else if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }
    };
    var loadBannerDirect = function (backgroundImage, backgroundPosition, wrap, cdnType, width, height) {
        var background = wrap.querySelector('.blog-background');
        var imageSrc = backgroundImage + getImageAddon(cdnType, width, height);

        Mlog("Start Loading Banner Direct... url: " + imageSrc + "  cdnType: " + cdnType);

        if (typeof(backgroundPosition) === 'string' && backgroundPosition.length > 0) {
            background.style.backgroundPosition = backgroundPosition;
        }

        background.style.backgroundImage = 'url("' + imageSrc + '")';
    };
    var loadBanner = function (img, backgroundImage, backgroundPosition, wrap, cdnType, width, height, blured) {
        var background = wrap.querySelector('.blog-background');
        var container = wrap.querySelector('.lazyload-container');

        if (!background) {
            console.warn("background is null", background);
            return;
        }
        if (!container) {
            console.warn("container is null", container);
            return;
        }

        var imageSrc = backgroundImage + getImageAddon(cdnType, width, height);

        Mlog("Start Loading Banner... url: " + imageSrc + "  cdnType: " + cdnType);


        background.classList.add("loading");

        remove(img);
        if (typeof(backgroundPosition) === 'string' && backgroundPosition.length > 0) {
            container.style.backgroundPosition = backgroundPosition;
            background.style.backgroundPosition = backgroundPosition;
        }
        container.style.backgroundImage = 'url("' + img.src + '")';
        container.classList.add('loaded');

        blured = blured || false;
        if (blured) {
            return;
        }

        // load Src background image
        var largeImage = new Image();
        largeImage.src = imageSrc;
        largeImage.onload = function() {
            remove(this);
            if (typeof imageLoad !== 'undefined' && imageLoad >= 1) {
                background.classList.add('bg-failed');
            } else {
                background.style.backgroundImage = 'url("' + imageSrc + '")';
                background.classList.remove('loading');
                container.classList.remove('loaded');
            }
            setTimeout(function () {
                remove(container);
                if (window.asyncBannerLoadCompleteNum >= 0) {
                    window.asyncBannerLoadCompleteNum ++;
                    Mlog("Loaded Banner: " + window.asyncBannerLoadCompleteNum);
                    if (window.asyncBannerLoadCompleteNum === window.asyncBannerLoadNum) {
                        window.asyncBannerLoadNum = -1170;
                        window.asyncBannerLoadCompleteNum = -1170;
                        $('body').trigger("ajax-banner:done");
                    } else if (window.asyncBannerLoadCompleteNum > window.asyncBannerLoadNum) {
                        console.error("loaded num is large than load num.");
                        setTimeout(function () {
                            window.asyncBannerLoadNum = -1170;
                            window.asyncBannerLoadCompleteNum = -1170;
                            $('body').trigger("ajax-banner:done");
                        }, 1170);
                    }
                }
            }, 1001);
        };
    };
    var loadPrefersDarkModeState = function () {
        var indicator = document.createElement('div');
        indicator.className = 'dark-mode-state-indicator';
        document.body.appendChild(indicator);
        if (parseInt(mGetComputedStyle(indicator, 'z-index'), 10) === 11) {
            LocalConst.PREFERS_DARK_MODE = true;
        }
        remove(indicator);
    };
    var mGetComputedStyle = function (element, style) {
        var value;
        if (window.getComputedStyle) {
            // modern browsers
            value = window.getComputedStyle(element).getPropertyValue(style);
        } else if (element.currentStyle) {
            // ie8-
            value = element.currentStyle[style];
        }
        return value;
    };
</script>
