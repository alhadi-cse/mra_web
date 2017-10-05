<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = 'Attachmentment of Supporting Documents';
    $isAdmin = !empty($user_group_id) && in_array(1, $user_group_id);
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($values) && !empty($values['BasicModuleBasicInformation'])) {

                $mfiName = $values['BasicModuleBasicInformation']['short_name_of_org'];
                $mfiFullName = $values['BasicModuleBasicInformation']['full_name_of_org'];
                if (!empty($mfiFullName) && !empty($mfiName))
                    $mfiName = $mfiFullName . " (<strong>" . $mfiName . "</strong>)";
                else
                    $mfiName = $mfiName . $mfiFullName;
            }
            ?>  
            <table>
                <tr>
                    <td colspan="3"><?php echo '<p style="margin:3px;"><strong>Name of Organization : </strong>' . $mfiName . '</p>'; ?></td>
                </tr>
                <tr>
                    <td>File Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('file_type_id', array('type' => 'select', 'id' => 'file_types', 'options' => $file_type_options, 'empty' => '---Select---', 'div' => false, 'label' => false)); ?></td>
                </tr>
                <tr style="display: none">
                    <td>org_id</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('org_id', array('type' => 'text', 'value' => $org_id, 'label' => false)); ?></td>
                </tr>                           
                <tr style="display: none">
                    <td>Image</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('file_name', array('type' => 'text', 'value' => $file_name, 'label' => false)); ?></td>
                </tr>            
                <tbody id="display_upload_option"></tbody>
            </table>            
            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td><?php echo $this->Js->link('Previous', array('controller' => 'BasicModuleRejectionInformations', 'action' => 'view?model_id=26'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();'))); ?></td>
                        <td><input type="button"  class="mybtns"  id="btnUpload" value="Upload" /></td>
                        <td><?php echo $this->Js->link('Next', array('controller' => 'BasicModuleBasicInformations', 'action' => 'application_preview_before_submit'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();'))); ?></td> 
                        <td></td>   
                    </tr>
                </table>
            </div>        
        </div>
    </fieldset>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        var i = 1;
        var file_type_id = 0;
        var ext = "";
        $("#file_types").change(function () {
            file_type_id = $('#file_types').val();
            addImage(file_type_id);
            $("#file_types option[value=" + file_type_id + "]").hide();
        });
        function addImage(file_type_id) {
            var typeName = $("#file_types option[value=" + file_type_id + "]").text();            
            var typeId = $("#file_types option[value=" + file_type_id + "]").val();
            var str = '<tr id="' + file_type_id + '" style="border: 1px solid #dddddd;">' + '<td style="text-align:right;"></td>' + '<td class="colons"></td>' +
                    '<td style="background-color:#f5f5f5;"><div style="float:left">' +
                    '<label id="lb">' +
                    '<img  alt="It is a documnet file" id="profileImage" style="width:65px; height:65px;border:2px;margin-top:10px" src="~/../img/no_img.png" />' +
                    '<input style="display:none" accept="image/*,.pdf,.doc,.docx" id="inputImage_' + i + '" type="file" class="upload_files" data-filename-placement="image upload" access="image/*" />' +
                    '</label></div>' +
                    '<div style="float:left; padding:30px 10px;">' +
                    '<label id="lbid_' + i + '">' + typeId + '</label>' +
                    '<label id="lb_' + i + '">.'+ typeName + '</label>' +
                    '<button id="btnRemove_' + i + '" class="remove btn-close data-close" type="button">' + '\
                       </button></div></td>' + '</tr>';
            $("#display_upload_option").append(str);
            $("#btnRemove_" + i).click(function () {
                $(this).closest("tr").remove();
                $("#file_types option[value=" + file_type_id + "]").show();
            });
            $("#inputImage_" + i).on('change', function (evt) {
                ext = $(this).val().split('.').pop().toLowerCase();
                if (ext == 'jpg') {
                    var tmpPath = URL.createObjectURL(evt.target.files[0]);
                    $(this).closest("td").find("#profileImage").attr('src', tmpPath);
                }
                if (ext == 'pdf') {
                    $(this).closest("td").find("#profileImage").attr('src', "~/../img/pdf.png");
                }
                if (ext == 'doc' || ext == 'docx') {
                    $(this).closest("td").find("#profileImage").attr('src', "~/../img/doc.png");
                } else {
                    $(this).closest("td").find("#profileImage").attr('alt', "dsfds");
                }
                return;
            });
            ++i;
        }
        $("#btnUpload").click(function () {
            var sz = 0;
            var error = false;
            var cnt = 0, sc = 0;

            $(".upload_files").each(function () {
                var thisId = $(this).attr('id');
                var file = document.getElementById(thisId).files[0];
                var size = (file.size / 1024 / 1024);
                sz += size;
                cnt++;
                if (size >= 2) {
                    error = true;
                }
            });

            if (sz < 5 && !error) {
                $(".upload_files").each(function () {
                    var thisId = $(this).attr('id');   
                    var ab=ext = thisId.split('_').pop().toLowerCase();
                    var typeId=$("#lbid_"+ab).text();
                    
                    ext = $("#" + thisId).val().split('.').pop().toLowerCase();
                    if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx']) == -1) {
                        alert('invalid file or  extension!');
                    } else {
                        var file = document.getElementById(thisId).files[0];
                        var sizes = (file.size / 1024 / 1024);
                        if (sizes < <?php echo!empty($max_size) ? $max_size : 3; ?>) {
                            var reader = new FileReader();
                            reader.readAsDataURL(file, 'image/jpeg');
                            reader.onload = function (event, preFileName) {
                                var result = event.target.result;
                                var fileName = document.getElementById(thisId).files[0].name;
                                var orgId = $('#org_id').val();
                                file_type_id = $('#file_types').val();
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
                                    url: "~/../BasicModuleFileAttachments/file_upload",
                                    data: {'data': result, 'org_id': orgId, 'file_type_ids': typeId, 'file_name': fileName},
                                    success: function (data) {
                                        $("#busy-indicator").fadeOut();
                                        msg.init('success', 'Attachment. . .', 'File has been Uploaded Successfully!');
                                        $("#ajax_div").html(data);
                                        return false;
                                    }                                    
                                });
                            };
                        } else {
                            alert("file size not more than <?php echo!empty($max_size) ? $max_size : 3; ?>MB ");
                        }
                    }
                });
            } else {
                if (error == true) {
                    alert("Any single size not more than <?php echo!empty($max_size) ? $max_size : 2; ?>MB ");
                } else {
                    alert("Total file size not more than <?php echo!empty($max_size) ? $max_size : 5; ?>MB ");
                }
            }
        });
    });
</script>