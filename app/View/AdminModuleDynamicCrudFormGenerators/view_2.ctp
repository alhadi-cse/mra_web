<fieldset>
    <legend><?php echo $title; ?></legend>
    <?php if (!empty($model_id) && !empty($model_name)) { ?>
        <?php
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
        $user_group_ids = $this->Session->read('User.GroupIds');
        $hide_add = $this->Session->read('Status.IsAdd');
        $hide_edit = $this->Session->read('Status.IsEdit');
        $hide_delete = $this->Session->read('Status.IsDelete');
        ?>
        <div>
            <?php
            if (!empty($values) && !empty($group_title_in_one_to_many_field_name)) {
                echo '<p style="margin:3px;"><strong>' . $group_title_in_one_to_many_field_description . ': </strong>' . $values[0][$associated_model_name][$associated_field_name_to_show] . '</p>';
            }
            if ((!empty($user_group_ids) && (in_array(2, $user_group_ids) || in_array(3, $user_group_ids) || in_array(5, $user_group_ids)))) {
                echo '';
            } else {
                if (empty($values) || !is_array($values) || count($values) < 1) {
                    echo '';
                } else {
                    echo $this->Form->create($model_name);
                    ?>
                    <table cellpadding="0" cellspacing="0" border="0">          
                        <tr>
                            <td style="padding-left:15px; text-align:right;">Search By</td>
                            <td>
                                <?php
                                echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px', 'options' => $search_options));
                                ?>
                            </td>
                            <td class="colons">:</td>
                            <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                            <td style="text-align:left;">
                                <?php
                                echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                                ?>
                            </td>               
                        </tr>
                    </table>
                    <?php
                    echo $this->Form->end();
                }
            }
            ?> 

            <div id="searching">
                <?php
                if (empty($values) || !is_array($values) || count($values) < 1) {
                    echo '<p class="error-message">' . 'No data is available !' . '</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            foreach ($fields_to_display_in_view as $field_to_display_in_view) {
                                $field_name = $field_to_display_in_view['LookupModelFieldDefinition']['field_name'];
                                if (($field_name == "org_id" || $field_name == "org_name") && !empty($org_id))
                                    continue;
                                if ($field_name == "branch_id" && !empty($branch_id))
                                    continue;

                                $field_name_for_sorting = "$model_name.$field_name";
                                echo "<th style='min-width:85px;'>" . $this->Paginator->sort($field_name_for_sorting, $field_to_display_in_view['LookupModelFieldDefinition']['field_description']) . "</th>";
                            }
                            $action = "<th style='width:130px;'>Action</th>";
                            if (!empty($hide_delete) && ($hide_delete == 1) && (!empty($hide_edit) && ($hide_edit == 1))) {
                                $action = "";
                            }
                            echo $action;
                            ?>
                        </tr>
                        <?php foreach ($values as $value) { ?>
                            <tr>
                                <?php
                                foreach ($fields_to_display_in_view as $field_to_display_in_view) {
                                    $field_name = $field_to_display_in_view['LookupModelFieldDefinition']['field_name'];

                                    if (($field_name == "org_id" || $field_name == "org_name") && !empty($org_id))
                                        continue;
                                    if ($field_name == "branch_id" && !empty($branch_id))
                                        continue;

                                    $text_align = 'text-align:justify;';
                                    if ($field_name == 'serial_no') {
                                        $text_align = 'text-align:center;';
                                    }
                                    $associated_field_name_to_show = $field_to_display_in_view['LookupModelFieldDefinition']['associated_field_name_to_show'];
                                    $associated_model_name = $field_to_display_in_view['LookupModelFieldDefinition']['associated_model_name'];
                                    $value_to_show = $value[$model_name][$field_name];

                                    if ($associated_field_name_to_show != '') {
                                        $value_to_show = $value[$associated_model_name][$associated_field_name_to_show];
                                    }

                                    echo (is_numeric($value_to_show) && ($field_name != 'serial_no') ? "<td style='padding-right:8px; text-align:right;'>" : "<td style='$text_align'>") . $value_to_show . "</td>";
                                }
                                $unique_data_id = $value[$model_name][$primary_key_field_name];
                                if (!empty($hide_delete) && ($hide_delete == 1) && (!empty($hide_edit) && ($hide_edit == 1))) {
                                    echo '';
                                } else {
                                    ?>                                                                       
                                    <td style="text-align:center;">
                                        <?php
                                        $edit_button = $this->Js->link('Edit', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'edit', $model_id, $unique_data_id), array_merge($pageLoading, array('class' => 'btnlink')));
                                        if (($finished_deadline) || ((!empty($hide_edit) && ($hide_edit == 1)))) {
                                            $edit_button = '';
                                        }
                                        echo $edit_button;
                                        if (($lookup_or_detail_id == 1)) {
                                            $delete_button = $this->Js->link('Delete', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'delete', $model_id, $unique_data_id), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
                                            if (!empty($hide_delete) && ($hide_delete == 1)) {
                                                $delete_button = '';
                                            }
                                            echo $delete_button;
                                        } else if ($lookup_or_detail_id == 2) {
                                            echo $this->Js->link('Details', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'preview', $model_id, $unique_data_id), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink')));
                                        }
                                        ?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table> 
                <?php } ?>
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
                        <?php if (!empty($user_group_ids) && in_array(5, $user_group_ids)) { ?>
                            <td><?php
                                if (!empty($previous_model_id)) {
                                    echo $this->Js->link('Previous', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => $previous_action_name . '?model_id=' . $previous_model_id), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();')));
                                } elseif (!empty($previous_controller_name)) {
                                    echo $this->Js->link('Previous', array('controller' => $previous_controller_name, 'action' => $previous_action_name), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();')));
                                }
                                ?>
                            </td>
                        <?php } ?>
                        <td>
                            <?php
                            $model_list = array(71, 72, 80, 81);
                            if ($model_id == 70) {
                                $btn_add_new = $this->Js->link('Add General MC', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 1, $branch_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')))
                                        . "&nbsp" . $this->Js->link('Add ME', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 2, $branch_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')))
                                        . "&nbsp" . $this->Js->link('Add UPP', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 3, $branch_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')))
                                        . "&nbsp" . $this->Js->link('Add Others', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 5, $branch_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            } elseif (in_array($model_id, $model_list)) {
                                $btn_add_new = $this->Js->link('Add New', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add_multi', $model_id, $branch_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            } else {
                                $btn_add_new = $this->Js->link('Add New', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            }
                            //echo $btn_add_new;                            
                            ///////////////
                            //$btn_add_new = $this->Js->link('Add New', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
//                            if (((!empty($record_count) && $record_count == 1) && (!empty($hide_add) && ($hide_add == 2))) || ($finished_deadline) || ((!empty($hide_add) && ($hide_add == 1)))) {
//                                $btn_add_new = '';
//                            }
                            echo $btn_add_new;
                            ?>
                        </td>
                        <?php if (!empty($user_group_ids) && in_array(5, $user_group_ids)) { ?>
                            <td><?php echo $this->Js->link('Preview', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'individual_preview', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'update' => '#popup_div'))); ?></td>

                            <td><?php
                                if (!empty($next_model_id)) {
                                    echo $this->Js->link('Next', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => $next_action_name . '?model_id=' . $next_model_id), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();')));
                                } elseif (!empty($next_controller_name)) {
                                    echo $this->Js->link('Next', array('controller' => $next_controller_name, 'action' => $next_action_name), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();')));
                                }
                                ?>
                            </td>
                        <?php } ?>
                        <td></td>
                    </tr>
                </table>
            </div>

        </div>

        <?php
    } else {
        echo '<p class="error-message"> No data is available ! </p>';
    }
    ?>
</fieldset>