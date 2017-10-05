<option value="">--Select--</option>
<?php 
if (!empty($subcategory_options))
{
?>    
<?php
    foreach ($subcategory_options as $key => $value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

