<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
    $hexColor = Mirages::$options->themeColor;
    $hexColorDark = Mirages::$options->themeColorDark;
    if (!Utils::hasValue($hexColorDark)) {
        $hexColorDark = $hexColor;
    }
?>
<!-- 主题主色调 -->
<style type="text/css">
    /* Color - Custom */
    body.color-custom a {
        color: <?php echo $hexColor?>;
    }
    body.color-custom *::selection {
        background: <?php echo Mirages::$options->get('themeSelectionBackgroundColor', $hexColor)?>;
        color: <?php echo Mirages::$options->get('themeSelectionColor', '#fff')?>;
    }
    body.color-custom #index article a, body.color-custom #post article a, body.color-custom #archive article a {
        color: <?php echo $hexColor?>;
    }
    body.color-custom #footer a:after, body.color-custom #header .nav li a:after, body.color-custom #post .post-meta a:after, body.color-custom #index .comments a:after, body.color-custom #index .post-content a:after, body.color-custom #post .post-content a:after, body.color-custom #archive .post-content a:after, body.color-custom #archive .comments a:after, body.color-custom #comments a:after {
        border-color: <?php echo $hexColor?>;
    }
    body.color-custom .post-content a {
        color: <?php echo $hexColor?>;
    }
    body.color-custom .post-near {
        color: <?php echo $hexColor?>;
    }
    body.color-custom #nav .search-box .search{
        color: <?php echo $hexColor?>;
    }
    body.color-custom #comments .comment-list a, body.color-custom #comments .respond a  {
        color: <?php echo $hexColor?>;
    }
    body.color-custom #comments .widget-title {
        color: <?php echo $hexColor?>;
    }
    body.color-custom .color-main {
        color: <?php echo $hexColor?> !important;
    }
    body.color-custom #disqus_thread a {
        color: <?php echo $hexColor?>;
    }
    body.color-custom #footer a {
        color: <?php echo $hexColor?>;
    }
    body.color-custom .github-box .github-box-download .download:hover{
        border-color: <?php echo $hexColor?> !important;
        background-color: <?php echo Utils::hex2RGBColor($hexColor, 0.4)?> !important;
    }
    body.color-custom .sp-progress {
        background: linear-gradient(45deg, <?php echo Utils::hex2RGBColor($hexColor, 0)?>, <?php echo Utils::hex2RGBColor($hexColor, 0.1)?> 25%, <?php echo Utils::hex2RGBColor($hexColor, 0.35)?> 50%, <?php echo Utils::hex2RGBColor($hexColor, 1)?> 75%, <?php echo Utils::hex2RGBColor($hexColor, 0.1)?>);
    }

    li.index-menu-item.current>a.index-menu-link, body.color-custom li.index-menu-item.current>a.index-menu-link{
        color: <?php echo $hexColor?>;
        border-left: .125rem solid <?php echo $hexColor?>;
    }

    body.color-custom .post-content .content-file:hover .content-file-icon,
    body.color-custom .post-content .content-file:hover .content-file-filename,
    body.color-custom .comment-content .content-file:hover .content-file-icon,
    body.color-custom .comment-content .content-file:hover .content-file-filename,
    body.color-custom.theme-sunset .post-content .content-file:hover .content-file-icon,
    body.color-custom.theme-sunset .post-content .content-file:hover .content-file-filename,
    body.color-custom.theme-sunset .comment-content .content-file:hover .content-file-icon,
    body.color-custom.theme-sunset .comment-content .content-file:hover .content-file-filename {
        color: <?php echo $hexColor?>;
    }

    body.color-custom .post-content .content-file:hover,
    body.color-custom .comment-content .content-file:hover,
    body.color-custom.theme-sunset .post-content .content-file:hover,
    body.color-custom.theme-sunset .comment-content .content-file:hover {
        border-color: <?php echo $hexColor?>;
    }

    /* Color - Custom Dark */
    body.theme-dark a {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom *::selection {
        background: <?php echo Mirages::$options->get('themeSelectionBackgroundDarkColor', $hexColorDark)?>;
        color: <?php echo Mirages::$options->get('themeSelectionDarkColor', '#fff')?>;
    }
    body.theme-dark.color-custom #index article a, body.theme-dark.color-custom #post article a, body.theme-dark.color-custom #archive article a {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom #footer a:after,
    body.theme-dark.color-custom #header .nav li a:after,
    body.theme-dark.color-custom #post .post-meta a:after,
    body.theme-dark.color-custom #index .comments a:after,
    body.theme-dark.color-custom #index .post-content a:after,
    body.theme-dark.color-custom #post .post-content a:after,
    body.theme-dark.color-custom #archive .post-content a:after,
    body.theme-dark.color-custom #archive .comments a:after,
    body.theme-dark.color-custom #comments a:after
    {
        border-color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom .post-content a {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom .post-near {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom #nav .search-box .search {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom #comments .comment-list a, body.theme-dark.color-custom #comments .respond a  {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom #comments .widget-title {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom .color-main {
        color: <?php echo $hexColorDark?> !important;
    }
    body.theme-dark.color-custom #disqus_thread a {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom #footer a {
        color: <?php echo $hexColorDark?>;
    }
    body.theme-dark.color-custom .github-box .github-box-download .download:hover{
        border-color: <?php echo $hexColorDark?> !important;
        background-color: <?php echo Utils::hex2RGBColor($hexColorDark, 0.4)?> !important;
    }
    body.color-custom.theme-dark #index .post-content a:not(.no-icon), body.color-custom.theme-dark #archive .post-content a:not(.no-icon),body.color-custom.theme-dark #post .post-content a:not(.no-icon) {
        color: <?php echo $hexColorDark?>;
    }
    body.color-custom.theme-dark li.index-menu-item.current>a.index-menu-link {
        color: <?php echo $hexColorDark?>;
        border-left: .125rem solid <?php echo $hexColorDark?>;
    }
    body.color-custom.theme-dark .sp-progress {
        background: linear-gradient(45deg, <?php echo Utils::hex2RGBColor($hexColorDark, 0)?>, <?php echo Utils::hex2RGBColor($hexColorDark, 0.1)?> 25%, <?php echo Utils::hex2RGBColor($hexColorDark, 0.35)?> 50%, <?php echo Utils::hex2RGBColor($hexColorDark, 1)?> 75%, <?php echo Utils::hex2RGBColor($hexColorDark, 0.1)?>);
    }

    body.color-custom.theme-dark .post-content .content-file:hover .content-file-icon,
    body.color-custom.theme-dark .post-content .content-file:hover .content-file-filename,
    body.color-custom.theme-dark .comment-content .content-file:hover .content-file-icon,
    body.color-custom.theme-dark .comment-content .content-file:hover .content-file-filename {
        color: <?php echo $hexColorDark?>;
    }
    body.color-custom.theme-dark .post-content .content-file:hover,
    body.color-custom.theme-dark .comment-content .content-file:hover {
        border-color: <?php echo $hexColorDark?>;
    }

<?php if(Device::isMobile()):?>
<?php else:?>
    /*桌面端*/
    body.color-custom #index .post .post-title:hover,body.color-custom #archive .post .post-title:hover {
        color: <?php echo $hexColor?>;
    }
    body.color-custom.theme-dark #index .post .post-title:hover,body.color-custom.theme-dark #archive .post .post-title:hover {
        color: <?php echo $hexColorDark?>;
    }
<?php endif?>
</style>