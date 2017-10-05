
<option value="">---Select---</option>
<?php 
if (!empty($period_type_options))
{
?>    
<?php
    foreach ($period_type_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

