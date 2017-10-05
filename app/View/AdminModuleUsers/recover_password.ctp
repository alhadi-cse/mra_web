<div class="logininfo" style="width:385px;">
    <?php
    $title = "Recover Your Lost Password";
    $pageLoading = array('update' => '#main_content', 'class' => 'mybtns', 'div' => false, 'evalScripts' => true,
        'before' => "$('#busy-indicator').fadeIn();",
        'complete' => "$('#busy-indicator').fadeOut();");

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>

    <fieldset style="margin:0;">
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('AdminModuleUser'); ?>
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">                
                <tr>
                    <td>User Id</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('user_name', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="font-weight:bold; text-align:center;">OR</td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('email', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                </tr>                            
            </table>
        </div>
        <div class="btns-div" id="buttons"> 
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers', 'action' => 'login'), array_merge($pageLoading, array('update' => '#main_content', 'confirm' => 'Are you sure to Close ?',)));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('style' => 'width:70px;',
                            'success' => "msg.init('success', '$title', 'Your password has been reset successfully. Please check your e-mail');",
                            'error' => "msg.init('error', '$title', 'New User Creation failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>