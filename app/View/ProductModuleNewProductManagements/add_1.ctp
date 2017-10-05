<div id="frmStatus_add">
    <?php
        if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
        $title = "New Product Management";
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
        $model_options = $this->Session->read('LookupModelDefinition.ModelOptions');
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend>
        <?php  echo $this->Form->create('ProductModuleNewProductManagement'); ?>
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="3">
                        <fieldset>
                            <legend>
                                Table Information
                            </legend>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>Module Name</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('module_id', array('type'=>'select', 'options'=>$module_options, 'label'=>false,'style'=>'width:255px;', 'empty'=>'---Select---')); ?></td>
                                </tr>
                                <tr>
                                    <td>Table Type</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('lookup_or_detail_id', array('type'=>'radio', 'options'=>array('1'=>'Lookup','2'=>'Details'), 'label'=>false, 'legend'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Table Name (like: product_module_table1_names or lookup_table1_names)</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('model_wise_table_name',array('label'=>false,'style'=>'width:250px;')); ?></td>
                                </tr>
                                <tr>
                                    <td>Table Description (like: Table 1 Name Information)</td>
                                    <td class="colons">:</td>
                                    <td ><?php echo $this->Form->input('model_description',array('label'=>false,'style'=>'width:250px;')); ?></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top:5px;">
                        <a id="add_button" href="#" class="mybtns">Add New Field</a>
                        <fieldset>
                            <legend>
                                <?php echo 'Field Information&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
                            </legend>
                            <div style="max-height:250px; overflow-y: scroll;">
                                <table id='my_table'></table>
                            </div>                            
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
       <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                            echo $this->Js->link('Close', array('controller' => 'ProductModuleNewProductManagements','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Save', array_merge($pageLoading,
                                                    array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
     <?php  echo $this->Form->end();?>
    </fieldset>
</div>

<script type="text/javascript">     
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
    $(function(){
        var counter = 0;        
        $('#add_button').click(function(event){
            event.preventDefault();
            var field_sorting_order = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_sorting_order', array('type'=>'text','class'=>'integers','label'=>false))); ?>;
            field_sorting_order = field_sorting_order.replace(/replace_with_counter/g,counter);
    
            var field_name = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_name', array('type'=>'text','label'=>false))); ?>;
            field_name = field_name.replace(/replace_with_counter/g,counter);
    
            var field_description = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_description', array('type'=>'text','label'=>false))); ?>;
            field_description = field_description.replace(/replace_with_counter/g,counter);
    
            var data_type = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.data_type',array('type'=>'select','options'=>array('int'=>'Integer','varchar'=>'Text','double'=>'Double','date'=>'Short Date','datetime'=>'Date with time'),'empty'=>'---Select---', 'label'=>false))); ?>;
            data_type = data_type.replace(/replace_with_counter/g,counter);            
    
            var field_size = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_size', array('type'=>'text','class'=>'integers','label'=>false))); ?>;
            field_size = field_size.replace(/replace_with_counter/g,counter);
    
            var is_primary_key = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_primary_key', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_primary_key = is_primary_key.replace(/replace_with_counter/g,counter);
    
            var is_auto_increment = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_auto_increment', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_auto_increment = is_auto_increment.replace(/replace_with_counter/g,counter);
            
            //is_exists_validity
            var is_exists_validity = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_exists_validity', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_exists_validity = is_exists_validity.replace(/replace_with_counter/g,counter);
    
            var is_mandatory = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_mandatory', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_mandatory = is_mandatory.replace(/replace_with_counter/g,counter);
    
            var control_type = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.control_type', array('type'=>'select', 'options'=>array('text'=>'Text Box','textarea'=>'Text Area','select'=>'Dropdown List','select_or_label'=>'Both Dropdown List and Caption','radio'=>'Radio Button','checkbox'=>'Check Box','date'=>'Date','year_month'=>'Year & Month','hidden'=>"Hidden/Don't Display"), 'label'=>false, 'empty'=>'---Select---'))); ?>;
            control_type = control_type.replace(/replace_with_counter/g,counter);
            
            var select_option_sources = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.select_option_sources', array('type'=>'select', 'options'=>$model_options, 'label'=>false, 'empty'=>'---Select---'))); ?>;
            select_option_sources = select_option_sources.replace(/replace_with_counter/g,counter);
             
            //dropdown_display_field
            var dropdown_display_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dropdown_display_field', array('type'=>'select', 'options'=>$model_options, 'label'=>false, 'empty'=>'---Select---'))); ?>;
            dropdown_display_field = dropdown_display_field.replace(/replace_with_counter/g,counter);
            
            //dropdown_value_field
            var dropdown_value_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dropdown_value_field', array('type'=>'select', 'options'=>$model_options, 'label'=>false, 'empty'=>'---Select---'))); ?>;
            dropdown_value_field = dropdown_value_field.replace(/replace_with_counter/g,counter);
            
            //dropdown_condition_field
            var dropdown_condition_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dropdown_condition_field', array('type'=>'select', 'options'=>$model_options, 'label'=>false, 'empty'=>'---Select---'))); ?>;
            dropdown_condition_field = dropdown_condition_field.replace(/replace_with_counter/g,counter);
            
            //containable_model_names
             
            //parent_model_name_for_select_option 
             
            //dependent_dropdown_display_field
            
            //dependent_dropdown_value_field
            
            //associated_field_name_to_show
            
            //associated_model_name
                                   
            
            var css_class_name = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.css_class_name', array('type'=>'select', 'options'=>array('integers'=>'Integer','decimals'=>'Decimal','text'=>'Text','date'=>'Date'), 'label'=>false, 'empty'=>'---Select---'))); ?>;
            css_class_name = css_class_name.replace(/replace_with_counter/g,counter);
    
            var is_published = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_published', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_published = is_published.replace(/replace_with_counter/g,counter);
    
            //display_in_search_params            
               
            var display_in_view_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_view_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_view_page = display_in_view_page.replace(/replace_with_counter/g,counter);
    
            var display_in_add_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_add_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_add_page = display_in_add_page.replace(/replace_with_counter/g,counter);
            
            var display_in_edit_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_edit_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_edit_page = display_in_edit_page.replace(/replace_with_counter/g,counter);   
            
            //display_in_details_page
                        
            var field_no = counter+1;
            
            var newRow = $(
                "<tr><td><fieldset><table>"+
                            "<tr><td colspan='3' style='padding:1px 200px; font-size:16px;'><legend><strong>Enter Field "+field_no+" Information</strong></legend></td></tr>"+
                            "<tr><td>Sorting Order</td><td class='colons'>:</td><td>"+field_sorting_order+"</td></tr>"+
                            "<tr><td>Field Name</td><td class='colons'>:</td><td>"+field_name+"</td></tr>"+
                            "<tr><td>Field Description</td><td class='colons'>:</td><td>"+field_description+"</td></tr>"+
                            "<tr><td>Data Type</td><td class='colons'>:</td><td>"+data_type+"</td></tr>"+
                            "<tr><td>Field Size</td><td class='colons'>:</td><td>"+field_size+"</td></tr>"+
                            "<tr><td>Is it a primary key?</td><td class='colons'>:</td><td>"+is_primary_key+"</td></tr>"+
                            "<tr><td>Does it support auto increment?</td><td class='colons'>:</td><td>"+is_auto_increment+"</td></tr>"+
                            "<tr><td>Does it support auto increment?</td><td class='colons'>:</td><td>"+is_exists_validity+"</td></tr>"+
                            "<tr><td>Is it a mandatory field?</td><td class='colons'>:</td><td>"+is_mandatory+"</td></tr>"+
                            "<tr><td>Binding control type</td><td class='colons'>:</td><td>"+control_type+"</td></tr>"+
                            "<tr><td>Select Option Source</td><td class='colons'>:</td><td>"+select_option_sources+"</td></tr>"+
                            "<tr><td>Select Option Source</td><td class='colons'>:</td><td>"+dropdown_display_field+"</td></tr>"+
                            "<tr><td>Select Option Source</td><td class='colons'>:</td><td>"+dropdown_value_field+"</td></tr>"+
                            "<tr><td>CSS Class Name</td><td class='colons'>:</td><td>"+css_class_name+"</td></tr>" +
                            "<tr><td>Is Published? </td><td class='colons'>:</td><td>"+is_published+"</td></tr>"+ 
                            "<tr><td>Display in View Page? </td><td class='colons'>:</td><td>"+display_in_view_page+"</td></tr>"+ 
                            "<tr><td>Display in Add Page? </td><td class='colons'>:</td><td>"+display_in_add_page+"</td></tr>"+ 
                            "<tr><td>Display in Edit Page? </td><td class='colons'>:</td><td>"+display_in_edit_page+"</td></tr></table>"+ 
                            "<p style='padding:5px 250px;'><a id='remove_button' href='#' onclick='deleteRow(this);' class='mybtns'>Remove Field "+field_no+"</a></p>"+
                 "</fieldset></td></tr>"
            );
            counter++;
            $('#my_table').append(newRow);
        });
    });

    function deleteRow(obj) {
        $(obj).closest('tr').remove();
    }
</script>
<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
?>