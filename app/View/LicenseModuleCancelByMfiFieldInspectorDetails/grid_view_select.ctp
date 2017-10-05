<?php 
if(!empty($org_list_values))
{
?>
<table class="view">
    <tr>
        <?php
            $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

            $this->Paginator->options($pageLoading);
            $title = "Inspector for Initial Field Inspection";            
            echo "<th style='width:250px;'>" . $this->Paginator->sort('LookupLicenseInspectorList.inspector_name.', 'Name of Organization') . "</th>";
            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupLicenseInspectorList.inspector_name.', 'Form Serial No.') . "</th>";            
        ?>
    </tr>
   <?php foreach($org_list_values as $org_list_value){ ?>
    <tr> 
        <td style="text-align: justify;"><?php echo $org_list_value['org_name']; ?></td>
        <td style="text-align: center;"><?php echo $org_list_value['form_serial_no']; ?></td>
        <td><?php echo $this->Js->link('Details', array('controller'=>'BasicModuleBranchInfoes','action'=>'details_all', $org_list_value['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div'))); ?></td>
    </tr>
    <?php  } ?>
    <tr>       
        <td style="font-size: 15px;text-align: left; padding: 8px 5px; background-color: #EAF1FC; color: #032553;" colspan="2"><strong>Name of Inspectors :&nbsp;</strong><?php echo $inspector_names; ?></td>        
    </tr>   
</table>
<?php if($this->Paginator->param('pageCount')>1) { ?>
    <div class="paginator">
      <?php 
        echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
             $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
             $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
      ?>
    </div>
<?php } 
}
?>  

<script>    
    $(document).ready(function(){
        $(".paging a").click(function(){
            $("#ajax_div").load(this.href);
            return false;
        });
    });
</script>
