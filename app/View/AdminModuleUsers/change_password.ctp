<?php echo $this->Session->flash(); ?>
<div>
    <?php
    $title = "Change Password";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
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
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('AdminModuleUser'); ?>
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="width:23%;">User Id</td>
                    <td class="colons">:</td>
                    <td style="width:75%;">
                        <?php
                        if (!empty($user_group) && $user_group == 1)
                            echo $this->Form->input('user_name', array('id' => 'user_name', 'type' => 'text', 'class' => 'medium', 'label' => false));
                        else
                            echo '<strong style="margin-left:5px;">' . $this->Session->read('User.Name') . '</strong>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Current Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('current_passwrd', array('id' => 'current_passwrd', 'type' => 'password', 'class' => 'medium', 'label' => false)); ?></td>                    
                </tr>
                <tr>
                    <td>New Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('user_passwrd', array('id' => 'user_passwrd', 'type' => 'password', 'class' => 'medium', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Confirm New Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('confirm_passwrd', array('id' => 'confirm_passwrd', 'type' => 'password', 'class' => 'medium', 'label' => false)); ?></td>
                </tr>
                <tr id="captcha">
                    <td colspan="2"></td>
                    <td>
                        <div class="captcha medium">
                            <?php $this->Captcha->render(); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>                    
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Update', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'Password has been changed successfully.');",
                            'error' => "msg.init('error', '$title', 'Update failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>

<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>

<script>

    $(function () {
        
        $('.creload').on('click', function () {
            var mySrc = $(this).prev().attr('src');
            var glue = '?';
            if (mySrc.indexOf('?') != -1) {
                glue = '&';
            }
            $(this).prev().attr('src', mySrc + glue + new Date().getTime());
            return false;
        });
        
        $("#user_name").parent("div").addClass("required");
        $("#current_passwrd").parent("div").addClass("required");
        $("#user_passwrd").parent("div").addClass("required");
        $("#confirm_passwrd").parent("div").addClass("required");
        //$("#AdminModuleUserCaptcha").parent("div").addClass("required");

        $('#AdminModuleUserCaptcha').closest("div").addClass('required_captcha');
        $("#AdminModuleUserCaptcha").width('225px');
    });

</script>