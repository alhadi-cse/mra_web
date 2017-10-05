<option value="">---Select---</option>
<?php
if (!empty($mauza_options)) {
    ?>    
    <?php
    foreach ($mauza_options as $key => $value):
        ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
        <?php
    endforeach;
}
?>

