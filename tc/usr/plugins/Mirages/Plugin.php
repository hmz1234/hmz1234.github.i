<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Mirages 主题专用插件
 *
 * @package Mirages
 * @author Hran
 * @version 7.10.0
 * @link https://get233.com
 */
class Mirages_Plugin implements Typecho_Plugin_Interface {

    //region 插件冲突解决方案系列

    /**
     * 用于插件冲突解决，该方法对应的 Hook 为：
     * `Typecho_Plugin::factory('Widget_Abstract_Contents')->content`
     * 详情请见 @see https://hran.me/archives/typecho-plugin-conflict-solution.html
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return mixed
     */
    private static function invokeContentPlugin($content, Widget_Abstract_Contents $widget) {
        $content = $widget->pluginHandle("Mirages_Plugin")->content($content, $widget);
        $content = $widget->pluginHandle("Mirages_Plugin")->content2($content, $widget);

        /*INSERT_PLACEHOLDER_CONTENT*/
        return $content;
    }

    /**
     * 用于插件冲突解决，该方法对应的 Hook 为：
     * `Typecho_Plugin::factory('Widget_Abstract_Contents')->excerpt`
     * 详情请见 @see https://hran.me/archives/typecho-plugin-conflict-solution.html
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return mixed
     */
    private static function invokeExcerptPlugin($content, Widget_Abstract_Contents $widget) {
        $content = $widget->pluginHandle("Mirages_Plugin")->excerpt($content, $widget);
        $content = $widget->pluginHandle("Mirages_Plugin")->excerpt2($content, $widget);


        /*INSERT_PLACEHOLDER_EXCERPT*/
        return $content;
    }

    /**
     * 用于插件冲突解决，该方法对应的 Hook 为：
     * `Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx`
     * 详情请见 @see https://hran.me/archives/typecho-plugin-conflict-solution.html
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return mixed
     */
    private static function invokeContentExPlugin($content, Widget_Abstract_Contents $widget) {
        $content = $widget->pluginHandle("Mirages_Plugin")->contentEx($content, $widget);
        $content = $widget->pluginHandle("Mirages_Plugin")->contentEx2($content, $widget);


        /*INSERT_PLACEHOLDER_CONTENT_EX*/
        return $content;
    }

    /**
     * 用于插件冲突解决，该方法对应的 Hook 为：
     * `Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx`
     * 详情请见 @see https://hran.me/archives/typecho-plugin-conflict-solution.html
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return mixed
     */
    private static function invokeExcerptExPlugin($content, Widget_Abstract_Contents $widget) {
        $content = $widget->pluginHandle("Mirages_Plugin")->excerptEx($content, $widget);
        $content = $widget->pluginHandle("Mirages_Plugin")->excerptEx2($content, $widget);


        /*INSERT_PLACEHOLDER_EXCERPT_EX*/
        return $content;
    }

    /**
     * 用于插件冲突解决，该方法对应的 Hook 为：
     * `Typecho_Plugin::factory('Widget_Contents_Post_Edit')->write`
     * 详情请见 @see https://hran.me/archives/typecho-plugin-conflict-solution.html
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return mixed
     */
    private static function invokePostWritePlugin($content, Widget_Abstract_Contents $widget) {
        $content = $widget->pluginHandle("Mirages_Plugin")->writePost($content, $widget);
        $content = $widget->pluginHandle("Mirages_Plugin")->writePost2($content, $widget);


        /*INSERT_PLACEHOLDER_POST_WRITE*/
        return $content;
    }

    /**
     * 用于插件冲突解决，该方法对应的 Hook 为：
     * `Typecho_Plugin::factory('Widget_Contents_Page_Edit')->write`
     * 详情请见 @see https://hran.me/archives/typecho-plugin-conflict-solution.html
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return mixed
     */
    private static function invokePageWritePlugin($content, Widget_Abstract_Contents $widget) {
        $content = $widget->pluginHandle("Mirages_Plugin")->writePage($content, $widget);
        $content = $widget->pluginHandle("Mirages_Plugin")->writePage2($content, $widget);


        /*INSERT_PLACEHOLDER_PAGE_WRITE*/
        return $content;
    }
    //endregion

    //region Const
    const KEY_WIDTH = "mirages-width";
    const KEY_HEIGHT = "mirages-height";
    const KEY_CDN_TYPE = "mirages-cdn-type";


    const VERSION = 71000.99;
    const VERSION_TAG = "7.10.0";

    const CDN_TYPE_OTHERS = -1;
    const CDN_TYPE_QINIU = 1;
    const CDN_TYPE_UPYUN = 2;
    const CDN_TYPE_LOCAL = 3;
    const CDN_TYPE_ALIYUN_OSS = 4;
    const CDN_TYPE_QCLOUD_CI = 5;

    const CDN_NAME_QINIU = 'QINIU';
    const CDN_NAME_UPYUN = 'UPYUN';
    const CDN_NAME_LOCAL = 'LOCAL';
    const CDN_NAME_ALIYUN_OSS = 'ALIOSS';
    const CDN_NAME_QCLOUD_CI = 'QCLOUD';
    //endregion

    //region Fields
    /** @var Typecho_Config */
    private static $devMode = false;
    private static $pluginOptions = null;
    /** @var Widget_Options */
    private static $themeOptions = null;
    private static $themeAdvancedOptions = array();
    private static $themeVersion = -1;
    private static $themeVersionRaw = null;
    private static $themeVersionTag = null;

    private static $currentWidget = NULL;

    private static $typechoVersion = null;
    private static $typechoDateVersion = null;

    private static $supportedCDNType = array(
        self::CDN_TYPE_QINIU,
        self::CDN_TYPE_UPYUN,
        self::CDN_TYPE_ALIYUN_OSS,
        self::CDN_TYPE_QCLOUD_CI,
    );

    private static $cdnHosts = array(
        self::CDN_NAME_QINIU => array(
            "clouddn.com",
            "qiniucdn.com",
            "qiniudn.com",
            "qnssl.com",
            "qbox.me",
        ),
        self::CDN_NAME_UPYUN => array(
            "upaiyun.com",
        ),
        self::CDN_NAME_ALIYUN_OSS => array(
            "aliyuncs.com"
        ),
        self::CDN_NAME_QCLOUD_CI => array(
            "piccd.myqcloud.com",
            "picsh.myqcloud.com",
            "picbj.myqcloud.com",
        )
    );

    private static $pluginBaseUrl = null;
    private static $themeBaseUrl = null;
    private static $biaoqingRootPath = array();

    private static $lastPostModified = false;
    private static $optionLoaded = false;

    private static $cleanMode = false;
    private static $cleanConfirmMode = false;

    private static $shortcodeTags = array();
    private static $shortcodeInlineTags = array();
    private static $shortcodeBlockTags = array();
    private static $shortcodeStats = array();
    //endregion

    //region 插件基础方法
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Contents_Post_Edit')->write_99999999 = array("Mirages_Plugin", 'postWrite');
        Typecho_Plugin::factory('Widget_Contents_Page_Edit')->write_99999999 = array("Mirages_Plugin", 'pageWrite');

        Typecho_Plugin::factory('Widget_Abstract_Contents')->content_99999999 = array('Mirages_Plugin', 'content');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerpt_99999999 = array('Mirages_Plugin', 'excerpt');

        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx_99999999 = array("Mirages_Plugin", 'contentEx');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx_99999999 = array("Mirages_Plugin", 'excerptEx');

        Helper::addRoute("mirages-api", "/mirages-api/[action]/[pathInfo]", "Mirages_Action", "dispatch");

        Typecho_Plugin::factory('admin/write-post.php')->bottom = array('Mirages_Plugin', 'writeBottom');
        Typecho_Plugin::factory('admin/write-page.php')->bottom = array('Mirages_Plugin', 'writeBottom');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        Helper::removeRoute('mirages-api');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        
        

        if (isset($_GET['action']) && $_GET['action'] == 'loadAllImageInfo') {
            self::loadAllImageInfo();
        }

        if (isset($_GET['action']) && $_GET['action'] == 'cleanAllImageInfo') {
            self::cleanAllImageInfo();
        }

        if (isset($_GET['action']) && $_GET['action'] == 'backupConfig') {
            self::backupMiragesConfig();
        }
        if (isset($_GET['action']) && $_GET['action'] == 'restoreConfig') {
            self::restoreMiragesConfig();
        }

