<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    ?>

    <fieldset style="border:1px solid #ddd;">

        <div class="form">
            <?php
            $sl_no = 0;
            $all_num_sl_no = (!empty($model_id) && ($model_id == 9 || $model_id == 10));

            foreach ($field_details_list as $group_id => $group_field_list) {
                $legend_group = empty($legend_groups[$group_id]) ? false : $legend_groups[$group_id];
                echo $legend_group ? "<fieldset style='padding:0 0 4px 0;'><legend>&#9635; " . $legend_group . "</legend>" : '';

                foreach ($group_field_list as $sub_group_id => $field_list) {
                    $tbl_width = '95%';
                    $has_slno = !empty($group_id) && $group_id;
                    $sl_alph = false;

                    if (!empty($legend_sub_groups[$group_id][$sub_group_id])) {
                        $tbl_width = '100%';
                        ?>
                        <table style="width:95%;" cellpadding="5" cellspacing="5" border="0">
                            <tr>
                                <?php
                                if ($has_slno && !$all_num_sl_no) {
                                    $sl_no++;
                                    echo "<td style='width:25px; padding:5px 0; vertical-align:top; font-weight:bold;'>" .
                                    ($sl_no < 10 ? "0$sl_no." : "$sl_no.") .
                                    "</td>";
                                    echo "<td colspan='3' style='min-width:670px; padding:5px 0 0 0; font-weight:bold; vertical-align:top;'>";
                                } else {
                                    echo "<td colspan='4' style='min-width:685px; padding:5px 0 0 0; font-size:9.5pt; font-weight:bold; vertical-align:top;'>" .
                                    "&#9899; ";
                                }

                                echo htmlspecialchars($legend_sub_groups[$group_id][$sub_group_id]) . "  : </td>";

                                $has_slno = false;
                                $sl_alph = 'a';
                                ?>
                            </tr>
                            <tr>
                                <td colspan="4" style="margin:0; padding:0; text-align:left;">
                                <?php } ?>

                                <table style="width:<?php echo$tbl_width; ?>;" cellpadding="5" cellspacing="5" border="0">
                                    <?php
                                    foreach ($field_list as $field_detail) {
                                        $model_name = $field_detail['model_name'];
                                        $field_name = $field_detail['field_name'];
                                        $field_description = $field_detail['field_description'];

                                        $tr_style = (strpos($field_name, 'vf_cal_') === 0) ? ' style="font-weight:bold;"' : '';
                                        ?>

                                        <tr<?php echo $tr_style; ?>>
                                            <?php
                                            if ($has_slno) {
                                                ++$sl_no;
                                                echo "<td style='width:25px; padding:5px 0; vertical-align:top; text-align:center; font-weight:bold;'>";
                                                echo $sl_no < 10 ? "0$sl_no." : "$sl_no.";
                                                echo "</td>";

                                                $field_label_style = " style='width:385px; padding:5px 0; vertical-align:top;'";
                                            } else if ($sl_alph) {
                                                echo "<td style='width:25px; padding:5px 3px 5px 7px; vertical-align:top; text-align:right; font-weight:bold;'>";
                                                if ($all_num_sl_no) {
                                                    ++$sl_no;
                                                    echo $sl_no < 10 ? "0$sl_no." : "$sl_no.";
                                                } else if ($sl_alph) {
                                                    echo "$sl_alph)";
                                                    ++$sl_alph;
                                                } else if ($sl_no_sub) {
                                                    ++$sl_no_sub;
                                                    $this->requestAction("/AdminModuleDynamicNonMfiCrudFormGenerators/intToRoman/$sl_no_sub") . ".";
                                                }
                                                echo "</td>";

                                                $field_label_style = " style='width:370px; padding:5px 0; vertical-align:top;'";
                                            } else {
                                                $field_label_style = " colspan='2' style='width:250px; padding:5px 0; vertical-align:top;'";
                                            }
                                            ?>
                                            <td <?php echo $field_label_style; ?>><?php echo htmlspecialchars($field_description); ?></td>
                                            <td style='width:5px; padding:5px 0; font-weight:bold; vertical-align:top;'>:</td>
                                            <td style="min-width:470px; padding:5px 0; vertical-align:top;"><?php echo $field_values[$model_name][$field_name]; ?></td>
                                        </tr>

                                    <?php } ?>

                                </table>

                                <?php
                                if (!empty($legend_sub_groups[$group_id][$sub_group_id])) {
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <?php
                    }
                }
                echo $legend_group ? "</fieldset>" : '';
            }
            ?>
        </div>

    </fieldset>
<?php } ?>
