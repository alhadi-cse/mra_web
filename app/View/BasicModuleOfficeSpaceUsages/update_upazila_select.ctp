<option value="">---Select---</option>
<?php 
if (!empty($upazila_options))
{
?>    
<?php
    foreach ($upazila_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

