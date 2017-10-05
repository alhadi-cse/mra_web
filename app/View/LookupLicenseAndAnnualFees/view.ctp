<div id="frmTypeOfOrg_view">
    <?php 
        $title = "License and Annual Fees Information"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

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
                            echo $this->Form->create('LookupLicenseAndAnnualFee');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search By</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option', 
                                                array('label' => false, 'style'=>'width:200px',
                                                    'options' => array('LookupLicenseAndAnnualFee.serial_no' => 'Serial no.',
                                                            'LookupLicenseAndAnnualFee.min_no_of_borrowers' => 'Minimum no. of Borrowers',
                                                            'LookupLicenseAndAnnualFee.max_no_of_borrowers' => 'Maximum no. of Borrowers'
                                                        ))
                                                    );
                                    ?>
                                </td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
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
                        <div id="searching" style="width:700px;">              
                            <table class="view">
                                <tr>
                                    <?php
                                        echo "<th style='min-width:50px;'>" . $this->Paginator->sort('LookupLicenseAndAnnualFee.serial_no', 'Serial no.') . "</th>";
                                        echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupLicenseAndAnnualFee.min_no_of_borrowers.', 'no. of Borrowers') . "</th>";
                                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupLicenseAndAnnualFee.license_fee', 'License Fee') . "</th>";
                                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupLicenseAndAnnualFee.annual_fee', 'Annual Fee') . "</th>";
                                        echo "<th style='width:115px;'>Action</th>";
                                    ?> 
                                </tr>
                               <?php foreach($values as $value){ ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $value['LookupLicenseAndAnnualFee']['serial_no']; ?></td>
                                    <td style="text-align: justify;"><?php echo ">".$value['LookupLicenseAndAnnualFee']['min_no_of_borrowers']." and <".$value['LookupLicenseAndAnnualFee']['max_no_of_borrowers']; ?></td>   
                                    <td style="text-align: justify;"><?php echo $value['LookupLicenseAndAnnualFee']['license_fee']; ?></td> 
                                    <td style="text-align: justify;"><?php echo $value['LookupLicenseAndAnnualFee']['annual_fee']; ?></td>        
                                    <td style="text-align:center; padding:2px; height:30px;">
                                        <?php 
                                            echo $this->Js->link('Edit', array('controller' => 'LookupLicenseAndAnnualFees','action' => 'edit', $value['LookupLicenseAndAnnualFee']['id']), array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Delete', array('controller' => 'LookupLicenseAndAnnualFees','action' => 'delete', $value['LookupLicenseAndAnnualFee']['id']), 
                                                    array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');",'class'=>'btnlink')));
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
        <div id="popup_div" style="display:none;">
        </div>
        <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
          <?php 
            echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
                 $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                 $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
          ?>
        </div>
        <?php } ?>        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php 
                            echo $this->Js->link('Add New', array('controller'=>'LookupLicenseAndAnnualFees','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td>                        
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