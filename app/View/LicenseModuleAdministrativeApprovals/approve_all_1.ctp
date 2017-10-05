<?php
    $title = 'Final Approval';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleAdministrativeApprovalAll'); ?>
        
        <div class="form"> 
            <table>
                <tr>
                    <td>
                        <div style="width:780px; height:auto; overflow-x:auto;">
                            <?php 
                                if($orgDetails==null || !is_array($orgDetails) || count($orgDetails)<1) {
                                    echo '<p class="error-message">';
                                    echo 'No data is available!';
                                    echo '</p>';
                                }
                                else {
                            ?>
                            
                            <table class="view">
                                <tr>
                                    <?php 
                                    if(!$this->Paginator->param('options'))
                                        echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                                    else 
                                        echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                        echo "<th style='width:100px;'>Approve All <input type='checkbox' id='chkbApprovalAll'/> </th>";
                                        echo "<th style='width:100px;'>Reason <br /><span style='padding:0; color:#fa8713;'>(if not approved)</span></th>";
                                        echo "<th style='width:100px;'>Comment</th>";
                                        
                                        
//                                                  $this->Form->input('', array('id'=>'chkbApprovalAll', 'type'=>'checkbox', 'div'=>false, 'legend'=>false, 'label'=>false)) . "</th>";
                                        //echo "<th style='width:120px;'>Action</th>";
                                    ?>
                                </tr>
                                <?php 
                                
//                                    debug($orgDetails);
//                                    debug($approval_status_options);
                                
                                    $rc=-1;
                                    foreach($orgDetails as $orgDetail) { 
                                        ++$rc;
                                        //debug($orgDetail);
                                ?>
                                <tr>
                                    <td style="display:none;">
                                        <?php 
                                            echo $this->Form->input("$rc.org_id", array('type'=>'hidden', 'value'=>$orgDetail['BasicModuleBasicInformation']['id'], 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                                            $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];
                                            
                                            if (!empty($mfiName))
                                                $mfiName = "<strong>".$mfiName.":</strong> ";
                                            
                                            if (!empty($mfiFullName))
                                                $mfiName = $mfiName.$mfiFullName;        
                                            
                                            echo $mfiName;
                                        ?>
                                    </td>                                    
                                    <td>
                                        <div>
                                        <?php 
                                        
                                            foreach ($approval_status_options as $value => $text) {
                                                echo "<input type='radio' style='margin:2px;' name='data[LicenseModuleAdministrativeApprovalAll][$rc][approval_status_id]' ";
                                                if (strpos($text, 'Not') !== false) { echo 'checked'; } //if($value=='2'){ echo 'checked'; }
                                                echo " value='$value'/>$text<br/>";
                                            }
                                        
//                                        foreach ($approval_status_options as $approval_status_option)
//                                        {
//                                            echo '<input id="x" type="radio" value="'.$approval_status_option["approval_status"].'" style="margin:2px;" name="data[LicenseModuleAdministrativeApprovalAll][0][approval_status_id]">';
//                                        }
                                        
                                            //echo $this->Form->input("", array('class'=>'chkbApproval', 'type'=>'checkbox', 'div'=>false, 'legend'=>false, 'label'=>false));
//                                            echo $this->Form->input("$rc.approval_status_options", array('class'=>'chkbApproval', 'type'=>'checkbox', 'div'=>false, 'legend'=>false, 'label'=>false));
//                                            echo '<label style="font-size:12px; display:inline; vertical-align:text-bottom;" class="chkbText"></label>';
//                                            echo $this->Form->input("$rc.approval_status_id", array('type'=>'radio', 'options'=>$approval_status_options, 'selected'=>'1', 'style'=>'clear:left; margin:2px;', 'div'=>false, 'label'=>false, 'legend'=>false));
                                            //echo $this->Form->input("$rc.approval_status_id", array('id'=>'approval_status_id', 'type'=>'hidden', 'value' => '2', 'label' => false));
                                            
                                            ////echo 'Approve';
//                                            echo $orgDetail['LookupLicenseApprovalStatus']['id'];
//                                            echo $orgDetail['LookupLicenseApprovalStatus']['approval_status'];
                                            echo $this->Form->input("$rc.approval_date", array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input("$rc.reason_if_not_approved",array('type'=>'text', 'style'=>'width:125px; padding:5px;', 'escape'=>false,'label'=>false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input("$rc.comment",array('type'=>'text', 'style'=>'width:125px; padding:5px;', 'escape'=>false,'label'=>false)); ?>
                                    </td>
                                    
                                </tr>
                                <?php  } ?>
                            </table> 
                            
                        </div>
                    </td>                
                </tr>
            </table>
        </div>
        
        <?php if($orgDetails && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
            <?php 
            
            if($this->Paginator->param('pageCount')>10)
            {
               echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')).
                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                    $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
            }
            else {
               echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
            }
          ?>
        </div>
        <?php } ?>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleAdministrativeApprovals','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Save All', array_merge($pageLoading, 
                                                    array('class'=>'mybtns','success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); } ?>
    </fieldset>
</div>

<script>
    
//    $(document).ready(function() {
//        $("#chkbApprovalAll").change(function(){
//            if(this.checked){
//                $(".chkbApproval").each(function(){this.checked=true;});
//            }else{
//                $(".chkbApproval").each(function(){this.checked=false;});             
//            }
//        });
//    }
    
    
    $(document).ready(function() {
        
        $("#chkbApprovalAll").on("change", function () {
            if(this.checked) {
                $(":radio[value=1]").prop('checked', true);
                $(":radio[value=2]").prop('checked', false);
            }
            else {
                $(":radio[value=1]").prop('checked', false);
                $(":radio[value=2]").prop('checked', true);
            }
            
            
//            $(".chkbText").text(this.checked ? 'Approve' : 'Not Approve');
//            $("#approval_status_id").val(this.checked ? '1' : '2');
        });
        
//        $("#chkbApprovalAll").on("change", function () {
////            $(".chkbApproval:input:checkbox").not(this).prop('checked', this.checked);
////            $(".chkbText").text(this.checked ? 'Approve' : 'Not Approve');
//            
//        if(this.checked)
//        {
//                $(":radio[value=1]").prop('checked', true);
//                
//        }
//        else
//            $(":radio[value=1]").prop('checked', false);
//            
//            
////            $(".chkbText").text(this.checked ? 'Approve' : 'Not Approve');
////            $("#approval_status_id").val(this.checked ? '1' : '2');
//        });
        
//        $(".chkbApproval").on("change", function (){
//            $(this).next(".chkbText").text(this.checked ? 'Approve' : 'Not Approve');
////            $(this).next("#approval_status_id").val(this.checked ? '1' : '2');
//        });
        
    });
    
    
        
//            $(this).next(".chkbText").text($(this).prop('checked')?'Approve':'Not Approve');
//            if($(this).attr('checked'))
//                $(this).next(".chkbText").text('Approve');
//            else $(this).next(".chkbText").text('Not Approve');

        
        
        //$('.chkbApproval').next('.chkbText').text('new label text1');
        
            
            //$('.:input:checkbox').not(this).prop('checked', this.checked);
//        $('.chkbText').text('aa');
        
        
//        //select and deselect
// $("#chkroot").click(function () {
//        $('input:checkbox').prop('checked', this.checked);
//    });
//
////If one item deselect then button CheckAll is UnCheck
//    $(".chkmark").click(function () {
//        if (!$(this).is(':checked'))
//            $("#chkroot").prop('checked', false);
//    });
        
        
//        $("#chkbApprovalAll").change(function(){
//            if(this.checked){
//                $(".chkbApproval").each(function(){this.checked=true;});
//            }else{
//                $(".chkbApproval").each(function(){this.checked=false;});             
//            }
//        });
//
//        $(".chkbApproval").click(function () {
//            if (!$(this).is(":checked")){
//                $("#chkbApprovalAll").prop("checked", false);
//            }else{
//                var flag = 0;
//                $(".chkbApproval").each(function(){
//                    if(!this.checked)
//                    flag=1;
//                }) 
//                if(flag == 0){ $("#select_all").prop("checked", true);}
//            }
//        });
    
</script>
