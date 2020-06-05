<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Content.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class Content {
    public static function excerpt($archive, $more = true) {
        if ($more === false) {
            return $archive->content;
        }
        if (false !== strpos($archive->text, '<!--more-->')) {
            return $archive->excerpt . "<p class=\"more btn btn-grey\"><a href=\"{$archive->permalink}\" title=\"{$archive->title}\">{$more}</a></p>";
        }
        $index = strpos($archive->content, '</p>');
        if ($index === false) {
            return $archive->content;
        } else {
            return substr($archive->content, 0, $index + 4) . "<p class=\"more btn btn-grey\"><a href=\"{$archive->permalink}\" title=\"{$archive->title}\">{$more}</a></p>";
        }
    }

    public static function listAllPosts($widget, $format) {
//        try {
//
//        } catch (Exception $e) {
//            if (Mirages::$options->devMode__isTrue) {
//                var_dump($e);
//            }
//            $posts = self::listAllPostsFromWidget($widget);
//        }
        $routerType = 'post';
        $router = Typecho_Router::get($routerType);
        if (!empty($router) && in_array('directory', $router['params'])) {
            $posts = self::listAllPostsFromWidget($widget);
        } else {
            $db = Typecho_Db::get();
            $select = $db
                ->select(array(
                    'table.contents.cid' => 'cid',
                    'MIN(table.contents.title)' => 'title',
                    'MIN(table.contents.slug)' => 'slug',
                    'MIN(table.contents.created)' => 'created',
                    'MIN(table.contents.password)' => 'password',
                    'MIN(table.metas.slug)' => 'category'
                ))
                ->from('table.contents')
                ->join('table.relationships', 'table.contents.cid = table.relationships.cid')
                ->join('table.metas', 'table.relationships.mid = table.metas.mid')
                ->where('table.metas.type = ?', 'category')
                ->where('table.contents.type = ?', 'post')
                ->where('table.contents.status = ?', 'publish')
                ->group('table.contents.cid')
                ->order('table.contents.created', Typecho_Db::SORT_DESC);

            $contents = $db->fetchAll($select);
            $posts = array();
            foreach ($contents as $content) {
                $content['slug'] = urlencode($content['slug']);
                $content['category'] = urlencode($content['category']);
                $content['date'] = new Typecho_Date($content['created']);

                /** 生成日期 */
                $content['year'] = $content['date']->year;
                $content['month'] = $content['date']->month;
                $content['day'] = $content['date']->day;

                /** 生成静态路径 */
                $content['pathinfo'] = Typecho_Router::url($routerType, $content);

                /** 生成静态链接 */
                $content['permalink'] = Typecho_Common::url($content['pathinfo'], Mirages::$options->index);

                if (!empty($content['password'])) {
                    $content['title'] = _t('此内容被密码保护');
                }
                $posts[] = $content;
            }
        }
        return self::groupPosts($posts, $format);
    }

    private static function groupPosts($posts, $format) {
        $ret = array();
        foreach ($posts as $post) {
            $ret[date($format, $post['created'])][$post['created']] = $post;
        }
        return $ret;
    }

    private static function listAllPostsFromWidget($widget) {
        $widget->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);
        $posts = array();
        while ($archives->next()) {
            $post = array();
            $post['created'] = $archives->created;
            $post['permalink'] = $archives->permalink;
            $post['title'] = $archives->title;
            $posts[] = $post;
        }
        return $posts;
    }


    /**
     * 显示下一个内容的标题链接
     *
     * @access public
     * @param string $format 格式
     * @param string $default 如果没有下一篇,显示的默认文字
     * @param array $custom 定制化样式
     * @return void
     */
    public static function theNext($archive, $created, $gmtTime, $type, $default)
    {
        $db = Typecho_Db::get();
        $content = $db->fetchRow($archive->select()->where('table.contents.created > ? AND table.contents.created < ?',
            $created, $gmtTime)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $type)
            ->where('table.contents.password IS NULL')
            ->order('table.contents.created', Typecho_Db::SORT_ASC)
            ->limit(1));

        if ($content) {
            $content = $archive->filter($content);
            $default = array(
                'title' => NULL,
                'tagClass' => NULL
            );
            $custom = array_merge($default, array());
            extract($custom);

            $linkText = empty($title) ? $content['title'] : $title;
            $linkText = Mirages::parseBiaoqing($linkText);
            $linkClass = empty($tagClass) ? '' : 'class="' . $tagClass . '" ';
            $linkInnerHtml = '<span class="post-near-span"><span class="prev-t no-user-select color-main">' . _mt('下一篇: ') . '</span><br><span>' . $linkText . '</span></span>';
            $link = '<a ' . $linkClass . 'href="' . $content['permalink'] . '" title="' . $content['title'] . '">' . $linkInnerHtml . '</a>';

            printf('%s', $link);
        } else {
            $linkInnerHtml = '<a href="javascript:void(0)" class="post-near-no-content"><span class="post-near-span"><span class="prev-t no-user-select color-main">' . _mt('下一篇: ') . '</span><br><span>' . $default . '</span></span></a>';
            echo Mirages::parseBiaoqing($linkInnerHtml);
        }
    }

    /**
     * 显示上一个内容的标题链接
     *
     * @access public
     * @param string $format 格式
     * @param string $default 如果没有上一篇,显示的默认文字
     * @param array $custom 定制化样式
     * @return void
     */
    public static function thePrev($archive, $created, $type, $default)
    {
        $db = Typecho_Db::get();

        $content = $db->fetchRow($archive->select()->where('table.contents.created < ?', $created)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $type)
            ->where('table.contents.password IS NULL')
            ->order('table.contents.created', Typecho_Db::SORT_DESC)
            ->limit(1));

        if ($content) {
            $content = $archive->filter($content);
            $default = array(
                'title' => NULL,
                'tagClass' => NULL
            );
            $custom = array_merge($default, array());
            extract($custom);

            $linkText = empty($title) ? $content['title'] : $title;
            $linkText = Mirages::parseBiaoqing($linkText);
            $linkClass = empty($tagClass) ? '' : 'class="' . $tagClass . '" ';
            $linkInnerHtml = '<span class="post-near-span"><span class="prev-t no-user-select color-main">' . _mt('上一篇: ') . '</span><br><span>' . $linkText . '</span></span>';
            $link = '<a ' . $linkClass . 'href="' . $content['permalink'] . '" title="' . $content['title'] . '">' . $linkInnerHtml . '</a>';

            printf('%s', $link);
        } else {
            $linkInnerHtml = '<a href="javascript:void(0)" class="post-near-no-content"><span class="post-near-span"><span class="prev-t no-user-select color-main">' . _mt('上一篇: ') . '</span><br><span>' . $default . '</span></span></a>';
            echo Mirages::parseBiaoqing($linkInnerHtml);
        }
    }


    public static function parse($content) {
        $content = TOC::buildIndex($content);
        return $content;
    }

    public static function category($categories, $link = true) {
        if ($categories) {
            $result = array();

            foreach ($categories as $category) {
                if ($link) {
                    $result[] = '<a href="' . $category['permalink'] . '">' . _mt($category['name']) . '</a>';
                } else {
                    $result[] = _mt($category['name']);
                }
            }

            echo implode(', ', $result);
        } else {
            _me('无分类');
        }
    }

    public static function randomBackgroundColor($cid) {
        $backgroundColors = array(
            array("#EB3349", "#F45C43"),
            array("#DD5E89", "#F7BB97"),
            array("#4CB8C4", "#3CD3AD"),
            array("#A6FFCB", "#12D8FA", "#1FA2FF"),
            array("#FF512F", "#F09819"),
            array("#1A2980", "#26D0CE"),
//            array("#FF512F", "#DD2476"),
            array("#F09819", "#EDDE5D"),
            array("#403B4A", "#E7E9BB"),
            array("#003973", "#E5E5BE"),
            array("#348F50", "#56B4D3"),
            array("#EDE574", "#E1F5C4"),
            array("#16A085", "#F4D03F"),
            array("#314755", "#26a0da"),
            array("#e65c00", "#F9D423"),
            array("#2193b0", "#6dd5ed"),
            array("#ec008c", "#fc6767"),
            array("#1488CC", "#2B32B2"),
            array("#ffe259", "#ffa751"),
            array("#11998e", "#38ef7d"),
            array("#00b09b", "#96c93d"),
            array("#3C3B3F", "#605C3C"),
            array("#fc4a1a", "#f7b733"),
        );
//        $total = 0;
//        $md5Array = @unpack("c*", md5($title, true));
//        if (is_array($md5Array) && !empty($md5Array)) {
//            foreach ($md5Array as $char) {
//                $total += $char;
//            }
//        }
        $cid = intval($cid);
        $index = abs($cid) % count($backgroundColors);
        $array =  @$backgroundColors[$index];
//        if (count($array) == 2) {
//            if ($cid % 2 == 0) {
//                $array[0] = $array[1];
//            } else {
//                $array[1] = $array[0];
//            }
//        }
        return $array;
    }

    public static function loadFirstImageFromArticle($content) {
        if (preg_match('/<img.*?data-src\=\"((http|https)\:\/\/[^>\"]+?\.(jpg|jpeg|bmp|webp|png))\"[^>]*>/i', $content, $matches)) {
            return $matches[1];
        }
        if (preg_match('/<img.*?src\=\"((http|https)\:\/\/[^>\"]+?\.(jpg|jpeg|bmp|webp|png))\"[^>]*>/i', $content, $matches)) {
            return $matches[1];
        }
        return FALSE;
    }

    public static function loadDefaultThumbnailForArticle($cid) {
        $defaultThumbs = self::exportThumbnails();
        if (count($defaultThumbs) > 0) {
            $index = abs(intval($cid)) % count($defaultThumbs);
            $thumb = $defaultThumbs[$index];
        } else {
            $thumb = NULL;
        }
        return $thumb;
    }

    private static function exportThumbnails() {
        $results = array();
        $thumbnails = Mirages::$options->defaultThumbnails;
        if (!empty($thumbnails)) {
            $thumbnails = mb_split("\n", $thumbnails);
            foreach ($thumbnails as $thumbnail) {
                $thumbnail = trim($thumbnail);
                if ($thumbnail == 'DEFAULT_THUMBS' || $thumbnail == 'DEFAULT_THUMBNAILS') {
//                    $defaults = self::exportDefaultThumbnails();
//                    $results = array_merge($results, $defaults);
                } else {
                    $thumbnail = str_replace('{THEME_ROOT}', THEME_MIRAGES_ROOT_DIR, $thumbnail);
                    $results[] = $thumbnail;
                }
            }
        } else {
//            $results = self::exportDefaultThumbnails();
        }
//        $usrDefaults = self::exportUsrDefaultThumbnails();
//        $results = array_merge($results, $usrDefaults);
//        $results = array_unique($results);
        return $results;
    }

    private static function exportUsrDefaultThumbnails() {
        $dir = THEME_MIRAGES_ROOT_DIR . 'usr/default_thumbs/';
        if (!file_exists($dir)) {
            return array();
        }
        $defaultThumbs = array();
        $it  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(1);
        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filename = $fileInfo->getFilename();
                if (preg_match("/^.*\\.(jpg|jpeg|bmp|png|gif)$/i", $filename)) {
                    $defaultThumbs[] = STATIC_PATH . 'usr/default_thumbs/' . $filename;
                }
            }
        }
        return $defaultThumbs;
    }
    private static function exportDefaultThumbnails() {
        $dir = THEME_MIRAGES_ROOT_DIR . 'images/default_thumbs/';
        if (!file_exists($dir)) {
            return array();
        }
        $defaultThumbs = array();
        $it  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(1);
        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filename = $fileInfo->getFilename();
                if (preg_match("/.*(\\d+)\\.(jpg|jpeg|bmp|png|gif)$/i", $filename)) {
                    $defaultThumbs[] = STATIC_PATH . 'images/default_thumbs/' . $filename;
                }
            }
        }
        return $defaultThumbs;
    }

    public static function detectBodyClassForPJAX($needle, $element) {
        if (strpos(Mirages::$options->bodyClass, $needle) !== FALSE) {
            echo <<<JS
            if (!{$element}.classList.contains('{$needle}')) {
                {$element}.classList.add('{$needle}');
            }
JS;
        } else {
            echo <<<JS
            if ({$element}.classList.contains('{$needle}')) {
                {$element}.classList.remove('{$needle}');
            }
JS;
        }
    }

    public static function cssUrl($filename) {
        if (Mirages::$options->devMode__isTrue) {
            $url = STATIC_PATH . "css/" . $filename . "?v=" . time();
        } else {
            $url = STATIC_PATH . "css/" . Mirages::$version . "/" . $filename;
        }
        return $url;
    }

    public static function jsUrl($filename) {
        if (Mirages::$options->devMode__isTrue) {
            $url = STATIC_PATH . "js/" . $filename . "?v=" . time();
        } else {
            $url = STATIC_PATH . "js/" . Mirages::$version . "/" . $filename;
        }
        return $url;
    }

    public static function exportDNSPrefetch() {
        $defaultDomain = array(
        );
        $customDomain = Mirages::$options->dnsPrefetch;
        if (!empty($customDomain)) {
            $customDomain = mb_split("\n", $customDomain);
            $defaultDomain = array_merge($defaultDomain, $customDomain);
            $defaultDomain = array_unique($defaultDomain);
        }
        $html = "<meta http-equiv=\"x-dns-prefetch-control\" content=\"on\">\n";
        foreach ($defaultDomain as $domain) {
            $domain = trim($domain, " \t\n\r\0\x0B/");
            if (!empty($domain)) {
                $html .= "<link rel=\"dns-prefetch\" href=\"//{$domain}\" />\n";
            }
        }
        return $html;
    }

    public static function exportGeneratorRules(Widget_Archive $archive) {
        $rules = array(
            "generator",
        );
        if (PJAX_ENABLED || COMMENT_SYSTEM !== Mirages_Const::COMMENT_SYSTEM_EMBED) {
            $rules[] = "commentReply";
            $rules[] = "antiSpam";
        }
        if ($archive->is('post') || $archive->is("page")) {
            $rules[] = "description=" . Typecho_Common::subStr($archive->getDescription(), 0, 100, "...");
        }
        return join("&", $rules);
    }

    public static function exportHeader(Widget_Archive $archive) {
        $options = Mirages::$options;
        $html = "";
        if ($archive->is("post") || $archive->is("page")) {
            $description = Typecho_Common::subStr($archive->getDescription(), 0, 100, "...");
            $createTime = date('c', $archive->created);
            $modifyTime = date('c', $archive->modified);
            if ((defined("PJAX_ENABLED") && !PJAX_ENABLED)) {
                $html .= "<link rel=\"canonical\" href=\"{$archive->permalink}\" />";
            }
            $html .= <<<EOF
<meta property="og:title" content="{$archive->title}" />
<meta property="og:site_name" content="{$options->title}" />
<meta property="og:type" content="article" />
<meta property="og:description" content="{$description}" />
<meta property="og:url" content="{$archive->permalink}" />
<meta property="article:published_time" content="{$createTime}" />
<meta property="article:modified_time" content="{$modifyTime}" />
<meta name="twitter:title" content="{$archive->title}" />
<meta name="twitter:description" content="{$description}" />
EOF;
            if (empty($options->banner)) {
                $html .= "<meta name=\"twitter:card\" content=\"summary\" />";
            } else {
                $html .= <<<EOF
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="{$options->banner}" />
<meta property="og:image" content="{$options->banner}" />
EOF;
            }
        } else if ($archive->is("index")) {
            $html .= <<<EOF
<meta property="og:title" content="{$options->title}" />
<meta property="og:site_name" content="{$options->title}" />
<meta property="og:type" content="website" />
<meta property="og:description" content="{$options->description}" />
<meta property="og:url" content="{$options->rootUrl}" />
EOF;
            if ($options->banner__hasValue) {
                $banner = $options->banner . Utils::getThumbnailImageAddOn(Mirages::$options->bannerCDNType, 640);
                $html .= "<meta property=\"og:image\" content=\"{$banner}\" />";
            }
        }

        return $html;

    }

    public static function outputCommentNumTag(Widget_Archive $archive) {
        $parsed = parse_url($archive->permalink);
        $html = "";
        if(COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_DISQUS) {
            $html = "<span class=\"comments\"><a href=\"{$archive->permalink}#disqus_thread\" data-disqus-identifier=\"{$parsed['path']}\">" . _mt('评论') . "</a></span>";
        } elseif (COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_GENTIE) {
            $html = "<span class=\"comments\"><a href=\"{$archive->permalink}#comments\" class=\"\"><span lang=\"join-count\" class=\"join-count\" data-sourceId=\"{$archive->cid}\"></span>" . _mt('评论') . "</a></span>";
        } elseif (COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_EMBED) {
            echo "<span class=\"comments\"><a href=\"{$archive->permalink}#comments\">";
            $archive->commentsNum(_mt('评论'), _mt('1 条评论'), _mt('%d 条评论'));
            echo "</a></span>";
        }

        echo $html;
    }

    public static function outputCommentJS(Widget_Archive $archive, $security) {
        if (COMMENT_SYSTEM !== Mirages_Const::COMMENT_SYSTEM_EMBED) {
            return;
        }
        $header = "";
        if (Mirages::$options->commentsThreaded && $archive->is('single')) {
            $header .= "<script type=\"text/javascript\">
(function () {
    window.TypechoComment = {
        dom : function (id) {
            return document.getElementById(id);
        },
    
        create : function (tag, attr) {
            var el = document.createElement(tag);
        
            for (var key in attr) {
                el.setAttribute(key, attr[key]);
            }
        
            return el;
        },

        reply : function (cid, coid) {
            var comment = this.dom(cid), parent = comment.parentNode,
                response = this.dom('" . $archive->respondId . "'), input = this.dom('comment-parent'),
                form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                textarea = response.getElementsByTagName('textarea')[0];

            if (null == input) {
                input = this.create('input', {
                    'type' : 'hidden',
                    'name' : 'parent',
                    'id'   : 'comment-parent'
                });

                form.appendChild(input);
            }

            input.setAttribute('value', coid);

            if (null == this.dom('comment-form-place-holder')) {
                var holder = this.create('div', {
                    'id' : 'comment-form-place-holder'
                });

                response.parentNode.insertBefore(holder, response);
            }

            comment.appendChild(response);
            this.dom('cancel-comment-reply-link').style.display = '';

            if (null != textarea && 'text' == textarea.name) {
                textarea.focus();
            }

            return false;
        },

        cancelReply : function () {
            var response = this.dom('{$archive->respondId}'),
            holder = this.dom('comment-form-place-holder'), input = this.dom('comment-parent');

            if (null != input) {
                input.parentNode.removeChild(input);
            }

            if (null == holder) {
                return true;
            }

            this.dom('cancel-comment-reply-link').style.display = 'none';
            holder.parentNode.insertBefore(response, holder);
            return false;
        }
    };
})();
</script>
";
        }
        if (Mirages::$options->commentsAntiSpam && $archive->is('single')) {
            $requestURL = $archive->request->getRequestUrl();
            $requestURL = str_replace('&_pjax=%23body', '', $requestURL);
            $requestURL = str_replace('?_pjax=%23body', '', $requestURL);
            $requestURL = str_replace('_pjax=%23body', '', $requestURL);
//            $requestURL = parse_url($requestURL);
//            unset($requestURL['query']);
//            $requestURL = Utils::httpBuildUrl($requestURL);
            $header .= "<script type=\"text/javascript\">
var registCommentEvent = function() {
    var event = document.addEventListener ? {
        add: 'addEventListener',
        focus: 'focus',
        load: 'DOMContentLoaded'
    } : {
        add: 'attachEvent',
        focus: 'onfocus',
        load: 'onload'
    };
    var r = document.getElementById('{$archive->respondId}');
        
    if (null != r) {
        var forms = r.getElementsByTagName('form');
        if (forms.length > 0) {
            var f = forms[0], textarea = f.getElementsByTagName('textarea')[0], added = false;

            if (null != textarea && 'text' == textarea.name) {
                textarea[event.add](event.focus, function () {
                    if (!added) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        
                        input.name = '_';
                            input.value = " . Typecho_Common::shuffleScriptVar(
                    $security->getToken($requestURL)) . "
                        //console.log('".$requestURL."');
                        //console.log('".$archive->request->getRequestUrl()."');
                        f.appendChild(input);
                        ";
            if (PJAX_ENABLED) {
                $header .= "
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'checkReferer';
                        input.value = 'false';
                        
                        f.appendChild(input);
                        ";
            }
            $header .= "

                        added = true;
                    }
                });
            }
        }
    }
};
</script>";
        }
        echo $header;
    }
}