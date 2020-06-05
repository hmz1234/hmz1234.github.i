<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('component/header.php'); ?>
<div id="post" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="display-none" itemscope itemprop="author" itemtype="http://schema.org/Person">
            <meta itemprop="name" content="<?php $this->author(); ?>"/>
            <meta itemprop="url" content="<?php $this->author('url'); ?>"/>
        </div>
        <div class="display-none" itemscope itemprop="publisher" itemtype="http://schema.org/Organization">
            <meta itemprop="name" content="<?php $this->author(); ?>"/>
            <div itemscope itemprop="logo" itemtype="http://schema.org/ImageObject">
                <meta itemprop="url" content="<?php echo Typecho_Common::gravatarUrl($this->author->mail, 50, Mirages::$options->commentsAvatarRating, NULL, true);?>">
            </div>
        </div>
        <meta itemprop="url mainEntityOfPage" content="<?php $this->permalink() ?>" />
        <meta itemprop="datePublished" content="<?php echo date('c' , $this->created);?>">
        <meta itemprop="dateModified" content="<?php echo date('c' , $this->modified);?>">
        <meta itemprop="headline" content="<?php $this->title();?>">
        <meta itemprop="image" content="<?php Mirages::$options->banner()?>">
        <?php if(!(
                Mirages::$options->showBanner
                && (
                        Utils::isTrue($this->fields->headTitle)
                        || (
                                intval($this->fields->headTitle) >= 0
                                && Mirages::$options->headTitle__isTrue
                        )
                )
        )): ?>
        <h1 class="post-title <?php echo Utils::postTitleClass($this->title)?>" itemprop="name headline"><?php echo Mirages::parseBiaoqing($this->title) ?></h1>
        <ul class="post-meta">
            <?php if (Mirages::$options->userNum > 1):?>
            <li><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a> • </li>
            <?php endif;?>
            <li><time><?php $this->date(I18n::dateFormat()); ?></time></li>
            <?php if(intval($this->viewsNum) > 0): ?>
            <li> • <?php _me('阅读: %d', $this->viewsNum);?></li>
            <?php endif;?>
            <li> • <?php Content::category($this->categories)?></li>
            <?php if (Mirages::$options->hideReadSettings__isFalse && Mirages::$options->navbarStyle != 1):?>
                <li> • <a href="javascript:void(0)" id="page-read-setting-toggle">阅读设置</a></li>
            <?php endif;?>
            <?php if($this->user->hasLogin()):?>
            <li class="edit"> • <a href="<?php Mirages::$options->adminUrl()?>write-post.php?cid=<?php $this->cid();?>" target="_blank"><?php _me('编辑'); ?></a></li>
            <?php endif;?>
        </ul>
        <?php endif?>
        <div class="post-content" itemprop="articleBody">
            <?php if (Utils::hasValue(Mirages::$options->postHeadContent)):?>
                <?php echo Mirages::$options->postHeadContent ?>
            <?php endif?>
            <?php echo Content::parse($this->content) ?>
            <?php if (Utils::hasValue(Mirages::$options->copyright) && !Utils::isTrue($this->fields->hideCopyright)):?>
                <?php $copyright = preg_replace('/(^\s*<\s*p\s*>)|(<\s*\/\s*p\s*>\s*$)/ixs', '', Mirages::$options->copyright);?>
                <?php $copyright = str_replace('{{title}}', Mirages::parseBiaoqing($this->title), $copyright);?>
                <?php $copyright = str_replace('{{link}}', $this->permalink, $copyright);?>
                <p class="content-copyright"><?php echo $copyright?></p>
            <?php endif?>
        </div>
        <div class="tags">
			<div itemprop="keywords" class="keywords 3af9fn"><?php $this->tags('', true, ''); ?></div>
            <?php if (Mirages::$options->disableModifyTime__isFalse && $this->modified > $this->created + 86400):?>
            <div class="modify-time"><?php _me('最后编辑于: %s', Utils::formatDate($this->modified, I18n::dateFormat()))?></div>
            <?php endif?>
        </div>
        <?php if(!Device::isPhone() && (Utils::hasValue(Mirages::$options->postQRCodeURL) || Utils::hasValue(Mirages::$options->rewardQRCodeURL))): ?>
        <div class="post-buttons">
            <a id="toggle-archives" class="btn btn-grey" href="<?php Mirages::$options->rootUrl();?>/<?php echo REWRITE_FIX ?>archives.html"><?php _me("返回文章列表")?></a>
            <?php if(Utils::hasValue(Mirages::$options->postQRCodeURL)):?>
            <a id="toggle-post-qr-code" class="btn btn-grey"><?php _me("文章二维码")?></a>
            <?php endif?>
            <?php if(Utils::hasValue(Mirages::$options->rewardQRCodeURL)): ?>
            <a id="toggle-reward-qr-code" class="btn btn-grey"><?php _me("打赏")?></a>
            <?php endif?>
        </div>
        <?php endif?>
    </article>
</div>
<?php $this->need('component/footer.php'); ?>
