<option value="">---Select---</option>
<?php
if (!empty($menu_options))
{
    foreach ($menu_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

