<?php if (!empty($field_list)) { ?>
    <div>
        <?php
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);

//        debug($field_list);
//        debug($pending_values);
//        debug($submitted_values);
//        debug($pending_values);
        ?>
        <div style="padding: 15px 0px 10px 0px;">
            <table>          
                <tr>
                    <td colspan="3" style="font-size: 16px; font-weight: bold; color: #052458;"><?php echo $title; ?></td>                                                      
                </tr>                        
                <tr>
                    <td style="min-width: 120px; font-weight: bold;">As on</td>
                    <td class="colons">:</td>
                    <td style="min-width: 625px;"><?php echo $period; ?></td>                                  
                </tr>
                <tr>
                    <td style="font-weight: bold;">Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $name_of_org; ?></td>                                  
                </tr>                        
            </table>
        </div>

        <fieldset>
            <legend><?php echo "Submitted"; ?></legend>
            <?php echo $this->Form->create($model_name . "_submitted"); ?>
            <table cellpadding="0" cellspacing="0" border="0">          
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option_submitted', array('label' => false, 'style' => 'width:200px',
                            'options' => $search_options
                        ));
                        ?>
                    </td>
                    <td class="colons" style="padding: 5px 0;">:</td>
                    <td><?php echo $this->Form->input('search_keyword_submitted', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;">
                        <?php
                        echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                        ?>
                    </td>               
                </tr>
            </table>
            <?php echo $this->Form->end(); ?> 

            <div id="searching" style="width:775px;">
                <?php
                if (!$submitted_values || !is_array($submitted_values) || count($submitted_values) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available!';
                    echo '</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            foreach ($field_list as $field_title => $field_name) {

                                //$field_name_for_sorting = $model_name . "." . $field_to_display_in_view['LookupModelFieldDefinition']['field_name'];
                                echo "<th style='min-width:85px;'>" . $this->Paginator->sort($field_name, $field_title) . "</th>";
                            }
                            echo "<th style='width:60px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($submitted_values as $value) { ?>
                            <tr>
                                <?php
                                foreach ($field_list as $field_title => $field_detail) {
                                    $field_detail = explode('.', $field_detail);
                                    echo "<td>" . $value[$field_detail[0]][$field_detail[1]] . "</td>";
                                }
                                //$unique_data_id = $value[$model_name][$primary_key_field_name];
                                ?>                                                                       
                                <td style="text-align:center;">
                                    <?php
                                    //echo $this->Js->link('Details', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'preview', $model_id, $unique_data_id, $title), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                <?php } ?>
            </div>

            <?php if ($submitted_values && $this->Paginator->param('pageCount') > 1) { ?>
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
        </fieldset>

        <fieldset>
            <legend><?php echo "Pending"; ?></legend>
            <?php echo $this->Form->create($model_name . "_pending"); ?>
            <table cellpadding="0" cellspacing="0" border="0">          
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option_pending', array('label' => false, 'style' => 'width:200px',
                            'options' => $search_options
                        ));
                        ?>
                    </td>
                    <td class="colons" style="padding: 5px 0;">:</td>
                    <td><?php echo $this->Form->input('search_keyword_pending', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;">
                        <?php
                        echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                        ?>
                    </td>               
                </tr>
            </table>
            <?php echo $this->Form->end(); ?> 

            <div id="searching" style="width:775px;">
                <?php
                //$branch_id = $cat_id = $cat_title = '';
                if (!$pending_values || !is_array($pending_values) || count($pending_values) < 1) {
                    echo '<p class="error-message">';
                    echo 'Did not find any data !';
                    echo '</p>';
                } else {

                    echo "<table class='view'>";

                    echo "<tr>";
                    foreach ($field_list as $field_title => $field_name) {
                        echo "<th style='min-width:85px;'>" . $this->Paginator->sort($field_name, $field_title) . "</th>";
                    }
                    echo "<th style='width:130px;'>Action</th>";
                    echo "</tr>";

                    foreach ($pending_values as $value) {
                        echo "<tr>";
                        $branch_id = $cat_id = $cat_title = '';

                        foreach ($field_list as $field_title => $field_detail) {
                            $field_detail = explode('.', $field_detail);
                            echo "<td>" . $value[$field_detail[0]][$field_detail[1]] . "</td>";
                        }

                        echo "<td style='text-align:center;'>";

                        if (!empty($value[$model_name]['branch_id'])) {
                            $branch_id = $value[$model_name]['branch_id'];
                            if (!empty($value[$model_name]['loan_category_id'])) {
                                $cat_id = $value[$model_name]['loan_category_id'];
                                $btn_edit = $this->Js->link("Edit $cat_title", array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, $cat_id, $branch_id), array_merge($pageLoading, array('class' => 'btnlink')));
                            } else {
                                $btn_edit = $this->Js->link('Edit', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi', $model_id, $branch_id), array_merge($pageLoading, array('class' => 'btnlink')));
                            }
                        } elseif (!empty($value[$model_name][$primary_key_field_name])) {
                            $unique_data_id = $value[$model_name][$primary_key_field_name];
                            $btn_edit = $this->Js->link('Edit', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'edit', $model_id, $unique_data_id), array_merge($pageLoading, array('class' => 'btnlink')));
                        }
                        echo $btn_edit;

//                                    echo $this->Js->link('Edit', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'edit', $model_id, $unique_data_id), array_merge($pageLoading, array('class' => 'btnlink')));
//                                    echo $this->Js->link('Details', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'preview', $model_id, $unique_data_id, $title), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink')));
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }
                ?>
            </div>

            <?php if ($pending_values && $this->Paginator->param('pageCount') > 1) { ?>
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
                        <td>
                            <?php
                            $model_list = array(71, 72, 80, 81);
                            if ($model_id == 70) {
                                $btn_add_new = $this->Js->link('Add General MC', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 1), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')))
                                        . "&nbsp" . $this->Js->link('Add ME', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 2), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')))
                                        . "&nbsp" . $this->Js->link('Add UPP', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 3), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')))
                                        . "&nbsp" . $this->Js->link('Add Others', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi_cat', $model_id, 5), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            } elseif (in_array($model_id, $model_list)) {
                                $btn_add_new = $this->Js->link('Add New', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add_multi', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            } else {
                                $btn_add_new = $this->Js->link('Add New', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            }
                            echo $btn_add_new;
                            ?>
                        </td>
                        <td><?php
                            $btn_submit_all = $this->Js->link('Submit All', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'submit_all', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            echo $btn_submit_all;
                            ?>
                        </td>
                        <td><?php
                            $btn_show_pending = $this->Js->link('Show Pending Branches', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'pending_branch_list', $model_id), array_merge($pageLoading, array('class' => 'mybtns', 'title' => '')));
                            echo $btn_show_pending;
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>

    <?php
} else {
    echo '<p class="error-message" style="margin:20px 10px;">Basic data is not available !</p>';
}
?>