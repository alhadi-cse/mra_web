<option value="">---Select---</option>
<?php 
if (!empty($state_options))
{
?>    
<?php
    foreach ($state_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

