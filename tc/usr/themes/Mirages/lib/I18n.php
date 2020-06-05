<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * I18n.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class I18n {
    private static $instance;

    private $locale;

    private $loadedLangs;

    private $dateFormat;

    private $fontFamily;

    private $serifFontFamily;

    private $isSettingsPage = false;

    private $loaded = false;

    private $loadSucceed = false;

    private function loadLangIfNotLoad() {
        if (!$this->loaded) {
            if (empty($this->locale)) {
                $this->locale = $this->acceptLocale();
            }
            $this->loadedLangs = array();
            $this->loadLang($this->locale);
            $this->loaded = true;
        }
    }

    private function setLocale($locale) {
        $this->locale = $locale;
    }

    private function setIsSettingsPage($is) {
        $this->isSettingsPage = $is;
    }

    private function acceptLocale() {
        $accepts = mb_split(',', @$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $acceptLocales = array();
        foreach ($accepts as $lang) {
            $q = "1.0";
            if (preg_match('/^([a-zA-Z0-9\-\_]+);q=([0-9\.]+)$/i', $lang, $matched)) {
                $q = $matched[2];
                $acceptLocales[$q] = $matched[1];
            } elseif (preg_match('/^([a-zA-Z0-9\-\_]+)$/i', $lang, $matched)) {
                $acceptLocales[$q] = $lang;
            } else {
                continue;
            }

            $locale = str_replace('-', '_', $acceptLocales[$q]);

            $parts = mb_split('_', $locale, 2);
            if (count($parts) == 2) {
                $locale = strtolower($parts[0]) . '_' . strtoupper($parts[1]);
            }
            
            $acceptLocales[$q] = $locale;
        }

        $langs = I18n_Options::listLocaleFiles();

        $keys = array_keys($acceptLocales);
        rsort($keys, SORT_NUMERIC);

        foreach ($keys as $key) {
            $locale = $acceptLocales[$key];
            if (in_array($locale, $langs)) {
                $resultLocale = $locale;
                break;
            } else {
                $parts = mb_split('_', $locale, 2);
                if (in_array($parts[0], $langs)) {
                    $resultLocale = $parts[0];
                    break;
                }
            }
        }

        if (empty($resultLocale)) {
            $resultLocale = "en";
        }

        return $resultLocale;
    }

    private function loadLang($locale) {
        if ($this->isSettingsPage) {
            $this->doLoadLang($locale, "lang/settings");
        } else {
            $this->doLoadLang($locale, "lang");
            $this->doLoadLang($locale, "usr/lang");
        }
    }
    
    private function doLoadLang($locale, $path) {
        $file = dirname(__DIR__) . "/{$path}/{$locale}.php";
        if (file_exists($file)) {
            $className = str_replace('/', '_', $path) . '_' . $locale;
            if (!class_exists($className)) {
                require_once($file);
            }
            if (class_exists($className)) {
                $lang = new $className();
            } else {
                $lang = null;
            }
            if (is_subclass_of($lang, "Lang")) {
                if (method_exists($lang, "translated")) {
                    $translated = $lang->translated();
                    if (is_array($translated)) {
                        $this->loadedLangs = array_merge($this->loadedLangs, $translated);
                        $this->loadSucceed = true;
                    }
                }
                // 日期格式
                $format = null;
                if (method_exists($lang, "dateFormat")) {
                    $format = $lang->dateFormat();
                }
                if (empty($format)) {
                    $format = Mirages::$options->postDateFormat;
                }
                $this->dateFormat = $format;

                // 非衬线体
                $fontFamily = null;
                if (method_exists($lang, "fontFamily")) {
                    $fontFamily = $lang->fontFamily();
                }
                if (empty($fontFamily)) {
                    $fontFamily = Mirages::$options->localeFontFamily;
                }
                $this->fontFamily = $fontFamily;

                // 衬线体
                $serifFontFamily = null;
                if (method_exists($lang, "serifFontFamily")) {
                    $serifFontFamily = $lang->serifFontFamily();
                }
                if (empty($serifFontFamily)) {
                    $serifFontFamily = Mirages::$options->localeSerifFontFamily;
                }
                $this->serifFontFamily = $serifFontFamily;
            }
        }
    }

    private function doTranslate($string) {
        $this->loadLangIfNotLoad();
        if (array_key_exists($string, $this->loadedLangs)) {
            $translated = $this->loadedLangs[$string];
        } else {
            $translated = _t($string);
        }
        return $translated;
    }

    private static function Instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function setLang($locale) {
        $instance = self::Instance();
        $instance->setLocale($locale);
    }

    public static function loadAsSettingsPage($is) {
        $instance = self::Instance();
        $instance->setIsSettingsPage($is);
    }

    public static function translate($string) {
        $instance = self::Instance();
        return $instance->doTranslate($string);
    }

    public static function dateFormat() {
        $instance = self::Instance();
        return $instance->dateFormat;
    }

    public static function fontFamily() {
        $instance = self::Instance();
        return empty($instance->fontFamily) ? '' : $instance->fontFamily . ', ';
    }

    public static function serifFontFamily() {
        $instance = self::Instance();
        return empty($instance->serifFontFamily) ? '' : $instance->serifFontFamily . ', ';
    }
}

class I18n_Options {
    private static function doListLocaleFiles($path) {
        $dir = dirname(__DIR__) . "/{$path}";
        if (!file_exists($dir)) {
            return array();
        }
        $it  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(1);

        $langs = array();
        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filename = $fileInfo->getFilename();
                $filename = str_replace('.php', '', $filename);
                $langs[] = $filename;
            }
        }
        return $langs;
    }

    private static function doListLangs($path) {
        $dir = dirname(__DIR__) . "/{$path}";
        if (!file_exists($dir)) {
            return array();
        }
        $it  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(1);

        $langs = array();

        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile()) {
                $file = $fileInfo->getPathname();
                $filename = $fileInfo->getFilename();
                $filename = str_replace('.php', '', $filename);
                if (is_readable($file)) {
                    $className = str_replace('/', '_', $path) . '_' . $filename;
                    if (!class_exists($className)) {
                        require_once($file);
                    }
                    if (class_exists($className)) {
                        $lang = new $className();
                    } else {
                        $lang = null;
                    }

                    if (is_subclass_of($lang, "Lang")) {
                        if (method_exists($lang, "locale")) {
                            $locale = $lang->locale();
                            if (!empty($locale) && method_exists($lang, "name")) {
                                $name = $lang->name();
                                if (!empty($name)) {
                                    $langs[$locale] = $name;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $langs;
    }

    public static function listLangs() {
        $lang = array('auto'=>'Auto');
        $langs = array_merge($lang, self::doListLangs("lang"), self::doListLangs("usr/lang"));
        return $langs;
    }

    public static function listLocaleFiles($isSettingsPage = false) {
        $lang = array();
        if ($isSettingsPage) {
            $langs = array_merge($lang, self::doListLocaleFiles("lang/settings"));
        } else {
            $langs = array_merge($lang, self::doListLocaleFiles("lang"), self::doListLocaleFiles("usr/lang"));
        }
        return array_unique($langs);
    }
}

function _mt($string) {
    if (func_num_args() <= 1) {
        return I18n::translate($string);
    } else {
        $args = func_get_args();
        array_shift($args);
        return vsprintf(I18n::translate($string), $args);
    }
}

function _me() {
    $args = func_get_args();
    echo call_user_func_array('_mt', $args);
}

function _mn($single, $plural, $number) {
    return $number > 1 ? I18n::translate($plural) : I18n::translate($single);
}