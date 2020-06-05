<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('component/header.php'); ?>
<div id="post" role="main">
    <article class="post page" itemscope itemtype="http://schema.org/BlogPosting" style="margin-bottom: 20px;">
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
        <?php if(!(Mirages::$options->showBanner && (Utils::isTrue($this->fields->headTitle) || (intval($this->fields->headTitle) >= 0 && Mirages::$options->headTitle__isTrue))) && !$this->is('page','about') && !$this->is('page','links')): ?>
        <h1 class="post-title <?php echo Utils::postTitleClass($this->title)?>"><?php echo Mirages::parseBiaoqing($this->title) ?>
            <?php if($this->user->hasLogin()):?>
                <a class="superscript" href="<?php Mirages::$options->adminUrl()?>write-page.php?cid=<?php $this->cid();?>" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
            <?php endif?>
        </h1>
        <?php endif?>
        <div class="post-content" itemprop="articleBody">
            <?php echo Content::parse($this->content) ?>
        </div>
    </article>
</div>
<?php $this->need('component/footer.php'); ?>
