<?php echo $this->Session->flash(); ?>

<div id="frmStatus_add">
    <?php
    $title = "Branch User Update";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    $user_name = $this->data['AdminModuleUser']['user_name'];
    $full_name_of_user = $this->data['AdminModuleUserProfile']['full_name_of_user'];
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <?php echo $this->Form->create('AdminModuleUser'); ?>
        <div class="form">
            <table style="width:87%;" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="min-width:150px;">Name of MFI</td>
                    <td class="colons">:</td>
                    <td style="min-width:475px; padding:7px 5px;"><?php echo $org_name; ?></td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td class="colons">:</td>
                    <td style="padding:7px 5px;"><?php echo $branch_with_address; ?></td>
                </tr>
                <tr>
                    <td >User Name</td>
                    <td class="colons">:</td>
                    <td style="padding:7px 5px;"><?php echo $full_name_of_user . $this->Form->input('full_name_of_user', array('type' => 'hidden', 'value' => $full_name_of_user, 'div' => false, 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td >User ID</td>
                    <td class="colons">:</td>
                    <td style="padding:7px 5px;"><?php echo $user_name . $this->Form->input('user_name', array('type' => 'hidden', 'value' => $user_name, 'div' => false, 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>User New Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('new_name_of_user', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td>User New ID</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('new_user_name', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('new_user_passwrd', array('id' => 'new_user_passwrd', 'type' => 'password', 'class' => 'medium required', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Confirm New Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('new_confirm_passwrd', array('id' => 'new_confirm_passwrd', 'type' => 'password', 'class' => 'medium required', 'label' => false)); ?></td>
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
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers', 'action' => 'view_branch_users'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Update', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'Branch user updated successful.');",
                            'error' => "msg.init('error', '$title', 'Branch user Update failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>

<script type="text/javascript">

    $(function () {
        $('.integers').numeric({decimal: false, negative: false});

        //$('.clabel').closest("div").addClass('required');
        //$('#AdminModuleUserCaptcha').closest("div").addClass('required');
        $('#AdminModuleUserCaptcha').closest("div").addClass('required_captcha');
        $('.creload').on('click', function () {
            $capcha = $(this).prev("img");
            $mySrc = $capcha.attr('src');
            $mySrc = $mySrc +
                    (($mySrc.indexOf('?') > -1) ? '&' : '?') +
                    new Date().getTime();
            $capcha.attr('src', $mySrc);
            return false;
        });
    });

</script>
