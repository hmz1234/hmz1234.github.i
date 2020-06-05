<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * zh_cn.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class Lang_zh extends Lang {

    /**
     * @return string 返回语言名称
     */
    public function name() {
        return "中文";
    }

    /**
     * @return array 返回包含翻译文本的数组
     */
    public function translated() {
        // 仅输出默认值
        return array(
            // Side Menu
            'Archives' => '归档',
            'Links' => '友情链接',
            'Music' => '音乐',
            'About' => '关于',
            'About Me' => '关于我',
        );
    }

    /**
     * @return string 返回日期的格式化字符串
     */
    public function dateFormat() {
        return "Y 年 m 月 d 日";
    }
}