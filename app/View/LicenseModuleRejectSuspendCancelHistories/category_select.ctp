<option value="">---Select---</option>
<?php 
if (!empty($category_options))
{
?>    
<?php
    foreach ($category_options as $key => $value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

