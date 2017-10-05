<option value="">---Select---</option>
<?php
if (!empty($union_options))
{
    foreach ($union_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

