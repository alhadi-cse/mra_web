
<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$user_id = $this->Session->read('User.Id');

if (!$user_id) {
    ?>

    <div id="home_content" style="padding:0 20px;">

        <fieldset style="border:1px solid #ddd;">
            <legend>About MFI-DBMS</legend>

            <?php
            echo $this->requestAction(array('controller' => 'Mrahome', 'action' => 'home_info'), array('return'));

            $pageLoading = array('update' => '#main_content', 'evalScripts' => true, 'class' => 'btnlink',
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

            echo '<div style="width:auto; padding:0 15px 3px; text-align:right;">' . $this->Js->link('Go >>', array('controller' => 'AdminModuleUsers', 'action' => 'login'), $pageLoading) . '</div>';
            ?>

        </fieldset>

    </div>

<?php } else { ?>
    <table cellpadding="0" cellspacing="0" style="width:100%;">
        <tr>
            <td class="content-left-td">
                <div class="left-menu-btns">
                    <?php
                    echo "<a id='full_screen_home' class='btn-full-screen menu-top-btns' style='float:left;' title='Full Screen' href='#'></a>";
                    echo "<strong style='font-size:15px; line-height:27px; color:#0373af;'>M E N U</strong>";
                    echo "<a id='left_menu_option' class='left-menu-btn left-menu-hide' style='float:right;' title='Hide Left Menu' href='#'></a>";
                    ?>
                    <div style="clear:both;"></div>
                </div>

                <div class="menu-content">
                    <?php echo $this->element('left_menu', array("variable_name" => "current")); ?>
                </div>
            </td>
            <td style="width:auto; min-width:8px;">&nbsp;</td>
            <td class="content-right-td">
                <div id="data_content" class="data-content">
                    <div class="user-info">
                        <?php
                        echo "<strong>";
                        $user_name = $this->Session->read('User.Name');
                        if (!empty($user_name)) {
                            $user_group_id = $this->Session->read('User.GroupId');
                            if (isset($user_group_id) && $user_group_id == 1)
                                $user_name .= '(admin)';

                            echo __("User Id: <span>%s</span>", $user_name);
                        }
                        echo "</strong>";

                        $left_menu_close_script = '$("#accordian > ul").find("li.active").first().find("ul").slideUp(500, function() {$("#accordian > ul").find(".active").removeClass("active");}); ';
                        $pageLoading = array('update' => '#ajax_div', 'class' => 'btn-close data-close', 'evalScripts' => true,
                            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                            'complete' => $left_menu_close_script . $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                            'title' => 'Back to Home');

                        echo $this->Js->link('', array('controller' => 'Mrahome', 'action' => 'user_info'), $pageLoading);

                        echo "<a id='full_screen_data' class='btn-full-screen data-full-screen' title='Full Screen' href='#'></a>";
                        ?>
                    </div>

                    <div id="ajax_div">
                        <?php echo $this->requestAction(array('controller' => 'Mrahome', 'action' => 'user_info'), array('return')); ?>
                    </div>

                    <?php //echo $this->Js->link('Letter', array('controller' => 'SupervisionModuleIssueLetterToMfiDetails', 'action' => 'preview_letter'), array_merge($pageLoading, array('class'=>'mybtns'))); ?>

                </div>

                <div id="popup_div" style="width:0; height:0; margin:0; padding:0;"></div>

            </td>
        </tr>
    </table>

<?php } ?>

<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>

<script>

    $(document).ready(function () {

        $("#left_menu_option").on("click", function () {
            var $isOpen = ($(".content-left-td").width() > 250);
            if ($isOpen) {
                $(".menu-content").toggle("slide", {direction: "left"}, 750, function () {
                    $(".content-left-td").animate({width: "30px"}, 350);
                    $("#left_menu_option").removeClass("left-menu-hide").addClass("left-menu-show").attr("title", "Show Left Menu");
                });

            } else {
                $(".content-left-td").animate({width: "274px"}, 500, function () {
                    $(".menu-content").toggle("slide", {direction: "left"}, 750);
                    $("#left_menu_option").removeClass("left-menu-show").addClass("left-menu-hide").attr("title", "Hide Left Menu");
                });
            }
            return false;
        });


        $("#full_screen_home").on("click", function () {
            if ($("#main_content").hasClass("full-screen")) {
                $("#main_content").removeClass("full-screen");
                $(this).removeClass("btn-exit-full-screen").addClass("btn-full-screen").attr("title", "Full Screen");
            } else {
                $("#main_content").addClass("full-screen");
                $(this).removeClass("btn-full-screen").addClass("btn-exit-full-screen").attr("title", "Exit Full Screen");
            }
            return false;
        });

        $("#full_screen_data").on("click", function () {
            if ($("#data_content").hasClass("full-screen")) {
                $("#data_content").removeClass("full-screen");
                $(this).removeClass("btn-exit-full-screen").addClass("btn-full-screen").attr("title", "Full Screen");
            } else {
                $("#data_content").addClass("full-screen");
                $(this).removeClass("btn-full-screen").addClass("btn-exit-full-screen").attr("title", "Exit Full Screen");
            }
            return false;
        });

    });

</script>
