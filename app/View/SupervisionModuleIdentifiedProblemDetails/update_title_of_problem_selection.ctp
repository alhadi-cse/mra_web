<option value="">---Select---</option>
<?php 
if (!empty($title_of_problem_options))
{
?>    
<?php
    foreach ($title_of_problem_options as $key=>$value): 
?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php
    endforeach; 
}
?>

