<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * en.php
 * Author     : Hran
 * Date       : 2016/12/11
 * Version    :
 * Description:
 */
class Lang_Settings_en extends Lang {

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
            '开启' => 'On',
            '关闭' => 'Off',
            '是' => 'Yes',
            '否' => 'No',
            '不使用' => 'Disable',
            '使用' => 'Enable',
            '请仅输入数字' => 'Please input number only',



            // function
            '主题外观' => 'Appearance',
            '网站公告' => 'Blog Notice',
            '配图及图像管理' => 'Images',
            '自定义主色调' => 'Custom Main Color',
            '文章与阅读' => 'Post And Pages',
            '导航栏' => 'Navbar',
            '评论 - 自带' => 'Comment - Embed',
            '评论 - Disqus' => 'Comment - Disqus',
            'Markdown 扩展' => 'Markdown Extends',
            '代码块' => 'Code Block',
            '主题自定义扩展' => 'Custom Extends',



            '主题基础色调' => 'Base Theme',
            '默认为 Mirages White' => 'Default: Mirages White',
            '使用卡片式文章列表' => 'Card List Style',
            '自动夜间模式' => 'Night Shift',
            'Banner 下边界添加弧型遮罩' => 'Show Curve at banner bottom.',
            '默认不添加弧型遮罩' => 'Default is no.',


            '博客公告消息' => 'Blog Notice Message',
            '首页大图内文字' => 'Index Page Title',
            '首页大图内描述' => 'Index Page Description',


            '站点背景大图地址' => 'Background Image At Index Page',
            '站点背景大图高度(%)' => 'Image Height At Index Page(%)',
            '站点背景大图高度(竖屏下)(%)' => 'Image Height At Index Page(Portrait)(%)',
            '标题默认显示在文章主图中' => 'Show Title Over Post Main Image',
            '卡片式文章列表的默认背景图列表' => 'Default Images For Card List Style',

            '自定义颜色' => 'Custom Colors',
            '自定义主题主色调' => 'Custom Main Color',
            '自定义主题主色调(夜间模式)' => 'Custom Main Color(Dark Mode)',
            '自定义 Selection Color' => 'Custom Selection Color',
            '自定义 Selection Background Color' => 'Selection Background Color',

            '侧边栏头像' => 'Head Photo',
            '界面语言' => 'Language',
            '侧边栏' => 'Side Menu',
            '始终显示 Dashboard(控制台) 菜单' => 'Always show dashboard in side menu.',
            '侧边栏底部按钮' => 'Side Toolbar',
            'Disqus 评论' => 'Disqus Comment',
            '相关文档' => 'Reference Documents',
            '评论 - 多说' => 'Comment - Duoshuo',
            '多说 Short Name' => 'Duoshuo Short Name',
            '多说评论' => 'Duoshuo Comment',
            '多说 User Id' => 'Duoshuo User Id',
            '自定义多说 Embed.js' => 'Custom Duoshuo Embed.js',
            '自定义多说 Author Id' => 'Custom Duoshuo Author Id',
            '二维码及打赏' => 'QR Code And Reward',
            '本页二维码生成地址' => 'QR Code Generator URL',
            '打赏二维码图片地址' => 'Reward QR Code URL',
            '速度优化' => 'Speed Optimize',
            '静态文件路径' => 'Static file path',
            '主题内置' => 'Embed',
            'Google 字体' => 'Google Fonts',
            '主题字体加载方式' => 'Web Fonts Source',
            '扩展功能' => 'Extension',
            '显示数学公式 (MathJax)' => 'Parse And Show MathJAX',
            '数学公式支持' => 'MathJAX Supports',
            'Markdown 语法扩展' => 'Markdown Language Extends',
            '不启用' => 'Disable',
            '启用 PJAX (BETA)' => 'Enable PJAX (BETA)',
            '高级选项' => 'Advance Settings',
            '其他选项' => 'Others Settings',
            '为 Windows 平台的 Chrome 浏览器启用平滑滚动' => 'Enable Smooth Scroll For Chrome On Windows',


            '感谢您使用 Mirages' => 'Thanks for using Mirages',
            '主题帮助文档' => 'Documents',
            '意见或建议' => 'Feedback',
            '主题更新日志' => 'Update Log',
            '版本: ' => 'Version: ',
            '新版本发布啦' => 'New Version Available.',
            '前往插件页面更新' => 'Upgrade Mirages',
            '最新版' => 'Latest version',
        );
    }

    /**
     * @return string 返回日期的格式化字符串
     */
    public function dateFormat() {
        return "F j, Y";
    }
}