<?php
echo '<p class="error-message">';
echo 'Will be updated very soon !';
echo '</p>';
?>
<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {

    $title = 'Attachmentment of Supporting Documents';
    $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($values) && !empty($values[0]['BasicModuleBasicInformation'])) {

                $mfiName = $values[0]['BasicModuleBasicInformation']['short_name_of_org'];
                $mfiFullName = $values[0]['BasicModuleBasicInformation']['full_name_of_org'];
                if (!empty($mfiFullName) && !empty($mfiName))
                    $mfiName = $mfiFullName . " (<strong>" . $mfiName . "</strong>)";
                else
                    $mfiName = $mfiName . $mfiFullName;

                echo '<p style="margin:3px;"><strong>Name of Organization : </strong>' . $mfiName . '</p>';
            }
            ?>  

            <table>
                <tr>
                    <td><label>File Name</label></td>
                    <td><input type="text" id="name" name="name" /> </td>
                </tr>
                <tr>
                    <td><label>Input file</label></td>
                    <td> <input id="inputImage" type="file" class="file" data-filename-placement="image upload" />
                    </td>
                </tr>
                <tr>
                    <td><input type="button"  style="width:70px;height:25px;" id="btnUpload" value="Upload" /></td>
                </tr>
            </table>

            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td><?php echo $this->Js->link('Previous', array('controller' => 'BasicModuleRejectionInformations', 'action' => 'view?model_id=26'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();'))); ?></td>
                        <td><?php echo $this->Js->link('Next', array('controller' => 'BasicModuleBasicInformations', 'action' => 'application_preview_before_submit'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();'))); ?></td> 
                        <td></td>   
                    </tr>
                </table>
            </div>        
        </div>
    </fieldset>
<?php } ?>

<script>
    
    $(document).ready(function () {

        $("#btnUpload").click(function () {
            var ext = $('#inputImage').val().split('.').pop().toLowerCase();

            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'doc']) == -1) {
                alert('invalid file or  extension! please upload pdf or image file');
            } else {
                var file = document.getElementById('inputImage').files[0];

                var size = (file.size / 1024 / 1024);
                alert(size);
                if (size < <?php echo $max_size ?>)
                {
                    var reader = new FileReader();
                    reader.readAsDataURL(file, 'image/jpeg');
                    reader.onload = shipOff;
                } else
                {
                    alert("file size not more then 2MB");
                }
            }
        });
    });

    function shipOff(event) {
        var result = event.target.result;
        var fileName = document.getElementById("inputImage").files[0].name;
        var orgName = $("#org_id").val();       
        var fileId = $("#file_id").val();

        $.ajax({
            beforeSend: function (xhr) {
                $("#busy-indicator").fadeIn();
            },
            complete: function (jqXHR, textStatus) {
                $("#busy-indicator").fadeOut();
            },
            dataType: "html",
            evalScripts: true,
            type: "POST",
            url: "~/../BasicModuleFileAttachments/view",
            data: {'data': result, 'org_id': orgName, 'name': fileName,'file_Id': fileId},
            success: function (data) {
                $("#busy-indicator").fadeOut();
                msg.init('success', 'Attachment. . .', 'Supporting documents uploaded successfully!');
                $("#ajax_div").html(data);
                return false;
            }
        });
        return false;
    }
</script>