<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<style type="text/css">
    body, button, input, optgroup, select, textarea {
        font-family: 'Mirages Custom', 'Merriweather', 'Open Sans', <?php echo I18n::fontFamily();?> 'Segoe UI Emoji', 'Segoe UI Symbol', Helvetica, Arial, sans-serif;
    }
    .github-box, .github-box .github-box-title h3 {
        font-family: 'Mirages Custom', 'Merriweather', 'Open Sans', <?php echo I18n::fontFamily();?> 'Segoe UI Emoji', 'Segoe UI Symbol', Helvetica, Arial, sans-serif !important;
    }
    .aplayer {
        font-family: 'Mirages Custom', 'Myriad Pro', 'Myriad Set Pro', 'Open Sans', <?php echo I18n::fontFamily();?> Helvetica, arial, sans-serif !important;
    }
    /* Serif */
    body.content-lang-en.content-serif .post-content {
        font-family: 'Lora', 'PT Serif', 'Source Serif Pro', Georgia, <?php echo I18n::fontFamily();?> serif;
    }
    body.content-lang-en.content-serif.serif-fonts .post-content {
        font-family: 'Lora', 'PT Serif', 'Source Serif Pro', <?php echo I18n::serifFontFamily(); ?> serif;
    }
    body.serif-fonts .post-content, body.serif-fonts .blog-title {
        font-family: <?php echo I18n::serifFontFamily(); ?> Georgia, serif;
    }
    .dark-mode-state-indicator {
        position: absolute;
        top: -999em;
        left: -999em;

        z-index: 1;
    }

    @media (prefers-color-scheme: dark) {
        .dark-mode-state-indicator {
            z-index: 11;
        }
    }
</style>
<style type="text/css">
    /** 页面样式调整 */
    <?php if(!Utils::hasValue(Mirages::$options->postQRCodeURL) || !Utils::hasValue(Mirages::$options->rewardQRCodeURL)): ?>
    .post-buttons a {
        width: -webkit-calc(100% / 2 - .3125rem);
        width: calc(100% / 2 - .3125rem);
    }
    <?php endif?>

    <?php if(Utils::hasValue(Mirages::$options->codeColor)):?>
    .post .post-content>*:not(pre) code {
        color: <?php echo Mirages::$options->codeColor?>;
    }
    <?php endif;?>
</style>
<?php
if(Utils::isHexColor(Mirages::$options->themeColor)) {
    $this->need('component/head_colors.php');
}
?>
<?php echo "\n"; ?>
<?php echo Utils::replaceStaticPath(Mirages::$options->customHTMLInHeadBottom); ?>
<script>
    var _czc = _czc || [];
    var _hmt = _hmt || [];
</script>
