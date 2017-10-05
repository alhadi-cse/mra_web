<?php echo $this->Session->flash(); ?>
<div class="logininfo" style="width:720px;">
    <?php
    $title = "Sign Up";
    $pageLoading = array('update' => '#content', 'evalScripts' => true,
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

    <div>
        <?php echo $this->Form->create('AdminModuleMfiSignUpDetail'); ?>

        <div class="logheader"><?php echo $title; ?></div>
        <fieldset>
            <div class="form">
                <table style="width:95%;" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width:175px;">Type of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('org_type_id', array('type' => 'select', 'options' => $org_type_options, 'id' => 'org_types', 'empty' => '---Select---', 'label' => false)); ?>
                    </tr>
                    <tr id="common_name"><!-- 'id' => 'form_sig', -->
                        <td colspan="3">
                            <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:175px;">Name of NGO-MFI</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('full_name_of_org', array('id' => 'form_org_name', 'type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Primary Registration</td>
                                    <td class="colons">:</td>
                                    <td>
                                        <div style="margin:0; padding:2px;">
                                            <?php
                                            if (!empty($primary_reg_act_options)) {
                                                echo $this->Form->input("primary_reg_act_id", array('id' => 'PrimaryRegActId', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-inline-checkbox', 'options' => $primary_reg_act_options, 'escape' => false, 'div' => false, 'label' => false));
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr id="new_mfi">
                        <td>District</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('district_id', array('type' => 'select', 'options' => $district_options, 'id' => 'districts', 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr id="licensed_mfi">
                        <td>License No.</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('license_no', array('type' => 'text', 'label' => false)); ?></td>
                    </tr>
                    <tr id="common_others">
                        <td colspan="3">
                            <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:175px; vertical-align:top;">Address</td>
                                    <td class="colons" style="vertical-align:top;">:</td>
                                    <td style="vertical-align:top;">
                                        <?php echo $this->Form->input('address_of_org', array('id' => 'form_address', 'type' => 'textarea', 'escape' => false, 'rows' => '5', 'cols' => '5', 'label' => false)); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Name of Authorized Person</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('name_of_authorized_person', array('id' => 'form_name', 'type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('designation_of_authorized_person', array('id' => 'form_desig', 'type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Phone No.</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('phone_no', array('id' => 'form_phone_no', 'type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Mobile No.</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('mobile_no', array('id' => 'form_mobile_no', 'type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('fax_no', array('id' => 'form_fax', 'type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>E-Mail</td>
                                    <td class="colons">:</td>
                                    <td>
                                        <?php
                                        echo $this->Form->input('email', array('id' => 'form_e_mail', 'type' => 'text', 'label' => false));
                                        echo $this->Form->input('approval_status', array('value' => 0, 'type' => 'hidden', 'label' => false));
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td>
                                        <div class="captcha">
                                            <?php $this->Captcha->render(); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="btns-div" id="buttons">
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>                                                
                        <td>
                            <?php
                                echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers', 'action' => 'login'), array_merge($pageLoading, array('update' => '#main_content', 'class' => 'mybtns', 'div' => false, 'confirm' => 'Are you sure to Close ?',)));
                            ?>
                        </td>
                        <td>
                            <a id="licenseAppPreview" class="mybtns" onclick="modal_open('license_app');">Next</a>
                        </td>
                    </tr>
                </table>
            </div>

        </fieldset>

        <div id="license_app_bg" class="modal-bg">

            <div id="license_app" class="modal-content">

                <div id="license_app_title" class="modal-title">
                    <span class="modal-title-txt">Application for License</span>
                    <button class="close" onclick="if (confirm('Are you sure to Close ?'))
                                modal_close('license_app');
                            return false;">✖</button>
                </div>

                <div id="app_content" style="margin:0; padding:2px; font-size:9.75pt; font-family:Verdana,Helvetica,Arial,Sans-serif;">
                    <div class="modal-body-content">
                        <div style="margin:10px; padding:5px; font-size:11pt; font-weight:bold; line-height:1.85; text-align:center;">
                            Microcredit Regulatory Authority Regulation, 2010<br />
                            Annexure A<br />
                            [Clause 3(1)]
                        </div>

                        <div style="margin:10px; padding:5px; font-size:10pt; font-weight:bold; text-align:right; ">
                            <span id="sl_no" style="border:1px solid #123; margin:0; padding:7px;">Serial No.: 4512784/16 </span>
                        </div>

                        <div class="mra_logo" style="height:100px; margin:10px; padding:5px; font-size:10pt; font-weight:bold; text-align:center; background: url('css/../img/mra_logo.png') no-repeat scroll left top / auto 100px;">
                            Microcredit Regulatory Authority<br />
                            62/3, Purana Paltan, NSC Tower (11<sup>th </sup> Floor)<br />
                            Dhaka-1000.
                        </div>

                        <div style="margin:10px; padding:5px; font-size:9.75pt; text-align:left;">
                            Executive Vice Chairman<br />
                            Microcredit Regulatory Authority<br />
                            62/3, Purana Paltan<br />
                            National Sports Council Tower<br />
                            Dhaka.<br />
                        </div>

                        <div style="margin:10px; padding:5px; font-size:10pt; font-weight:bold; text-align:center;">Application for License</div>

                        <div style="margin:10px; padding:5px; font-size:9.75pt; font-weight:normal; text-align:justify;">
                            Dear Sir,<br />

                            In order to carry out Microcredit operations in Bangladesh as a Microcredit Organization, 
                            I / We do hereby apply, as per Article 16 of the Microcredit Regulatory Authority Act, 
                            2006 (Law 32 of the year 2006) for obtaining License from your office with necessary supporting documents, 
                            being registered under sub-clause <span id="sub_clause" style="min-width:50px; font-weight:bold; text-decoration:underline;"></span> mentioned in clause 2(21) of the Act. 
                            Sincerely yours

                        </div>

                        <div style="margin:10px; padding:5px;">
                            <table border="0" rules="none" cellpadding="0"  style="width:85%; margin:0 auto; font-size:9.75pt; font-weight:bold;">
                                <tr>
                                    <td style="width:160px">Signature</td>
                                    <td style="width:15px;" class="colons">:</td>
                                    <td id="sig">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td class="colons">:</td>
                                    <td id="name"></td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td class="colons">:</td>
                                    <td id="desig"></td>
                                </tr>
                                <tr>
                                    <td>Name of Organization</td>
                                    <td class="colons">:</td>
                                    <td id="org_name"></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td class="colons">:</td>
                                    <td id="address"></td>
                                </tr>
                                <tr>
                                    <td>Phone No.</td>
                                    <td class="colons">:</td>
                                    <td id="phone_no"></td>
                                </tr>
                                <tr>
                                    <td>Mobile No.</td>
                                    <td class="colons">:</td>
                                    <td id="mobile_no"></td>
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td class="colons">:</td>
                                    <td id="fax"></td>
                                </tr>
                                <tr>
                                    <td>E-mail</td>
                                    <td class="colons">:</td>
                                    <td id="e_mail"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div style="margin:0; padding:2px; font-weight:bold; font-size:10pt; text-align:center; background:#f4f5f7;">
                        <label><input type="checkbox" id="cb_admit">I accept and understand all the terms and conditions.</label>
                    </div>
                </div>

                <div class="btns-div" style="margin-top:0;">
                    <table style="width:100%; margin:0 auto; padding:0;" cellspacing="5">
                        <tr>
                            <td style="width:50%">&nbsp;</td>
                            <td>
                                <?php
                                echo $this->Js->submit('Submit', array_merge($pageLoading, array('id' => 'app_submit', 'class' => 'modal-btns disabled', 'div' => false,
                                    'confirm' => 'Are you sure to submit ?',
                                    'success' => "modal_close('license_app'); msg.init('success', 'Application for License', 'Application submition has been completed.');",
                                    'error' => "msg.init('error', 'Application for License', 'Application submition failed !');")));
                                ?>
                            </td>
                            <td>
                                <button id="btnClose" class="modal-close" onclick="if (confirm('Are you sure to Close ?'))
                                            modal_close('license_app');
                                        return false;">Close</button>
                            </td>
                            <td>
                                <button id="btnPrint" class="modal-btns" onclick="print_content('app_content', 'License Application');
                                        return false;">Print</button>
                            </td>
                            <td>
                                <button id="btnPdf" class="modal-btns" onclick="create_pdf('app_content', 'License Application');
                                        return false;">Save as PDF</button>
                            </td>
                            <td style="width:50%">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>

    </div>
</div>

<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>

<script type="text/javascript">

    $('.creload').on('click', function () {
        $capcha = $(this).prev("img");
        $mySrc = $capcha.attr('src');
        $mySrc = $mySrc +
                (($mySrc.indexOf('?') > -1) ? '&' : '?') +
                new Date().getTime();
        $capcha.attr('src', $mySrc);
        return false;
    });

    function show_new_mfi() {
        $("#new_mfi").show();
        $("#buttons").show();
        $("#common_name").show();
        $("#common_others").show();
        $("#licensed_mfi").hide();
    }

    function show_licensed_mfi() {
        $("#licensed_mfi").show();
        $("#buttons").show();
        $("#common_name").show();
        $("#common_others").show();
        $("#new_mfi").hide();
    }

    function hide_all() {
        $("#new_mfi").hide();
        $("#licensed_mfi").hide();
        $("#buttons").hide();
        $("#common_name").hide();
        $("#common_others").hide();
    }


//    function check_option() {
//        $('#cb_admit').on('click', function () {
//            $('#app_submit').prop("disabled", !this.checked);
//        });
//    }

//    function check_admit(is_checked) {
//        if (is_checked)
//            $('#app_submit').prop('disabled', false).removeClass('disabled');
//        else
//            $('#app_submit').prop('disabled', true).addClass('disabled');
//
//        return false;
//    }

    function modal_open(content) {
        load_data();
        $("#" + content).css({top: '-350px', left: 0, opacity: 0});
        $("#" + content + "_bg").fadeIn(350, function () {
            $("#" + content).animate({top: '0', opacity: 1}, 500);
        });
    }

    function modal_close(content) {
        $("#" + content).animate({top: '-350px', opacity: 0}, 500, function () {
            $("#" + content + "_bg").fadeOut(350);
        });
    }

    function load_data() {
        $('#sl_no').html("Serial No.: 4512784/16");
        $('#sub_clause').html("MRA Act-2006");

        //$('#sig').html($('#form_sig').val());
        $('#name').html($('#form_name').val());
        $('#desig').html($('#form_desig').val());
        $('#org_name').html($('#form_org_name').val());
        $('#address').html($('#form_address').val());
        $('#phone_no').html($('#form_phone_no').val());
        $('#mobile_no').html($('#form_mobile_no').val());
        $('#fax').html($('#form_fax').val());
        $('#e_mail').html($('#form_e_mail').val());
        return;
    }

    function print_content(print_div_id, print_title, print_css_class) {

        if (!confirm('Are you sure to Print ?'))
            return false;

        if (!print_title)
            print_title = "MRA :: MIS-DBMS";

        $('#' + print_div_id + ' > div.modal-body-content').css({'height': 'auto', 'max-height': '200%', 'overflow': 'visible'});
        var print_content = $('#' + print_div_id).clone(true).html();
        $('#' + print_div_id + ' > div.modal-body-content').removeAttr('style');

        var w = 1000;
        var h = 640;
        if (window.screen) {
            w = window.screen.availWidth - 100;
            h = window.screen.availHeight - 80;
        }

        var objWindow = window.open("MRA :: MIS-DBMS", "PrintWindow", "top=35,left=50,width=" + w + ",height=" + h +
                ",location=0,toolbar=0,statusbar=0,menubar=0,scrollbars=1,resizable=1");

        objWindow.document.write('<html> <head><title>' + print_title + '</title></head>');
        objWindow.document.write('<body><div class="' + print_css_class + '">' + print_content + '</div></body></html>');
        objWindow.document.close();
        objWindow.focus();
        objWindow.print();

        return false;
    }



    function create_pdfX(content_id, pdf_title) {

        var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };
        doc.fromHTML($('#' + content_id).html(), 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });
        doc.save(pdf_title + '.pdf');
    }


    function create_pdf(content_id, pdf_title) {
        var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };

        doc.fromHTML($('#' + content_id).get(0), 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });
        doc.save(pdf_title + '.pdf');
    }

    $(document).ready(function () {
        draggable_modal('license_app_title', 'license_app', 'license_app_bg');
        $('#cb_admit').on('change', function () {
            if (this.checked)
                $('#app_submit').prop('disabled', false).removeClass('disabled');
            else
                $('#app_submit').prop('disabled', true).addClass('disabled');
            return false;
        });
        selectedVal = $("#org_types option:selected").val();
        var selected_value = parseInt(selectedVal);
        if (selectedVal == "") {
            hide_all();
        } else {
            if (selected_value == 1) {
                show_new_mfi();
            } else if (selected_value == 2) {
                show_licensed_mfi();
            } else {
                hide_all();
            }
        }

        $('#org_types').change(function () {
            if ($(this).val() == "1") {
                show_new_mfi();
            } else if ($(this).val() == "2") {
                show_licensed_mfi();
            } else {
                hide_all();
            }
        });
    });
</script>