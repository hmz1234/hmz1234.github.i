<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * EnvCheck.php
 * Author     : Hran
 * Date       : 2019/09/19
 * Version    :
 * Description:
 */
class Mirages_EnvCheck {

    public static function checkEnv() {
        Mirages_Utils::log("开始执行环境检查...");

        self::doCheckPhpVersion();
        self::doCheckMbString();
        self::doCheckWritable();
        self::doCheckZipArchive();
        self::doCheckGetImageSize();

        Mirages_Utils::log("环境检查完成");

    }

    private static function doCheckPhpVersion() {
        if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            mLog("【必须】PHP版本: 当前版本: " . PHP_VERSION . ", 正常", Mirages_Utils::LOG_LEVEL_SUCCESS);
        } else {
            mLog("【必须】PHP版本: 当前版本: " . PHP_VERSION . ", PHP 版本过低，使用主题时可能会报错，建议更新到 7.0 或以上版本。", Mirages_Utils::LOG_LEVEL_ERROR);
        }
    }

    private static function doCheckMbString() {
        if (function_exists('mb_split')) {
            mLog('【必须】PHP - mb_string 扩展: 正常', Mirages_Utils::LOG_LEVEL_SUCCESS);
        } else {
            mLog('【必须】PHP - mb_string 扩展: 没有检测到 mb_string 扩展，使用主题或插件时将报错！', Mirages_Utils::LOG_LEVEL_ERROR);
        }
    }

    private static function doCheckWritable() {
        if (@Mirages_Utils::is_really_writable(Mirages_Utils::themesDir())) {
            mLog('主题目录读写权限: 正常', Mirages_Utils::LOG_LEVEL_SUCCESS);
        } else {
            mLog('主题目录读写权限：主题目录不可写，将无法使用主题自动更新功能', Mirages_Utils::LOG_LEVEL_WARNING);
        }
        if (@Mirages_Utils::is_really_writable(Mirages_Utils::pluginsDir())) {
            mLog('插件目录读写权限: 正常', Mirages_Utils::LOG_LEVEL_SUCCESS);
        } else {
            mLog('插件目录读写权限：插件目录不可写，将无法使用主题自动更新功能', Mirages_Utils::LOG_LEVEL_WARNING);
        }

        $uploadsPath = defined('__TYPECHO_UPLOAD_DIR__') ? __TYPECHO_UPLOAD_DIR__ : "/usr/uploads";
        $uploadsPath = __TYPECHO_ROOT_DIR__ . rtrim($uploadsPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (@Mirages_Utils::is_really_writable($uploadsPath)) {
            mLog('上传目录读写权限: 正常', Mirages_Utils::LOG_LEVEL_SUCCESS);
        } else {
            mLog('上传目录读写权限：上传目录不可写，使用 Typecho 的附件上传功能时可能会出错', Mirages_Utils::LOG_LEVEL_WARNING);
        }
    }

    private static function doCheckZipArchive() {
        if (class_exists("ZipArchive")) {
            mLog('PHP - Zip 扩展: 正常', Mirages_Utils::LOG_LEVEL_SUCCESS);
        } else {
            mLog('PHP - Zip 扩展: 没有检测到 Zip 扩展，将无法使用主题自动更新功能', Mirages_Utils::LOG_LEVEL_WARNING);
        }
    }

    private static function doCheckGetImageSize() {
        if (function_exists('getimagesize')) {
            $themeName = Helper::options()->theme;
            $image = Helper::options()->themeFile($themeName, 'screenshot.png');
            if (!file_exists($image)) {
                mLog('PHP - GD 扩展: 主题缩略图: ' . $image, Mirages_Utils::LOG_LEVEL_WARNING);
                mLog('PHP - GD 扩展: 主题缩略图不存在，无法检测该功能', Mirages_Utils::LOG_LEVEL_WARNING);
                return;
            }
            $imageSize = @getimagesize($image);
            if (!empty($imageSize) && count($imageSize) >= 2 && $imageSize[0] > 0 && $imageSize[1] > 0) {
                mLog('PHP - GD 扩展: 正常', Mirages_Utils::LOG_LEVEL_SUCCESS);
            } else {
                mLog('PHP - GD 扩展: getimagesize: ' . @json_encode($imageSize), Mirages_Utils::LOG_LEVEL_WARNING);
                mLog('PHP - GD 扩展: getimagesize 函数未能正确返回图片尺寸信息，在使用云存储优化功能时，文章内图片（仅包含上传到博客主机的图片）的图片加载动画功能将不可用。', Mirages_Utils::LOG_LEVEL_WARNING);
            }
        } else {
            mLog('PHP - GD 扩展: 没有检测到 getimagesize 函数，在使用云存储优化功能时，文章内图片（仅包含上传到博客主机的图片）的图片加载动画功能将不可用。', Mirages_Utils::LOG_LEVEL_WARNING);
        }
    }
}