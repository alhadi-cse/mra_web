<option value="">--Select--</option>
<?php 
if (!empty($parameter_group_options))
{
?>    
<?php
    foreach ($parameter_group_options as $key => $value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

