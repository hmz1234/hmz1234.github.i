<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * VersionManager.php
 * Author     : Hran
 * Date       : 2017/2/18
 * Version    :
 * Description:
 */
class Mirages_VersionManager {
    private static $currentVersion = null;
    private static $themeDir = null;

    public static function requestUpdate() {
        @set_time_limit(600);
        @ignore_user_abort(1);

        if (!(Mirages_Utils::is_really_writable(Mirages_Utils::themesDir()) && Mirages_Utils::is_really_writable(Mirages_Utils::pluginsDir()))) {
            $message = array(
                'status' => 'error',
                'hideLog' => 1,
                'message' => "当前主机环境没有写入权限，无法自动升级。请联系作者获取更新包手动更新。",
            );
            return $message;
        }

        if (!class_exists("ZipArchive")) {
            $message = array(
                'status' => 'error',
                'hideLog' => 1,
                'message' => "当前 PHP 环境没有安装 Zip 扩展，无法自动升级。请安装 Zip 扩展或联系作者获取更新包手动更新。",
            );
            return $message;
        }

        mLog("开始执行更新...");

        $package = self::loadUpdatePackage();
        if (array_key_exists('status', $package) && array_key_exists('message', $package)) {
            mLog("更新下载地址获取失败，原因是：" . $package['message'], Mirages_Utils::LOG_LEVEL_ERROR);
            return $package;
        }

        $package = self::downloadPackage($package);
        if (array_key_exists('status', $package) && array_key_exists('message', $package)) {
            mLog("更新包下载失败，原因是：" . $package['message'], Mirages_Utils::LOG_LEVEL_ERROR);
            return $package;
        }

        $package = self::unPackage($package);
        if (array_key_exists('status', $package) && array_key_exists('message', $package)) {
            mLog("更新包解压失败，原因是：" . $package['message'], Mirages_Utils::LOG_LEVEL_ERROR);
            return $package;
        }

        $package = self::copyUsrDirectory($package);
        if (array_key_exists('status', $package) && array_key_exists('message', $package)) {
            mLog("复制主题 usr 目录失败，原因是：" . $package['message'], Mirages_Utils::LOG_LEVEL_ERROR);
            return $package;
        }

        $package = self::changeToNewVersion($package);
        if (array_key_exists('status', $package) && array_key_exists('message', $package)) {
            mLog("切换至新版本失败，原因是：" . $package['message'], Mirages_Utils::LOG_LEVEL_ERROR);
            return $package;
        }

        mLog("更新成功，已更新至 {$package['version']}", Mirages_Utils::LOG_LEVEL_SUCCESS);

        $message = array(
            'status' => 'success',
            'message' => "更新成功，已更新至 {$package['version']}",
        );
        return $message;
    }

    private static function changeToNewVersion($package) {
        mLog('准备切换主题和插件至新版本');
        $themeCurrent = self::$themeDir;
        if (empty($themeCurrent)) {
            mLog('不可能走得到这里啊*2', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题未启用或主题/插件已损坏，请启用主题或重新安装主题/插件',
            );
            return $message;
        }
        if (!array_key_exists('theme_extract_path', $package) || empty($package['theme_extract_path'])) {
            mLog('主题解压目录不存在，这肯定是个 BUG', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题解压目录不存在',
            );
            return $message;
        }
        $themeNew = $package['theme_extract_path'];

        $pluginCurrent = __DIR__;
        if (empty($pluginCurrent)) {
            mLog('见鬼了', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '见鬼了',
            );
            return $message;
        }
        if (!array_key_exists('plugin_extract_path', $package) || empty($package['plugin_extract_path'])) {
            mLog('插件解压目录不存在，这肯定是个 BUG', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '插件解压目录不存在',
            );
            return $message;
        }
        $pluginNew = $package['plugin_extract_path'];

        $themeOld = Mirages_Utils::themesDir() . "Mirages-Old";
        $pluginOld = Mirages_Utils::pluginsDir() . "Mirages-Old";

        if (file_exists($themeOld)) {
            Mirages_Utils::deleteDirectory($themeOld);
        }
        if (file_exists($pluginOld)) {
            Mirages_Utils::deleteDirectory($pluginOld);
        }

        mLog('正在切换主题至新版本');
        rename($themeCurrent, $themeOld);
        rename($themeNew, $themeCurrent);
        mLog('已切换插件至新版本');
        mLog('正在切换插件至新版本');
        rename($pluginCurrent, $pluginOld);
        rename($pluginNew, $pluginCurrent);

//        if (file_exists($themeOld)) {
//            MiragesPluginUtils::deleteDirectory($themeOld);
//        }
        if (file_exists($pluginOld)) {
            Mirages_Utils::deleteDirectory($pluginOld);
        }

        Mirages_Utils::deleteDirectory($package['theme_file']);
        Mirages_Utils::deleteDirectory($package['plugin_file']);
        Mirages_Utils::deleteDirectory($themeCurrent . DIRECTORY_SEPARATOR . 'Package.json');
        Mirages_Utils::deleteDirectory($pluginCurrent . DIRECTORY_SEPARATOR . 'Package.json');

        return $package;
    }

