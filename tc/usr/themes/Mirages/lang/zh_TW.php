<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * en.php
 * Author     : Hran
 * Date       : 2016/12/11
 * Version    :
 * Description:
 */
class Lang_zh_TW extends Lang {

    /**
     * @return string 返回语言名称
     */
    public function name() {
        return "中文(繁體)";
    }

    /**
     * @return array 返回包含翻译文本的数组
     */
    public function translated() {
        return array(
            // Post
            '阅读: %d' => '閱讀: %d',
            '编辑' => '編輯',
            '标签: ' => '標籤: ',
            '无' => '無',
            '返回文章列表' => '返回文章清單',
            '文章二维码' => '文章二維碼',
            '打赏' => '打賞',

            // 页眉 head
            '分类 %s 下的文章' => '分類 %s 下的文章',
            '包含关键字 %s 的文章' => '包含關鍵字 %s 的文章',
            '标签 %s 下的文章' => '標籤 %s 下的文章',
            '%s 发布的文章' => '%s 發佈的文章',

            '当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="%s">升级你的浏览器</a>.' => '當前網頁 <strong>不支援</strong> 你正在使用的瀏覽器. 為了正常的訪問, 請 <a href="%s">升級你的瀏覽器</a>.',

            // 评论 Comments
            '评论' => '評論',
            '暂无评论' => '暫無評論',
            '1 条评论' => '1 條評論',
            '仅有一条评论' => '僅有一條評論',
            '%d 条评论' => '%d 條評論',
            '已有 %d 条评论' => '已有 %d 條評論',
            '评论列表' => '評論清單',
            '添加新评论' => '添加新評論',
            '提交评论' => '提交評論',
            '称呼' => '稱呼',
            '电子邮件' => '電子郵件',
            '网站' => '網站',
            '回复' => '回復',
            '在这里输入你的评论...' => '在這裡輸入你的評論...',
            //参数分别为: 用户链接、用户名、登出链接
            '登录为 <a href="%s">%s</a>. <a href="%s" no-pjax title="Logout">退出 &raquo;</a>' => '登錄為: <a href="%s">%s</a>. <a href="%s" no-pjax title="退出">退出 &raquo;</a>',
            '登录为' => '登錄為',
            '退出' => '退出',
            '<strong>不接收</strong>回复邮件通知' => '<strong>不接收</strong>回復郵件',

            // 列表 List
            '阅读全文' => '閱讀全文',
            '没有找到内容' => '沒有找到內容',

            // 归档 Archives
            '标签云' => '標籤雲',
            '时间归档' => '時間歸檔',

            // 404页面 404
            '页面未找到' => '頁面未找到',

            // 侧边栏 Side Menu
            '搜索...' => '搜索...',
            '控制台' => '主控台',
            '首页' => '首頁',
            '关于' => '關於',
            '文章分类' => '文章分類',
            '分类' => '分類',
            '夜间模式' => '夜間模式',
            '日间模式' => '日間模式',
            '自动模式' => '自動模式',
            '文章目录' => '文章目錄',
            'RSS 订阅' => 'RSS 訂閱',
            '更多' => '更多',


            // 页脚 Footer
            '本页链接的二维码' => '本頁連結的二維碼',
            '打赏二维码' => '打賞二維碼',
            '上一篇: ' => '上一篇: ',
            '下一篇: ' => '下一篇: ',
            '上一页' => '上一頁',
            '下一页' => '下一頁',
            '没有了' => '沒有了',
            '无标签' => '無標籤',
            '最后编辑于: %s' => '最後編輯與: %s',


            // 日期格式化'
            '%d 年前'   => '%d 年前',
            '%d 个月前' => '%d 個月前',
            '%d 星期前' => '%d 星期前',
            '%d 天前'   => '%d 天前',
            '%d 小时前' => '%d 小時前',
            '%d 分钟前' => '%d 分鐘前',
            '%d 秒前'   => '%d 秒前',
            '1 年前'   => '1 年前',
            '1 个月前' => '1 個月前',
            '1 星期前' => '1 星期前',
            '1 天前'   => '1 天前',
            '1 小时前' => '1 小時前',
            '1 分钟前' => '1 分鐘前',
            '1 秒前'   => '1 秒前',
        );
    }

    /**
     * @return string 返回日期的格式化字符串
     */
    public function dateFormat() {
        return "Y-m-d";
    }

    /**
     * @return string 返回默认的 font family(不包含英文字体)
     * 返回空值则使用默认字体
     * 另外，你也可以使用 <code>Mirages::$options->localeFontFamily</code> 来获取默认的语言字体, 默认字体包含了一些简体中文等常用语言支持
     * 例如：
     * return "'WTF Font', " . Mirages::$options->localeFontFamily;
     */
    public function fontFamily() {
        return "'PingFang TC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei'";
    }

    /**
     * @return string 返回默认的**衬线体** font family(不包含英文字体)
     * 返回空值则使用默认字体
     * 另外，你也可以使用 <code>Mirages::$options->localeFontFamily</code> 来获取默认的语言字体, 默认字体包含了一些简体中文等常用语言支持
     * 例如：
     * return "'WTF Font', " . Mirages::$options->localeFontFamily;
     */
    public function serifFontFamily() {
        return "'Noto Serif CJK TC', 'Noto Serif CJK', 'Noto Serif TC', 'Source Han Serif TC', 'Source Han Serif', 'source-han-serif-tc', 'source-han-serif-sc', 'Noto Serif SC', 'SongTi TC', '宋体'";
    }


}