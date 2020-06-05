<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Language.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
abstract class Lang {
    
    public function locale() {
        $c = get_called_class();
        $c = str_replace('Usr_', '', $c);
        $c = str_replace('Lang_', '', $c);
        $c = str_replace('Settings_', '', $c);
        return $c;
    }

    /**
     * @return string 返回语言名称
     */
    public abstract function name();

    /**
     * @return string 返回日期的格式化字符串
     */
    public abstract function dateFormat();

    /**
     * @return string 返回默认的**非衬线体** font family(不包含英文字体)
     * 返回空值则使用默认字体
     * 另外，你也可以使用 <code>Mirages::$options->localeFontFamily</code> 来获取默认的语言字体, 默认字体包含了一些简体中文等常用语言支持
     * 例如：
     * return "'WTF Font', " . Mirages::$options->localeFontFamily;
     */
    public function fontFamily() {
        return null;
    }

        /**
     * @return string 返回默认的**衬线体** font family(不包含英文字体)
     * 返回空值则使用默认字体
     * 另外，你也可以使用 <code>Mirages::$options->localeFontFamily</code> 来获取默认的语言字体, 默认字体包含了一些简体中文等常用语言支持
     * 例如：
     * return "'WTF Font', " . Mirages::$options->localeFontFamily;
     */
    public function serifFontFamily() {
        return null;
    }



    /**
     * @return array 返回包含翻译文本的数组
     */
    public abstract function translated();
}