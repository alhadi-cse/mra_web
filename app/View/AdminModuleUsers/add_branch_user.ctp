<?php //echo $this->Session->flash();                      ?>

<div id="frmStatus_add">
    <?php
    $title = "Create Branch User";
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
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <?php echo $this->Form->create('AdminModuleUser'); ?>
        <div class="form">           
            <table style="width:85%; min-width:700px;" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="width:130px;">Name of MFI</td>
                    <td class="colons">:</td>
                    <td style="width:475px; padding:7px 5px;"><?php echo $org_name; ?></td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td class="colons">:</td>
                    <td class="filter_opt">
                        <?php
                        echo $this->Form->input('AdminModuleUserProfile.branch_id', array('type' => 'select', 'id' => 'org_branch_id', 'class' => 'medium required', 'options' => $branch_name_options, 'empty' => '---Select---', 'escape' => false, 'label' => false));
                        if (!empty($branch_name_options) && count($branch_name_options) > 15) {
                            echo "<div>filter:<input type='text' id='txtOrgBranchFilter' style='width:100px !important; margin-left:5px;'></div>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td >User ID</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('AdminModuleUser.user_name', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('AdminModuleUser.user_passwrd', array('type' => 'password', 'class' => 'medium required', 'label' => false, 'after' => '<span style="font-weight:bold;color:#723;"> (at least 4 characters)</span>')); ?></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('AdminModuleUser.confirm_passwrd', array('type' => 'password', 'class' => 'medium required', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Full Name of User</td>
                    <td class="colons">:</td>
                    <td><?php
                        echo $this->Form->input('AdminModuleUserProfile.full_name_of_user', array('type' => 'text', 'class' => 'medium required', 'label' => false));
                        echo $this->Form->input('AdminModuleUser.created_date', array('type' => 'hidden', 'value' => "'" . date('Y-m-d H:i:s') . "'", 'label' => false));
                        echo $this->Form->input('AdminModuleUser.activation_status_id', array('type' => 'hidden', 'value' => '1', 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
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
                        echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers', 'action' => 'view_branch_users'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to close ?')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'Branch user created successfully.');",
                            'error' => "msg.init('error', '$title', 'Branch user creation failed!');")));
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

        if ($('#org_branch_id'))
            $('#org_branch_id').filterByText($('#txtOrgBranchFilter'), true);

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

<?php
$this->Js->get('#organizations')->event('change', $this->Js->request(array(
            'controller' => 'AdminModuleUsers',
            'action' => 'select_branch'
                ), array(
            'update' => '#branches',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>