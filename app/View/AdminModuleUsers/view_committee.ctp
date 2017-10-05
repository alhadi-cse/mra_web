<div id="frmUserInfo_view">
    <?php 
        $title = "Committee Information";
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
          <?php echo $title; ?>  
        </legend>         
        <div class="form"> 
            <table >
                <tr>        
                    <td style="text-align: justify;font-family: verdana,helvetica,arial;">
                        <?php 
                            $message = "";
                            if (!empty($msg)){
                                $message = $msg;
                            }
                            echo $this->Form->create('AdminModuleUser');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search By</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option', 
                                                array('label' => false, 'style'=>'width:200px',
                                                    'options' => array('AdminModuleUserGroupDistribution.user_group_id' => 'User Group'))
                                                    );
                                    ?>
                                </td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('search_keyword',array('type'=>'select','options'=>$user_group_options,'empty'=>'---Select---', 'label'=>false, 'style' => 'width:200px;')); ?></td>
                                <td style="text-align:left;">
                                   <?php
                                       echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                                    ?>
                               </td>               
                            </tr>
                        </table>
                        <?php  echo $this->Form->end(); ?> 
                    </td>        
                </tr>
                <tr>
                    <td>
                        <div id="searching" style="width:750px;">              
                            <table class="view">
                                <tr>
                                    <?php
                                        echo "<th style='min-width:200px;'>" . $this->Paginator->sort('AdminModuleUserGroup.group_name', 'User Group') . "</th>";
                                        echo "<th style='width:75px;'>" . $this->Paginator->sort('AdminModuleUser.user_name', 'User Name') . "</th>";
                                        echo "<th style='width:150px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name', 'Full Name') . "</th>";
                                        echo "<th style='width:105px;'>" . $this->Paginator->sort('LookupUserCommitteeMemberType.committee_member_type', 'Member Type') . "</th>";
                                        echo "<th style='width:150px;'>Action</th>";
                                    ?>
                                </tr>
                               <?php foreach($values as $value){ ?>
                                <tr>
                                    <td style="text-align: justify;"><?php echo $value['AdminModuleUserGroup']['group_name']; ?></td>
                                    <td style="text-align: justify;"><?php echo $value['AdminModuleUser']['user_name']; ?></td>
                                    <td style="text-align: justify;"><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                                    <td style="text-align: justify;">
                                        <?php 
                                         $membertypes = $value['AdminModuleUserWithCommitteeMemberType']; 
                                         $member_type_title='';
                                         foreach($membertypes as $membertype){
                                            $member_type_title .= $membertype['LookupUserCommitteeMemberType']['committee_member_type'].', ';    
                                         }
                                         $member_type_title = rtrim($member_type_title, ", ");
                                         echo $member_type_title;
                                        ?>
                                    </td>
                                    <td style="text-align: justify;">
                                        <?php
                                            echo $this->Js->link('Edit', array('confirm' => 'Are you sure to edit?','controller' => 'AdminModuleUsers','action' => 'edit', $value['AdminModuleUser']['id']), array_merge($pageLoading, array('class'=>'btnlink'))).
                                                 $this->Js->link('Details', array('controller' => 'AdminModuleUsers','action' => 'details', $value['AdminModuleUser']['id']), array_merge($pageLoading, array('class'=>'btnlink')));  
                                        ?>
                                    </td>
                                </tr>
                               <?php  } ?>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
            <?php
            if($this->Paginator->param('pageCount')>5){
                echo $this->Paginator->first('<<', array('class'=>'prevPg', 'title'=>'Goto first page.'), null, array('class'=>'prevPg no_link')).
                     $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                     $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                     $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                     $this->Paginator->last('>>', array('class' => 'nextPg', 'title' => 'Goto last page.'), null, array('class' => 'nextPg no_link'));
            }
            else {
                echo $this->Paginator->prev('<<', array('class'=>'prevPg', 'title'=>'Goto previous page.'), null, array('class'=>'prevPg no_link')).
                     $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                     $this->Paginator->next('>>', array('class'=>'nextPg', 'title'=>'Goto next page.'), null, array('class'=>'nextPg no_link'));
            }
          ?>
        </div>
        <?php } ?>
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php  echo $this->Js->link('Add New User', array('controller'=>'AdminModuleUsers','action'=>'add_committee'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'')));  ?>     
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
<script>
    $(document).ready(function(){
        $(".paging a").click(function(){
            $("#ajax_div").load(this.href);
            return false;
        })
    });
</script>