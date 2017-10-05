<?php if (!empty($data_type_options)) { 
    foreach ($data_type_options as $key=>$value): ?>
<input type="checkbox" name="data[AdminModulePeriodDetail][data_type_id][]" value="<?php echo $key; ?>" id="AdminModulePeriodDetailDataTypeId<?php echo $key; ?>" checked="true" /><label for="AdminModulePeriodDetailDataTypeId<?php echo $key; ?>"><?php echo $value; ?></label>
<?php endforeach; } ?>
