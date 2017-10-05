
<fieldset>
    <legend>MRA Home</legend>

    <div id="home_info" class="home_info" style="margin-top:-5px;">

        <?php
        $user_id = $this->Session->read('User.Id');

        if ($user_id) {
            ?>
        <div id="user_details">
                <?php echo $this->requestAction(array('controller' => 'AdminModuleUsers', 'action' => 'user_details'), array('return')); ?>
        </div>

        <!--            <div id="set_period">
            <?php //echo $this->requestAction(array('controller' => 'AdminModulePeriodDetails', 'action' => 'set_period'), array('return')); ?>
                    </div>-->

        <?php } else { ?>
        <p>Invalid User Information</p>
        <?php } ?>

    </div>

</fieldset>
