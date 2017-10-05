<option value="">---Select---</option>
<?php 
if (!empty($reason_options))
{
?>    
<?php
    foreach ($reason_options as $key => $value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

