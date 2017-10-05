<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($IsValidUser)) {
    $title = "Identification of Problems";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?> 

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>         
            <?php echo $this->Form->create('SupervisionModuleIdentifiedProblemDetail'); ?>

            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                       <td>Name of Organization</td>
                       <td class="colons">:</td>
                       <td><?php echo $this->Form->input('org_id', array('type'=>'text', 'value'=>$org_name_options[$org_id], 'disabled'=>'disabled', 'label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Type of Problem</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('type_of_problem_id', array('type'=>'text', 'value'=>$type_of_problem_options[$type_of_problem_id], 'disabled'=>'disabled', 'label'=>false)); ?></td>
                        <!--<td><?php //echo $this->Form->input('type_of_problem_id', array('type' => 'select', 'options' => $type_of_problem_options, 'id' => 'type_of_problems', 'empty' => '---Select---', 'label' => false)); ?></td>-->
                    </tr>
                    <tr>
                        <td>Title of Problem</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('title_of_problem_id', array('type'=>'text', 'value'=>$title_of_problem_options[$title_of_problem_id], 'disabled'=>'disabled', 'label'=>false)); ?></td>
                        <!--<td><?php //echo $this->Form->input('title_of_problem_id', array('type' => 'select', 'options'=>$title_of_problem_options, 'id' => 'title_of_problems', 'empty' => '---Select---', 'label' => false)); ?></td>-->
                    </tr>
                    <tr>
                        <td style="vertical-align:top;">Description of Problem</td>
                        <td class="colons" style="vertical-align:top;">:</td>
                        <td style="vertical-align:top;">
                          <?php echo $this->Form->input('description_of_problem', array('type' => 'textarea', 'escape' => false,'rows' => '15', 'cols' => '5', 'label' => false));?>
                        </td>
                    </tr>                                                                               
                </table>
            </div>

            <div class="btns-div"> 
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td>
                            <?php
                                echo $this->Js->submit('Update', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'A Problemshas been added successfully.');",
                                    'error' => "msg.init('error', '$title', '$title has been failed to add !');")));
                            ?>
                        </td>
                        <td>
                            <?php
                            $viewable_user_groups = $this->Session->read('ViewableUserGroups');
                            echo $this->Js->link('Close', array('controller' => 'SupervisionModuleIdentifiedProblemDetails', 'action' => 'view?this_state_ids='.$this_state_ids.'&viewable_user_groups='.$viewable_user_groups), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                            ?>
                        </td>                        
                        <td></td>
                    </tr>
                </table>
            </div>

            <?php echo $this->Form->end(); ?>
        </fieldset>

    </div>

    <script>

        $(document).ready(function () {
            $('.integers').numeric({decimal: false, negative: false});
            $('.decimals').numeric({decimal: ".", negative: false});
        });

    </script>

<?php } 
$this->Js->get('#type_of_problems')->event('change', $this->Js->request(array(
                'controller' => 'SupervisionModuleIdentifiedProblemDetails',
                'action' => 'update_title_of_problem_selection'), array(
                'update' => '#title_of_problems',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );

if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
?>
