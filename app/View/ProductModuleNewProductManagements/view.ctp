<div id="frmTypeOfOrg_view">    
    <?php
        if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
        $title = "Other Activities";
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
            <table>
                <tr>
                    <td style="text-align: justify;font-family: verdana,helvetica,arial;">
                        <?php
                            $message = "";
                            if (!empty($msg)){
                                $message = $msg;
                            }
                            echo $this->Form->create('ProductModuleNewProductManagement');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search Option</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option',
                                                array('label' => false, 'style'=>'width:200px',
                                                    'options' =>
                                                        array('LookupModelDefinition.model_description' => 'Model Name',
                                                              'LookupModelFieldDefinition.field_description' => 'Field Name'
                                                            )));
                                    ?>
                                </td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
                                <td style="text-align:left;">
                                   <?php
                                       echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                                    ?>
                               </td>
                            </tr>
                        </table>
                        <?php  echo $this->Form->end(); ?>
                    </td>        
                </tr>
                <tr>
                    <td>
                        <div id="searching" style="width:780px;">              
                            <table class="view">
                                <tr>
                                    <?php
                                        echo "<th style='width:205px;'>" . $this->Paginator->sort('LookupModelDefinition.model_description', 'Model Name') . "</th>";
                                        //echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupModelFieldDefinition.field_name', 'Field Name') . "</th>";                                        
                                        echo "<th style='width:200px;'>" . $this->Paginator->sort('LookupModelFieldDefinition.field_description', 'Field Description') . "</th>"; 
                                        echo "<th style='width:150px;'>Action</th>";
                                    ?>
                                </tr>
                               <?php foreach($values as $value){ ?>
                                <tr>
                                    <td style="text-align: justify;"><?php echo $value['LookupModelDefinition']['model_description']; ?></td>
<!--                                    <td style="text-align: justify;"><?php //echo $value['LookupModelFieldDefinition']['field_name']; ?></td> -->
                                    <td style="text-align: justify;"><?php echo $value['LookupModelFieldDefinition']['field_description']; ?></td>        
                                    <td style="text-align: justify;">                                  
                                        <?php 
                                            echo $this->Js->link('Details', array('controller'=>'LookupModelDefinitions', 'action'=>'preview', $value['LookupModelDefinition']['id']), array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')))
                                                .$this->Js->link('Edit', array('controller' => 'LookupModelFieldDefinitions','action' => 'edit', $value['LookupModelFieldDefinition']['model_id']), array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Delete', array('controller' => 'LookupModelFieldDefinitions','action' => 'delete', $value['LookupModelFieldDefinition']['model_id']), 
                                                    array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');",'class'=>'btnlink')));
                                       ?>
                                    </td>
                                </tr>
                               <?php  } ?>
                            </table> 
                        </div>
                    </td>                
                </tr>
            </table>
        </div>        
        <div id="popup_div" style="display:none;">
        </div>
        <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
          <?php 
            echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
                 $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                 $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
          ?>
        </div>
        <?php } ?>        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                            echo $this->Js->link('Add New', array('controller'=>'ProductModuleNewProductManagements','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Js->link('Add New Table/Model', array('controller'=>'LookupModelDefinitions','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Js->link('Add Fields(Table Attributes)', array('controller'=>'LookupModelFieldDefinitions','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
    </fieldset>
</div> 
<script>    
    $(document).ready(function(){
        $(".paging a").click(function(){
            $("#ajax_div").load(this.href);
            return false;
        })        
    });     
</script>