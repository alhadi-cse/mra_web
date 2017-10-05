<option value="">---Select---</option>
<?php 
if (!empty($user_group_options)) {
?>    
<?php
    foreach ($user_group_options as $key=>$value):
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

