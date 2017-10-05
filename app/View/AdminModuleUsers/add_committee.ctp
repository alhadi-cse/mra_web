<?php echo $this->Session->flash(); ?>
<div id="frmStatus_add">
    <?php
        $title = "Add user information";
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
        if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>            
        </legend> 
        
        <?php  echo $this->Form->create('AdminModuleUser'); ?>
        <div class="form">           
            <table cellpadding="0" cellspacing="0" border="0"> 
                <tr>
                    <td class="labelTd">User Group <span style="color:red; font-weight: bold;">*</span></td>
                    <td class="colons" style="padding-left: 102px;">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('AdminModuleUserGroupDistribution.user_group_id',array('type'=>'select','options'=>$user_group_options,'id'=>'group_names','empty'=>'---Select---', 'label'=>false)); ?></td>                    
                </tr>
                <tr>
                    <td class="labelTd" >User Name <span style="color:red; font-weight: bold;">*</span></td>
                    <td class="colons" style="padding-left: 102px;">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('AdminModuleUser.user_name',array('type'=>'text','label'=>false)); ?></td>
                </tr>
                <tr>
                    <td class="labelTd">Password <span style="color:red; font-weight: bold;">*</span></td>
                    <td class="colons" style="padding-left: 102px;">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('AdminModuleUser.user_passwrd',array('type'=>'password','label'=>false)); ?></td>
                </tr>
                <tr>
                    <td class="labelTd">Confirm Password <span style="color:red; font-weight: bold;">*</span></td>
                    <td class="colons" style="padding-left: 102px;">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('AdminModuleUser.confirm_password',array('type'=>'password','label'=>false)); ?></td>
                </tr>                
                <tr>
                    <td class="labelTd">E-Mail <span style="color:red; font-weight: bold;">*</span></td>
                    <td class="colons" style="padding-left: 102px;">:</td>
                    <td class="inputTd">
                        <?php 
                            echo $this->Form->input('AdminModuleUserProfile.email',array('type'=>'text','label'=>false));                    
                            echo $this->Form->input('AdminModuleUser.created_date',array('type'=>'hidden', 'value'=>"'".date('Y-m-d H:i:s')."'", 'label'=>false));
                            echo $this->Form->input('AdminModuleUser.activation_status_id',array('type'=>'hidden', 'value'=>'0', 'label'=>false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="labelTd">Mobile No. <span style="color:red; font-weight: bold;">*</span></td>
                    <td class="colons" style="padding-left: 102px;">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('AdminModuleUserProfile.mobile_no',array('type'=>'text','class'=>'integers','label'=>false)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" id="only_committee" style=" text-align: left;">
                        <table border="1" style="text-align: left;padding-right:112px;">            
                            <tr>
                                <td>Committee Member Type <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons" style="padding-left:56px;">:</td>
                                <td ><?php echo $this->Form->input('AdminModuleUserWithCommitteeMemberType.committe_member_type_id',array('type'=>'select','multiple'=>'checkbox','options'=>$committe_member_type_options,'class'=>'inline-checkbox','escape' =>true,'label'=>false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>    
                <tr>
                    <td colspan="3" id="only_mfi">
                        <table cellpadding="0" cellspacing="0" border="0">            
                            <tr>
                                <td class="labelTd">Short Name of MFI <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('BasicModuleBasicInformation.mfi_short_name',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                            <tr>
                                <td class="labelTd">Full Name of MFI <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('BasicModuleBasicInformation.mfi_full_name',array('type'=>'text','label'=>false)); ?></td>
                            </tr>                    
                            <tr>
                                <td class="labelTd">Primary Registration No. <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('BasicModuleBasicInformation.primary_registration_no',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                            <tr>
                                <td class="labelTd">Primary Registration Authority <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('BasicModuleBasicInformation.primary_registration_authority_id', array('type'=>'select', 'options'=>$primary_reg_act_options, 'empty'=>'---Select---', 'label'=>false)); ?></td>                        
                            </tr>
                            <tr>
                                <td class="labelTd">Name of Authorized Person <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('BasicModuleBasicInformation.full_name_of_authorized_person',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                            <tr>
                                <td class="labelTd">Designation of Authorized Person <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('BasicModuleBasicInformation.designation_of_authorized_person',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>  
                <tr>
                    <td colspan="3" id="other">
                        <table cellpadding="0" cellspacing="0" border="0">  
                            <tr>
                                <td class="labelTd">Full Name of User <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons" style="padding-left: 92px;">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('AdminModuleUserProfile.full_name_of_user',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                            <tr>
                                <td class="labelTd">Designation of User <span style="color:red; font-weight: bold;">*</span></td>
                                <td class="colons" style="padding-left: 92px;">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('AdminModuleUserProfile.designation_of_user',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                            <tr>
                                <td class="labelTd">Dept. in Office</td>
                                <td class="colons" style="padding-left: 92px;">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('AdminModuleUserProfile.div_name_in_office',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                            <tr>
                                <td class="labelTd">Name of Organization</td>
                                <td class="colons" style="padding-left: 92px;">:</td>
                                <td class="inputTd"><?php echo $this->Form->input('AdminModuleUserProfile.org_name',array('type'=>'text','label'=>false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>           
                <tr>
                    <td colspan="3" style="padding:2px 10px 2px 225px;"><?php $this->Captcha->render(); ?></td>
                </tr>             
            </table>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
     <?php  echo $this->Form->end(); ?>
    </fieldset>    
</div>
<script type="text/javascript">
    jQuery('.creload').on('click', function() {
        var mySrc = $(this).prev().attr('src');
        var glue = '?';
        if(mySrc.indexOf('?')!=-1)  {
            glue = '&';
        }
        $(this).prev().attr('src', mySrc + glue + new Date().getTime());
        return false;
    });
    $(document).ready(function(){ 
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
             
        selectedVal = $( "#group_names option:selected" ).val();        
        var selected_value = parseInt(selectedVal)
        
        if(selectedVal==""){
           hide_all();         
        }else{
            if (selected_value==2){                
               show_mfi();
            }
            else {
               hide_mfi();
            }
            if(selected_value>10){
                show_committee_memeber_type();
            }
            else{
                hide_committee_memeber_type();
            }
        }        
         
        $('#group_names').change(function(){           
             
            if ($(this).val()=="2"){                
               show_mfi();
            }
            else {
               hide_mfi();
            }
            
            var group_id = parseInt($(this).val())
            if(group_id>10){
                show_committee_memeber_type();
            }
            else{
                hide_committee_memeber_type();
            }           
          });           
                
        function show_mfi() {
            $("#only_mfi").show();
            $("#other").hide();                      
        }         
        function hide_mfi() {            
            $("#only_mfi").hide();
            $("#other").show();             
        } 
        function hide_all() {
            $("#only_mfi").hide();
            $("#other").hide(); 
            $("#only_committee").hide();
        }        
        function show_committee_memeber_type(){
            $("#only_committee").show();            
        }        
        function hide_committee_memeber_type(){
            $("#only_committee").hide();            
        }
    });    
</script>
