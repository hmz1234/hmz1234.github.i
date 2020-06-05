<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php $parsed = parse_url($this->permalink);?>
<?php if(COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_DISQUS):?>
    <div id="comments">
        <span class="widget-title text-center" style="padding-bottom: 15px;"><?php _me('评论列表')?></span>
        <?php if($this->is('single')):?>
            <script type="text/javascript">
                var disqus_identifier = "<?php echo $parsed['path'];?>";
            </script>
        <?php endif?>
        <div id="disqus_thread"></div>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
    </div>
<?php elseif(COMMENT_SYSTEM === Mirages_Const::COMMENT_SYSTEM_EMBED):?>
    <div id="comments">
        <?php
        $parameter = array(
            'parentId'      => $this->hidden ? 0 : $this->cid,
            'parentContent' => $this->row,
            'respondId'     => $this->respondId,
            'commentPage'   => $this->request->filter('int')->commentPage,
            'allowComment'  => $this->allow('comment')
        );

        $this->widget('Mirages_Widget_Comments_Archive', $parameter)->to($comments);
        ?>
        <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond">
            <div class="cancel-comment-reply">
                <?php $comments->cancelReply(); ?>
            </div>
            <span id="response" class="widget-title text-left"><?php _me('添加新评论'); ?></span>
            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form">
                <?php if($this->user->hasLogin()): ?>
                    <p class="comment-login-info"><?php _me('登录为 <a href="%s">%s</a>. <a href="%s" no-pjax title="Logout">退出 &raquo;</a>', Mirages::$options->profileUrl, $this->user->screenName, Mirages::$options->logoutUrl)?></p>
                <?php endif; ?>
                <p>
                    <textarea rows="5" name="text" id="textarea" placeholder="<?php _me('在这里输入你的评论...')?>" style="resize:none;" required><?php $this->remember('text'); ?></textarea>
                </p>
                <?php if(!$this->user->hasLogin()): ?>
                    <input class="comment-input" type="text" name="author" id="author" placeholder="<?php _me('称呼')?> *" value="<?php $this->remember('author'); ?>" required />
                    <input class="comment-input" type="email" name="mail" id="mail" placeholder="<?php _me('电子邮件')?><?php if ($this->options->commentsRequireMail): ?> *<?php endif;?>" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail): ?> required<?php endif;?> />
                    <input class="comment-input" type="url" name="url" id="url" placeholder="<?php _me('网站')?><?php if ($this->options->commentsRequireURL): ?> *<?php endif; ?>" value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
                <?php endif; ?>
                <p style="margin-top: 10px">
                    <span class="OwO"></span>
                    <?php if (class_exists("CommentToMail_Plugin")):?>
                    <span class="comment-mail-me">
                        <input name="banmail" type="checkbox" value="stop" id="comment-ban-mail">
                        <label for="comment-ban-mail">
                        <?php _me('<strong>不接收</strong>回复邮件通知')?></label>
                    </span>
                    <?php endif;?>
                </p>
                <p><input type="submit" value="<?php _me('提交评论'); ?>" data-now="<?php _me('刚刚'); ?>" data-init="<?php _me('提交评论'); ?>" data-posting="<?php _me('提交评论中...'); ?>" data-posted="<?php _me('评论提交成功'); ?>" data-empty-comment="<?php _me('必须填写评论内容'); ?>" class="button" id="submit"></p>
            </form>
        </div>
        <?php else: ?>
            <div class="comment-closed">
                <p><?php _me('该页面评论已关闭')?></p>
            </div>
        <?php endif;?>
        <?php if ($comments->have()): ?>
            <div class="comment-separator">
                <div class="comment-tab-current">
                    <span class="comment-num"><?php $this->commentsNum(_mt('评论列表'), _mt('已有 1 条评论'), _mt('已有 %d 条评论')); ?></span>
                </div>
            </div>
            <?php $comments->listComments(array('avatarSize' => 100, 'defaultAvatar' => Mirages::$options->defaultGravatar, 'replyWord' => _mt('回复'))); ?>
            <?php $comments->pageNav(_mt('上一页'), _mt('下一页'), 0, '', 'wrapClass=page-navigator&prevClass=btn btn-primary btn-small prev&nextClass=btn btn-primary btn-small next'); ?>
        <?php endif; ?>
    </div>
<?php endif?>



