

<div class="datagrid"  id="basicInfo" title="Detail" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>            
        <table cellpadding="7" cellspacing="8" border="0">
            <tr>
                <td style="width:120px; padding:3px 5px; font-weight:bold; vertical-align:top;">Agency of Name</td>
                <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">:</td>
                <td style="padding:3px 8px; font-weight:bold; vertical-align:top;">
                    <?php
                    if (!empty($org_details['CDBNonMfiBasicInfo']['name_of_org']))
                        echo $org_details['CDBNonMfiBasicInfo']['name_of_org'];
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width:120px; padding:3px 5px; font-weight:bold; vertical-align:top;">Type of Agency</td>
                <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">:</td>
                <td style="padding:3px 8px; font-weight:bold; vertical-align:top;">
                    <?php
                    if (!empty($org_details['LookupCDBNonMfiType']['type_name']))
                        echo $org_details['LookupCDBNonMfiType']['type_name'];
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width:120px; padding:3px 5px; font-weight:bold; vertical-align:top;">Ministry/Authority</td>
                <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">:</td>
                <td style="padding:3px 8px; font-weight:bold; vertical-align:top;">
                    <?php
                    if (!empty($org_details['LookupCDBNonMfiMinistryAuthorityName']['name_of_ministry_or_authority']))
                        echo $org_details['LookupCDBNonMfiMinistryAuthorityName']['name_of_ministry_or_authority'];
                    ?>
                </td>
            </tr>
            <tr>
                <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">Covered Districts</td>
                <td style="padding:3px 5px; font-weight:bold; vertical-align:top;">:</td>
                <td style="padding:0; vertical-align:top;">
                    <div style="width:100%; max-height:50vh; padding:0; overflow:auto; columns:4; column-gap:8px; column-count:4;"> 
                        <?php
                        echo $this->Form->input("district_ids", array('type' => 'select', 'id' => "district_id", 'multiple' => 'checkbox', 'class' => 'multi-checkbox', 'options' => $all_dist_list, 'div' => false, 'escape' => true, 'label' => false));
                        ?>
                    </div>
                </td>
            </tr>

        </table> 
    </fieldset>
</div>

<script>
    $(function () {
        $("#basicInfo").dialog({
            modal: true, width: 850,
            buttons: {
                Close: function () {
                    $(this ).dialog("close");
                }
            }
        });
    });
</script>