<?php echo $this->Session->flash(); ?>
<div>
    <?php
    $title = "Re-Assign User Roles";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('AdminModuleDetailSubMenuGroup'); ?>
        <div class="form">
            <table cellpadding="6" cellspacing="4" border="0">
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
                    <td>User Group</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('user_group_id', array('type' => 'select', 'id' => 'group_names', 'class' => 'medium', 'options' => $user_group_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>                    
                </tr>                            
            </table>
        </div>
        <div class="btns-div" id="buttons"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'AdminModuleDetailSubMenuGroups', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Save Changes', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title successfully.');",
                            'error' => "msg.init('error', '$title', 'Role Assign Failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
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