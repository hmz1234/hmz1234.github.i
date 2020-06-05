<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * MiragesPluginUtils.php
 * Author     : Hran
 * Date       : 2017/2/18
 * Version    :
 * Description:
 */
class Mirages_Utils {
    private static $userAgent = "";

    //region Utils

    const LOG_LEVEL_INFO = 0;
    const LOG_LEVEL_SUCCESS = 1;
    const LOG_LEVEL_WARNING = 2;
    const LOG_LEVEL_ERROR = 3;

    static function hasValue($field) {
        if (is_numeric($field)) {
            return true;
        }
        return strlen($field) > 0;
    }

    static function isTrue($field, $key = NULL) {
        if (is_array($field) && !empty($key)) {
            return in_array($key, $field);
        }
        return $field > 0 || strtolower($field) == 'true';
    }
    static function isFalse($field, $key = NULL) {
        return !self::isTrue($field, $key);
    }

    static function log($msg, $level = NULL) {
        if ($level == self::LOG_LEVEL_ERROR) {
            $style = 'style="color: red;"';
        } elseif ($level == self::LOG_LEVEL_SUCCESS) {
            $style = 'style="color: #393;"';
        } elseif ($level == self::LOG_LEVEL_WARNING) {
            $style = 'style="color: #F93;"';
        } else {
            $style = '';
        }
        echo  date('[Y-m-d H:i:s] ') . "<span {$style}>" . $msg . "</span><br>\n";
    }

