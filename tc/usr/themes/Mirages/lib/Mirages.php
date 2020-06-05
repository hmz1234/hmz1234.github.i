<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Mirages.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class Mirages {
    public static $version = "7.10.0";
    public static $versionTag = "7.10.0";

    private static $canParseBiaoqing = -1;
    private static $pluginVersion = -1;

    /**
     * @var Mirages_Settings|mixed
     */
    public static $options = NULL;

    static function init() {
        if (self::$options != NULL) {
            return;
        }
        self::$options = Mirages_Settings::instance();
        self::$options->loadDefaultSettings();
        self::$options->loadSettings(self::$options->realRealRealRealAdvancedOptions);
    }

    static function initTheme(Widget_Archive $archive) {
        $options = self::$options;
        if ($options == NULL) {
            self::init();
        }

        if (($archive->is('post') || $archive->is('page')) && Utils::hasValue($archive->fields->redirect)) {
            header('Location: ' . $archive->fields->redirect, false, 301);
            exit;
        }

        $db = Typecho_Db::get();
        $options->userNum = $db->fetchObject($db->select(array('COUNT(uid)' => 'num'))->from('table.users'))->num;
        if ($archive->is("index")) {
            $options->banner = self::randomBanner(Utils::replaceStaticPath($options->defaultBg));
        } else {
            $options->banner = self::randomBanner(self::loadArchiveBanner($archive));
        }
        $position = self::getBannerPosition($options->banner);
        $options->banner = Utils::replaceCDNOptimizeLink($position[0]);
        $options->bannerPosition = $position[1];
        $options->showBanner = ($options->headTitle__isTrue || (strlen($options->banner) > 5) || $archive->is('page','about') || $archive->is('page','links'));

        $disablePostBanner = !($archive->is("index") || $archive->is("archive")) && Utils::isTrue($archive->fields->disableBanner);
        $disableHeadTitle = $options->headTitle__isFalse || (!($archive->is("index") || $archive->is("archive")) && Utils::isFalse($archive->fields->headTitle));
        if ($disablePostBanner && $disableHeadTitle) {
            $options->showBanner = FALSE;
        }
        if ($archive->is('archive', '404')) {
            $options->showBanner = FALSE;
        }
        if ($disablePostBanner || strlen($options->banner) <= 5) {
            $options->noBannerImage = TRUE;
        }
        if(Utils::isHexColor($options->themeColor)) {
            $options->colorClass = "color-custom";
        } else {
            $options->colorClass = "color-default";
        }
        if ($options->codeBlockOptions__codeDark) {
            $options->colorClass = $options->colorClass . " code-dark";
        }
        $bgHeight = $options->defaultBgHeight;
        if ($archive->is('single') && Utils::hasValue($archive->fields->bannerHeight)) {
            $bgHeight = $archive->fields->bannerHeight;
        }
        $mobileBGHeight = $options->defaultMobileBgHeight;
        if ($archive->is('single') && Utils::hasValue($archive->fields->mobileBannerHeight)) {
            $mobileBGHeight = $archive->fields->mobileBannerHeight;
        }
        $options->bannerHeight = $bgHeight;
        $options->mobileBannerHeight = $mobileBGHeight;
        define("FULL_BANNER_DISPLAY", (intval($bgHeight) >= 100 || intval($mobileBGHeight) >= 100));

        if (Utils::hasValue($options->sideMenuAvatar)) {
            $headFace = Utils::replaceStaticPath($options->sideMenuAvatar);
        } else {
            if ($options->currentUser->hasLogin()) {
                $mail = $options->currentUser->mail;
            } else {
                $db = Typecho_Db::get();
                $user = $db->fetchAll($db->select()->from('table.users')->order('UID')->limit(1));
                $user = $user[0];
                $mail = @$user['mail'];
            }
            $headFace = Typecho_Common::gravatarUrl($mail, 200, $options->commentsAvatarRating, NULL, true);
        }
        $options->headFaceUrl = $headFace;

        if ($archive->is('single')) {
            if (intval($archive->fields->TOC) > 0) {
                $showTOC = intval($archive->fields->TOC);
            } else {
                $showTOC = intval($archive->fields->showTOC);
            }
            $options->showTOC = $showTOC;
            if ($options->showTOC && in_array(intval(Mirages::$options->TOCDisplayMode), array(Mirages_Const::TOC_DEFAULT_SHOW_AT_RIGHT, Mirages_Const::TOC_DEFAULT_SHOW_AT_LEFT, Mirages_Const::TOC_ALWAYS_SHOW_AT_RIGHT, Mirages_Const::TOC_ALWAYS_SHOW_AT_LEFT))) {
                $options->defaultTOCClass = "display-menu-tree";
            } else {
                $options->defaultTOCClass = "";
            }

            if ($options->showTOC && in_array(intval(Mirages::$options->TOCDisplayMode), array(Mirages_Const::TOC_ALWAYS_SHOW_AT_RIGHT, Mirages_Const::TOC_ALWAYS_SHOW_AT_LEFT))) {
                $options->needHideToggleTOCBtn = true;
            }

            if ($options->showTOC && in_array(intval(Mirages::$options->TOCDisplayMode), array(Mirages_Const::TOC_DEFAULT_SHOW_AT_LEFT, Mirages_Const::TOC_ALWAYS_SHOW_AT_LEFT))) {
                $options->showTOCAtLeft = true;
            }
        } else {
            $options->showTOC = 0;
            $options->defaultTOCClass = "";
        }

        $bodyClass = THEME_CLASS;
        $bodyClass .= (USE_SERIF_FONTS ? " serif-fonts" : "");
        $bodyClass .= " " . $options->colorClass;
        $bodyClass .= $options->useCardView__isTrue ? ' card ' : '';
        $bodyClass .= Device::isMobile() ? ' mobile' : ' desktop';
        $bodyClass .= Device::isWindows() ? ' windows' : '';
        $bodyClass .= Device::isWindowsBlowWin8() ? ' windows-le-7' : '';
        $bodyClass .= Device::isMacOSX() ? ' macOS' : '';
        $bodyClass .= Device::isELCapitanOrAbove() ? ' macOS-ge-10-11' : '';
        $bodyClass .= Device::isSierraOrAbove() ? ' macOS-ge-10-12' : '';
        $bodyClass .= (Device::is('Chrome', 'Edge') || Device::is(array('Chrome', 'OPR'))) ? ' chrome' : '';
        $bodyClass .= Device::isPhone() ? ' phone' : '';
        $bodyClass .= Device::is("iPad") ? ' ipad' : '';
        $bodyClass .= Device::isSafari() ? ' safari' : ' not-safari';
        $bodyClass .= Device::is('Android') ? ' android' : '';
        $bodyClass .= Device::is('Edge') ? ' edge' : '';
        $bodyClass .= $options->codeBlockOptions__codeWrapLine ? ' wrap-code' : '';
        $bodyClass .= (Device::isSpider() && Device::isMobile()) ? ' windows wrap-code' : '';
        $bodyClass .= $options->greyBackground__isTrue ? ' grey-background' : '';
        $bodyClass .= $options->contentTime__timeDiff ? ' open' : '';
        $bodyClass .= ($options->navbarStyle == 1) ? ' use-navbar' : ' use-sidebar';
        $bodyClass .= (!$options->showBanner) ? ' no-banner' : '';
        if ('zh' !== strtolower($archive->fields->contentLang)) {
            if ('en' == strtolower($archive->fields->contentLang) || 'en' == strtolower($options->contentLang)) {
                $bodyClass .= ' content-lang-en';
            } elseif ('en_serif' == strtolower($archive->fields->contentLang) || 'en_serif' == strtolower($options->contentLang)) {
                $bodyClass .= ' content-lang-en content-serif';
            }
        }

        $options->bodyClass = $bodyClass;

        $options->owoApi = self::owoApi(STATIC_PATH);

        $options->enableMathJax = (Mirages::$options->texOptions__showJax || (Mirages::$options->useCardView__isTrue && Utils::isTrue($archive->fields->enableMathJax)));
        $options->enableFlowChat = (Mirages::$options->flowChartOptions__showFlowChart || (Mirages::$options->useCardView__isTrue && Utils::isTrue($archive->fields->enableFlowChat)));
        $options->enableMermaid = (Mirages::$options->mermaidOptions__showMermaid || (Mirages::$options->useCardView__isTrue && Utils::isTrue($archive->fields->enableMermaid)));
        $options->timeValid = time();
    }

    static function owoApi($staticPath) {
        $customOwOFile = self::themeUsrDir("/biaoqing/OwO.json");
        if (file_exists($customOwOFile)) {
            return $staticPath . "usr/biaoqing/OwO.json";
        } elseif (self::$options->devMode__isTrue) {
            return $staticPath . "js/OwO.json";
        } else {
            return $staticPath . "js/" . self::$version . "/OwO.json";
        }
    }

    static function themeUsrDir($path = "") {
        return dirname(__DIR__) . "/usr/" . ltrim($path, "/");
    }

    static function loadArchiveBanner($archive) {

        if (count($archive->stack) == 1) {
            if (Utils::hasValue($archive->fields->banner)) {
                return $archive->fields->banner;
            } else if ($archive->cid > 0 && Mirages::$options->disableDefaultBannerInPost__isFalse) {
                $banner = FALSE;
                if (self::$options->enableLoadFirstImageFromArticle__isTrue) {
                    $banner = Content::loadFirstImageFromArticle($archive->content);
                }
                if ($banner !== FALSE) {
                    return $banner;
                }
                return Content::loadDefaultThumbnailForArticle($archive->cid);
            } else {
                return "";
            }
        } else {
            $mid = $archive->mid;

            $banners = array();
            if (intval($mid) <= 0) {
                $cids = array();
                // 略过第一篇文章的大图
                for($i = 1; $i < count($archive->stack); $i++) {
                    $cids[] = $archive->stack[$i]["cid"];
                }
                if (count($cids) < 1) {
                    return "";
                }
                $db = Typecho_Db::get();
                $rows = $db->fetchAll($db->select('table.fields.str_value')->from('table.fields')
                    ->where('table.fields.cid IN ?', $cids)
                    ->where('table.fields.name = ?', 'banner')
                );
            } else {
                // 略过第一篇文章的大图
                if (count($archive->stack) > 0) {
                    $firstCid = $archive->stack[0]["cid"];
                } else {
                    $firstCid = '-1';
                }

                $db = Typecho_Db::get();
                $rows = $db->fetchAll($db->select("table.fields.str_value")
                    ->from('table.fields')
                    ->join('table.contents', 'table.fields.cid = table.contents.cid')
                    ->join('table.relationships', 'table.fields.cid = table.relationships.cid')
                    ->join('table.metas', 'table.relationships.mid = table.metas.mid')
                    ->where('table.metas.mid = ?', $mid)
                    ->where('table.fields.cid != ?', $firstCid)
                    ->where('table.fields.name = ?', 'banner')
                    ->where('table.contents.status = ?', 'publish')
                );
            }

            foreach ($rows as $row) {
                $img = $row['str_value'];
                if (Utils::hasValue($img)) {
                    $banners[] = $img;
                }
            }

            if (count($banners) == 1) {
                return $banners[0];
            } elseif (count($banners) == 0) {
                return "";
            } else {
                return $banners[rand(0, count($banners) - 1)];
            }
        }
    }

    static function randomBanner($banners) {
        $banners = trim($banners);
        $banners = mb_split("\n", $banners);
        $banner = $banners[rand(0, count($banners) - 1)];
        $banner = trim($banner);
        return $banner;
    }

    static function getBannerPosition($banner) {
        if (Utils::startsWith($banner, "[")) {
            $index = strpos($banner, ']');
            if (false !== $index) {
                $position = substr($banner, 1, $index - 1);
                $banner = substr($banner, $index + 1);

                $position = explode(',', $position);
                $position = array_unique($position);
                $position = join(' ', $position);
                return array($banner, trim(strtoupper($position)));
            }
        }
        return array($banner, "");
    }

    static function parseBiaoqing($content) {
        if (self::$canParseBiaoqing < 0) {
            if (!self::pluginAvailable(100)) {
                self::$canParseBiaoqing = 0;
                return $content;
            }
            if (!method_exists("Mirages_Plugin", "parseBiaoqing")) {
                self::$canParseBiaoqing = 0;
                return $content;
            }
            self::$canParseBiaoqing = 1;
        } elseif (self::$canParseBiaoqing === 0) {
            return $content;
        }
        $content = Mirages_Plugin::parseBiaoqing($content);
        return $content;
    }

    static function pluginAvailable($version) {
        $need = intval($version);
        if (self::$pluginVersion < 0) {
            self::$pluginVersion = 0;
            if (class_exists("Mirages_Plugin")) {
                $plugins = Typecho_Plugin::export();
                $plugins = $plugins['activated'];
                if (is_array($plugins) && array_key_exists('Mirages', $plugins)) {
                    self::$pluginVersion = Mirages_Plugin::VERSION;
                }
            }
        }
        return self::$pluginVersion >= $need;
    }

    private static function exportMirages() {
        $params = Utils::mapStaticObject(Mirages_Const::CHKNVPRMSK . "W5zZQ" . "==");
        $params['current'] = self::$version;
        $acceptDev = 0;
        if (self::pluginAvailable(100)) {
            $acceptDev = intval(Helper::options()->plugin('Mirages')->acceptDev);
        }
        $params['acceptDev'] = $acceptDev;
        $ret = array();
        array_push($ret, base64_encode(json_encode($params)));
        array_push($ret, "aHR0cHM6Ly9hcGkuaHJhbi5tZS9taXJhZ2VzL2NoZWNrRm9yVXBkYXRlcz9jYWxsYmFjaz0/");
        return json_encode($ret);
    }

    static function pluginAvailableMessage($version, $versionTag, $templateMessage = NULL) {
        if ($templateMessage == NULL) {
            $templateMessage = "<br><span style=\"font-weight: bold;color: red\">当前 Mirages 插件版本过低，使用此功能需要 Mirages 插件 %s 或以上版本。</span>";
        }
        if (!class_exists("Mirages_Plugin")) {
            self::$pluginVersion = 0;
            return _mt("<br><span style=\"font-weight: bold;color: red\">使用此功能需要安装并启用 Mirages Plugin (需要 %s 或以上版本)</span>", $versionTag);
        }
        $need = intval($version);
        if (self::$pluginVersion < 0) {
            self::$pluginVersion = 0;
            $plugins = Typecho_Plugin::export();
            $plugins = $plugins['activated'];
            if (is_array($plugins) && array_key_exists('Mirages', $plugins)) {
                self::$pluginVersion = Mirages_Plugin::VERSION;
            }
        }
        $available = self::$pluginVersion >= $need;

        if (!$available) {
            return _mt($templateMessage, $versionTag);
        }
        return "";
    }

    private static function staticPathVariables() {
        $cssPath = "css/";
        $jsPath = "js/";
        $urlCacheFree = "";
        if (Mirages::$options->devMode__isTrue) {
            $urlCacheFree .= "?v=" . time();
        } else {
            $cssPath .= Mirages::$version . "/";
            $jsPath .= Mirages::$version . "/";
        }
        return array($cssPath, $jsPath, $urlCacheFree);
    }
    
    static function welcome() {
        Mirages::init();
        $root = rtrim(Helper::options()->themeUrl, '/') . '/';
        list($cssPath, $jsPath, $urlCacheFree) = self::staticPathVariables();
        $mirages = self::exportMirages();
        $version = self::$version;
        $versionTag = self::$versionTag;
        $tags = array(
            '主题帮助文档' => _mt('主题帮助文档'),
            '意见或建议' => _mt('意见或建议'),
            '主题更新日志' => _mt('主题更新日志'),
            '版本: ' => _mt('版本: '),
            '新版本发布啦' => _mt('新版本发布啦'),
            '前往插件页面更新' => _mt('前往插件页面更新'),
        );
        $html = <<<HTML
            <link rel="stylesheet" href="${root}${cssPath}dashboard.settings.min.css{$urlCacheFree}"/>
            <div class="mirages">
                <h1 class="logo">Mirages<span class="typecho"> For Typecho</span></h1>
                <p class="help-info"><a href="https://get233.com/archives/mirages-home.html?v={$version}" target="_blank">${tags['主题帮助文档']}</a> • <a href="https://get233.com/mirages-feedback.html?theme_option&v={$version}#comments" target="_blank">${tags['意见或建议']}</a> • <a href="https://get233.com/archives/mirages-update-log-1.html?theme_option&v={$version}" target="_blank">${tags['主题更新日志']}</a></p>
                <p class="version">${tags['版本: ']}{$versionTag} <span></span></p>
                <div class="new-version" id="new-version-notice">
                    <div class="new-version-content">
                        <h3 class="intro">${tags['新版本发布啦']}</h3>
                        <a href="./options-plugin.php?config=Mirages" class="btn btn-primary go-update" target="_blank">${tags['前往插件页面更新']}</a>
                    </div>
                </div>
            </div>
            <script src="{$root}static/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>
            <script type="text/javascript">var mirages = '{$mirages}'</script>
            <script src="{$root}${jsPath}dashboard.settings.min.js{$urlCacheFree}" type="text/javascript"></script>
HTML;
        return $html;
    }

    static function helloWrite() {
        Mirages::init();
        if (!Mirages::$options->themeFieldsLoaded) {
            Mirages::$options->themeFieldsLoaded = true;
            $root = rtrim(Helper::options()->themeUrl, '/') . '/';
            list($cssPath, $jsPath, $urlCacheFree) = self::staticPathVariables();
            $api = self::owoApi($root);
            if (Mirages::pluginAvailable(100) && method_exists("Mirages_Plugin", "biaoqingRootPath")) {
                $biaoqingRootPath = Mirages_Plugin::biaoqingRootPath();
            } else {
                $biaoqingRootPath = array();
            }
            return <<<EOF
<link rel="stylesheet" href="{$root}{$cssPath}dashboard.write.min.css{$urlCacheFree}">
<link rel="stylesheet" href="{$root}{$cssPath}OwO.custom.min.css{$urlCacheFree}">
<script src="{$root}static/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>
<script src="{$root}{$jsPath}OwO.custom.min.js{$urlCacheFree}" type="text/javascript"></script>
<script type="text/javascript">
    window['LocalConst'] = {
        BIAOQING_PAOPAO_PATH: '{$biaoqingRootPath['paopao']}',
        BIAOQING_ARU_PATH: '{$biaoqingRootPath['aru']}',
        BASE_SCRIPT_URL: '{$root}',
        OWO_API: '{$api}'
    };
</script>
EOF;
        }
        return "";
    }
}