<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
require_once("zh.php");
/**
 * zh_cn.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class Lang_zh_CN extends Lang_zh {

    /**
     * @return string 返回语言名称
     */
    public function name() {
        return "中文(简体)";
    }
}