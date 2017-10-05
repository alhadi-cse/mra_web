<?php if (!empty($user_group_lists)) { ?>
<table align="left">
    <tr>
        <td style="vertical-align: top;" style="vertical-align: top; min-width: 400px;">Already Assigned User Groups</td>
        <td style="padding: 0 15px 0 0px; vertical-align: top;" class="colons">:</td>
        <td style="border: 1px solid #05A7DE; border-radius: 4px; padding: 5px; min-width: 230px;">
            <ul style="padding-left: 15px;">
                <?php foreach ($user_group_lists as $key=>$value): ?>
                    <li><?php echo $value; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
</table>
<?php } ?>