    private static function copyUsrDirectory($package) {
        mLog('准备复制主题 usr 目录');
        $themeDir = self::$themeDir;
        if (empty($themeDir)) {
            mLog('不可能走得到这里啊', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题未启用或主题/插件已损坏，请启用主题或重新安装主题/插件',
            );
            return $message;
        }
        if (!file_exists($themeDir . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Mirages.php')) {
            mLog('这看上去并不是 Mirages 主题', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题未启用或主题/插件已损坏，请启用主题或重新安装主题/插件',
            );
            return $message;
        }
        $usrDir = $themeDir . DIRECTORY_SEPARATOR . 'usr';
        if (!file_exists($usrDir)) {
            mLog('这看上去并不是 Mirages 授权版本，或者版本等级太低。', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题未启用或主题/插件已损坏，请启用主题或重新安装主题/插件',
            );
            return $message;
        }
        if (!array_key_exists('theme_extract_path', $package) || empty($package['theme_extract_path'])) {
            mLog('主题解压目录不存在，这肯定是个 BUG', Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题解压目录不存在',
            );
            return $message;
        }
        mLog('正在复制主题 usr 目录');
        $destination = $package['theme_extract_path'] . DIRECTORY_SEPARATOR . 'usr';
        Mirages_Utils::copyDirectory($usrDir, $destination);
        if (array_key_exists('pattern', $package) && array_key_exists('content', $package)) {
            foreach (
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($package['theme_extract_path'], RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST) as $item
            ) {
                if ($item->isFile()) {
                    $ext = @pathinfo($item->getPathName())['extension'];
                    if (in_array(strtolower($ext), array('php', 'css'))) {
                        $content = file_get_contents($item);
                        if (strpos($content, $package['pattern'])) {
                            $content = str_replace($package['pattern'], $package['content'], $content);
                            file_put_contents($item, $content);
                        }
                    }
                }
            }
        }
        mLog('复制主题 usr 目录完成', Mirages_Utils::LOG_LEVEL_SUCCESS);
        return $package;
    }

    private static function unPackage($package) {
        //unzip theme and check
        mLog("开始解压主题更新包...");
        $file = $package['theme_file'];
        $zip = new ZipArchive();
        $res = $zip->open($file);
        if ($res === TRUE) {
            $destination = Mirages_Utils::themesDir() . 'Mirages-latest';
            if (file_exists($destination)) {
                mLog("目录 {$destination} 已存在，正在删除...");
                Mirages_Utils::deleteDirectory($destination);
                mLog("目录 {$destination} 删除成功");
            }
            mLog("正在解压主题更新包...");
            Mirages_Utils::extractZipFiles($zip, $destination);
            mLog("主题更新包解压完成", Mirages_Utils::LOG_LEVEL_SUCCESS);
            $zip->close();
            mLog("正在校验主题解压目录...");
            if (!self::validateExtractedPackage($destination, $package['version'])) {
                mLog("主题解压目录 Hash 值校验失败", Mirages_Utils::LOG_LEVEL_ERROR);
                $message = array(
                    'status' => 'error',
                    'message' => '主题解压目录 Hash 值校验失败，请重试。',
                );
                return $message;
            }
            $package['theme_extract_path'] = $destination;
        } else {
            mLog("主题更新包解压失败。", Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '主题更新包解压失败。',
            );
            return $message;
        }



        //unzip plugin and check
        mLog("开始解压插件更新包...");
        $file = $package['plugin_file'];
        $zip = new ZipArchive();

        $res = $zip->open($file);
        if ($res === TRUE) {
            $destination = Mirages_Utils::pluginsDir() . 'Mirages-latest';
            if (file_exists($destination)) {
                mLog("目录 {$destination} 已存在，正在删除...");
                Mirages_Utils::deleteDirectory($destination);
                mLog("目录 {$destination} 删除成功");
            }
            mLog("正在解压插件更新包...");

            Mirages_Utils::extractZipFiles($zip, $destination);

            mLog("插件更新包解压完成", Mirages_Utils::LOG_LEVEL_SUCCESS);
            $zip->close();

            mLog("正在校验插件解压目录...");
            if (!self::validateExtractedPackage($destination, $package['version'])) {
                mLog("插件解压目录 Hash 值校验失败", Mirages_Utils::LOG_LEVEL_ERROR);
                $message = array(
                    'status' => 'error',
                    'message' => '插件解压目录 Hash 值校验失败，请重试。',
                );
                return $message;
            }
            $package['plugin_extract_path'] = $destination;
        } else {
            mLog("插件更新包解压失败。", Mirages_Utils::LOG_LEVEL_ERROR);
            $message = array(
                'status' => 'error',
                'message' => '插件更新包解压失败。',
            );
            return $message;
        }

        return $package;
    }

    private static function validateExtractedPackage($directory, $version) {
        mLog("开始校验解压目录：{$directory}");
        $validateFile = $directory . DIRECTORY_SEPARATOR . 'Package.json';
        if (!file_exists($validateFile)) {
            mLog("校验失败，Hash 字典不存在", Mirages_Utils::LOG_LEVEL_ERROR);
            return false;
        }
        $md5Dictionary = json_decode(file_get_contents($validateFile), true);
        if (is_array($md5Dictionary) && array_key_exists('version', $md5Dictionary) && $md5Dictionary['version'] == $version && array_key_exists('md5', $md5Dictionary)) {
            $md5Dictionary = $md5Dictionary['md5'];
        } else {
            mLog("校验失败，Hash 字典加载失败", Mirages_Utils::LOG_LEVEL_ERROR);
            return false;
        }
        while(list($path, $md5) = each($md5Dictionary)) {
            mLog("正在校验文件：{$path}");
            $file = $directory . str_replace('/', DIRECTORY_SEPARATOR, $path);
            if (!file_exists($file)) {
                mLog("校验失败，文件：{$file} 不存在", Mirages_Utils::LOG_LEVEL_ERROR);
                return false;
            }
            if ($md5 != strtoupper(md5_file($file))) {
                mLog("校验失败，文件：{$file} MD5 值不正确", Mirages_Utils::LOG_LEVEL_ERROR);
                return false;
            }
        }
        mLog("目录 {$directory} 校验成功", Mirages_Utils::LOG_LEVEL_SUCCESS);
        return true;
    }

    private static function downloadPackage($package) {

        // download theme and check
        $file = Mirages_Utils::themesDir() . 'Mirages-' . $package['version'] . '.zip';
        $needDownload = true;
        if (file_exists($file)) {
            $md5 = strtoupper(md5_file($file));
            if ($md5 == $package['theme_md5']) {
                mLog("主题更新包已存在，无需重新下载", Mirages_Utils::LOG_LEVEL_SUCCESS);
                $needDownload = false;
            }
        }
        if ($needDownload) {
            mLog("开始下载主题更新包...");
            $ret = Mirages_Utils::download($package['theme_download_url'], $file);
            if (!$ret) {
                mLog("未能下载更新包，fopen 和 curl 均不可用", Mirages_Utils::LOG_LEVEL_ERROR);
                $message = array(
                    'status' => 'error',
                    'message' => '未能下载更新包，fopen 和 curl 均不可用，请检查服务器配置后重试。',
                );
                return $message;
            }
            mLog("主题更新包下载完成，正在校验更新包...");
            $md5 = strtoupper(md5_file($file));
            if ($md5 != $package['theme_md5']) {
                mLog("校验失败。文件不可用。原因是：" . $md5 . ' != ' . $package['theme_md5'], Mirages_Utils::LOG_LEVEL_ERROR);
                mLog("已下载的主题更新包大小为: " . @filesize($file), Mirages_Utils::LOG_LEVEL_ERROR);
                $message = array(
                    'status' => 'error',
                    'message' => '主题更新包下载不完整，请稍后重试。',
                );
                return $message;
            }
            mLog("校验成功。主题更新包下载成功。", Mirages_Utils::LOG_LEVEL_SUCCESS);
        }
        $package['theme_file'] = $file;

        // download theme and check
        $file = Mirages_Utils::pluginsDir() . 'Mirages-' . $package['version'] . '.zip';
        $needDownload = true;
        if (file_exists($file)) {
            $md5 = strtoupper(md5_file($file));
            if ($md5 == $package['plugin_md5']) {
                mLog("插件更新包已存在，无需重新下载", Mirages_Utils::LOG_LEVEL_SUCCESS);
                $needDownload = false;
            }
        }
        if ($needDownload) {
            mLog("开始下载插件更新包...");
            $ret = Mirages_Utils::download($package['plugin_download_url'], $file);
            if (!$ret) {
                mLog("未能下载更新包，fopen 和 curl 均不可用", Mirages_Utils::LOG_LEVEL_ERROR);
                $message = array(
                    'status' => 'error',
                    'message' => '未能下载更新包，fopen 和 curl 均不可用，请检查服务器配置后重试。',
                );
                return $message;
            }
            mLog("插件更新包下载完成，正在校验更新包...");
            $md5 = strtoupper(md5_file($file));
            if ($md5 != $package['plugin_md5']) {
                mLog("校验失败。文件不可用。原因是：" . $md5 . ' != ' . $package['plugin_md5'], Mirages_Utils::LOG_LEVEL_ERROR);
                mLog("已下载的插件更新包大小为: " . @filesize($file), Mirages_Utils::LOG_LEVEL_ERROR);
                $message = array(
                    'status' => 'error',
                    'message' => '插件更新包下载不完整，请稍后重试。',
                );
                return $message;
            }
            mLog("校验成功。插件更新包下载成功。", Mirages_Utils::LOG_LEVEL_SUCCESS);
        }
        $package['plugin_file'] = $file;
        $package['download_status'] = 'success';
        return $package;
    }

    private static function loadUpdatePackage() {
        $result = array();
        $params = self::loadRequestParams();
        if (empty($params)) {
            $result['status'] = 'error';
            $result['message'] = "主题未启用或主题/插件已损坏，请启用主题或重新安装主题/插件";
            mLog("获取请求参数失败", Mirages_Utils::LOG_LEVEL_ERROR);
            return $result;
        }
        $requestURL = "https://api.hran.me/mirages/requestUpdate";
        mLog("正在获取更新下载地址...");
        $json = Mirages_Utils::httpRequest($requestURL, 'POST', $params);
        if (is_array($json) && !empty($json) && array_key_exists('error_no', $json)) {
            $status = $json['error_no'];
            if ($status == 1) {
                $package = @$json['package'];
                if (!empty($package) && array_key_exists('version', $package)
                                     && array_key_exists('theme_download_url', $package)
                                     && array_key_exists('theme_md5', $package)
                                     && array_key_exists('plugin_download_url', $package)
                                     && array_key_exists('plugin_md5', $package)) {
                    $result = $package;
                    mLog("下载地址获取成功", Mirages_Utils::LOG_LEVEL_SUCCESS);
                } else {
                    $message = "未检测到新版本";
                    if (!(strtolower($params['acceptDev']) == 'true' || intval($params['acceptDev']) > 0)) {
                        $message .= "，若需要更新至开发版，请将插件设置修改为接受开发版后重试。";
                    }
                    $result['status'] = 'success';
                    $result['message'] = $message;
                }
            } elseif ($status == 403) {
                $result['status'] = 'error';
                $result['hideLog'] = 1;
                $result['message'] = @$json['message'];
            }
        } else {
            $result['status'] = 'error';
            $result['hideLog'] = 1;
            $result['message'] = "请求失败， 请稍后重试";
        }
        return $result;
    }

    private static function loadRequestParams() {
        $params = array();
        $themeName = Helper::options()->theme;
        if (!empty($themeName)) {
            if (!class_exists("Mirages")) {
                $file = Helper::options()->themeFile($themeName, '/lib/Mirages.php');
                if (file_exists($file)) {
                    if (!class_exists("Mirages")) {
                        require_once($file);
                    }
                } else {
                    mLog("请求参数获取失败：找不到 Mirages 类", Mirages_Utils::LOG_LEVEL_ERROR);
                    return $params;
                }
            }
            if (!class_exists("License")) {
                $file = Helper::options()->themeFile($themeName, '/usr/License.php');
                if (file_exists($file)) {
                    if (!class_exists("License")) {
                        require_once($file);
                    }
                } else {
                    mLog("请求参数获取失败：找不到 License 类", Mirages_Utils::LOG_LEVEL_ERROR);
                    return $params;
                }
            }
        } else {
            mLog("请求参数获取失败：无效的主题名", Mirages_Utils::LOG_LEVEL_ERROR);
            return $params;
        }

        self::$currentVersion = Mirages::$version;
        $params['current'] = Mirages::$version;
        $params['name'] = License::$name;
        $params['pk'] = License::$pk;
        self::$themeDir = Helper::options()->themeFile($themeName, '');
        $pluginOptions = Typecho_Widget::widget('Widget_Options')->plugin("Mirages");
        $params['acceptDev'] = $pluginOptions->acceptDev;
        mLog("当前版本为：{$params['current']}", Mirages_Utils::LOG_LEVEL_SUCCESS);
        mLog("接收开发版：{$params['acceptDev']}", Mirages_Utils::LOG_LEVEL_SUCCESS);

        return $params;
    }
}