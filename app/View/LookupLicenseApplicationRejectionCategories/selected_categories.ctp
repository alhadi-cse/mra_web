
<option value="">-----Select-----</option>
<?php
if (!empty($category_options)) {
    foreach ($category_options as $value => $option) {
        echo "<option value='$value'>$option</option>";
    }
}
?>
