<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<a id="toggle-nav" class="btn btn-primary" href="javascript:void(0);"><span>MENU</span></a>
<nav id="site-navigation" class="sidebar no-user-select" role="navigation">
    <div id="nav">
        <div class="author navbar-header">
            <a href="<?php Mirages::$options->rootUrl()?>/<?php echo REWRITE_FIX ?>about.html">
                <img src="<?php echo Mirages::$options->headFaceUrl ?>" alt="Avatar" width="100" height="100"/>
            </a>
        </div>
        <div class="search-box navbar-header">
            <form class="form" id="search-form" action="<?php Mirages::$options->rootUrl()?>/<?php echo REWRITE_FIX ?>"  role="search">
                <input id="search" type="text" name="s" required placeholder="<?php _me('搜索...')?>" class="search search-form-input">
                <button id="search_btn" type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <ul id="menu-menu-1" class="menu navbar-nav" data-content="<?php echo intval(Mirages::$options->timeValid / 1170) % 1000?>">
            <?php if($this->user->hasLogin() || Mirages::$options->alwaysShowDashboardInSideMenu == 1):?>
            <li class="menu-item" style="margin-bottom: 30px;"><a href="<?php Mirages::$options->adminUrl()?>" target="_blank"><?php _me('控制台')?></a></li>
            <?php endif?>
            <li class="menu-item"><a href="<?php Mirages::$options->rootUrl()?>"><?php _me('首页')?></a></li>
            <li>
                <a class="slide-toggle"><?php _me('分类')?></a>
                <div class="category-list hide">
                    <?php $this->destory('Widget_Metas_Category_List');?>
                    <?php $this->widget('Widget_Metas_Category_List')->listCategories('wrapClass=list'); ?>
                </div>
            </li>
            <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
            <?php while($pages->next()): ?>
            <li class="menu-item"><a class="<?php if($this->is('page', $pages->slug)): ?> current<?php endif; ?>" href="<?php $pages->permalink(); ?>" title="<?php _me($pages->title) ?>"><?php _me($pages->title) ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php
    $toolbarItems = mb_split("\n", Mirages::$options->toolbarItems);
    $hideRssBarItem = false;
    $hideNightShiftBarItem = false;
    $toolbarItemsOutput = "";
    foreach ($toolbarItems as $toolbarItem) {
        $item = mb_split(":", $toolbarItem, 2);
        if (count($item) !== 2) continue;
        $itemName = strtolower(trim($item[0]));
        $itemLink = trim($item[1]);
        if ($itemName === 'rss' && strtoupper($itemLink) === 'HIDE') {
            $hideRssBarItem = true;
            continue;
        }
        if ($itemName === 'read-settings' && strtoupper($itemLink) === 'HIDE') {
            $hideNightShiftBarItem = true;
            Mirages::$options->hideReadSettings = 1;
            continue;
        }
        $toolbarItemsOutput .= '<li><a id="side-toolbar-'. $itemName .'" href="'. $itemLink .'" title="'. _mt(ucfirst($itemName)) .'" target="_blank"><i class="fa fa-'. $itemName .'"></i></a></li>';
    }
    ?>
    <?php if (!($hideRssBarItem && $hideNightShiftBarItem && empty($toolbarItemsOutput))):?>
        <div id="nav-toolbar">
            <div class="side-toolbar">
                <ul class="side-toolbar-list">
                    <?php if (!$hideRssBarItem):?>
                        <li><a id="side-toolbar-rss" href="<?php Mirages::$options->rootUrl()?>/<?php echo REWRITE_FIX ?>feed" title="<?php _me('RSS')?>"><i class="fa fa-feed"></i></a></li>
                    <?php endif;?>
                    <?php echo $toolbarItemsOutput?>
                    <?php if (!$hideNightShiftBarItem):?>
                        <li><a id="side-toolbar-read-settings" href="javascript:void(0);" title="<?php _me("阅读设置") ?>"><i class="fa fa-font"></i></a></li>
                    <?php endif;?>
                </ul>
                <div class="read-settings-container animated">
                    <div class="read-settings animated">
                        <div class="font-size-controls animated">
                            <button type="button" class="font-size-control control-btn-smaller waves-effect waves-button" data-mode="smaller">A</button>
                            <button type="button" class="font-size-display" disabled>100%</button>
                            <button type="button" class="font-size-control control-btn-larger waves-effect waves-button" data-mode="larger">A</button>
                        </div>
                        <div class="background-color-controls animated">
                            <ul>
                                <li><a href="javascript:void(0)" title="<?php _me("自动模式")?>" class="background-color-control auto <?php echo NIGHT_SHIFT_BTN_CLASS == "auto-mode" ? "selected" : ""?>" data-mode="auto"><i class="fa fa-adjust"></i></a></li>
                                <li><a href="javascript:void(0)" title="<?php _me("日间模式")?>" class="background-color-control white <?php echo NIGHT_SHIFT_BTN_CLASS == "day-mode" ? "selected" : ""?>" data-mode="white"><i class="fa fa-check-circle"></i></a></li>
                                <li><a href="javascript:void(0)" title="<?php _me("日落模式")?>" class="background-color-control sunset <?php echo NIGHT_SHIFT_BTN_CLASS == "sunset-mode" ? "selected" : ""?>" data-mode="sunset"><i class="fa fa-check-circle"></i></a></li>
                                <li><a href="javascript:void(0)" title="<?php _me("夜间模式")?>" class="background-color-control dark <?php echo NIGHT_SHIFT_BTN_CLASS == "night-mode" ? "selected" : ""?>" data-mode="dark"><i class="fa fa-check-circle"></i></a></li>
                            </ul>
                        </div>
                        <div class="font-family-controls">
                            <button type="button" class="font-family-control <?php echo USE_SERIF_FONTS ? "selected" : ""?> control-btn-serif" data-mode="serif">Serif</button>
                            <button type="button" class="font-family-control <?php echo USE_SERIF_FONTS ? "" : "selected"?> control-btn-sans-serif" data-mode="sans-serif">Sans Serif</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
</nav>