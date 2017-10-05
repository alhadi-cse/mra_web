<div class="logininfo" style="width:600px;">
    <?php
    $title = "Create an account for new license";

    $pageloading = array('update' => '#main_content', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>


    <div class="logheader"><?php echo $title; ?></div>

    <?php echo $this->Form->create('AdminModuleUser'); ?>

    <div class="form-view">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>Short Name of MFI</td>
                <td class="colons">:</td>
                <td ><?php
                    echo $this->Form->input('mfi_short_name', array('type' => 'text', 'label' => false));
                    //echo $this->Form->input('user_group_id',array('type'=>'hidden','value' => $user_group_id,'label'=>false));
                    ?></td>
            </tr>
            <tr>
                <td>Full Name of MFI</td>
                <td class="colons">:</td>
                <td><?php
                    echo $this->Form->input('mfi_full_name', array('type' => 'text', 'label' => false));
                    //debug($this->Form->input('mfi_full_name',array('type'=>'text','label'=>false)));
                    ?></td>
            </tr>
            <tr>
                <td>User Id</td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('user_name', array('type' => 'text', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Password</td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('user_password', array('type' => 'password', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => false)); ?></td>
            </tr>
<!--                    <tr>
                <td>District</td>
                <td class="colons">:</td>
                <td><?php //echo $this->Form->input('district_id', array('type'=>'select', 'options'=>$districtsOptions, 'id'=>'districts', 'empty'=>'---Select---', 'label'=>false));   ?></td>
            </tr>-->
            <tr>
                <td>Registration No.</td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('registration_no', array('type' => 'text', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>Mobile No.</td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('mobile_no', array('type' => 'text', 'class' => 'integers', 'label' => false)); ?></td>
            </tr>
            <tr>
                <td>E-Mail</td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('email', array('type' => 'text', 'label' => false)); ?></td>
            </tr>  
            <tr>
                <td colspan="3" style="padding:2px 10px 2px 140px;"><?php $this->Captcha->render(); ?></td>
            </tr>
        </table>
    </div>

    <div style="border-top:2px solid #23a5f7; width:auto; height:auto; margin-top:5px; background-color:#f0f5f8;"> 
        <table style="margin:0 auto;" cellspacing="7">
            <tr>                    
                <td style="text-align:center;">
                    <?php
                    echo $this->Js->submit('Save', array_merge($pageloading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                        'error' => "msg.init('error', '$title', 'Insertion failed!');")));
                    ?>
                </td>                    
                <td>
                    <?php
                    echo $this->Js->link('Close', array('controller' => 'Mrahome', 'action' => 'home'), array_merge($pageloading, array('class' => 'mybtns')));
                    ?>
                </td>
            </tr>
            <tr>                                                             
                <td style="text-align:center;" colspan="2">
                    Already have User ID?&nbsp;&nbsp;<?php echo $this->Js->link('Sign In', array('controller' => 'AdminModuleUsers', 'action' => 'login'), $pageloading); ?>
                </td>                   
            </tr>
        </table>
    </div> 
    <?php echo $this->Form->end(); ?>


</div>

<script type="text/javascript">
    jQuery('.creload').on('click', function () {
        var mySrc = $(this).prev().attr('src');
        var glue = '?';
        if (mySrc.indexOf('?') != -1) {
            glue = '&';
        }
        $(this).prev().attr('src', mySrc + glue + new Date().getTime());
        return false;
    });
//
//$('#AdminModuleUser').validate({ 
// debug: false,
// errorClass: "authError",
// errorElement: "span",
//  rules: {
//            "data[AdminModuleUser][mfi_short_name]": {
//               required: true,
//               maxlength: 10               
//            },
//            "data[AdminModuleUser][mfi_full_name]": {
//               required: true,
//               maxlength: 50               
//            },
//            "data[AdminModuleUser][user_id]": {
//               required: true,
//               minlength: 6,
//               maxlength: 20
//            },
//            "data[AdminModuleUser][password]": {
//               required: true,
//               minlength: 6
//            },               
//            "data[AdminModuleUser][registration_no]": {
//               required: true        
//            },
//            "data[AdminModuleUser][email]": {
//               required: true,
//               email: true
//            }
//        },   
//submitHandler: function(form) {
//        form.submit();
//    }, 
//highlight: function(element, errorClass) {
//            $(element).removeClass(errorClass);
//    }
// });
//$(document).ready(function () {
//    $('.integers').numeric({ decimal: false, negative: false });
//    $('.decimals').numeric({ decimal: ".", negative: false });
//});     
</script>
<?php
//    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) 
//        echo $this->Js->writeBuffer();
?>