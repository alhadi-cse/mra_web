<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = "Identification of Problems";
    $isAdmin = !empty($user_group_id) && $user_group_id == 1;
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>        
        <div>
            <div class="form">                
                <div style="width:950px; height:auto; overflow-x:auto;">
                    <?php
                    if ($values == null || !is_array($values) || count($values) < 1) {
                        echo '<p class="error-message">';
                        echo 'Did not find any data !';
                        echo '</p>';
                    } else {
                        ?>
                        <table class="view">
                            <tr>
                                <?php
                                echo "<th style='width:100px;text-align:center;'>" . $this->Paginator->sort('LookupSupervisionTypeOfProblem.type_of_problems', 'Type of Problem') . "</th>";
                                echo "<th style='width:200px;'>" . $this->Paginator->sort('LookupSupervisionTitleOfProblem.title_of_problems', 'Title of Problem') . "</th>";
                                echo "<th style='width:450px;'>" . $this->Paginator->sort('SupervisionModuleIdentifiedProblemDetail.description_of_problem', 'Description of Problem') . "</th>";
                                ?>
                            </tr>
                            <?php foreach ($values as $value) { ?>
                                <tr>
                                    <td style='text-align:center;'><?php echo $value['LookupSupervisionTypeOfProblem']['type_of_problems']; ?></td> 
                                    <td><?php echo $value['LookupSupervisionTitleOfProblem']['title_of_problems']; ?></td>
                                    <td><?php echo $value['SupervisionModuleIdentifiedProblemDetail']['description_of_problem']; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>                
            </div>
        </div>
    </fieldset>
<?php } ?>