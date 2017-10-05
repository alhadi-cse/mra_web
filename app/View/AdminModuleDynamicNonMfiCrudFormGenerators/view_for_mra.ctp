<?php if (!empty($model_details) && !empty($field_values)) { ?>

    <div>
        <?php
            $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

            $this->Paginator->options($pageLoading);            
        ?>
        <fieldset>
            <legend><?php echo $title; ?></legend>
            <?php            
                echo $this->Form->create($model_name); ?>
                <table cellpadding="0" cellspacing="0" border="0">          
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search By</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                'options' => $search_options
                            ));
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
            <?php echo $this->Form->end(); ?> 

            <div id="searching" style="width:775px;">
                <?php
                if (!$submitted_values || !is_array($submitted_values) || count($submitted_values) < 1) {
                    echo '<p class="error-message">';
                    echo 'Did not find any data !';
                    echo '</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            foreach ($fields_to_display_in_view as $field_to_display_in_view) {
                                $field_name_for_sorting = $model_name . "." . $field_to_display_in_view['LookupModelFieldDefinition']['field_name'];
                                echo "<th style='min-width:85px;'>" . $this->Paginator->sort($field_name_for_sorting, $field_to_display_in_view['LookupModelFieldDefinition']['field_description']) . "</th>";
                            }
                            echo "<th style='width:60px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($submitted_values as $value) { ?>
                            <tr>
                                <?php
                                foreach ($fields_to_display_in_view as $field_to_display_in_view) {
                                    $field_name = $field_to_display_in_view['LookupModelFieldDefinition']['field_name'];
                                    $associated_field_name_to_show = $field_to_display_in_view['LookupModelFieldDefinition']['associated_field_name_to_show'];
                                    $associated_model_name = $field_to_display_in_view['LookupModelFieldDefinition']['associated_model_name'];
                                    $value_to_show = $value[$model_name][$field_name];
                                    if ($associated_field_name_to_show != '') {
                                        $value_to_show = $value[$associated_model_name][$associated_field_name_to_show];
                                    }
                                    echo "<td>" . $value_to_show . "</td>";
                                }
                                $unique_data_id = $value[$model_name][$primary_key_field_name];
                                ?>                                                                       
                                <td>
                                    <?php
                                        echo $this->Js->link('Details', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'preview', $model_id, $unique_data_id, $title), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink')));
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
            <div class="btns-div">
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </fieldset>        
    </div>

    <?php
} else {
    echo '<p>&nbsp;</p><p>&nbsp;</p><p class="error-message">';
    echo 'No data is available!';
    echo '</p>';
}
?>
