<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('component/header.php'); ?>

    <div id="archive" role="main">
        <?php if ($this->have()): ?>
    	    <?php while($this->next()): ?>
                <?php if (Mirages::$options->useCardView__isTrue):?>
                    <article itemscope itemtype="http://schema.org/BlogPosting" class="<?php echo Utils::isTrue($this->fields->disableDarkMask) ? 'no-mask':''?>">
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
                        <meta itemprop="dateModified" content="<?php echo date('c' , $this->modified);?>">
                        <a href="<?php $this->permalink() ?>">
                            <?php
                            $style = "";
                            $banner = FALSE;
                            $bannerPosition = "";
                            $bannerCDNType = -1;
                            if (Utils::hasValue($this->fields->banner)) {
                                $banner = Mirages::randomBanner($this->fields->banner);
                            } else {
                                if (Mirages::$options->enableLoadFirstImageFromArticle__isTrue) {
                                    $banner = Content::loadFirstImageFromArticle($this->content);
                                }
                                if ($banner === FALSE) {
                                    $banner = Content::loadDefaultThumbnailForArticle($this->cid);
                                }
                            }
                            if (!Utils::hasValue($banner)) {
                                $bgArray = Content::randomBackgroundColor($this->cid);
                                if (is_array($bgArray) && !empty($bgArray)) {
                                    $bg = join(',', $bgArray);
                                    $style = "style=\"background: {$bgArray[0]};background: -webkit-linear-gradient(90deg, {$bg}); background: linear-gradient(90deg, {$bg});\"";
                                }
                            } else {
                                $position = Mirages::getBannerPosition($banner);
                                $banner = $position[0];
                                $bannerPosition = $position[1];
                                if (Mirages::pluginAvailable(102)) {
                                    $bannerCDNType = Mirages_Plugin::getCdnType($banner);
                                }
                            }
                            ?>
                            <div class="post-card" id="post-card-<?php $this->cid();?>" <?php echo $style?>>
                                <?php if (Utils::hasValue($banner)):?>
                                    <?php if (Mirages::$options->enableLazyLoad__isTrue):?>
                                        <div class="blog-background"></div>
                                        <div class="lazyload-container"></div>
                                        <script type="text/javascript">registLoadBanner();</script>
                                        <meta itemprop="image" content="<?php echo $banner;?>">
                                        <meta itemprop="thumbnailUrl" content="<?php echo $banner . Utils::getThumbnailImageAddOn($bannerCDNType, 320);?>">
                                        <img alt="Thumbnail" src="<?php echo $banner . Utils::getThumbnailImageAddOn($bannerCDNType)?>" style="display: none" onload="javascript:loadBanner(this, '<?php echo $banner?>', '<?php echo $bannerPosition?>', document.querySelector('#post-card-<?php $this->cid();?>'), '<?php echo $bannerCDNType?>', document.querySelector('#post-card-<?php $this->cid();?>').offsetWidth, document.querySelector('#post-card-<?php $this->cid();?>').offsetHeight)">
                                    <?php else:?>
                                        <div class="blog-background"></div>
                                        <script type="text/javascript">
                                            loadBannerDirect('<?php echo $banner?>', '<?php echo $bannerPosition?>', document.querySelector('#post-card-<?php $this->cid();?>'), '<?php echo $bannerCDNType?>', document.querySelector('#post-card-<?php $this->cid();?>').offsetWidth, document.querySelector('#post-card-<?php $this->cid();?>').offsetHeight);
                                        </script>
                                    <?php endif;?>
                                <?php endif;?>
                                <div class="post-card-mask">
                                    <div class="post-card-container">
                                        <h2 class="post-card-title" itemprop="headline"><?php $this->sticky();echo Mirages::parseBiaoqing($this->title);?></h2>
                                        <div class="post-card-info">
                                            <?php if (Mirages::$options->userNum > 1):?><span itemprop="author" itemscope itemtype="http://schema.org/Person"><?php $this->author(); ?> • </span><?php endif;?>
                                            <span itemprop="datePublished" content="<?php $this->date('c'); ?>"><?php $this->date(I18n::dateFormat()); ?> • </span>
                                            <span><?php Content::category($this->categories, false)?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php else:?>
                    <article  itemscope itemtype="http://schema.org/BlogPosting">
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
                        <meta itemprop="dateModified" content="<?php echo date('c' , $this->modified);?>">
                        <div class="post">
                            <a href="<?php $this->permalink() ?>"><h4 class="post-title" itemprop="headline"><?php echo Mirages::parseBiaoqing($this->title)?></h4></a>
                            <div class="post-info">
                                <span itemprop="datePublished" content="<?php $this->date('c'); ?>"><?php $this->date(I18n::dateFormat()); ?> • </span>
                                <?php Content::outputCommentNumTag($this)?>
                            </div>
                            <div class="post-content" itemprop="description">
                                <p><?php echo Content::parse(Content::excerpt($this, _mt("阅读全文"))); ?></p>
                            </div>
                        </div>
                    </article>
                <?php endif;?>
            <?php endwhile; ?>
        <?php else: ?>
            <article class="post">
                <h1 class="post-title no-content"><?php _me('没有找到内容'); ?></h1>
            </article>
        <?php endif; ?>

        <?php $this->pageNav(_mt('上一页'), _mt('下一页'), 0, '', 'wrapClass=page-navigator&prevClass=btn btn-primary prev&nextClass=btn btn-primary next'); ?>
    </div>

	<?php $this->need('component/footer.php'); ?>
