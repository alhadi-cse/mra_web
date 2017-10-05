<div id="frmBasicInfo_add">
    <?php
    $title = "Update Covered Districts List";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('CDBNonMfiCoveredDistrict'); ?>
        <div class="form">            
            <table cellpadding="0" cellspacing="0" border="0"> 
                <tr>
                    <td style="width:120px; padding:3px 5px; font-weight:bold; vertical-align:top;">Agency Name</td>
                    <td style="width:5px; padding:3px 5px; font-weight:bold; vertical-align:top;">:</td>
                    <td style="padding:3px 8px; font-weight:bold; vertical-align:top;">
                        <?php
                        if (!empty($org_details))
                            echo $org_details['CDBNonMfiBasicInfo']['name_of_org'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">Covered Districts</td>
                    <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">:</td>
                    <td style="padding:3px; vertical-align:top;">
                        <fieldset>
                            <?php
//                            $legend_title = "<label for='rpt_$rpt_id'>▣ $report_title</label>"
//                                    . "<input type='checkbox' id='all_dist' class='all_dist' style='margin:0 0 0 4px; vertical-align:middle;'>";

                            echo "<label for='all_dist' style='padding:7px 0 7px 7px; font-weight:bold;'>▣ All Districts</label>"
                            . "<input type='checkbox' id='all_dist' class='all_dist' style='margin:0 0 0 4px; vertical-align:middle;'> <br />";


                            //$this->Js->get('#all_dist')->event('change', 'CheckAllMultiCheckBox(this);');
                            //$this->Js->get('#all_dist')->event('change', 'CheckAllMultiCheckBox(this);');
                            ?>
                            <div style="width:100%; max-height:50vh; padding:5px; overflow:auto; columns:4; column-gap:8px; column-count:4;"> 
                                <?php
                                echo $this->Form->input("district_ids", array('type' => 'select', 'id' => "district_id", 'multiple' => 'checkbox', 'class' => 'multi-checkbox', 'options' => $all_dist_list, 'div' => false, 'escape' => true, 'label' => false));
                                ?>
                            </div>

                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Js->submit('Update', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                            'error' => "msg.init('error', '$title', '$title has been failed to update!');")));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'CDBNonMfiCoveredDistricts', 'action' => 'view', 'all'), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<script>
    $(function () {
        $(".all_dist").on("change", function (evt) {
            CheckAllMultiCheckBox(this);
        });
        $(".multi-checkbox input[type='checkbox']").on("change", function (evt) {
            CheckMultiCheckBox(this);
        });

        CheckMultiCheckBox("#all_dist");
    });

    function CheckAllMultiCheckBox(obj) {
        $(obj).closest("fieldset").find(".multi-checkbox input:checkbox").prop("checked", $(obj).prop("checked"));
    }

    function CheckMultiCheckBox(obj) {
        var $chkbParent = $(obj).closest("fieldset");
        var noOfCheckBoxes = $chkbParent.find(".multi-checkbox input:checkbox").length;

        var optChecked = null;
        if ($(this).prop("checked")) {
            var noOfCheckedBoxes = $chkbParent.find(".multi-checkbox input:checkbox:checked").length;
            if (noOfCheckedBoxes === 0) {
                optChecked = false;
            }
            if (noOfCheckedBoxes === noOfCheckBoxes) {
                optChecked = true;
            }
        } else {
            var noOfUnCheckedBoxes = $chkbParent.find(".multi-checkbox input:checkbox:not(:checked)").length;
            if (noOfUnCheckedBoxes === 0) {
                optChecked = true;
            }
            if (noOfUnCheckedBoxes === noOfCheckBoxes) {
                optChecked = false;
            }
        }

        $chkbParent.find("input:checkbox.all_dist").prop("indeterminate", optChecked === null);
        $chkbParent.find("input:checkbox.all_dist").prop("checked", optChecked);
        return false;
    }

</script>