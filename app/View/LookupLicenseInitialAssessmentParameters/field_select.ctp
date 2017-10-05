<option value="">--Select--</option>
<?php 
if (!empty($field_options))
{
?>    
<?php
    foreach ($field_options as $key => $value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

