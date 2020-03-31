<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="brand">
                <div class="logo">

                </div> <?php echo "<b>ZION</b>" ?></a>
            </div>
        </div>
        <nav class="menu">

            <ul class="sidebar-menu metismenu" id="sidebar-menu">

                <?php foreach ($allowed_modules->result() as $module) : ?>
                    <?php $sub_menus = json_decode($module->sub_menus, true); ?>

                    <?php if (count($sub_menus) > 0): ?>
                        <?php $parent_active = ( stristr($module->module_id, $this->router->fetch_class()) ? 'active open' : '' ); ?>
                        <li class="nav-parent <?= $parent_active ?>">       

                            <a href="<?php echo site_url("$module->module_id"); ?>" title="<?php echo $this->lang->line('module_' . $module->module_id . '_desc'); ?>">
                                <?= $module->icons ?>
                                <span class="nav-label"><?php echo $this->lang->line("module_" . $module->module_id) != '' ? $this->lang->line("module_" . $module->module_id) : $module->label; ?></span>
                                <i class="fa arrow"></i>
                            </a>

                            <ul class="sidebar-nav">
                                <?php foreach ($sub_menus as $sub_menu_key => $sub_menu_url): ?>
                                    <li>
                                        <?php $child_active = ( stristr($sub_menu_url, $this->router->fetch_method()) && stristr($module->module_id, $this->router->fetch_class()) ? 'active' : '' ); ?>
                                        <a href="<?php echo site_url("$module->module_id"); ?>/<?= $sub_menu_url ?>" class="<?= $child_active; ?>">
                                            <?= $sub_menu_key ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?> 
                            </ul>
                        </li>

                    <?php else: ?>
                        <?php $active = ( stristr($module->module_id, $this->router->fetch_class()) ? 'active' : '' ); ?>
                        <li class="<?= $active; ?>">
                            <a href="<?php echo site_url("$module->module_id"); ?>"> 
                                <?= $module->icons ?>
                                <span class="nav-label"><?= $this->lang->line("module_" . $module->module_id) != '' ? $this->lang->line("module_" . $module->module_id) : $module->label; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
    
</aside>



<div class="nano left-sidebar">
    <div class="nano-content">
        <ul class="nav nav-pills nav-stacked nav-inq">



        </ul>
    </div>
</div>