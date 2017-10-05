
<select id="keyboard" type="select" class="bangla_font dropdown" title="কীবোর্ড নির্বাচন করুন"">
    <option value=""> --- কীবোর্ড নির্বাচন --- </option>
    <option value="unijoy"> ইউনিজয় কীবোর্ড </option>
    <option value="phonetici"> ফোনেটিক কীবোর্ড </option>
    <option value="uniphonetic"> ইউনিফোনেটিক কীবোর্ড </option>
    <option value="english"> English Keyboard </option>
</select>

<ul class="mymenu">

    <li>
        <?php
        $user_id = $this->Session->read('User.Id');

        $pageLoading = array('update' => '#main_content', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        if ($user_id) {
            $pageLoading['update'] = '#content';
            echo $this->Js->link('Log Out', array('controller' => 'AdminModuleUsers', 'action' => 'logout'), $pageLoading);
        } else {
            echo $this->Js->link('Login', array('controller' => 'AdminModuleUsers', 'action' => 'login'), $pageLoading);
            echo '</li> <li>';
            echo $this->Js->link('Sign Up', array('controller' => 'AdminModuleMfiSignUpDetails', 'action' => 'sign_up'), $pageLoading);
//            echo '</li> <li>';
//            echo $this->Js->link('Sign Up for Licensed MFI', array('controller' => 'AdminModuleMfiSignUpDetails', 'action' => 'licensed_mfi_sign_up'), $pageLoading);
        }
        ?>
    </li>

    <li><a href="#">User Guide</a></li>

</ul>

<script type="text/javascript">

    var kbSelectedOpt;

    $(document).ready(function () {

        if (kbSelectedOpt && $('#keyboard')) {
            $('#keyboard').val(kbSelectedOpt);
            //$('input[type=text].bangla_text, input[type=password].bangla_text, textarea.bangla_text').each(function () {
            $('input[type=text], input[type=password], textarea').each(function () {
                SetKeyboard(this.id, kbSelectedOpt);
            });
        }

        $('#keyboard').change(function () {
            kbSelectedOpt = $('#keyboard').val();
            //$('input[type=text].bangla_text, input[type=password].bangla_text, textarea.bangla_text').each(function () {
            $('input[type=text], input[type=password], textarea').each(function () {
                SetKeyboard(this.id, kbSelectedOpt);
            });
        });

    });

</script>

<?php
//if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
//    echo $this->Js->writeBuffer();
?>
