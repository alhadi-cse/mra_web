<option value="">---Select---</option>
<?php
if (!empty($options)) {
    ?>
    <?php
    foreach ($options as $key => $value):
        ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
        <?php
    endforeach;
}
?>