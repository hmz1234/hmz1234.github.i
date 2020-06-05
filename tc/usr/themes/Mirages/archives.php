<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php

/**
 * Archives
 *
 * @package custom
 */
$tagLimit = Mirages::$options->tagLimit;
if (empty($tagLimit) || $tagLimit < 0) {
    if (!($tagLimit === 0 || $tagLimit === '0')) {
        $tagLimit = 30;
    }
}
$this->need('component/header.php'); ?>
<div id="archives">
<?php if(!(Mirages::$options->showBanner && (Utils::isTrue($this->fields->headTitle) || (intval($this->fields->headTitle) >= 0 && Mirages::$options->headTitle__isTrue)))): ?>
    <h1 id="archives-title"><?php _me($this->title) ?></h1>
<?php endif?>
    <div id="archives-tags">
        <h3><?php _me("标签云")?></h3>
        <?php Typecho_Widget::widget('Widget_Metas_Tag_Cloud', array('ignoreZeroCount' => true, 'limit' => $tagLimit))->to($tags); ?>
        <?php if($tags->have()): ?>
            <?php while ($tags->next()): ?>
                <a class="itags" href="<?php $tags->permalink();?>">
                    <?php echo Mirages::parseBiaoqing($tags->name) ?></a>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    <div id="archives-content">
        <br>
        <?php
        $format = Mirages::$options->archivesGroupByYear__isTrue ? 'Y' : 'Y-m';
        $posts = Content::listAllPosts($this, $format);
        function toDate($groupedDate) {
            @list($year, $month) = explode('-', $groupedDate, 2);
            $format = '%d 年';
            if ($month !== NULL) {
                $format .= ' %d 月';
            }
            return _mt($format, $year, $month);
        }
        ?>

        <?php foreach ($posts as $groupedDate => $archives):?>
        <div class="archive-title" id="archives-<?php echo $groupedDate?>">
            <div class="archives" data-date="<?php echo $groupedDate?>">
                <h3><?php echo toDate($groupedDate)?></h3>
                <?php foreach ($archives as $archive):?>
                    <div class="brick">
                        <a href="<?php echo $archive['permalink']?>">
                            <span class="time"><?php echo date('m-d', $archive['created'])?></span> <?php echo $archive['title']?>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php $this->need('component/footer.php'); ?>