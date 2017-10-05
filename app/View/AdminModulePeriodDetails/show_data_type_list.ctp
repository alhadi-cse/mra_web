<?php if (!empty($data_type_options)) { ?>   
<table align="left">
    <tr>
        <td style="padding-top: 5px;vertical-align: top;" style="vertical-align: top;">Type of Data</td>
        <td style="padding-left: 15px; vertical-align: top;" class="colons">:</td>
        <td style="padding-top: 5px;">
            <ul style="padding-left: 15px;">                 
                <?php foreach ($data_type_options as $key=>$value): ?>
                    <li><?php echo $value; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
</table> 
<?php  } ?>
