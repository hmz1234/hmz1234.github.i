<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * TOC.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class TOC {
    const MAX_LEVEL = 3;

    private static $id = 1;
    private static $tree = array();
    private static function parseCallback($match) {
        $parent = &self::$tree;

        $html = $match[0];
        $n = $match[1];
        $menu = array(
            'num' => $n,
            'title' => trim( strip_tags( $html ) ),
            'id' => 'menu_index_' . self::$id,
            'sub' => array()
        );
        $current = array();
        if( $parent ) {
            $current = &$parent[ count( $parent ) - 1 ];
        }
        if( ! $parent || ( isset( $current['num'] ) && $n <= $current['num'] ) ) {
            $parent[] = $menu;
        } else {
            while( is_array( $current[ 'sub' ] ) ) {
                if( $current['num'] == $n - 1 ) {
                    $current[ 'sub' ][] = $menu;
                    break;
                }
                elseif( $current['num'] < $n && $current[ 'sub' ] ) {
                    $current = &$current['sub'][ count( $current['sub'] ) - 1 ];
                }
                else {
                    for( $i = 0; $i < $n - $current['num']; $i++ ) {
                        $current['sub'][] = array(
                            'num' => $current['num'] + 1,
                            'sub' => array()
                        );
                        $current = &$current['sub'][0];
                    }
                    $current['sub'][] = $menu;
                    break;
                }
            }
        }
        self::$id++;
        return "<span id=\"{$menu['id']}\" name=\"{$menu['id']}\"></span>" . $html;
    }

    private static function buildMenuHtml($tree, $level, $include = true) {
        $menuHtml = '';
        if ($level > self::MAX_LEVEL) {
            return $menuHtml;
        }
        foreach( $tree as $menu ) {
            if( ! isset( $menu['id'] ) && $menu['sub'] ) {
                $menuHtml .= self::buildMenuHtml( $menu['sub'], $level, false);
            } elseif( $menu['sub'] ) {
                $menuHtml .= "<li class=\"index-menu-item\"><a data-scroll class=\"index-menu-link\" href=\"#{$menu['id']}\" title=\"{$menu['title']}\"><span class=\"menu-content\">{$menu['title']}</span></a>" . self::buildMenuHtml( $menu['sub'], $level + 1) . "</li>";
            } else {
                $menuHtml .= "<li class=\"index-menu-item\"><a data-scroll class=\"index-menu-link\" href=\"#{$menu['id']}\" title=\"{$menu['title']}\"><span class=\"menu-content\">{$menu['title']}</span></a></li>";
            }
        }
        if( $include ) {
            $menuHtml = '<ul class="index-menu-list">' . $menuHtml . '</ul>';
        }
        return $menuHtml;
    }

    public static function buildIndex($html) {
        self::$id = 1;
        self::$tree = array();
        $html = preg_replace_callback('/<h([1-6])[^>]*>.*?<\/h\1>/s', array('TOC', 'parseCallback'), $html);
        return $html;
    }

    public static function output($html) {
        if (empty(self::$tree)) {
            preg_replace_callback( '/<h([1-6])[^>]*>.*?<\/h\1>/s', array( 'TOC', 'parseCallback' ), $html );
        }
        $menuTree = '<div class="index-menu">' . self::buildMenuHtml(self::$tree, 1) . '</div>';
        return $menuTree;
    }
}