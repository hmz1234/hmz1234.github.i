<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Utils.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class Utils {
    public static function hasValue($field) {
        if (is_numeric($field)) {
            return true;
        }
        return strlen($field) > 0;
    }

    public static function isTrue($field, $key = NULL) {
        if (is_array($field) && !empty($key)) {
            return in_array($key, $field);
        }
        return $field > 0 || strtolower($field) == 'true';
    }
    public static function isFalse($field, $key = NULL) {
        return !self::isTrue($field, $key);
    }

    public static function startsWith($haystack, $needle) {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        } else {
            return !substr_compare($haystack, $needle, 0, strlen($needle));
        }
    }
    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
    public static function hex2RGBColor($hex, $alpha = 1) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        if ($alpha >= 1 || $alpha < 0) {
            return "rgb({$r}, {$g}, {$b})";
        }
        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }
    public static function isHexColor($hex) {
        if (strlen($hex) != 7 && strlen($hex) != 4) {
            return false;
        }
        if (!preg_match('/^#[0-9a-fA-F]+$/i', $hex)) {
            return false;
        }
        return true;
    }
    public static function isPjax() {
        if (array_key_exists('HTTP_X_PJAX', $_SERVER) && $_SERVER['HTTP_X_PJAX']) {
            return true;
        }
        return false;
    }

    public static function fromExternalLinks() {
        $referer = @$_SERVER['HTTP_REFERER'];
        if (empty($referer)) {
            return true;
        }

        $host = @$_SERVER['HTTP_HOST'];
        if (empty($host)) {
            return true;
        }
        if(strpos($referer, $host) === false) {
            return true;
        }
        return false;
    }

    public static function timeDiff($time) {
        $time = intval($time);
        if ($time < time() - 86400 * 30) {
            return true;
        }
        return false;
    }

    public static function replaceStaticPath($html) {
        return str_replace("{{%STATIC_PATH%}}", STATIC_PATH, $html);
    }

    public static function replaceCDNOptimizeLink($url) {
        if (empty($url)) {
            return $url;
        }

        if (!(Mirages::$options->cdnDomain__hasValue && Mirages::$options->devMode__isFalse)) {
            return $url;
        }
        return preg_replace('/^'.preg_quote(rtrim(Mirages::$options->siteUrl, '/'), '/').'/', rtrim(Mirages::$options->cdnDomain, '/'), $url, 1);
    }

    public static function mapStaticObject($object) {
        $arr = array();
        try {
            if (is_string($object) && strpos($object, "=") !== FALSE) {
                $object = base64_decode($object);
            }
            if (class_exists($object)) {
                $ref = new ReflectionClass($object);
                $arr = $ref->getStaticProperties();
            }
        } catch (ReflectionException $e) {
        }

        return $arr;
    }

    public static function httpBuildUrl(array $parts) {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
            (isset($parts['user']) ? "{$parts['user']}" : '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            (isset($parts['host']) ? "{$parts['host']}" : '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            (isset($parts['path']) ? "{$parts['path']}" : '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }

    public static function injectCustomCSS() {
        $dir = dirname(__DIR__) . "/usr/css";
        if (!file_exists($dir)) {
            return "";
        }
        $it  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(1);

        $customCSS = "";
        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filename = $fileInfo->getFilename();
                if (self::endsWith(strtolower($filename), '.css')) {
                    $customCSS .= "<link rel=\"stylesheet\" href=\"" . STATIC_PATH . "usr/css/{$filename}?v=" . STATIC_VERSION . "\">\n";
                }
            }
        }
        return $customCSS;
    }

    public static function injectCustomJS() {
        $dir = dirname(__DIR__) . "/usr/js";
        if (!file_exists($dir)) {
            return "";
        }
        $it  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(1);

        $customJS = "";
        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filename = $fileInfo->getFilename();
                if (self::endsWith(strtolower($filename), '.js')) {
                    $customJS .= "<script src=\"" . STATIC_PATH . "usr/js/{$filename}?v=" . STATIC_VERSION . "\" type=\"text/javascript\"></script>\n";
                }
            }
        }
        return $customJS;
    }

    public static function getThumbnailImageAddOn($cdnType, $width=64) {
        if (Mirages::pluginAvailable(102)) {
            if ($cdnType == Mirages_Plugin::CDN_TYPE_OTHERS || $cdnType == Mirages_Plugin::CDN_TYPE_LOCAL) {
                return "";
            }
            if ($cdnType == Mirages_Plugin::CDN_TYPE_UPYUN && method_exists("Mirages_Plugin", "UPYunSplitTag")) {
                return Mirages_Plugin::UPYunSplitTag() . "/max/{$width}";
            }
        }

        if (Mirages::pluginAvailable(103)) {
            if ($cdnType == Mirages_Plugin::CDN_TYPE_ALIYUN_OSS) {
                return "?x-oss-process=image/resize,w_{$width}/quality,q_75";
            }
        }

        return "?imageView2/2/w/{$width}/q/75";
    }

    public static function toCode($code) {
        return "<code>{$code}</code>";
    }

    public static function formatDate($time, $format) {
        $date = new Typecho_Date($time);

        if (strtoupper($format) == 'NATURAL') {
            return self::naturalDate($date->timeStamp);
        }
        return $date->format($format);
    }

    public static function naturalDate($from) {
        $now = new Typecho_Date();
        $now = $now->timeStamp;
        $between = $now - $from;
        if ($between > 31536000) {
            return date(I18n::dateFormat(), $from);
        } else if ($between > 0 && $between < 172800                                // 如果是昨天
            && (date('z', $from) + 1 == date('z', $now)                             // 在同一年的情况
                || date('z', $from) + 1 == date('L') + 365 + date('z', $now))) {    // 跨年的情况
            return _mt('昨天 %s', date('H:i', $from));
        } else if ($between == 0) {
            return _mt('刚刚');
        }
        $f = array(
            '31536000' => '%d 年前',
            '2592000' => '%d 个月前',
            '604800' => '%d 星期前',
            '86400' => '%d 天前',
            '3600' => '%d 小时前',
            '60' => '%d 分钟前',
            '1' => '%d 秒前',
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($between / (int)$k)) {
                if ($c == 1) {
                    return _mt(sprintf($v, $c));
                }
                return _mt($v, $c);
            }
        }
        return "";
    }

    public static function postTitleClass($title) {
        $short = 8;
        $long = 25;
        if (preg_match('/[a-zA-Z0-9\-\s\|\(\)\[\]\{\}\/\.\,\?\!]+/i', $title)) {
            $short = 18;
            $long = 60;
        }
        if (mb_strlen($title) <= $short) {
            return " post-title-short";
        } else if (mb_strlen($title) >= $long) {
            return " post-title-long";
        }
        return "";
    }
}