
<option value="">-----Select-----</option>
<?php
if (!empty($reason_options)) {
    foreach ($reason_options as $value => $option) {
        echo "<option value='$value'>$option</option>";
    }
}
?>
