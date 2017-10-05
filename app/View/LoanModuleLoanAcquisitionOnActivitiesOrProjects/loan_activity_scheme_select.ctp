<option value="">---Select---</option>
<?php
if (!empty($loan_activity_scheme_options))
{
    foreach ($loan_activity_scheme_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

