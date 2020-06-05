<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * en.php
 * Author     : Hran
 * Date       : 2016/12/11
 * Version    :
 * Description:
 */
class Lang_en extends Lang {

    /**
     * @return string 返回语言名称
     */
    public function name() {
        return "English";
    }

    /**
     * @return array 返回包含翻译文本的数组
     */
    public function translated() {
        return array(
            // Post
            '阅读: %d' => 'Read: %d',
            '编辑' => 'Edit',
            '标签: ' => 'Tags: ',
            '无' => 'None',
            '返回文章列表' => 'Archives',
            '文章二维码' => 'QR Code',
            '打赏' => 'Tip',

            // 页眉 head
            '分类 %s 下的文章' => 'Category: %s',
            '包含关键字 %s 的文章' => 'Contains %s',
            '标签 %s 下的文章' => 'Tags: %s',
            '%s 发布的文章' => '%s Posts',
            
            '当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="%s">升级你的浏览器</a>.' => 'It\'s Strongly Recommended to <a href="%s">Upgrade Your Browser</a> to <strong>GET a Better Experience</strong>.',

            // 评论 Comments
            '评论' => 'Comment',
            '暂无评论' => 'No Comment',
            '1 条评论' => '1 Comment',
            '仅有一条评论' => '1 Comment',
            '%d 条评论' => '%d Comments',
            '已有 %d 条评论' => '%d Comments',
            '评论列表' => 'Comment Lists',
            '添加新评论' => 'Leave a Comment',
            '提交评论' => 'Submit',
            '称呼' => 'Name',
            '电子邮件' => 'Email',
            '网站' => 'Website',
            '回复' => 'Reply',
            '在这里输入你的评论...' => 'Input your comment here...',
            '<strong>不接收</strong>回复邮件通知' => '<strong>DO NOT</strong> Send me reply emails',

            //参数分别为: 用户链接、用户名、登出链接
            '登录为 <a href="%s">%s</a>. <a href="%s" no-pjax title="Logout">退出 &raquo;</a>' => 'Login As: <a href="%s">%s</a>. <a href="%s" no-pjax title="Logout">Logout &raquo;</a>',

            '登录为' => 'Login As: ',
            '退出' => 'Logout',

            // 列表 List
            '阅读全文' => 'Read More',
            '没有找到内容' => 'No Content Here',

            // 归档 Archives
            '标签云' => 'Tags',
            '时间归档' => 'Archives',
            '归档' => 'Archives',

            // 404页面 404
            '页面未找到' => 'Page Not Found',

            // 侧边栏 Side Menu
            '搜索...' => 'Search...',
            '控制台' => 'Dashboard',
            '首页' => 'Home',
            '关于' => 'About',
            '友情链接' => 'Links',
            '文章分类' => 'Category',
            '分类' => 'Category',
            '夜间模式' => 'Night Mode',
            '日间模式' => 'Daytime Mode',
            '自动模式' => 'Auto Mode',
            '文章目录' => 'Catalog',
            'RSS 订阅' => 'RSS',
            '更多' => 'More',


            // 页脚 Footer
            '本页链接的二维码' => 'QR Code for this page',
            '打赏二维码' => 'Tipping QR Code',
            '上一篇: ' => 'Prev: ',
            '下一篇: ' => 'Next: ',
            '上一页' => 'Prev',
            '下一页' => 'Next',
            '没有了' => 'None',
            '无标签' => 'No Tags',
            '最后编辑于: %s' => 'Last Modified: %s',

            // 日期格式化'
            '%d 年前'   => '%d Years Ago',
            '%d 个月前' => '%d Months Ago',
            '%d 星期前' => '%d Weeks Ago',
            '%d 天前'   => '%d Days Ago',
            '%d 小时前' => '%d Hours Ago',
            '%d 分钟前' => '%d Minutes Ago',
            '%d 秒前'   => '%d Seconds Ago',
            '1 年前'   => '1 Year Ago',
            '1 个月前' => '1 Month Ago',
            '1 星期前' => '1 Week Ago',
            '1 天前'   => '1 Day Ago',
            '1 小时前' => '1 Hour Ago',
            '1 分钟前' => '1 Minute Ago',
            '1 秒前'   => '1 Second Ago',
            '昨天 %s'   => 'Yesterday %s',

        );
    }

    /**
     * @return string 返回日期的格式化字符串
     */
    public function dateFormat() {
        return "F j, Y";
    }
}