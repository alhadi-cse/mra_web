<div id="frmUserInfo_view">
    <?php    
        $title = 'Assign Roles';        
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend>
        <div class="form">
            <?php echo $this->Form->create('AdminModuleDetailSubMenuGroup'); ?>            
            <table cellpadding="0" cellspacing="5" border="0">
                <tr>
                    <td>Module</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('module_id', array('type' => 'select', 'id' => 'module_names', 'class' => 'medium', 'options' => $module_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>                    
                </tr>
                <tr>
                    <td>Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('menu_id', array('type' => 'select', 'id' => 'menu_names', 'class' => 'medium', 'options' => $menu_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>                    
                </tr>
                <tr>
                    <td>Sub-Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('sub_menu_id', array('type' => 'select', 'id' => 'sub_menu_names', 'class' => 'medium', 'options' => $sub_menu_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>                    
                </tr>
                <tr>
                    <td colspan="3">
                        <table>
                            <tr>
                                <td><?php echo $this->Js->submit('Search', array_merge($pageLoading,array('url'=>"/AdminModuleDetailSubMenuGroups/view/custom",'class'=>'btnsearch'))); ?></td>
                                <td><?php echo $this->Js->submit('View All', array_merge($pageLoading,array('url'=>"/AdminModuleDetailSubMenuGroups/view/all",'class'=>'btnsearch'))); ?></td>
                            </tr>
                        </table>
                   </td>
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>
            <div id="searching">
                <table class="view">
                    <tr>
                        <?php
                            echo "<th style='min-width:100px;'>" . $this->Paginator->sort('AdminModuleModule.module_name', 'Module') . "</th>";
                            echo "<th style='width:110px;'>" . $this->Paginator->sort('AdminModuleMenu.menu_title', 'Category') . "</th>";
                            echo "<th style='width:160px;'>" . $this->Paginator->sort('AdminModuleSubMenu.sub_menu_title', 'Sub-Category') . "</th>";
                            echo "<th style='width:115px;'>" . $this->Paginator->sort('AdminModuleUserGroup.group_name', 'User Group') . "</th>";
                            echo "<th style='width:160px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values as $value) { ?>
                        <tr>
                            <td><?php echo $value['AdminModuleModule']['module_name']; ?></td>
                            <td><?php echo $value['AdminModuleMenu']['menu_title']; ?></td>
                            <td><?php echo $value['AdminModuleSubMenu']['sub_menu_title']; ?></td>
                            <td><?php echo $value['AdminModuleUserGroup']['group_name']; ?></td>                            
                            <td><?php echo $this->Js->link('Re-Assign', array('controller' => 'AdminModuleDetailSubMenuGroups', 'action' => 're_assign_role', $value['AdminModuleDetailSubMenuGroup']['module_id'],$value['AdminModuleDetailSubMenuGroup']['menu_id'],$value['AdminModuleDetailSubMenuGroup']['sub_menu_id'],$value['AdminModuleDetailSubMenuGroup']['user_group_id']), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:60px;'))) .
                                           $this->Js->link('Remove', array('controller' => 'AdminModuleDetailSubMenuGroups', 'action' => 'delete', $value['AdminModuleDetailSubMenuGroup']['module_id'],$value['AdminModuleDetailSubMenuGroup']['menu_id'],$value['AdminModuleDetailSubMenuGroup']['sub_menu_id'],$value['AdminModuleDetailSubMenuGroup']['user_group_id']), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink', 'style' => 'width:60px;','confirm' => 'Are you sure to remove?')));
                            ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

        </div>
        <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php
                if ($this->Paginator->param('pageCount') > 10) {
                    echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                    $this->Paginator->last('>>', array('class' => 'nextPg', 'title' => 'Goto last page.'), null, array('class' => 'nextPg no_link'));
                } else {
                    echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                }
                ?>
            </div>
        <?php } ?>
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php echo $this->Js->link('Assign Role', array('controller' => 'AdminModuleDetailSubMenuGroups', 'action' => 'assign_role'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Assign User Role'))); ?>     
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
<?php
$this->Js->get('#module_names')->event('change', 
    $this->Js->request(array(
        'controller'=>'AdminModuleDetailSubMenuGroups',
        'action'=>'update_menu_options'
        ), array(
        'update'=>'#menu_names',            
        'async'=>true,
        'method'=>'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm'=>true,
                'inline'=>true
            ))
        ))
    );
$this->Js->get('#module_names')->event('change', 
    $this->Js->request(array(
        'controller'=>'AdminModuleDetailSubMenuGroups',
        'action'=>'update_sub_menu_options'
        ), array(
        'update'=>'#sub_menu_names',            
        'async'=>true,
        'method'=>'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm'=>true,
                'inline'=>true
            ))
        ))
    );

$this->Js->get('#menu_names')->event('change', 
    $this->Js->request(array(
        'controller'=>'AdminModuleDetailSubMenuGroups',
        'action'=>'update_sub_menu_options'
        ), array(
        'update'=>'#sub_menu_names',            
        'async'=>true,
        'method'=>'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm'=>true,
                'inline'=>true
            ))
        ))
    );
?>

<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) 
        echo $this->Js->writeBuffer();
?>