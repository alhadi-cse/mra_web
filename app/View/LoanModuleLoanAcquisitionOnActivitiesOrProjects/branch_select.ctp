<option value="">---Select---</option>
<?php 
if (!empty($branch_options))
{
?>    
<?php
    foreach ($branch_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

