<option value="">---Select---</option>
<?php 
if (!empty($loan_activity_subcategory_options))
{
?>    
<?php
    foreach ($loan_activity_subcategory_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

