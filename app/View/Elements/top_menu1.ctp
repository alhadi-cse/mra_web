


<select id="keyboard" class="bangla_font" name="select" style="width:150px; height:24px; margin:3px 5px; padding:2px;">
    <option value=""><--- বাংলা কিবোর্ড ---></option>
    <option value="unijoy">ইউনিজয় কিবোর্ড</option>
    <option value="phonetici">ফোনেটিক কিবোর্ড</option>
    <option value="uniphonetic">ইউনিফোনেটিক কিবোর্ড</option>
    <option value="english">ইংলিশ কিবোর্ড</option>
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

    $('input[type=text].bangla_text, input[type=password].bangla_text, textarea.bangla_text').on('focus', function () {
        if ($('#keyboard'))
            SetKeyboard($(this).id, $('#keyboard')[0].value);
        else
            makeUnijoyEditor($(this.id));
    }).off(function () {
    });

    $(document).ready(function () {
        $('#keyboard').change(function () {
            $('input[type=text].bangla_text, input[type=password].bangla_text, textarea.bangla_text').each(function () {
                SetKeyboard($(this.id), $('#keyboard')[0].value);
            });
        });
    });

</script>

<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>
