<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Raw Html
 *
 * @package custom
 */

/**
 * @var $this Widget_Archive
 */
$html = $this->row['text'];
if(Utils::startsWith($html, "<!--markdown-->")){
    $html = substr($html, 15);
}
echo $html;