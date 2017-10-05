<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = 'General Information of Agency(Non-MFI)';
$isAdmin = !empty($user_group_id) && $user_group_id == 1;
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>

<div id="frmTypeOfOrg_view">
    <fieldset>
        <legend><?php echo $title; ?></legend>                
        <div class="form"> 
            <table>
                <?php if (empty($org_id)) { ?>
                    <tr>
                        <td style="text-align:justify;">
                            <?php echo $this->Form->create('CDBNonMfiBasicInfo'); ?>
                            <table cellpadding="0" cellspacing="0" border="0">                           
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search by</td>
                                    <td>
                                        <?php
                                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:215px',
                                            'options' =>
                                            array('CDBNonMfiBasicInfo.name_of_org' => 'Name of Agency',
                                                'LookupCDBNonMfiType.type_name' => 'Type of Agency',
                                                'LookupCDBNonMfiMinistryAuthorityName.name_of_ministry_or_authority' => 'Ministry/Authority')
                                        ));
                                        ?>
                                    </td>
                                    <td style="font-weight:bold;">:</td>
                                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                                    <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                                    <td>
                                        <?php
                                        if (!empty($opt_all) && $opt_all && $isAdmin) {
                                            echo $this->Js->link('View All', array('controller' => 'CDBNonMfiCoveredDistricts', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <?php echo $this->Form->end(); ?> 
                        </td>        
                    </tr>
                <?php } ?>
                <tr>
                    <td>
                        <div id="searching">
                            <style>
                                .view td {
                                    vertical-align: top;
                                }
                            </style>
                            <table class="view" style="width:780px;">
                                <tr>
                                    <th style="width:200px;"><?php echo $this->Paginator->sort('CDBNonMfiBasicInfo.name_of_org', 'Name of Agency') ?></th>
                                    <th style="width:120px;"><?php echo $this->Paginator->sort('LookupCDBNonMfiType.type_name', 'Type of Agency') ?></th>
                                    <th style="width:150px;"><?php echo $this->Paginator->sort('LookupCDBNonMfiMinistryAuthorityName.name_of_ministry_or_authority', 'Ministry/Authority') ?></th>
                                    <th style="width:185px;">Covered Districts</th>
                                    <th style="width:65px;">Action</th>
                                </tr>
                                <?php foreach ($org_list as $org_detail) { ?>
                                    <tr>
                                        <td><?php echo $org_detail['CDBNonMfiBasicInfo']['name_of_org']; ?></td>
                                        <td><?php echo $org_detail['LookupCDBNonMfiType']['type_name']; ?></td>
                                        <td><?php echo $org_detail['LookupCDBNonMfiMinistryAuthorityName']['name_of_ministry_or_authority']; ?></td>
                                        <td>
                                            <?php
                                            $orgId = $org_detail['CDBNonMfiBasicInfo']['id'];
                                            if ($orgId && !empty($dist_list[$orgId])) {
                                                $distList = implode(" ➤", $dist_list[$orgId]);
                                                echo ($distList ? "➤" . $distList : "" );
                                                //● ➧ ➤ ❖
                                            }
                                            ?>
                                        </td> 
                                        <td style="text-align:center; padding:2px; height:30px;">
                                            <?php
                                            echo $this->Js->link('Edit', array('controller' => 'CDBNonMfiCoveredDistricts', 'action' => 'edit', $org_detail['CDBNonMfiBasicInfo']['id']), array_merge($pageLoading, array('class' => 'btnlink')))
                                            . $this->Js->link('Details', array('controller' => 'CDBNonMfiCoveredDistricts', 'action' => 'details', $org_detail['CDBNonMfiBasicInfo']['id']), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink',)));
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>                               
                        </div>
                    </td>                
                </tr>
            </table>
        </div>
        <div id="popup_div" style="display:none;">
        </div>
        <?php if ($org_list && $this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php
                echo $this->Paginator->prev('<<', array('class' => 'prevPg'), null, array('class' => 'prevPg no_link')) .
                $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                $this->Paginator->next('>>', array('class' => 'nextPg'), null, array('class' => 'nextPg no_link'));
                ?>
            </div>
        <?php } ?>
    </fieldset>
</div>  

<script>
    $(document).ready(function () {
        $(".paging a").click(function () {
            $("#ajax_div").load(this.href);
            return false;
        });
    });
</script>
