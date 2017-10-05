<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = "Upload the Picture of Office";
    $isAdmin = !empty($user_group_id) && in_array(1, $user_group_id);
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <table cellpadding="5" cellspacing="7" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="padding:15px 0;"><?php echo $orgName; ?></td>
                </tr>
                <tr>
                    <td>Branch Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $branchName; ?></td>
                </tr>
                <tr>
                    <td>Upload Picture</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'div' => false, 'label' => false));
                        echo $this->Form->input('branch_id', array('type' => 'hidden', 'value' => $branch_id, 'div' => false, 'label' => false));
                        echo $this->Form->input('branch_serial', array('type' => 'hidden', 'value' => $branch_serial, 'div' => false, 'label' => false));
                        echo $this->Form->input('image_name', array('type' => 'hidden', 'value' => $image_name, 'div' => false, 'label' => false));
                        ?>

                        <style>
                            #image {
                                float: left;
                                border: 3px solid #ccc;
                                width: 180px;
                                height: 150px;
                                overflow: hidden;

                                background-size: 100% 100%;
                                background-color: #fff;
                                background-repeat: no-repeat;
                            }

                            #image strong {
                                position: relative;
                                right: -6px;
                                bottom: -103px;
                                float: right;
                                border: 1px solid #ddd;
                                padding: 5px 8px;
                                color: #2383d7;
                                background-color: rgba(250, 250, 250, 0.85);

                                transition: bottom .5s ease-out;
                            }

                            #image:hover strong {
                                bottom: -73px;
                            }

                        </style>
                        <label>
                            <div id="image"><strong>Browse</strong></div>
                            <input style="display:none" accept="image/*" id="inputImage" type="file" class="upload_files" data-filename-placement="image upload" access="image/*" />
                        </label>
                    </td>
                </tr>               
            </table>
            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>                        
                        <td></td>
                        <td><input type="button" class="mybtns" id="btnUpload" value="Upload & Next" /></td>
                        <td></td>
                        <td></td>   
                    </tr>
                </table>
            </div>           
        </div> 
    </fieldset>
<?php } ?>
<script>

    $(document).ready(function () {
        //var inputImage = $("#author_sign").val();
        var inputImage = $("#image_name").val();

        var imgUrl = "~/../img/no_img.png";
        var imgText = " Browse Picture to Add ";

        if (inputImage != '') {
            imgUrl = "~/../files/uploads/branches/" + inputImage;
            imgText = " Browse Picture to Change ";
        }

        $("#image").css("background-image", "url('" + imgUrl + "')");
        $("#image strong").html(imgText);
        $("#inputImage").on("change", function (evt) {
            var imgTempPath = URL.createObjectURL(evt.target.files[0]);
            $("#image").css("background-image", "url('" + imgTempPath + "')");
            $("#image strong").html("Change");
        });

        $("#btnUpload").click(function () {

            var ext = $("#inputImage").val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                alert("Invalid file or extension! please upload an image file");
            } else {
                var file = document.getElementById("inputImage").files[0];
                var size = (file.size / 1024);

                if (size < 500) {
                    var reader = new FileReader();
                    reader.readAsDataURL(file, "image/jpeg");

                    reader.onload = shipOff;
                } else {
                    alert("Image size must be less than 500 KB");
                }
            }
        });
    });

    function shipOff(event) {
        var result = event.target.result;
        var fileName = document.getElementById("inputImage").files[0].name;
        var orgId = $("#org_id").val();
//        var orgName = $("#org_id").val();
//        alert(orgName);
//        return;
        var branchId = $("#branch_id").val();
        var branchSerial = $("#branch_serial").val();
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
            url: "~/../BasicModuleBranchInfos/file_upload",
            data: {'data': result, 'branch_id': branchId, 'branch_serial': branchSerial, 'org_id': orgId, 'name': fileName},
            success: function (data) {
                $("#busy-indicator").fadeOut();
                msg.init('success', 'Branch Information. . .', 'Office Information Added Successfully!');
                $("#ajax_div").html(data);
                return false;
            }
        });

        return false;
    }
</script>