    static function httpParseQuery($query) {
        $parameters = array();
        $queryParts = explode('&', $query);
        foreach ($queryParts as $queryPart) {
            $keyValue = explode('=', $queryPart, 2);
            if (empty($keyValue[1])) {
                $keyValue[1] = "true";
            }
            $parameters[$keyValue[0]] = $keyValue[1];
        }
        return $parameters;
    }
    static function httpBuildUrl(array $parts) {
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

    static function parseHTMLTagAttribute($text) {
        $attributes = array();
        $pattern = '#(?(DEFINE)
            (?<name>[a-zA-Z][a-zA-Z0-9-:]*)
            (?<value_double>"[^"]+")
            (?<value_single>\'[^\']+\')
            (?<value_none>[^\s>]+)
            (?<value>((?&value_double)|(?&value_single)|(?&value_none)))
        )
        (?<n>(?&name))(\s*=\s*(?<v>(?&value)))?#xs';
        if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $attributes[$match['n']] = isset($match['v'])
                    ? trim($match['v'], '\'"')
                    : "true";
            }
        }
        return $attributes;
    }
    static function buildHTMLTagAttribute(array $attrs) {
        $output = "";
        foreach ($attrs as $key => $value) {
            $value = self::trimAttributeValue($value);
            $output .= " {$key}=\"{$value}\"";
        }
        return $output;
    }

    static function trimAttributeValue($value) {
        return trim($value, " '\"\t\n\r\0\x0B");
    }

    // arbitrary compressions in empty is allowed in PHP 5.5+ only
    static function emptyAttribute($value) {
        $value = self::trimAttributeValue($value);
        return empty($value);
    }

    static function startsWith($haystack, $needle) {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        } else {
            return !substr_compare($haystack, $needle, 0, strlen($needle));
        }
    }
    static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }


    static function httpRequest($url, $method = 'GET', $params = NULL){
        $client = Typecho_Http_Client::get();
        if (empty($client)) {
            return false;
        }
        switch($method){
            case 'GET':
                $client->setTimeout(10)
                    ->setHeader('Referer', isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '')
                    ->setHeader('User-Agent', self::$userAgent);
                if (!empty($params)) {
                    $client->setQuery($params);
                }
                break;
            case 'POST':
                $client->setTimeout(10)
                    ->setData($params)
                    ->setHeader('Referer', isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '')
                    ->setHeader('User-Agent', self::$userAgent);
                if (!empty($params)) {
                    $client->setData($params);
                }
                break;
            default:
                return false;
        }
        try{
            $client->send($url);
        }catch(Exception $e){
            return false;
        }
        $jsonStr = $client->getResponseBody();
        $json = json_decode($jsonStr, true);
        return $json === null ? $jsonStr : $json;
    }

    static function httpRequestRaw($url, $method = 'GET', $params = NULL) {
        $client = Typecho_Http_Client::get();
        if (empty($client)) {
            return false;
        }
        switch($method){
            case 'GET':
                $client->setTimeout(10)
                    ->setHeader('User-Agent', self::$userAgent);
                if (!empty($params)) {
                    $client->setQuery($params);
                }
                break;
            case 'POST':
                $client->setTimeout(10)
                    ->setData($params)
                    ->setHeader('User-Agent', self::$userAgent);
                if (!empty($params)) {
                    $client->setData($params);
                }
                break;
            default:
                return false;
        }
        try{
            $client->send($url);
        }catch(Exception $e){
            return false;
        }
        return $client->getResponseBody();
    }

    static function themesDir() {
        return __TYPECHO_ROOT_DIR__ . __TYPECHO_THEME_DIR__ . DIRECTORY_SEPARATOR;
    }
    static function pluginsDir() {
        return __TYPECHO_ROOT_DIR__ . __TYPECHO_PLUGIN_DIR__ . DIRECTORY_SEPARATOR;
    }

    static function deleteDirectory($path) {
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    }

                    else if (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        }

        else if ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
    }

    static function copyDirectory($source, $destination) {
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }

    /**
     * 解压 Zip 文件
     * 该方法存在的意义是 $zipArchive->extractTo()方法不支持中文路径
     * @param ZipArchive $zipArchive
     * @param $destination
     */
    static function extractZipFiles(ZipArchive $zipArchive, $destination) {
        if (!file_exists($destination)) {
            @mkdir($destination, 0777, true);
        }
        $fileName = $zipArchive->filename;
        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $name = $zipArchive->getNameIndex($i);
            $from = "zip://{$fileName}#{$name}";
            $to = $destination . DIRECTORY_SEPARATOR . $name;
            if (self::endsWith($to, DIRECTORY_SEPARATOR)) {
                if (!file_exists($to)) {
                    @mkdir($to, 0777, true);
                }
            } else {
                $dir = dirname($to);
                if (!file_exists($dir)) {
                    @mkdir($dir, 0777, true);
                }
                copy($from, $to);
            }
        }
    }

    static function zipDirectory($directory, $destination) {
        $rootPath = realpath($directory);

        $zip = new ZipArchive();
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    /**
     * Tests for file writability
     *
     * is_writable() returns TRUE on Windows servers when you really can't write to
     * the file, based on the read-only attribute. is_writable() is also unreliable
     * on Unix servers if safe_mode is on.
     *
     * @link    https://bugs.php.net/bug.php?id=54709
     * @param   string
     * @return  bool
     */
    static function is_really_writable($file) {
        // Create cache directory if not exists
        if (!file_exists($file)) {
            mkdir($file, 0755);
        }
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (version_compare(PHP_VERSION, '5.4', '>=') OR ! ini_get('safe_mode'))) {
            return is_writable($file);
        }
        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file)) {
            $file = rtrim($file, '/').'/'.md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE)
            {
                return FALSE;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        }
        elseif ( ! is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

    static function download($url, $downloadTo) {
        if (self::isFopenAvailable()) {
            file_put_contents($downloadTo, fopen($url, 'r'));
            return true;
        } elseif (self::isCurlAvailable()) {
            $ch = curl_init();
            $fp = fopen($downloadTo, "w");
            curl_setopt_array($ch, array(
                CURLOPT_FILE    => $fp,
                CURLOPT_TIMEOUT =>  28800, // timeout seconds
                CURLOPT_URL     => $url,
            ));
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    private static function isFopenAvailable() {
        if (function_exists("ini_get")) {
            return false;
        }
        $fopenAvailable = ini_get('allow_url_fopen');
        if ($fopenAvailable == 0 || strtolower($fopenAvailable) == 'off' || !$fopenAvailable) {
            return false;
        }
        return true;
    }

    private static function isCurlAvailable() {
        return function_exists('curl_version');
    }

    //endregion
}

function mLog($msg, $level = NULL) {
    Mirages_Utils::log($msg, $level);
}