        if (isset($_GET['action']) && $_GET['action'] == 'requestUpdate') {
            $message = Mirages_VersionManager::requestUpdate();
            if ($message['status'] == 'success' || (array_key_exists('hideLog', $message) && $message['hideLog'] > 0)) {
                Typecho_Widget::widget('Widget_Notice')->set(_t($message['message']), $message['status']);
                Typecho_Response::getInstance()->goBack();
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'envCheck') {
            Mirages_EnvCheck::checkEnv();
        }

        if (isset($_GET['action']) && $_GET['action'] == 'deleteOldTheme') {
            $message = self::deleteOldTheme();
            if (!empty($message)) {
                Typecho_Widget::widget('Widget_Notice')->set(_t($message['message']), $message['status']);
                Typecho_Response::getInstance()->goBack();
            }
            return;
        }

        if (self::$themeVersion < 0) {
            self::loadThemeVersion();
        }

        echo <<<JAVASCRIPT
<script type="text/javascript">
function btnClick(element) {
    return confirm("确定要" + element.innerText + "吗？")
}
</script>
JAVASCRIPT;


        $needShowRestartBanner = self::checkIfNeedRestartPlugin();
        if ($needShowRestartBanner) {
            echo <<<HTML
            <style>
            .notice-block {
                padding: 1rem;
                border-radius: 1rem;
                border: 2px solid #FF4642;
                text-align: center;
                background-color: rgba(255,70,66,0.07);
            }
            .notice-block p.title {
                font-size: 1rem;
                font-weight: 500;
                color: #FF4642;
                margin-bottom: .5rem;
            }
            .notice-block p.content {
                margin-top: 0;
            }
            </style>
            <div class="notice-block">
                <p class="title">插件需要禁用后重启以使部分功能生效</p>
                <p class="content">禁用插件后会导致插件设置丢失！建议使用插件的配置备份功能备份配置后再禁用重启。</p>
            </div>
HTML;

        }


        $form->addInput(new Title_Plugin('speedTitle', NULL, NULL, _t('速度优化'), NULL));
        $customQiniuHosts = new Typecho_Widget_Helper_Form_Element_Hidden('customQiniuHosts', NULL, NULL, _t('自定义七牛域名'), _t('配置您在七牛融合 CDN 中配置的加速域名，如果使用的是七牛的默认域名，择不需要写。<br>每行一个，只要写域名即可，不需要写http/https.<br>例如：cdn.example.com'));
        $form->addInput($customQiniuHosts);
        $customUPYunHosts = new Typecho_Widget_Helper_Form_Element_Hidden('customUPYunHosts', NULL, NULL, _t('自定义又拍云存储域名'), _t('配置您在又拍云存储中定义的自定义域名，如果使用的是又拍云提供的默认域名，择不需要写。<br>每行一个，只要写域名即可，不需要写http/https.<br>例如：cdn.example.com'));
        $form->addInput($customUPYunHosts);
        $customCDNHosts = new Typecho_Widget_Helper_Form_Element_Textarea('customCDNHosts', NULL, NULL, _t('自定义 CDN 域名'), _t('配置您的自定义域名使用的云存储服务类型，云存储的默认域名不需要填写。<br>每行配置一个域名，自定义域名只要写域名即可，不需要写http/https.<br>配置方式为: <code>自定义域名 : CDN类型</code><br>例如：cdn.example.com: QINIU<br>目前可以配置的CDN类型有：<br>七牛云存储「QINIU」<br> 又拍云存储「UPYUN」<br>阿里云 OSS「ALIOSS」<br>腾讯云存储-<a href="https://cloud.tencent.com/product/ci" target="_blank">数据万象</a>「QCLOUD」'));
        $form->addInput($customCDNHosts);
        $upYunSplitTag = new Typecho_Widget_Helper_Form_Element_Select('upYunSplitTag', array('!'=>_t('!'), '-'=>_t('-'), '_'=>_t('_')), '!', _t('又拍云间隔标识符'), _t('用于分隔图片 URL 和处理信息，可登录又拍云<a href="https://console.upyun.com/services/" target="_blank">控制台</a>，在 「服务」 > 「功能配置」 > 「云处理」 中设置。<br>又拍云的默认间隔标识符为「!」'));
        $form->addInput($upYunSplitTag);


        $form->addInput(new Title_Plugin('biaoqingTitle', NULL, NULL, _t('表情解析'), NULL));
        $biaoqingRootPath = new Typecho_Widget_Helper_Form_Element_Textarea('biaoqingRootPath', NULL, NULL, _t('自定义表情根目录'), _t('<span style="color: #F55852">其实完全可以不用配置</span><br>配置自定义的表情根目录，如果定义了就会使用自定义的路径加载表情，否则会使用<span style="color: #F55852">插件中自带的表情哦</span><br>每行配置一个表情路径，格式为：表情名称 (加英文冒号:) 自定义路径<br>示例：paopao:https://github.com/path/to/paopao/<br>目前可以配置的表情有：「paopao」、「aru」'));
        $form->addInput($biaoqingRootPath);

        $backupTime = self::loadConfigLastBackupTime();

        $form->addInput(new Title_Plugin('configBackup', NULL, NULL, _t('主题及插件配置备份'), NULL));
        $backupBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $backupBtn->value(_t('备份主题及插件配置'));
        $backupBtn->description(_t("<span style=\"font-weight: bold;color: #F55852\">备份前请先点击页面下方的「保存设置」保存插件配置。</span><br>备份主题及插件的配置信息至数据库。备份仅会保留一份，之前的备份将会被新的备份覆盖。<br>${backupTime}"));
        $backupBtn->input->setAttribute('class','btn btn-s primary btn-operate');
        $backupBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $backupBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=backupConfig',Helper::options()->adminUrl));
        $form->addItem($backupBtn);

        $restoreBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $restoreBtn->value(_t('恢复主题及插件配置'));
        $restoreBtn->description(_t("<span style=\"font-weight: bold;color: #F55852\">恢复备份会覆盖掉现在的主题及插件的设置，且不可回滚，请谨慎操作。</span><br>将前面备份的主题及插件配置恢复"));
        $restoreBtn->input->setAttribute('class','btn btn-s btn-warn btn-operate');
        $restoreBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $restoreBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=restoreConfig',Helper::options()->adminUrl));
        $form->addItem($restoreBtn);

        $form->addInput(new Title_Plugin('btnTitle', NULL, NULL, _t('操作'), NULL));

        $queryBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $queryBtn->value(_t('为所有文章获取图片基础信息，请谨慎操作。'));
        $queryBtn->description(_t('<span style="font-weight: bold;color: #F55852">建议在执行此操作前备份数据库。</span><br>要实现图片加载动画功能，需要在图片链接中保存图片基础信息。<br>你可以点击此按钮转换所有的文章。<br>除此之外，你在每次保存文章的时候插件同样会自动获取并保存这些信息。<br>本操作一次最多转换 5 篇文章。'));
        $queryBtn->input->setAttribute('class','btn btn-s btn-warn btn-operate');
        $queryBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $queryBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=loadAllImageInfo',Helper::options()->adminUrl));
        $form->addItem($queryBtn);

        $cleanBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $cleanBtn->value(_t('<span style="font-weight: 700;margin-right: 0;">清理</span>所有文章获取到的图片基础信息，请谨慎操作。'));
        $cleanBtn->description(_t('<span style="font-weight: bold;color: #F55852">建议在执行此操作前备份数据库。</span><br>清理前面的操作写入的图片基础信息，建议仅在切换至其他主题时清理，当然，不清理也不会有什么影响。<br>所以这是一个适用于强迫症的操作<br>'));
        $cleanBtn->input->setAttribute('class','btn btn-s btn-warn btn-operate');
        $cleanBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $cleanBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=cleanAllImageInfo',Helper::options()->adminUrl));
        $form->addItem($cleanBtn);

        $envCheckBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $envCheckBtn->value(_t('主机环境检测'));
        $envCheckBtn->description(_t('检查主题及插件依赖的主机环境是否正常'));
        $envCheckBtn->input->setAttribute('class','btn btn-s primary btn-operate');
        $envCheckBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $envCheckBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=envCheck',Helper::options()->adminUrl));
        $form->addItem($envCheckBtn);

        $form->addInput(new Title_Plugin('updateTitle', NULL, NULL, _t('在线更新'), _t('并不会在你不知情的情况下进行更新主题，只有在你点击更新操作的时候才会执行更新。<br>你可以在<a href="./options-theme.php" target="_blank">主题外观设置</a>页面查看主题的最新版本及更新信息。')));
        $acceptsDev = new Typecho_Widget_Helper_Form_Element_Radio('acceptDev', array('0'=>_t('是'), '1'=>_t('<span style="color: #F55852; font-weight: 700;">否，我愿意更新到开发版</span>')), '0', _t('仅接受正式版更新'),_t('选择是否接收开发版更新，<strong style="color: #F55852">该选项在保存后才会生效。</strong>'));
        $form->addInput($acceptsDev);

        $updateBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $updateBtn->value(_t('更新主题和插件至最新版本'));
        $updateBtn->description(_t('更新操作可能会耗时1分钟甚至更长时间，这取决于你主机的网络速度和其他硬件条件。<br>在这期间，请不要进行任何操作，否则，可能会导致主题更新失败。<br><strong style="color: #F55852">主题升级后，新版主题默认继承旧版主题的外观设置，如果启用旧版主题，所有主题的外观设置都将丢失！<br>所以，升级主题的过程中(包含升级前和升级后), 请不要在外观设置中启用/切换任何主题，否则当前使用的主题设置将会全部丢失！</strong>'));
        $updateBtn->input->setAttribute('class','btn btn-s btn-warn btn-operate');
        $updateBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $updateBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=requestUpdate',Helper::options()->adminUrl));
        $form->addItem($updateBtn);

        $deleteBtn = new Typecho_Widget_Helper_Form_Element_Submit();
        $deleteBtn->value(_t('删除旧版主题'));
        $deleteBtn->description(_t('在线更新完成后，暂时不会删除旧版本使用的主题。原因是在线更新的新版主题将不再包含你对主题所做的所有增改（除了主题目录下的 /usr 文件夹下的内容）。<br>这样会在主题列表中存在两个 Mirages 主题，你可以在备份后或确认不需要以后，点击这个按钮删除旧版主题。'));
        $deleteBtn->input->setAttribute('class','btn btn-s btn-warn btn-operate');
        $deleteBtn->input->setAttribute('onclick','javascript:return btnClick(this)');
        $deleteBtn->input->setAttribute('formaction',Typecho_Common::url('/options-plugin.php?config=Mirages&action=deleteOldTheme',Helper::options()->adminUrl));
        $form->addItem($deleteBtn);

        $form->addInput(new Title_Plugin('placeholderTitle', NULL, NULL, '', NULL));
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    private static function checkIfNeedRestartPlugin() {
        $router = Typecho_Router::get('mirages-api');
        if (empty($router)) {
            return true;
        }

        $plugins = Typecho_Plugin::export();
        $handles = @$plugins["activated"]['Mirages']['handles'];
        if (!empty($handles)) {
            if (!array_key_exists('admin/write-post.php:bottom', $handles)) {
                return true;
            }
            if (!array_key_exists('admin/write-page.php:bottom', $handles)) {
                return true;
            }
        }

        return false;
    }
    //endregion

    //region 插件操作响应方法
    private static function loadAllImageInfo() {
        self::loadPluginOptions();

        if (self::$themeVersion < 0) {
            self::loadThemeVersion();
        }

        $db = Typecho_Db::get();

        $count = 0;
        $currentCid = -1;
        $lastTitle = "";

        while (true) {
            $contents = $db->fetchAll($db->select('*')
                ->from('table.contents')
                ->where('cid > ?', $currentCid)
                ->order('cid', Typecho_Db::SORT_ASC)
                ->limit(10));

            if (empty($contents)) {
                break;
            }
            foreach ($contents as $content) {
                self::$lastPostModified = false;
                $text = $content['text'];
                $lastTitle = $content['title'];
                if (empty($text)) {
                    continue;
                }
                $cid = $content['cid'];

                if (!is_numeric($cid)) {
                    continue;
                }
                $currentCid = $cid;
                self::$cleanMode = false;
                self::$cleanConfirmMode = false;
                if (Mirages_Utils::startsWith($text, '<!--markdown-->')) {
                    $parsedContent = self::handleMarkdown($text, array("Mirages_Plugin", '_doParseContentBeforePublish'));
                } else {
                    $parsedContent = self::handleHtml('<!--html-->'.$text, array("Mirages_Plugin", "_doParseContentBeforePublish"));
                }

                if (self::validateContentAfterParsed($text, $parsedContent)) {
                    if (Mirages_Utils::startsWith($parsedContent, '<!--html-->')) {
                        $parsedContent = substr($parsedContent, 11);
                    }
                } else {
                    $message = "文章《{$lastTitle}》处理失败";
                    Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'error');
                    Typecho_Response::getInstance()->goBack();
                    return;
                }

                if ($parsedContent === $content['text']) {
                    continue;
                }

                if (!self::$lastPostModified) {
                    continue;
                }

                $db->query($db->update('table.contents')
                    ->rows(array(
                        'text' => $parsedContent,
                    ))
                    ->where('cid = ?', $content['cid']));
                $count++;
                if ($count >= 5) {
                    break;
                }
            }
            if ($count >= 5) {
                break;
            }
        }

        if ($count == 0) {
            $message = "操作成功！文章已全部处理完毕！";
        } else {
            $message = "操作成功！本次处理了 {$count} 篇文章。最后一篇文章为《{$lastTitle}》";
        }

        Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'success');
        Typecho_Response::getInstance()->goBack();
    }

    private static function cleanAllImageInfo() {
        self::loadPluginOptions();

        $db = Typecho_Db::get();

        $count = 0;
        $currentCid = -1;
        $lastTitle = "";

        while (true) {
            $contents = $db->fetchAll($db->select('*')
                ->from('table.contents')
                ->where('cid > ?', $currentCid)
                ->order('cid', Typecho_Db::SORT_ASC)
                ->limit(10));

            if (empty($contents)) {
                break;
            }
            foreach ($contents as $content) {
                self::$lastPostModified = false;
                $text = $content['text'];
                $lastTitle = $content['title'];
                if (empty($text)) {
                    continue;
                }
                $cid = $content['cid'];

                if (!is_numeric($cid)) {
                    continue;
                }
                $currentCid = $cid;
                self::$cleanMode = true;
                self::$cleanConfirmMode = true;
                if (Mirages_Utils::startsWith($text, '<!--markdown-->')) {
                    $parsedContent = self::handleMarkdown($text, array("Mirages_Plugin", '_doParseContentBeforePublish'));
                } else {
                    $parsedContent = self::handleHtml('<!--html-->'.$text, array("Mirages_Plugin", "_doParseContentBeforePublish"));
                }

                if (self::validateContentAfterParsed($text, $parsedContent)) {
                    if (Mirages_Utils::startsWith($parsedContent, '<!--html-->')) {
                        $parsedContent = substr($parsedContent, 11);
                    }
                } else {
                    $message = "文章《{$lastTitle}》处理失败";
                    Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'error');
                    Typecho_Response::getInstance()->goBack();
                    return;
                }

                if ($parsedContent === $content['text']) {
                    continue;
                }

                if (!self::$lastPostModified) {
                    continue;
                }

                $db->query($db->update('table.contents')
                    ->rows(array(
                        'text' => $parsedContent,
                    ))
                    ->where('cid = ?', $content['cid']));
                $count++;
            }
        }

        if ($count == 0) {
            $message = "操作成功！文章已全部清理完毕！";
        } else {
            $message = "操作成功！文章已全部清理完毕！本次清理了 {$count} 篇文章。最后一篇文章为《{$lastTitle}》";
        }

        Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'success');
        Typecho_Response::getInstance()->goBack();
    }


    private static function loadConfigLastBackupTime() {
        $db = Typecho_Db::get();
        $widget = Typecho_Widget::widget('Widget_Abstract_Options');

        // 备份插件配置
        $contents = $db->fetchRow($widget->select()->where('name = ?', 'miragesConfigBackup:time')->where('user = 0'));
        $color = "#2299dd";
        $backupTime = _t("从未备份");
        $hasBackupTime = false;
        if (!empty($contents) && intval($contents['value']) > 0) {
            $date = new Typecho_Date(intval($contents['value']));
            $color = "#0c6";
            $backupTime = $date->format('Y-m-d H:i:s');
            $hasBackupTime = true;
        }

        if ($hasBackupTime) {
            // 校验插件及主题配置备份数据
            $pluginBackup = $db->fetchRow($widget->select()->where('name = ?', 'miragesConfigBackup:plugin')->where('user = 0'));
            if (empty($pluginBackup) || empty($pluginBackup['value'])) {
                $color = "#F55852";
                $backupTime = _t("上次备份不完整，请重新执行备份！");
            }
            $themeBackup = $db->fetchRow($widget->select()->where('name = ?', 'miragesConfigBackup:theme')->where('user = 0'));
            if (empty($themeBackup) || empty($themeBackup['value'])) {
                $color = "#F55852";
                $backupTime = _t("上次备份不完整，请重新执行备份！");
            }
        }

        return "<span style=\"font-weight: bold;color: ${color}\">" . _t("上次备份时间") . ": {$backupTime}</span>";
    }

    private static function backupMiragesConfig() {
        $db = Typecho_Db::get();
        $widget = Typecho_Widget::widget('Widget_Abstract_Options');

        // 备份插件配置
        $currentPluginConfigRow = $db->fetchRow($widget->select()->where('name = ?', 'plugin:Mirages')->where('user = 0'));

        if (!empty($currentPluginConfigRow)) {
            $pluginBackupExist = $widget->size($db->sql()->where('name = ?', 'miragesConfigBackup:plugin')->where('user = 0')) > 0;
            if ($pluginBackupExist) {
                $backup = array(
                    'value' => $currentPluginConfigRow['value']
                );
                $widget->update($backup, $db->sql()->where('name = ?', 'miragesConfigBackup:plugin')->where('user = 0'));
            } else {
                $backup = array(
                    'name' => 'miragesConfigBackup:plugin',
                    'user' => 0,
                    'value' => $currentPluginConfigRow['value']
                );
                $widget->insert($backup);
            }
        }

        // 备份主题配置
        $currentThemeConfigRow = $db->fetchRow($widget->select()->where('name = ?', 'theme:Mirages')->where('user = 0'));

        if (!empty($currentThemeConfigRow)) {
            $themeBackupExist = $widget->size($db->sql()->where('name = ?', 'miragesConfigBackup:theme')->where('user = 0')) > 0;
            if ($themeBackupExist) {
                $backup = array(
                    'value' => $currentThemeConfigRow['value']
                );
                $widget->update($backup, $db->sql()->where('name = ?', 'miragesConfigBackup:theme')->where('user = 0'));
            } else {
                $backup = array(
                    'name' => 'miragesConfigBackup:theme',
                    'user' => 0,
                    'value' => $currentThemeConfigRow['value']
                );
                $widget->insert($backup);
            }
        }

        // 添加备份时间
        $backupTimeExist = $widget->size($db->sql()->where('name = ?', 'miragesConfigBackup:time')->where('user = 0')) > 0;
        $currentTime = time();
        if ($backupTimeExist) {
            $backup = array(
                'value' => $currentTime
            );
            $widget->update($backup, $db->sql()->where('name = ?', 'miragesConfigBackup:time')->where('user = 0'));
        } else {
            $backup = array(
                'name' => 'miragesConfigBackup:time',
                'user' => 0,
                'value' => $currentTime
            );
            $widget->insert($backup);
        }

        $message = "主题及插件配置备份成功！";
        Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'success');
        Typecho_Response::getInstance()->goBack();
    }

    private static function restoreMiragesConfig() {
        $db = Typecho_Db::get();
        $widget = Typecho_Widget::widget('Widget_Abstract_Options');

        // 获取插件及主题配置备份数据
        $pluginBackup = $db->fetchRow($widget->select()->where('name = ?', 'miragesConfigBackup:plugin')->where('user = 0'));
        if (empty($pluginBackup) || empty($pluginBackup['value'])) {
            $message = "备份恢复终止：未找到 Mirages 插件配置备份数据！";
            Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'error');
            Typecho_Response::getInstance()->goBack();
            return;
        }
        $themeBackup = $db->fetchRow($widget->select()->where('name = ?', 'miragesConfigBackup:theme')->where('user = 0'));
        if (empty($themeBackup) || empty($themeBackup['value'])) {
            $message = "备份恢复终止：未找到 Mirages 主题配置备份数据！";
            Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'error');
            Typecho_Response::getInstance()->goBack();
            return;
        }

        // 恢复插件配置
        $pluginConfigRowExist = $widget->size($db->sql()->where('name = ?', 'plugin:Mirages')->where('user = 0')) > 0;
        if ($pluginConfigRowExist) {
            $backup = array(
                'value' => $pluginBackup['value']
            );
            $widget->update($backup, $db->sql()->where('name = ?', 'plugin:Mirages')->where('user = 0'));
        } else {
            $backup = array(
                'name' => 'plugin:Mirages',
                'user' => 0,
                'value' => $pluginBackup['value']
            );
            $widget->insert($backup);
        }

        // 恢复主题配置
        $themeConfigRowExist = $widget->size($db->sql()->where('name = ?', 'theme:Mirages')->where('user = 0')) > 0;
        if ($themeConfigRowExist) {
            $backup = array(
                'value' => $themeBackup['value']
            );
            $widget->update($backup, $db->sql()->where('name = ?', 'theme:Mirages')->where('user = 0'));
        } else {
            $backup = array(
                'name' => 'theme:Mirages',
                'user' => 0,
                'value' => $themeBackup['value']
            );
            $widget->insert($backup);
        }

        $message = "主题及插件配置恢复成功！";
        Typecho_Widget::widget('Widget_Notice')->set(_t($message), 'success');
        Typecho_Response::getInstance()->goBack();
    }

    private static function deleteOldTheme() {
        self::loadPluginOptions();

        $themeOld = Mirages_Utils::themesDir() . "Mirages-Old";

        if (file_exists($themeOld)) {
            $success = Mirages_Utils::deleteDirectory($themeOld);
            if ($success) {
                $message = '删除成功';
            } else {
                $message = '删除失败，请尝试手动删除';
            }
        } else {
            $success = false;
            $message = '删除失败: ' . $themeOld . ' 目录不存在';
        }
        return array(
            'status' => $success ? 'success' : 'error',
            'message' => $message,
        );
    }

    //endregion

    //region 插件注册 invoke 方法
    public static function postWrite($content, Widget_Abstract_Contents $widget) {
        $content = self::beforePublish($content, $widget);
        $content = self::invokePostWritePlugin($content, $widget);
        return $content;
    }

    public static function pageWrite($content, Widget_Abstract_Contents $widget) {
        $content = self::beforePublish($content, $widget);
        $content = self::invokePageWritePlugin($content, $widget);
        return $content;
    }

    public static function content($content, Widget_Abstract_Contents $widget) {
        $content = self::parseContent($content, $widget);
        $content = self::invokeContentPlugin($content, $widget);
        return $content;
    }
    public static function excerpt($content, Widget_Abstract_Contents $widget) {
        $content = self::parseContent($content, $widget);
        $content = self::invokeExcerptPlugin($content, $widget);
        return $content;
    }

    public static function contentEx($content, Widget_Abstract_Contents $widget) {
        $content = self::beforeShown($content, $widget);
        $content = self::invokeContentExPlugin($content, $widget);
        return $content;
    }
    public static function excerptEx($content, Widget_Abstract_Contents $widget) {
        if (!(method_exists($widget, "is") && $widget->is('single'))) {
            $content = self::beforeShown($content, $widget);
        }
        $content = self::invokeExcerptExPlugin($content, $widget);
        return $content;
    }
    public static function writeBottom() {
        self::loadPluginOptions();
        $version = self::$devMode ? time() : self::VERSION_TAG;
        echo "<script type=\"text/javascript\" src=\"" . self::$pluginBaseUrl . "js/dashboard.write.min.js?v=" . $version . "\"></script>";
    }
    //endregion

    //region 插件逻辑处理入口方法
    /**
     * 插件方法
     * @param $content
     * @param Widget_Abstract_Contents $widget
     * @return array
     */
    private static function beforePublish($content, Widget_Abstract_Contents $widget) {
        self::loadPluginOptions();

        self::$currentWidget = &$widget;

        if (self::lazyloadEnabled()) {
            $_contentText = $content['text'];
            self::$cleanMode = false;
            self::$cleanConfirmMode = false;
            if (Mirages_Utils::startsWith($_contentText, '<!--markdown-->')) {
                $parsedContent = self::handleMarkdown($_contentText, array("Mirages_Plugin", '_doParseContentBeforePublish'));
            } else {
                $parsedContent = self::handleHtml('<!--html-->'.$_contentText, array("Mirages_Plugin", "_doParseContentBeforePublish"));
            }
            if (self::validateContentAfterParsed($_contentText, $parsedContent)) {
                if (Mirages_Utils::startsWith($parsedContent, '<!--html-->')) {
                    $parsedContent = substr($parsedContent, 11);
                }
            } else {
                // rollback
                $parsedContent = $_contentText;
            }
            $content['text'] = $parsedContent;
        }

        self::$currentWidget = null;
        return $content;
    }

    private static function parseContent($text, Widget_Abstract_Contents $widget) {
        self::loadPluginOptions();

        self::$currentWidget = &$widget;

        if ($widget->isMarkdown) {
            $text = self::handleMarkdownSyntaxCompatibility($text);
            $content = $widget->markdown($text);
        } else {
            $content = $widget->autoP($text);
        }

        self::$currentWidget = null;

        return $content;
    }

    private static function beforeShown($content, Widget_Abstract_Contents $widget) {
        self::loadPluginOptions();

        self::$currentWidget = &$widget;

        $content = self::handleHtml($content, array("Mirages_Plugin", '_doParseContentBeforeShow'), array("Mirages_Plugin", "_doEscapeCodeBeforeShow"));

        $content = self::renderForAllHtml($content);

        $content = self::unEscapeMark($content);

        self::$currentWidget = null;

        return $content;
    }
    //endregion

    //region 插件逻辑处理实际执行回调方法

    private static function validateContentAfterParsed($original, $parsed) {
        if (Mirages_Utils::startsWith($original, '<!--markdown-->')) {
            if (!Mirages_Utils::startsWith($parsed, '<!--markdown-->')) {
                return false;
            }
        } else {
            if (!Mirages_Utils::startsWith($parsed, '<!--html-->')) {
                return false;
            }
        }
        return true;
    }

    private static function _doParseContentBeforePublish($content) {
        $_content = $content;
        try {
            // 处理 ![alt](image_url)
            $content = preg_replace_callback('/!\[([^\]]*?)\]\(([^\)]+?)\)/sm', array('Mirages_Plugin', '_doHandleImageWithAltBeforePublish'), $content);

            // 处理 [index]: image_url
            $content = preg_replace_callback('/\[([\d\s]+)\]\s*:\s*(((https:|http:|ftp:|rtsp:|mms:){0,1}\/\/)[^\s]+)/sm', array('Mirages_Plugin', '_doHandleImageWithIndexBeforePublish'), $content);

            // 处理 Image 标签
            $content = preg_replace_callback('/\<img\s*([^\>]+?)\s*\/{0,1}\>/sm', array('Mirages_Plugin', '_doHandleImageTagBeforePublish'), $content);

            $error = error_get_last();

            if (!empty($error) && array_key_exists('message', $error) && Mirages_Utils::startsWith($error['message'], 'preg_replace_callback')) {
                var_dump($error);
                return $_content;
            }
        } catch (Exception $e) {
            return $_content;
        }

        return $content;
    }

    private static function _doHandleImageWithAltBeforePublish($matches) {
        $title = $matches[1];
        $url = $matches[2];

        $url = self::addMiragesWidthAndSize($url);

        return "![{$title}]({$url})";
    }

    private static function _doHandleImageWithIndexBeforePublish($matches){
        $index = $matches[1];
        $url = $matches[2];

        $url = self::addMiragesWidthAndSize($url);

        return "[{$index}]: {$url}";
    }

    private static function _doHandleImageTagBeforePublish($matches) {
        $attrs = $matches[1];

        $attrs = Mirages_Utils::parseHTMLTagAttribute($attrs);
        if (!empty($attrs['src'])) {

            $url = Mirages_Utils::trimAttributeValue($attrs['src']);
            $url = self::addMiragesWidthAndSize($url);

            $attrs['src'] = $url;
        }

        $attrs = Mirages_Utils::buildHTMLTagAttribute($attrs);

        return "<img{$attrs}>";
    }

    private static function _doParseContentBeforeShow($content) {
        // 处理 Image 标签
        $content = preg_replace_callback('/\<img\s*([^\>]+?)\s*\/{0,1}\>/sm', array('Mirages_Plugin', '_doHandleImageTagBeforeShow'), $content);

        $content = self::doParseBiaoqing($content);

        if ((!empty(self::$themeOptions->markdownExtend) && in_array('enablePhonetic', self::$themeOptions->markdownExtend))) {
            $content = self::_renderPhonetic($content);
        }
        if (version_compare(self::$typechoDateVersion, '17.01.01', '<')) {
            if ((!empty(self::$themeOptions->markdownExtend) && in_array('enableDeleteLine', self::$themeOptions->markdownExtend))) {
                $content = self::_renderDeleteTag($content);
            }
        }
        if ((!empty(self::$themeOptions->markdownExtend) && in_array('enableCheckbox', self::$themeOptions->markdownExtend))) {
            $content = self::_renderCheckbox($content);
        }

        $content = self::_escapeCharacter($content);
        $content = self::_renderCards($content);

        return $content;
    }

    private static function _doEscapeCodeBeforeShow($htmlCode) {
        if ((!empty(self::$themeOptions->markdownExtend) && in_array('enableHighlightText', self::$themeOptions->markdownExtend))) {
            $htmlCode = self::_escapeCodeForHighlight($htmlCode);
        }
        $htmlCode = self::_escapeShortcodeOpenMarkForCode($htmlCode);
        return $htmlCode;
    }

    private static function _doHandleImageTagBeforeShow($matches) {
        $attrs = $matches[1];

        $attrs = Mirages_Utils::parseHTMLTagAttribute($attrs);
        if (array_key_exists('src', $attrs) && !Mirages_Utils::emptyAttribute($attrs['src'])) {
            $url = Mirages_Utils::trimAttributeValue($attrs['src']);
        } elseif (array_key_exists('data-src', $attrs) && !Mirages_Utils::emptyAttribute($attrs['data-src'])) {
            $url = Mirages_Utils::trimAttributeValue($attrs['data-src']);
        }
        if (!empty($url)) {
            $originUrl = $url;
            $url = self::_replaceIMGWithCDNDomain($url);
            $part = parse_url($url);
            $lazyLoaded = false;
            if (is_array($part) && array_key_exists('fragment', $part) && !empty($part['fragment'])) {
                $fragment = $part['fragment'];
                $fragment = str_replace("&amp;", "&", $fragment);
                $fragment = str_replace("#", "&", $fragment);
                $fragment = Mirages_Utils::httpParseQuery($fragment);

                if (array_key_exists(self::KEY_CDN_TYPE, $fragment)) {
                    $cdnType = $fragment[self::KEY_CDN_TYPE];

                    // 本地图像被替换为了 CDN Domain，以 CDN Domain 的形式加载
                    if ($cdnType == self::CDN_TYPE_LOCAL && $originUrl != $url) {
                        $cdnType = self::getCdnType($part);
                        $fragment[self::KEY_CDN_TYPE] = $cdnType;
                    }
                } else {
                    $cdnType = self::getCdnType($part);
                }

                if (self::lazyloadEnabled()
                    && in_array($cdnType, self::$supportedCDNType)
                    && array_key_exists(self::KEY_WIDTH, $fragment)
                    && array_key_exists(self::KEY_HEIGHT, $fragment)
                ) {
                    $width = intval($fragment[self::KEY_WIDTH]);
                    $height = intval($fragment[self::KEY_HEIGHT]);
                    if ($width > 0 && $height > 0) {

                        $fragmentAttrs = array();

                        foreach ($fragment as $key => $value) {
                            if (!empty($key) && !empty($value)) {
                                $fragmentAttrs['data-'.$key] = $value;
                            }
                        }
                        $fragmentAttrs['style'] = "width: {$width}px;";

                        unset($part['fragment']);
                        $url = Mirages_Utils::httpBuildUrl($part);

                        $attrs['data-src'] = $url;

                        $attrs['class'] = "img-small";
                        $attrs['no-zoom'] = "true";
                        $attrs['src'] = self::getThumbnailByCdnType($url, $cdnType);

                        $paddingBottom = round($height * 100.0 / $width, 2) . '%';

                        if (self::themeAdvancedSettingIsTrue('enableImageShadow')) {
                            $fragmentAttrs['data-shadow'] = "true";
                        }

                        if (array_key_exists('data-noshadow', $fragmentAttrs)) {
                            unset($fragmentAttrs['data-shadow']);
                            unset($fragmentAttrs['data-noshadow']);
                        }

                        $attrs = Mirages_Utils::buildHTMLTagAttribute($attrs);
                        $fragmentAttrs = Mirages_Utils::buildHTMLTagAttribute($fragmentAttrs);

                        $replace = <<<EOF
    <section class="lazy-load" {$fragmentAttrs}>
        <div class="placeholder" style="padding-bottom: {$paddingBottom};"></div>
        <div class="progressiveMedia">
            <img{$attrs} onload="javascript:this.classList.add('loaded');">
        </div>
    </section>
EOF;
                        $lazyLoaded = true;
                    }
                } else {
                    // 不支持图像加载动画
                    unset($fragment[self::KEY_WIDTH]);
                    unset($fragment[self::KEY_HEIGHT]);
                    unset($fragment[self::KEY_CDN_TYPE]);

                    foreach ($fragment as $key => $value) {
                        if (!empty($key) && !empty($value)) {
                            $attrs['data-'.$key] = $value;
                        }
                    }

                    if (self::themeAdvancedSettingIsTrue('enableImageShadow')) {
                        $attrs['data-shadow'] = "true";
                    }

                    if (array_key_exists('data-noshadow', $attrs)) {
                        unset($attrs['data-shadow']);
                        unset($attrs['data-noshadow']);
                    }
                }
            }

            if(!$lazyLoaded && (!empty(self::$themeOptions->qiniuOptions) && in_array('qiniuOptimize', self::$themeOptions->qiniuOptions))) {
                unset($part['fragment']);
                unset($part['query']);
                $url = Mirages_Utils::httpBuildUrl($part);
                $attrs['data-'.self::KEY_CDN_TYPE] = self::getCdnType($part);
                $attrs['data-src'] = $url;
                unset($attrs['src']);
            }

        }

        if (empty($replace)) {
            $attrs = Mirages_Utils::buildHTMLTagAttribute($attrs);
            $replace = "<img{$attrs}>";
        }

        return $replace;

    }


    /**
     * 增强 Markdown 语法兼容性
     * 为了提升后续导出时 Markdown 语法与其他应用的兼容性，这里仅在解析 Markdown 前做处理，
     * 不会更改数据库中保存的原文。
     *
     * 包含：
     *      代码块解析：highlight.js 中代码块定义语言时不能包含特殊字符
     *      MathJax 字符转义的问题。
     * @param $markdown
     * @return mixed
     */
    private static function handleMarkdownSyntaxCompatibility($markdown) {
        $markdown = str_replace("```objective-c", "```objectivec", $markdown);
        $markdown = str_replace("```c++", "```cpp", $markdown);
        $markdown = str_replace("```c#", "```csharp", $markdown);
        $markdown = str_replace("```f#", "```fsharp", $markdown);
        $markdown = str_replace("```F#", "```fsharp", $markdown);

//        $markdown = self::escapeTexBlock($markdown);
        $markdown = self::escapeAllMarkdown($markdown);

        return $markdown;
    }

    private static function renderForAllHtml($content) {
        if ((!empty(self::$themeOptions->markdownExtend) && in_array('enableHighlightText', self::$themeOptions->markdownExtend))) {
            $content = self::_renderHighlight($content);
        }
        $content = self::parseShortcode($content);
        return $content;
    }

    private static function escapeAllMarkdown($markdown) {
        if ((!empty(self::$themeOptions->markdownExtend) && in_array('enableHighlightText', self::$themeOptions->markdownExtend))) {
            $markdown = self::_escapeMarkdownForHighlight($markdown);
        }
        return $markdown;
    }

    private static function unEscapeMark($content) {
        if ((!empty(self::$themeOptions->markdownExtend) && in_array('enableHighlightText', self::$themeOptions->markdownExtend))) {
            $content = self::_unEscapeForHighlight($content);
        }
//        $content = self::_doUnEscapeTexBlock($content);
        $content = self::_unEscapeShortcodeOpenMark($content);
        return $content;
    }

    private static function escapeTexBlock($markdown) {

        if (!(!empty(self::$themeOptions->texOptions) && in_array('showJax', self::$themeOptions->texOptions))) {
            return $markdown;
        }

        $markdown = preg_replace_callback('{
				(?:\n|\r|\A)
				(
					\s*\$\$
				)
				
				[ ]?(\w+)?(?:,[ ]?(\d+))?[ ]* (\n|\r)+  #Whitespace and newline following marker.
				
				# 3: Content
				(
					(?>
						(?!\1 [ ]* (\n|\r))	# Not a closing marker.
						.*(\n|\r)+
					)+
				)
				
				# Closing marker.
				\1 [ ]* (\n|\r)+
			}xm', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);

        $markdown = preg_replace_callback('{
				(?:\n|\r|\A)
				([ ]*)\\\\\[
				
				[ ]?(\w+)?(?:,[ ]?(\d+))?[ ]* (\n|\r)+  #Whitespace and newline following marker.
				
				# 3: Content
				(
					(?>
						(?!\1 [ ]* (\n|\r))	# Not a closing marker.
						.*(\n|\r)+
					)+
				)
				
				# Closing marker.
				\1\\\\\] [ ]* (\n|\r)+
			}xm', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);

        $markdown = preg_replace_callback('/\$\$[\S\s]+?\$\$/i', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);
        $markdown = preg_replace_callback('/\\\\\([\S\s]+?\\\\\)/i', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);
        $markdown = preg_replace_callback('/\\\\begin[\S\s]+?\\\\end\{.+\}/i', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);
        $markdown = preg_replace_callback('/\\\\begin[\S\s]+?\\\\end/i', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);
        $markdown = preg_replace_callback('/\\\\\[[\S\s]+?\\\\\]/i', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);

        if (!empty(self::$themeOptions->texOptions) && in_array('useDollarForInline', self::$themeOptions->texOptions)) {
            $markdown = preg_replace_callback('/\$[^\r\n\$]+?\$/i', array('Mirages_Plugin', '_escapeTexBlockCallback'), $markdown);
        }
        return $markdown;
    }

    private static function _escapeTexBlockCallback($matches) {
        $tex = $matches[0];
        $tex = self::_doEscapeTexBlock($tex);
        return $tex;
    }

    private static function _doEscapeTexBlock($tex) {
        $tex = str_replace(" ", "@@PH@SPACE@MIRAGES@@", $tex);
        $tex = str_replace("\\", "@@PH@BACK@SLASH@MIRAGES@@", $tex);
        $tex = str_replace("$", "@@PH@DOLLAR@MIRAGES@@", $tex);
        $tex = str_replace("_", "@@PH@UNDERLINE@MIRAGES@@", $tex);
        $tex = str_replace("[", "@@PH@LEFT@SQUARE@BRACKET@MIRAGES@@", $tex);
        $tex = str_replace("]", "@@PH@RIGHT@SQUARE@BRACKET@MIRAGES@@", $tex);
        return $tex;
    }

    private static function _doUnEscapeTexBlock($tex) {
        $tex = str_replace("@@PH@SPACE@MIRAGES@@", " ", $tex);
        $tex = str_replace("@@PH@BACK@SLASH@MIRAGES@@", "\\", $tex);
        $tex = str_replace("@@PH@DOLLAR@MIRAGES@@", "$", $tex);
        $tex = str_replace("@@PH@UNDERLINE@MIRAGES@@", "_", $tex);
        $tex = str_replace("@@PH@LEFT@SQUARE@BRACKET@MIRAGES@@", "[", $tex);
        $tex = str_replace("@@PH@RIGHT@SQUARE@BRACKET@MIRAGES@@", "]", $tex);
        return $tex;
    }

    private static function _renderPhonetic($content) {
        $content = preg_replace('/\{\{\s*([^\:]+?)\s*\:\s*([^}]+?)\s*\}\}/is',
            "<ruby>$1<rp> (</rp><rt>$2</rt><rp>) </rp></ruby>", $content);
        return $content;
    }

    private static function _renderDeleteTag($content) {
        $content = preg_replace('/\~\~([^\<\>]+?)\~\~/i', "<del>$1</del>", $content);
        return $content;
    }

    private static function _renderHighlight($content) {
        $content = preg_replace('/\=\=(((?!(\<\/{0,1}(pre|p|table|thead|tbody|tr|th|td|div|iframe|embed|canvas|audio|video|ol|ul|li|blockquote|hr)(\s.*?)*\>)|\r|\n).)*?)\=\=/i', "<span class=\"highlight-text\">$1</span>", $content);
        $content = str_replace('\=', '=', $content);
        return $content;
    }
    private static function _escapeCodeForHighlight($content) {
        $content = str_replace("=", "@@PH@EQUALMARK@MIRAGES@@", $content);
        return $content;
    }
    private static function _escapeMarkdownForHighlight($markdown) {
        $markdown = str_replace("\\\\=", "@@PH@BACK@SLASH2@EQUALMARK@MIRAGES@@", $markdown);
        $markdown = str_replace("\\=", "@@PH@BACK@SLASH@EQUALMARK@MIRAGES@@", $markdown);
        return $markdown;
    }
    private static function _unEscapeForHighlight($content) {
        $content = str_replace("@@PH@EQUALMARK@MIRAGES@@", "=", $content);
        $content = str_replace("@@PH@BACK@SLASH@EQUALMARK@MIRAGES@@", "=", $content);
        $content = str_replace("@@PH@BACK@SLASH2@EQUALMARK@MIRAGES@@", "\=", $content);
        return $content;
    }

    private static function _renderCheckbox($content) {
        return preg_replace_callback('/<li>\s*\[(\s|x)\]\s*([^<>]+)<\/li>/i', function ($match) {
            $checked = $match[1];
            $text = $match[2];
            $html = '<li class="task-list-item"><input type="checkbox"';
            if ($checked == 'x') {
                $html .= " checked";
            }
            $html .= ' onclick="return false;">';
            if (preg_match('/^[A-Za-z0-9`~\$%\^&\*\-=\+\\\|\/!;:,\.\?\'\"\(\)\[\]\{\}]{1}.*/i', $text)) {
                $html .= '<span class="display-none">&nbsp;</span>';
            }
            $html .= '<span class="task-list-item-text">';
            $html .= $text;
            $html .= '</span>';
            if (preg_match('/.*[A-Za-z0-9`~\$%\^&\*\-=\+\\\|\/!;:,\.\?\'\"\(\)\[\]\{\}]{1}$/i', $text)) {
                $html .= '<span class="display-none">&nbsp;</span>';
            }
            $html .= '</li>';
            return $html;
        }, $content);
    }

    private static function _escapeCharacter($content) {
        if (version_compare(self::$typechoDateVersion, '17.01.01', '<')) {
            $content = str_replace('\~', '~', $content);
        }
        $content = str_replace('\$', '<span>$</span>', $content);
        return $content;
    }

    private static function _replaceIMGWithCDNDomain($url) {
        if (self::$devMode || strlen(trim(self::$themeOptions->cdnDomain)) <= 0) {
            return $url;
        }
        return preg_replace('/^'.preg_quote(rtrim(self::$themeOptions->siteUrl, '/'), '/').'/', rtrim(self::$themeOptions->cdnDomain, '/'), $url, 1);
    }

    private static function _renderCards($content) {
        $currentGroupId = 0;
        $lastFindIndex = 0;
        $lastFindLength = 0;
        $linkGroup = array();
        $linkGroupStartIndex = array();
        $linkGroupEndIndex = array();
        $first = true;

        $totalCount = preg_match_all('/(<p>)*<a[^<>]+href=\"([^\"]+?)\"[^<>]*>([^<>]+?)<\/a>\s*\+\s*\(<a[^<>]+href=\"([^\"]+?)\"[^<>]*>([^<>]+?)<\/a>\)(<\/p>)*(<\s*br\s*\/\s*>)*(<\s*\/\s*br\s*>)*/ixs', $content, $matches);

        if ($totalCount <= 0) {
            $totalCount = preg_match_all('/(<p>)*<a[^<>]+href=\"([^\"]+?)\"[^<>]*>([^<>]+?)<\/a>\s*\+\s*\(<a[^<>]+href=\"([^\"]+?)\)\"[^<>]*>([^<>]+?)\)<\/a>(<\/p>)*(<\s*br\s*\/\s*>)*(<\s*\/\s*br\s*>)*/ixs', $content, $matches);
        }

        if ($totalCount <= 0) {
            $totalCount = preg_match_all('/(<p>)*<a[^<>]+href=\"([^\"]+?)\"[^<>]*>([^<>]+?)<\/a>\s*\+\s*\(([^<>]+?)\)(<\/p>)*(<\s*br\s*\/\s*>)*(<\s*\/\s*br\s*>)*/ixs', $content, $matches);
        }

        for ($i = 0; $i < $totalCount; $i++) {
            if ($first) {
                $first = false;
                $useNewGroup = true;
                $currentFindIndex = strpos($content, $matches[0][$i]);
                $currentFindLength = strlen($matches[0][$i]);
            } else {
                $lastEndIndex = $lastFindIndex + $lastFindLength;
                $currentFindIndex = strpos($content, $matches[0][$i], $lastEndIndex - 1);
                $currentFindLength = strlen($matches[0][$i]);
                if ($currentFindIndex - $lastEndIndex >= 0) {
                    $splitContent = substr($content, $lastEndIndex, $currentFindIndex - $lastEndIndex);
                    if (strlen($splitContent) > 0 && preg_match('/\w+/xs', $splitContent)) {
                        $trimSplitContent = preg_replace('/<\s*br\s*\/\s*>/ixs', '', $splitContent);
                        $trimSplitContent = preg_replace('/<\s*\/\s*br\s*>/ixs', '', $trimSplitContent);
                        $trimSplitContent = preg_replace('/<\s*br\s*>/ixs', '', $trimSplitContent);
                        if (strlen($trimSplitContent) > 0 && preg_match('/\w+/xs', $trimSplitContent)) {
                            $useNewGroup = true;
                        } else {
                            $useNewGroup = false;
                        }
                    } else {
                        $useNewGroup = false;
                    }
                } else {
                    $useNewGroup = false;
                }
            }

            if ($useNewGroup) {
                $currentGroupId ++;
            }
            if (!isset($linkGroup[$currentGroupId])) {
                $linkGroup[$currentGroupId] = array();
            }
            if ($useNewGroup) {
                $linkGroupStartIndex[$currentGroupId] = $currentFindIndex;
            }
            $linkGroupEndIndex[$currentGroupId] = $currentFindIndex + $currentFindLength;
            $match = array();
            $match[2] = $matches[2][$i];
            $match[3] = $matches[3][$i];
            $match[4] = $matches[4][$i];
            $linkGroup[$currentGroupId][] = $match;
            $lastFindIndex = $currentFindIndex;
            $lastFindLength = $currentFindLength;
        }

        $output = "";
        for ($i = 1; $i <= $currentGroupId; $i++) {
            $start = $linkGroupStartIndex[$i];

            if ($i > 1){
                $lastId = $i - 1;
                $lastEnd = $linkGroupEndIndex[$lastId];
                $output .= substr($content, $lastEnd, $start - $lastEnd);
            } else {
                $output .= substr($content, 0, $start);
            }
            $matches = $linkGroup[$i];
            $linkGroupHtml = "<div class=\"link-box\">\n";

            foreach ($matches as $match) {
                $linkGroupHtml .= <<<HTML
<a href="{$match[2]}" target="_blank" class="no-underline">
    <div class="thumb">
        <img width="200" height="200" src="{$match[4]}" alt="{$match[3]}">
    </div>
    <div class="content">
        <p class="title">
            {$match[3]}
        </p>
    </div>
</a>
HTML;

//                $linkGroupHtml .= "<a href=\"{$match[2]}\" target=\"_blank\" class=\"no-underline\">";
//                $linkGroupHtml .= "<div class=\"thumb\">";
//                $linkGroupHtml .= "<img width=\"200\" height=\"200\" src=\"{$match[4]}\" alt=\"{$match[3]}\"></div>";
//                $linkGroupHtml .= "<div class=\"content\">";
//                $linkGroupHtml .= "<div class=\"title\"><h3>{$match[3]}</h3></div>";
//                $linkGroupHtml .= "</div></a>\n";
            }
            $linkGroupHtml .= '</div>';
            $output .= $linkGroupHtml;
        }

        if ($currentGroupId < 1) {
            return $content;
        }

        $output .= substr($content, $linkGroupEndIndex[$currentGroupId]);
        return $output;
    }

    //endregion

    //region Shortcode 解析

    private static function cleanBlockContentEmptyTags($content) {
        $content = trim($content);

        while (Mirages_Utils::startsWith($content, "<br>")) {
            $content = substr($content, 4);
            $content = ltrim($content);
        }
        while (Mirages_Utils::startsWith($content, "</p><p>")) {
            $content = substr($content, 7);
            $content = ltrim($content);
        }
        while (Mirages_Utils::startsWith($content, "</p>")) {
            $content = substr($content, 4);
            $content = ltrim($content);
        }

        while (Mirages_Utils::endsWith($content, "<br>")) {
            $content = substr($content, 0, strlen($content) - 4);
            $content = rtrim($content);
        }
        while (Mirages_Utils::endsWith($content, "</p><p>")) {
            $content = substr($content, 0, strlen($content) - 7);
            $content = rtrim($content);
        }
        while (Mirages_Utils::endsWith($content, "<p>")) {
            $content = substr($content, 0, strlen($content) - 3);
            $content = rtrim($content);
        }
        $content = trim($content);
        return $content;
    }

    private static function checkIfSingleBlockContent($content) {
        if (Mirages_Utils::startsWith($content, "<pre") && Mirages_Utils::endsWith($content, "</pre>") && substr_count($content, "<pre") == 1) {
            return true;
        }

        return false;
    }

    private static function initShortcodeTags() {
        if (!empty(self::$shortcodeTags)) {
            return;
        }

        self::$shortcodeInlineTags = array('comment', 'x', 'btn', 'button', 'label', 'tag', '!');
        self::$shortcodeBlockTags = array('html', 'tabs', 'tip', 'hint', 'file', 'hide', 'imgc', 'collapse', 'github');

        self::$shortcodeTags['comment'] =
        self::$shortcodeTags['x'] = function ($content, $params, $tag) {
            if ($content == null) {
                return FALSE;
            }
            return '';
        };

        self::$shortcodeTags['html'] = function ($content, $params, $tag) {
            $html = str_replace("<br>", "\n", $content);
            $html = htmlspecialchars_decode($html);
            return $html;
        };

        self::$shortcodeTags['btn'] =
        self::$shortcodeTags['button'] = function ($content, $params, $tag) {
            if (empty($params) || !is_array($params)) {
                return FALSE;
            }
            if (array_key_exists('href', $params)) {
                $href = $params['href'];
            } elseif (array_key_exists('link', $params)) {
                $href = $params['link'];
            } elseif (array_key_exists('url', $params)) {
                $href = $params['url'];
            } else {
                return FALSE;
            }
            if (empty($content) && array_key_exists('title', $params)) {
                $content = $params['title'];
            }
            return "<a href=\"{$href}\" target=\"_blank\" class=\"btn btn-primary\">{$content}</a>";
        };

        self::$shortcodeTags['tabs'] = function ($content, $params, $tag) {
            $pattern = self::get_shortcode_regex(array('tab'));
            preg_match_all("/$pattern/ixm", $content, $matches);

            if (is_array($params) && array_key_exists('selected', $params)) {
                $defaultSelectIndex = intval($params['selected']);
            } else {
                $defaultSelectIndex = 1;
            }
            if ($defaultSelectIndex < 1 || $defaultSelectIndex > count($matches[0])) {
                $defaultSelectIndex = 1;
            }

            $tabHead = '';
            $tabBody = '';
            for ($index = 0; $index < count($matches[0]); $index++) {
                if ($matches[1][$index] == '[' && $matches[6][$index] == ']') {
                    continue;
                }

                $attr = self::shortcode_parse_atts($matches[3][$index]);
                $content = isset($matches[5][$index]) ? $matches[5][$index] : null;

                $content = self::cleanBlockContentEmptyTags($content);
                $singleBlock = self::checkIfSingleBlockContent($content) ? "single-block" : "";

                $content = self::parseShortcode($content);

                $tabIndex = $index + 1;

                if (is_array($attr) && array_key_exists('title', $attr)) {
                    $title = $attr['title'];
                } elseif (is_array($attr) && array_key_exists('name', $attr)) {
                    $title = $attr['name'];
                } else {
                    $title = 'Tab ' . $tabIndex;
                }

                if ($tabIndex == $defaultSelectIndex) {
                    $selectedClass = "selected";
                } else {
                    $selectedClass = "";
                }

                $tabHead .= "<div class=\"content-tab-title ${selectedClass}\" role=\"tab\" data-tab-index=\"${tabIndex}\">${title}</div>";
                $tabBody .= "<div class=\"content-tab-content ${selectedClass} ${singleBlock}\" data-tab-index=\"${tabIndex}\">${content}</div>";
            }

            return <<<HTML
<div class="content-tabs">
    <div class="content-tabs-head">${tabHead}</div>
    <div class="content-tabs-body">${tabBody}</div>
</div>
HTML;
        };

        self::$shortcodeTags['tip'] =
        self::$shortcodeTags['hint'] = function ($content, $params, $tag) {
            $content = trim($content);

            while (Mirages_Utils::startsWith($content, "<br>")) {
                $content = substr($content, 4);
                $content = ltrim($content);
            }

            while (Mirages_Utils::endsWith($content, "<br>")) {
                $content = substr($content, 0, strlen($content) - 4);
                $content = rtrim($content);
            }

            $content = self::parseShortcode($content);
            $type = 'info';
            $title = null;
            if (is_string($params)) {
                $type = $params;
            } elseif (is_array($params)) {
                if (array_key_exists(0, $params)) {
                    $type = $params[0];
                }
                if (array_key_exists('type', $params)) {
                    $type = $params['type'];
                }
                if (array_key_exists('title', $params)) {
                    $title = $params['title'];
                }
            }

            $type = trim(strtolower($type));
            if ($type == 'warn') {
                $type = 'warning';
            }
            if ($type == 'error') {
                $type = 'danger';
            }
            if (empty($type)) {
                $type = 'info';
            }
            if ($type == 'success') {
                $icon = 'check-circle';
            } elseif ($type == 'warning') {
                $icon = 'exclamation-circle';
            } elseif ($type == 'danger') {
                $icon = 'exclamation-triangle';
            } else {
                $icon = 'info-circle';
            }

            if (!empty($title)) {
                $title = "<p class=\"content-hint-title\">${title}</p>";
            } else {
                $title = "";
            }
            return <<<HTML
<div class="content-hint hint-${type}">
<i class="content-hint-icon fa fa-${icon}"></i>
${title}
${content}
</div> 
HTML;
        };

        self::$shortcodeTags['file'] = function ($content, $params, $tag) {
            if (empty($params) || !is_array($params)) {
                return FALSE;
            }
            if (array_key_exists('href', $params)) {
                $link = $params['href'];
            } elseif (array_key_exists('link', $params)) {
                $link = $params['link'];
            } elseif (array_key_exists('url', $params)) {
                $link = $params['url'];
            } else {
                return FALSE;
            }

            $content = trim($content);

            if (array_key_exists('filename', $params)) {
                $filename = $params['filename'];
            } elseif (array_key_exists('name', $params)) {
                $filename = $params['name'];
            } elseif (!empty($content)) {
                $filename = $content;
            } else {
                $filename = pathinfo($link, PATHINFO_BASENAME);
                $filename = urldecode($filename);
            }

            if (array_key_exists('icon', $params)) {
                $filename = $params['icon'];
            } else {
                $ext = pathinfo($link, PATHINFO_EXTENSION);
                $ext = strtolower(trim($ext));
                if (in_array($ext, array('zip', 'rar', '7z', 'tar', 'taz', 'tgz', 'tbz', 'gz', 'rz'))) {
                    $icon = 'file-archive-o';
                } elseif (in_array($ext, array('doc', 'docx'))) {
                    $icon = 'file-word-o';
                } elseif (in_array($ext, array('xls', 'xlsx', 'csv', 'tsv'))) {
                    $icon = 'file-excel-o';
                } elseif (in_array($ext, array('ppt', 'pptx'))) {
                    $icon = 'file-powerpoint-o';
                } elseif (in_array($ext, array('jpg', 'jpeg', 'png', 'bmp', 'gif'))) {
                    $icon = 'file-image-o';
                } elseif (in_array($ext, array('mp3', 'aac', 'm4a', 'm4r', 'wav', 'ape', 'flac'))) {
                    $icon = 'file-audio-o';
                } elseif (in_array($ext, array('mp4', 'm4v', 'mkv', 'mov', 'webm', '3gp', 'avi', 'wmv', 'mpg', 'vob', 'flv', 'swf', 'rmvb', 'rm'))) {
                    $icon = 'file-video-o';
                } elseif (in_array($ext, array('pdf'))) {
                    $icon = 'file-pdf-o';
                } elseif (in_array($ext, array('java', 'php', 'html', 'css', 'sass', 'scss', 'js', 'go', 'c', 'h', 'm', 'cpp', 'c++', 'cc', 'cxx', 'cs', 'aspx', 'asax', 'jsp', 'perl', 'pl', 'py', 'swift', 'sql'))) {
                    $icon = 'file-code-o';
                } else {
                    $icon = 'download';
                }
            }

            return <<<HTML
<a class="content-file" href="${link}" target="_blank">
<i class="content-file-icon fa fa-${icon}"></i>
<span class="content-file-filename">${filename}</span>
</a>
HTML;
        };

        self::$shortcodeTags['label'] =
        self::$shortcodeTags['tag'] = function ($content, $params, $tag) {
            if (is_array($params)) {
                if (array_key_exists('type', $params)) {
                    $colorScheme = $params['type'];
                } else {
                    $colorScheme = 'default';
                }
                if (array_key_exists('outline', $params)) {
                    $outline = strtolower(trim($params['type']));
                    if ($outline == 'false' || $outline == '0') {
                        $useOutline = false;
                    } else {
                        $useOutline = true;
                    }
                } elseif (in_array("outline", array_values($params))) {
                    $useOutline = true;
                } else {
                    $useOutline = false;
                }
            } else {

                $useOutline = false;
                $colorScheme = 'default';
            }

            $colorScheme = strtolower(trim($colorScheme));
            if (!in_array($colorScheme, array('default', 'primary', 'info', 'warn', 'warning', 'danger', 'error', 'success'))) {
                $colorScheme = 'default';
            }
            if ($colorScheme == 'warn') {
                $colorScheme = 'warning';
            }
            if ($colorScheme == 'error') {
                $colorScheme = 'danger';
            }

            if ($useOutline) {
                $colorScheme = 'outline-' . $colorScheme;
            }

            return <<<HTML
<span class="label label-${colorScheme}">${content}</span>
HTML;
        };

        self::$shortcodeTags['!'] = function ($content, $params, $tag) {
            return '&nbsp;<i class="fa fa-exclamation-circle"></i>&nbsp;';
        };
        self::$shortcodeTags['hide'] = function ($content, $params, $tag) {
            $key = 'shouldDisplayHideComment';
            if (!array_key_exists($key, self::$shortcodeStats)) {

                $user = Typecho_Widget::widget('Widget_User');
                if ($user->hasLogin()) {
                    $display = true;
                } else {
                    $db = Typecho_Db::get();
                    $sql = $db->select()->from('table.comments')
                        ->where('type = ?', 'comment')
                        ->where('cid = ?', self::$currentWidget->cid)
                        ->where('mail = ?', self::$currentWidget->remember('mail', true));

                    if ((!empty(self::$themeOptions->embedCommentOptions) && in_array('comment2ViewStrict', self::$themeOptions->embedCommentOptions))) {
                        $sql->where('status = ?', 'approved');
                    }
                    $sql->limit(1);
                    $result = $db->fetchAll($sql);
                    $display = !empty($result);
                }

                self::$shortcodeStats[$key] = $display;
            }

            $shouldDisplay = self::$shortcodeStats[$key];
            if ($shouldDisplay) {
                $content = self::parseShortcode($content);
                return <<<HTML
<div class="reply2view show">${content}</div>
HTML;

            } else {
                return <<<HTML
<div class="reply2view">此处内容需要评论回复后方可阅读</div>
HTML;

            }
        };

        self::$shortcodeTags['imgc'] = function ($content, $params, $tag) {
            return '<figcaption class="image-caption-manual">' . $content . '</figcaption>';
        };

        self::$shortcodeTags['collapse'] = function ($content, $params, $tag) {
            if (!is_array($params)) {
                return FALSE;
            }

            if (array_key_exists('title', $params)) {
                $title = $params['title'];
            } else {
                return FALSE;
            }
            if (array_key_exists('show', $params)) {
                $show = $params['show'];
            } else {
                $show = "";
            }
            if (strtolower($show) == 'true' || $show > 0) {
                $show = "show";
            } else {
                $show = "";
            }

            $key = 'collapseId';
            if (!array_key_exists($key, self::$shortcodeStats)) {
                self::$shortcodeStats[$key] = 0;
            }
            self::$shortcodeStats[$key] ++;
            $collapseId = self::$shortcodeStats[$key];

            $content = self::parseShortcode($content, null, array('tabs'));

            $content = self::cleanBlockContentEmptyTags($content);
            $singleBlock = self::checkIfSingleBlockContent($content) ? "single-block" : "";
            if (strpos($content, '<') === FALSE) {
                $content = "<p>${content}</p>";
            }
            if (!Mirages_Utils::startsWith($content, "<")) {
                $content = "<p>" . $content;
            }
            return <<<HTML
        <div class="collapse-block">
            <div class="collapse-header ${show}" data-mirages-toggle="collapse" data-target="#collapse-block-${collapseId}">
                <p class="title">${title} <span class="angle"><i class="fa fa-angle-double-down" aria-hidden="true"></i></span></p>
            </div>
            <div id="collapse-block-${collapseId}" class="collapse-content collapse ${show} ${singleBlock}">
                ${content}
            </div>
        </div>
HTML;
        };

        self::$shortcodeTags['github'] = function ($content, $params, $tag) {
            if (array_key_exists('repo', $params)) {
                $repo = $params['repo'];
            } else {
                return FALSE;
            }
            return '<div class="github-widget" data-repo="' . $repo . '"></div>';
        };
    }

    private static function parseShortcode($markdown, $tagNames = NULL, $excludeTagNames = NULL) {
        self::initShortcodeTags();

        if (false === strpos( $markdown, '[' ) ) {
            return $markdown;
        }
        if (empty(self::$shortcodeTags) || !is_array(self::$shortcodeTags)) {
            return $markdown;
        }

        if (empty($tagNames) && empty($excludeTagNames)) {
            $pattern = self::get_shortcode_regex();
        } elseif (!empty($tagNames)) {
            $tagNames = array_diff($tagNames, $excludeTagNames);
            $pattern = self::get_shortcode_regex($tagNames);
        } else {
            $tagNames = array_keys(self::$shortcodeTags);
            $tagNames = array_diff($tagNames, $excludeTagNames);
            $pattern = self::get_shortcode_regex($tagNames);
        }

        $markdown = preg_replace_callback("/$pattern/ixm", array('Mirages_Plugin', '_parseShortcodeTag'), $markdown);

        return $markdown;
    }

    private static function _escapeShortcodeOpenMarkForCode($code) {
        $code = str_replace("[", "@@PH@LEFT@SQUARE@BRACKET@MIRAGES@@", $code);
        $code = str_replace("]", "@@PH@RIGHT@SQUARE@BRACKET@MIRAGES@@", $code);
        return $code;
    }
    private static function _unEscapeShortcodeOpenMark($content) {
        // do nothing
        // use _doUnEscapeTexBlock to unEscape [ ]
        $content = str_replace("@@PH@LEFT@SQUARE@BRACKET@MIRAGES@@", "[", $content);
        $content = str_replace("@@PH@RIGHT@SQUARE@BRACKET@MIRAGES@@", "]", $content);
        return $content;
    }

    private static function _parseShortcodeTag($m) {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag  = $m[2];
        $attr = self::shortcode_parse_atts($m[3]);
        if (!(array_key_exists(strtolower($tag), self::$shortcodeTags) && is_callable(self::$shortcodeTags[strtolower($tag)]))) {
            return $m[0];
        }

        $content = isset($m[5]) ? $m[5] : null;

        $output = call_user_func(self::$shortcodeTags[strtolower($tag)], $content, $attr, $tag);
        if ($output === FALSE) {
            return $m[0];
        }

        return $m[1] . $output . $m[6];
    }

    private static function get_shortcode_regex($tagnames = null) {
        if (empty($tagnames)) {
            $tagnames = array_keys(self::$shortcodeTags);
        }
        $tagregexp = join( '|', array_map( 'preg_quote', $tagnames ) );
        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
        // Also, see shortcode_unautop() and shortcode.js.
        // phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
        return
            '\\['                                // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
        // phpcs:enable
    }

    private static function get_shortcode_atts_regex() {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
    }

    private static function shortcode_parse_atts($text) {
        $atts    = array();
        $pattern = self::get_shortcode_atts_regex();
        $text    = preg_replace( "/[\x{00a0}\x{200b}]+/u", ' ', $text );
        if ( preg_match_all( $pattern, $text, $match, PREG_SET_ORDER ) ) {
            foreach ( $match as $m ) {
                if ( ! empty( $m[1] ) ) {
                    $atts[ strtolower( $m[1] ) ] = stripcslashes( $m[2] );
                } elseif ( ! empty( $m[3] ) ) {
                    $atts[ strtolower( $m[3] ) ] = stripcslashes( $m[4] );
                } elseif ( ! empty( $m[5] ) ) {
                    $atts[ strtolower( $m[5] ) ] = stripcslashes( $m[6] );
                } elseif ( isset( $m[7] ) && strlen( $m[7] ) ) {
                    $atts[] = stripcslashes( $m[7] );
                } elseif ( isset( $m[8] ) && strlen( $m[8] ) ) {
                    $atts[] = stripcslashes( $m[8] );
                } elseif ( isset( $m[9] ) ) {
                    $atts[] = stripcslashes( $m[9] );
                }
            }
            // Reject any unclosed HTML elements
            foreach ( $atts as &$value ) {
                if ( false !== strpos( $value, '<' ) ) {
                    if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                        $value = '';
                    }
                }
            }
        } else {
            $atts = ltrim( $text );
        }
        return $atts;
    }

    //endregion

    //region 云存储优化方法
    /**
     * 检查 host 类型，目前可用的值有 七牛、又拍、其他
     * @param $host
     * @return int
     */
    public static function getCdnType($host) {
        self::loadPluginOptions();
        if (is_string($host) && !is_array($host)) {
            $host = parse_url($host);
            if (array_key_exists('host', $host)) {
                $host = $host['host'];
            } else {
                return self::CDN_TYPE_OTHERS;
            }
        } elseif (is_array($host)) {
            if (array_key_exists('host', $host)) {
                $host = $host['host'];
            } else {
                return self::CDN_TYPE_OTHERS;
            }
        }
        // check if local
        $local = parse_url(self::$themeOptions->siteUrl);
        if (is_array($local) && array_key_exists('host', $local) && $local['host'] === $host) {
            return self::CDN_TYPE_LOCAL;
        }

        // check if qiniu cdn
        foreach (self::$cdnHosts[self::CDN_NAME_QINIU] as $qiniuHost) {
            if (empty($qiniuHost)) {
                continue;
            }
            if (Mirages_Utils::endsWith($host, $qiniuHost)) {
                return self::CDN_TYPE_QINIU;
            }
        }

        // check if qiniu cdn
        foreach (self::$cdnHosts[self::CDN_NAME_UPYUN] as $upYunHost) {
            if (empty($upYunHost)) {
                continue;
            }
            if (Mirages_Utils::endsWith($host, $upYunHost)) {
                return self::CDN_TYPE_UPYUN;
            }
        }

        // check if aliyun oss
        foreach (self::$cdnHosts[self::CDN_NAME_ALIYUN_OSS] as $aliyunOss) {
            if (empty($aliyunOss)) {
                continue;
            }
            if (Mirages_Utils::endsWith($host, $aliyunOss)) {
                return self::CDN_TYPE_ALIYUN_OSS;
            }
        }

        // check if qcloud ci
        foreach (self::$cdnHosts[self::CDN_NAME_QCLOUD_CI] as $qcloud) {
            if (empty($qcloud)) {
                continue;
            }
            if (Mirages_Utils::endsWith($host, $qcloud)) {
                return self::CDN_TYPE_QCLOUD_CI;
            }
        }
        return self::CDN_TYPE_OTHERS;
    }

    /**
     * 为 URL 添加图片宽高信息
     * @param $url
     * @return string
     */
    private static function addMiragesWidthAndSize($url) {
        $part = parse_url($url);

        // 清理模式
        if (self::$cleanMode === true && self::$cleanConfirmMode === true) {
            if (array_key_exists('fragment', $part) && !empty($part['fragment'])) {
                $fragment = $part['fragment'];
                $fragment = str_replace("#", "&", $fragment);
                $fragment = Mirages_Utils::httpParseQuery($fragment);

                unset($fragment[self::KEY_WIDTH]);
                unset($fragment[self::KEY_HEIGHT]);
                unset($fragment[self::KEY_CDN_TYPE]);

                if (empty($fragment)) {
                    unset($part['fragment']);
                } else {
                    $part['fragment'] = http_build_query($fragment);
                }
                self::$lastPostModified = true;
                $url = Mirages_Utils::httpBuildUrl($part);
            }
            return $url;
        }
        $currentCDNType = self::getCdnType($part);

        if (array_key_exists('fragment', $part) && !empty($part['fragment'])) {
            $fragment = $part['fragment'];
            $fragment = str_replace("#", "&", $fragment);
            $fragment = Mirages_Utils::httpParseQuery($fragment);

            if (array_key_exists(self::KEY_WIDTH, $fragment) && array_key_exists(self::KEY_HEIGHT, $fragment)) {
                if (intval($fragment[self::KEY_WIDTH]) > 0 && intval($fragment[self::KEY_HEIGHT]) > 0) {
                    $part['fragment'] = str_replace("#", "&", $part['fragment']);
                    return Mirages_Utils::httpBuildUrl($part);
                }
            }
            if (array_key_exists('nolazyload', $fragment) || (array_key_exists('lazyload', $fragment) && $fragment['lazyload'] == 'false')) {
                $part['fragment'] = str_replace("#", "&", $part['fragment']);
                return Mirages_Utils::httpBuildUrl($part);
            }
        }

        if (in_array($currentCDNType, self::$supportedCDNType)) {

            $part4size = $part;
            unset($part4size['query']);
            unset($part4size['fragment']);
            if (!array_key_exists('scheme', $part4size)) {
                $part4size['scheme'] = 'http';
            }
            if ($currentCDNType === self::CDN_TYPE_QINIU) {
                $url4size = Mirages_Utils::httpBuildUrl($part4size)."?imageInfo";
            } elseif ($currentCDNType === self::CDN_TYPE_UPYUN) {
                $splitTag = self::$pluginOptions->upYunSplitTag;
                if (empty($splitTag)) {
                    $splitTag = "!";
                }
                $url4size = Mirages_Utils::httpBuildUrl($part4size) . $splitTag . "/info";
            } elseif ($currentCDNType === self::CDN_TYPE_ALIYUN_OSS) {
                $url4size = Mirages_Utils::httpBuildUrl($part4size) . "?x-oss-process=image/info";
            } elseif ($currentCDNType === self::CDN_TYPE_QCLOUD_CI) {
                $url4size = Mirages_Utils::httpBuildUrl($part4size)."?imageInfo";
            } else {
                $url4size = NULL;
            }
            if (!empty($url4size)) {
                $json = Mirages_Utils::httpRequest($url4size);
            }
            if (!empty($json) && is_array($json)) {
                $width = -1;
                $height = -1;
                if (array_key_exists('width', $json) && array_key_exists('height', $json)) {
                    $width = $json['width'];
                    $height = $json['height'];
                } elseif (array_key_exists('ImageWidth', $json) && array_key_exists('ImageHeight', $json)) {
                    $_w = $json['ImageWidth'];
                    $_h = $json['ImageHeight'];
                    $width = @$_w['value'];
                    $height = @$_h['value'];
                }
                if (intval($width) > 0 && intval($height) > 0) {
                    $fragment[self::KEY_WIDTH] = intval($width);
                    $fragment[self::KEY_HEIGHT] = intval($height);
                    $fragment[self::KEY_CDN_TYPE] = $currentCDNType;
                    $part['fragment'] = http_build_query($fragment);
                    self::$lastPostModified = true;
                }
            }
        } elseif ($currentCDNType === self::CDN_TYPE_LOCAL) {
            $filepath = self::getUploadsFilePath($url);
            if (file_exists($filepath)) {
                $imageSize = @getimagesize($filepath);
                if (!empty($imageSize) && count($imageSize) >= 2) {
                    $fragment[self::KEY_WIDTH] = intval($imageSize[0]);
                    $fragment[self::KEY_HEIGHT] = intval($imageSize[1]);
                    $fragment[self::KEY_CDN_TYPE] = $currentCDNType;
                    $part['fragment'] = http_build_query($fragment);
                    self::$lastPostModified = true;
                }
            }
        }
        $url = Mirages_Utils::httpBuildUrl($part);
        return $url;
    }

    private static function getUploadsFilePath($url) {
        $part = parse_url($url);
        unset($part['fragment']);
        unset($part['query']);
        unset($part['user']);
        unset($part['pass']);
        $url = Mirages_Utils::httpBuildUrl($part);
        if (!Mirages_Utils::startsWith($url, self::$themeOptions->siteUrl)) {
            return false;
        }
        $url = rtrim(preg_replace('/^' . preg_quote(rtrim(self::$themeOptions->siteUrl, '/'), '/') . '/', '', $url, 1), '/');
        $uploadsPath = defined('__TYPECHO_UPLOAD_DIR__') ? __TYPECHO_UPLOAD_DIR__ : "/usr/uploads";
        if (!Mirages_Utils::startsWith($url, $uploadsPath)) {
            return false;
        }
        $path = Typecho_Common::url($url,
            defined('__TYPECHO_UPLOAD_ROOT_DIR__') ? __TYPECHO_UPLOAD_ROOT_DIR__ : __TYPECHO_ROOT_DIR__);
        return $path;
    }

    private static function getThumbnailByCdnType($url, $cdnType) {
        $part = parse_url($url);
        $cdnType = Mirages_Utils::trimAttributeValue($cdnType);
        if (self::CDN_TYPE_QINIU == $cdnType) {
            $part['query'] = "imageView2/2/w/64/q/20";
            unset($part['fragment']);
            return Mirages_Utils::httpBuildUrl($part);
        } elseif (self::CDN_TYPE_UPYUN == $cdnType) {
            $splitTag = self::$pluginOptions->upYunSplitTag;
            if (empty($splitTag)) {
                $splitTag = "!";
            }
            $part['path'] .= $splitTag . "/max/64";
            unset($part['fragment']);
            return Mirages_Utils::httpBuildUrl($part);
        } elseif (self::CDN_TYPE_ALIYUN_OSS == $cdnType) {
            $part['query'] = "x-oss-process=image/resize,w_64/quality,Q_20";
            unset($part['fragment']);
            return Mirages_Utils::httpBuildUrl($part);
        } elseif (self::CDN_TYPE_QCLOUD_CI == $cdnType) {
            $part['query'] = "imageView2/2/w/64/q/20";
            unset($part['fragment']);
            return Mirages_Utils::httpBuildUrl($part);
        }
        return $url;
    }

    public static function UPYunSplitTag() {
        self::loadPluginOptions();
        return self::$pluginOptions->upYunSplitTag;
    }

    public static function outputGetCDNTypeJS() {
        self::loadPluginOptions();
        echo <<<EOF
var getCDNType = function() {
}
EOF;

    }

    //endregion

    //region 表情解析方法
    private static function doParseBiaoqing($content) {
        $content = preg_replace_callback('/\#\[\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\]/is',
            array('Mirages_Plugin', '_parsePaopaoBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\@\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is',
            array('Mirages_Plugin', '_parsePaopaoBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\#\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
            array('Mirages_Plugin', '_parseAruBiaoqingCallback'), $content);

        return $content;
    }

    private static function _parsePaopaoBiaoqingCallback($match) {
        return "<img class=\"biaoqing newpaopao\" src=\"".self::$biaoqingRootPath['paopao'] . str_replace('%', '', urlencode($match[1])) . "_2x.png\" height=\"30\" width=\"30\" no-zoom>";
    }
    private static function _parseAruBiaoqingCallback($match) {
        return "<img class=\"biaoqing alu\" src=\"".self::$biaoqingRootPath['aru'] . str_replace('%', '', urlencode($match[1])) . "_2x.png\" height=\"33\" width=\"33\" no-zoom>";
    }

    public static function parseBiaoqing($content) {
        self::loadPluginOptions();
        return self::doParseBiaoqing($content);
    }
    public static function biaoqingRootPath() {
        self::loadPluginOptions();
        return self::$biaoqingRootPath;
    }
    //endregion

    //region 内容分块处理方法
    /**
     * 处理 Markdown 文本，确保不会解析代码块中的内容
     * @param $markdown
     * @param callable $contentCallable
     * @param callable|array $codeCallback
     * @return string
     */
    private static function handleMarkdown($markdown, $contentCallable, $codeCallback = array()) {
        $replaceStartIndex = array();
        $replaceEndIndex = array();
        $currentReplaceId = 0;
        $searchIndex = 0;
        $contentLength = strlen($markdown);

        while (true) {
            $codeBlockStartIndex = false;
            $codeBlockEndIndex = false;
            $inlineCodeStartIndex = false;
            $inlineCodeEndIndex = false;
            if (preg_match('{
				(?:\n|\r|\A)
				# 1: Opening marker
				\s*(
					~{3,}|`{3,} # Marker: three tilde or more.
				)
				
				[ ]?(\w+)?(?:,[ ]?(\d+))?[ ]* (\n|\r)+ # Whitespace and newline following marker.
				
				# 3: Content
				(
					(?>
						(?!\1 [ ]* (\n|\r))	# Not a closing marker.
						.*(\n|\r)+
					)+
				)
				
				# Closing marker.
				\s*\1 [ ]* (\n|\r)+
			}xm', $markdown, $matches, 0, $searchIndex)) {
                $codeBlockStartIndex = strpos($markdown, $matches[0], $searchIndex);
                if ($codeBlockStartIndex) {
                    $codeBlockEndIndex = $codeBlockStartIndex + strlen($matches[0]);
                }
            }
            if (preg_match('/`+([^\n]*?)`+/sm', $markdown, $matches, 0, $searchIndex)) {
                $inlineCodeStartIndex = strpos($markdown, $matches[0], $searchIndex);
                if ($inlineCodeStartIndex) {
                    $inlineCodeEndIndex = $inlineCodeStartIndex + strlen($matches[0]);
                }
            }
            if (!$codeBlockStartIndex) {
                $codeBlockStartIndex = $contentLength;
            }
            if (!$inlineCodeStartIndex) {
                $inlineCodeStartIndex = $contentLength;
            }
            $useBlock = false;
            if ($codeBlockStartIndex <= $inlineCodeStartIndex) {
                $useBlock = true;
            }
            $replaceStartIndex[$currentReplaceId] = $searchIndex;
            $replaceEndIndex[$currentReplaceId] = $useBlock ? $codeBlockStartIndex : $inlineCodeStartIndex;;
            $searchIndex = $useBlock ? $codeBlockEndIndex : $inlineCodeEndIndex;
            $currentReplaceId++;
            if ($codeBlockStartIndex >= $contentLength && $inlineCodeStartIndex>= $contentLength) {
                break;
            }
        }

        $output = "";
        $output .= substr($markdown, 0, $replaceStartIndex[0]);
        for ($i = 0; $i < count($replaceStartIndex); $i++) {
            $part = substr($markdown, $replaceStartIndex[$i], $replaceEndIndex[$i] - $replaceStartIndex[$i]);
            $renderedPart = self::handleHtml($part, $contentCallable);
            $output.= $renderedPart;

            if ($i < count($replaceStartIndex) - 1) {
                $codePart = substr($markdown, $replaceEndIndex[$i], $replaceStartIndex[$i + 1] - $replaceEndIndex[$i]);
                if (empty($codeCallback)) {
                    $renderedCodePart = $codePart;
                } else {
                    if (is_array($codeCallback)) {
                        $className = $codeCallback[0];
                        $method = $codeCallback[1];
                        $renderedCodePart = call_user_func($className.'::'.$method, $codePart);
                    } else {
                        $renderedCodePart = $codeCallback($codePart);
                    }
                }
                $output .= $renderedCodePart;
            }
        }
        $output .= substr($markdown, $replaceEndIndex[count($replaceStartIndex) - 1]);
        return $output;
    }

    /**
     * 处理 HTML 文本，确保不会解析代码块中的内容
     * @param $content
     * @param callable $contentCallback
     * @param callable|array $codeCallback
     * @return string
     */
    private static function handleHtml($content, $contentCallback, $codeCallback = array()) {
        $replaceStartIndex = array();
        $replaceEndIndex = array();
        $currentReplaceId = 0;
        $replaceIndex = 0;
        $searchIndex = 0;
        $searchCloseTag = false;
        $contentLength = strlen($content);
        while (true) {
            if ($searchCloseTag) {
                $tagName = substr($content, $searchIndex, 4);
                if ($tagName == "<cod") {
                    $searchIndex = strpos($content, '</code>', $searchIndex);
                    if (!$searchIndex) {
                        break;
                    }
                    $searchIndex += 7;
                } elseif ($tagName == "<pre") {
                    $searchIndex = strpos($content, '</pre>', $searchIndex);
                    if (!$searchIndex) {
                        break;
                    }
                    $searchIndex += 6;
                } elseif ($tagName == "<kbd") {
                    $searchIndex = strpos($content, '</kbd>', $searchIndex);
                    if (!$searchIndex) {
                        break;
                    }
                    $searchIndex += 6;
                } elseif ($tagName == "<scr") {
                    $searchIndex = strpos($content, '</script>', $searchIndex);
                    if (!$searchIndex) {
                        break;
                    }
                    $searchIndex += 9;
                } elseif ($tagName == "<sty") {
                    $searchIndex = strpos($content, '</style>', $searchIndex);
                    if (!$searchIndex) {
                        break;
                    }
                    $searchIndex += 8;
                } else {
                    break;
                }

                if (!$searchIndex) {
                    break;
                }
                $replaceIndex = $searchIndex;
                $searchCloseTag = false;
                continue;
            } else {
                $searchCodeIndex = strpos($content, '<code', $searchIndex);
                $searchPreIndex = strpos($content, '<pre', $searchIndex);
                $searchKbdIndex = strpos($content, '<kbd', $searchIndex);
                $searchScriptIndex = strpos($content, '<script', $searchIndex);
                $searchStyleIndex = strpos($content, '<style', $searchIndex);
                if (!$searchCodeIndex) {
                    $searchCodeIndex = $contentLength;
                }
                if (!$searchPreIndex) {
                    $searchPreIndex = $contentLength;
                }
                if (!$searchKbdIndex) {
                    $searchKbdIndex = $contentLength;
                }
                if (!$searchScriptIndex) {
                    $searchScriptIndex = $contentLength;
                }
                if (!$searchStyleIndex) {
                    $searchStyleIndex = $contentLength;
                }
                $searchIndex = min($searchCodeIndex, $searchPreIndex, $searchKbdIndex, $searchScriptIndex, $searchStyleIndex);
                $searchCloseTag = true;
            }
            $replaceStartIndex[$currentReplaceId] = $replaceIndex;
            $replaceEndIndex[$currentReplaceId] = $searchIndex;
            $currentReplaceId++;
            $replaceIndex = $searchIndex;
        }

        $output = "";
        $output .= substr($content, 0, $replaceStartIndex[0]);
        for ($i = 0; $i < count($replaceStartIndex); $i++) {
            $part = substr($content, $replaceStartIndex[$i], $replaceEndIndex[$i] - $replaceStartIndex[$i]);
            if (empty($contentCallback)) {
                $renderedPart = $part;
            } else {
                if (is_array($contentCallback)) {
                    $className = $contentCallback[0];
                    $method = $contentCallback[1];
                    $renderedPart = call_user_func($className.'::'.$method, $part);
                } else {
                    $renderedPart = $contentCallback($part);
                }
            }
            $output.= $renderedPart;

            if ($i < count($replaceStartIndex) - 1) {
                $codePart = substr($content, $replaceEndIndex[$i], $replaceStartIndex[$i + 1] - $replaceEndIndex[$i]);
                if (empty($codeCallback)) {
                    $renderedCodePart = $codePart;
                } else {
                    if (is_array($codeCallback)) {
                        $className = $codeCallback[0];
                        $method = $codeCallback[1];
                        $renderedCodePart = call_user_func($className.'::'.$method, $codePart);
                    } else {
                        $renderedCodePart = $codeCallback($codePart);
                    }
                }
                $output .= $renderedCodePart;
            }
        }
        $output .= substr($content, $replaceEndIndex[count($replaceStartIndex) - 1]);
        return $output;
    }
    //endregion

    //region Options 及其相关信息
    private static function loadPluginOptions() {
        if (self::$optionLoaded) {
            return;
        }
        self::$devMode = defined("MIRAGES_DEVELOPER") && MIRAGES_DEVELOPER;
        self::$themeOptions = Typecho_Widget::widget('Widget_Options');
        self::$pluginOptions = self::$themeOptions->plugin("Mirages");
        self::$pluginBaseUrl = rtrim(preg_replace('/^'.preg_quote(rtrim(self::$themeOptions->siteUrl, '/'), '/').'/', rtrim(self::$themeOptions->rootUrl, '/'), self::$themeOptions->pluginUrl, 1),'/').'/Mirages/';
        self::$themeBaseUrl  = rtrim(preg_replace('/^'.preg_quote(rtrim(self::$themeOptions->siteUrl, '/'), '/').'/', rtrim(self::$themeOptions->rootUrl, '/'), self::$themeOptions->themeUrl,  1),'/').'/';

        $props = self::$themeOptions->realRealRealRealAdvancedOptions;
        $props = mb_split("\n", $props);
        foreach ($props as $prop) {
            $item = mb_split("=", $prop, 2);
            if (is_array($item) && count($item) == 2) {
                $key = trim($item[0]);
                if (Mirages_Utils::startsWith($key, "#")) {
                    continue;
                }
                if (strpos($key, '.')) {
                    $key = mb_split("\.", $key, 2);
                    if (is_array($key) && count($key) == 2) {
                        $key = $key[0] . '__' . $key[1];
                    }
                }
                $value = trim($item[1]);
                self::$themeAdvancedOptions[$key] = $value;
            }
        }

        @$version = explode('/', self::$themeOptions->version);
        if (count($version) == 2) {
            self::$typechoVersion = trim($version[0]);
            self::$typechoDateVersion = trim($version[1]);
        }

        // 七牛自定义域名
        $customQiniuHosts = self::$pluginOptions->customQiniuHosts;
        if (!empty($customQiniuHosts)) {
            $customQiniuHosts = mb_split("(\n|\r)", $customQiniuHosts);
            self::$cdnHosts[self::CDN_NAME_QINIU] = array_merge(self::$cdnHosts[self::CDN_NAME_QINIU], $customQiniuHosts);
            self::$cdnHosts[self::CDN_NAME_QINIU] = array_unique(self::$cdnHosts[self::CDN_NAME_QINIU]);
        }

        // 又拍云自定义域名
        $customUPYunHosts = self::$pluginOptions->customUPYunHosts;
        if (!empty($customUPYunHosts)) {
            $customUPYunHosts = mb_split("(\n|\r)", $customUPYunHosts);
            self::$cdnHosts[self::CDN_NAME_UPYUN] = array_merge(self::$cdnHosts[self::CDN_NAME_UPYUN], $customUPYunHosts);
            self::$cdnHosts[self::CDN_NAME_UPYUN] = array_unique(self::$cdnHosts[self::CDN_NAME_UPYUN]);
        }

        $customCDNHosts = self::$pluginOptions->customCDNHosts;
        if (!empty($customCDNHosts)) {
            $customHosts = mb_split("(\n|\r)", $customCDNHosts);
            foreach ($customHosts as $customHost) {
                $type = mb_split(":", $customHost, 2);
                if (is_array($type) && count($type) == 2) {
                    $host = trim($type[0]);
                    $cdn = strtoupper(trim($type[1]));
                    if (!array_key_exists($cdn, self::$cdnHosts)) {
                        self::$cdnHosts[$cdn] = array();
                    }
                    self::$cdnHosts[$cdn][] = $host;
                }
            }

            foreach (array_keys(self::$cdnHosts) as $key) {
                self::$cdnHosts[$key] = array_unique(self::$cdnHosts[$key]);
            }
        }

        //表情自定义路径
        $biaoqingRootPath = self::$pluginOptions->biaoqingRootPath;
        if (!empty($biaoqingRootPath)) {
            $biaoqingRootPath = mb_split("(\n|\r)", $biaoqingRootPath);
            foreach ($biaoqingRootPath as $path) {
                $item = mb_split(":", $path, 2);
                if (count($item) !== 2) continue;

                $biaoqingName = strtolower(trim($item[0]));
                $biaoqingPath = trim($item[1]);
                $biaoqingPath = rtrim($biaoqingPath, '/').'/';

                self::$biaoqingRootPath[$biaoqingName] = $biaoqingPath;
            }
        }
        $embedBiaoqingRootPath = rtrim(self::$pluginBaseUrl, '/').'/biaoqing/';
        if (strlen(trim(self::$themeOptions->cdnDomain)) > 0) {
            $embedBiaoqingRootPath = rtrim(preg_replace('/^'.preg_quote(rtrim(self::$themeOptions->siteUrl, '/'), '/').'/', rtrim(self::$themeOptions->cdnDomain, '/'), $embedBiaoqingRootPath, 1),'/').'/';
        }
        if (!array_key_exists('paopao', self::$biaoqingRootPath)) {
            self::$biaoqingRootPath['paopao'] = $embedBiaoqingRootPath . 'paopao/';
        }
        if (!array_key_exists('aru', self::$biaoqingRootPath)) {
            self::$biaoqingRootPath['aru'] = $embedBiaoqingRootPath . 'aru/';
        }

        self::$optionLoaded = true;
    }

    private static function loadThemeVersion() {
        $themeName = Helper::options()->theme;
        if (!empty($themeName)) {
            $file = Helper::options()->themeFile($themeName, '/lib/Mirages.php');
            if (file_exists($file)) {
                if (!class_exists("Mirages")) {
                    require_once($file);
                }
                if (strlen(Mirages::$version) > 5) {
                    $themeVersion = substr(Mirages::$version, 0, 5);
                } else {
                    $themeVersion = Mirages::$version;
                }
                self::$themeVersionRaw = Mirages::$version;
                self::$themeVersionTag = Mirages::$versionTag;
                $themeVersion = preg_replace('/\D/i', '', $themeVersion);
                self::$themeVersion = intval($themeVersion);
            }
        }
    }

    private static function themeAdvancedSettingIsTrue($key) {
        if (array_key_exists($key, self::$themeAdvancedOptions) && Mirages_Utils::hasValue(self::$themeAdvancedOptions[$key])) {
            return true;
        }
        return false;
    }

    private static function lazyloadEnabled() {
        if (self::$themeVersion < 0) {
            self::loadThemeVersion();
        }

        return (self::$themeOptions->enableLazyLoad == 1);
    }
    //endregion

}

class Title_Plugin extends Typecho_Widget_Helper_Form_Element
{

    public function label($value)
    {
        /** 创建标题元素 */
        if (empty($this->label)) {
            $this->label = new Typecho_Widget_Helper_Layout('label', array('class' => 'typecho-label', 'style'=>'font-size: 2em;border-bottom: 1px #ddd solid;padding-top:2em;'));
            $this->container($this->label);
        }

        $this->label->html($value);
        return $this;
    }

    public function input($name = NULL, array $options = NULL)
    {
        $input = new Typecho_Widget_Helper_Layout('p', array());
        $this->container($input);
        $this->inputs[] = $input;
        return $input;
    }

    protected function _value($value) {}

}