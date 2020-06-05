<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<style type="text/css">
    <?php if (!Device::isWindows()):?>
    /* Font - Open Sans */
    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: 300;
        font-display: fallback;
        src: local('Open Sans Light'),
        local('OpenSans-Light'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/300.woff2) format('woff2'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/300.woff) format('woff');
    }
    @font-face {
        font-family: 'Open Sans';
        font-style: italic;
        font-weight: 300;
        font-display: fallback;
        src: local('Open Sans Light Italic'),
        local('OpenSansLight-Italic'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/300i.woff2) format('woff2'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/300i.woff) format('woff');
    }
    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: 400;
        font-display: fallback;
        src: local('Open Sans'),
        local('OpenSans'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/400.woff2) format('woff2'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/400.woff) format('woff');
    }
    @font-face {
        font-family: 'Open Sans';
        font-style: italic;
        font-weight: 400;
        font-display: fallback;
        src: local('Open Sans Italic'),
        local('OpenSans-Italic'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/400i.woff2) format('woff2'),
        url(<?php echo STATIC_PATH ?>fonts/OpenSans/400i.woff) format('woff');
    }
    <?php endif;?>
    /* Lora */
    @font-face {
        font-family: 'Lora';
        font-style: normal;
        font-weight: 400;
        font-display: fallback;
        src:
                local('Lora Regular'),
                local('Lora-Regular'),
                url(<?php echo STATIC_PATH ?>fonts/Lora/400.woff2) format('woff2'),
                url(<?php echo STATIC_PATH ?>fonts/Lora/400.woff) format('woff');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family: 'Lora';
        font-style: normal;
        font-weight: 700;
        font-display: fallback;
        src:
                local('Lora Bold'),
                local('Lora-Bold'),
                url(<?php echo STATIC_PATH ?>fonts/Lora/700.woff2) format('woff2'),
                url(<?php echo STATIC_PATH ?>fonts/Lora/700.woff) format('woff');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
</style>
