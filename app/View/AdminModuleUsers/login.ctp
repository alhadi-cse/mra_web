<div class="logininfo" style="width:385px;">
    <?php
    $title = "Sign in";

    $pageLoading = array('update' => '#content', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    
    $pageLoading_for_password_recovery = array('update' => '#main_content', 'evalScripts' => true,
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

    <div class="logheader"><?php echo $title; ?></div>

    <?php echo $this->Form->create('AdminModuleUser'); ?>
    <div style="margin:5px 20px 5px 5px;">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>User Id</td>
                <td class="colons">:</td>
                <td style="height:40px;"><?php echo $this->Form->input('user_name', array('type' => 'text', 'class' => 'login bangla_text', 'style' => 'width:200px;', 'label' => false, 'div' => false)); ?></td>
            </tr>
            <tr>
                <td>Password</td>
                <td class="colons">:</td>
                <td style="height:40px;"><?php echo $this->Form->input('user_passwrd', array('type' => 'password', 'class' => 'login bangla_text', 'style' => 'width:200px;', 'label' => false, 'div' => false)); ?></td>
            </tr>
        </table>
    </div>

    <div class="btns-div">
        <table style="margin:0 auto;" cellspacing="5" border="0">
            <tr>                
                <td style="text-align: center;">
                    <?php echo $this->Js->submit('Sign in', array_merge($pageLoading, array('error' => "msg.init('error', '$title', 'Sign in failed!');"))); ?>
                </td>                
            </tr>
            <tr>
                <td style="text-align: center;">
                    <?php echo $this->Js->link('Forgot password?', array('controller' => 'AdminModuleUsers', 'action' => 'recover_password'), array_merge($pageLoading_for_password_recovery, array('title' => 'Recover Your Lost Password'))); ?>                    
                </td>
            </tr>
        </table>
    </div>

    <?php echo $this->Form->end(); ?>

    <?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
    ?>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        if ($('#keyboard') && $('#keyboard').val()) {
            //$('input[type=text].bangla_text, input[type=password].bangla_text, textarea.bangla_text').each(function () {
            $('input[type=text], input[type=password], textarea').each(function () {
                SetKeyboard(this.id, $('#keyboard').val());
            });
        }
    });

</script>
