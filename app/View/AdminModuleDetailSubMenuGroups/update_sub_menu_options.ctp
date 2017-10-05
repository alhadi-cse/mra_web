<option value="">---Select---</option>
<?php 
if (!empty($sub_menu_options)) {
?>    
<?php
    foreach ($sub_menu_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

