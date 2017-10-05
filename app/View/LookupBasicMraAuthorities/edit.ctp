<div id="frmStatus_add">
    <?php
    $title = "Authorized Persons Information of MRA";

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <?php echo $this->Form->create('LookupBasicMraAuthority'); ?>
        <div class="form">
            <table cellpadding="5" cellspacing="0" border="0" style="width:95%;">
                <tr style="display: none">
                    <td>Id</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('id', array('type' => 'text', 'class' => 'decimals', 'id' => 'id', 'label' => false)); ?></td>
                </tr> 
                <tr>
                    <td style="width:215px;">Sl. No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('serial_no', array('type' => 'text', 'class' => 'decimals', 'id' => 'slNo', 'label' => false)); ?></td>
                </tr> 
                <tr>
                    <td>Name of Authorized Person</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('authority_name', array('id' => 'authorityName', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Designation of Authorized Person</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('authority_designation', array('id' => 'authorityDesignation', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Signature</td>
                    <td class="colons">:</td>
                    <td>
                        <style>
                            #image {
                                border: 1px solid #ccc;
                                width: 250px;
                                height: 70px;
                                overflow: hidden;
                                
                                background-size: 100% 100%;
                                background-color: #fff;
                                background-repeat: no-repeat;
                            }

                            #image strong {
                                position: relative;
                                right: -6px;
                                bottom: -80px;
                                float: right;
                                border: 1px solid #ddd;
                                padding: 5px 8px;
                                color: #2383d7;
                                background-color: rgba(250, 250, 250, 0.85);

                                transition: bottom .5s ease-out;
                            }

                            #image:hover strong {
                                bottom: -45px;
                            }

                        </style>

                        <label style="float:left; margin:5px;">
                            <div id="image">
                                <strong>Add Signature</strong>
                            </div>
                            <input style="display:none" accept="image/*" id="inputImage" type="file" class="upload_files" data-filename-placement="image upload" access="image/*" />
                        </label>
                        
                        <strong style="display:block; margin-top:15px; color:#e32;">
                            *Size must be: (250X70)px  *Width=250px & Height=70px
                        </strong>

                        <?php echo $this->Form->input('authority_sign', array('type' => 'text', 'style' => 'display:none', 'id' => 'author_sign', 'label' => false)); ?>
                    </td>
                </tr>
            </table>
        </div>        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LookupBasicMraAuthorities', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <input type="button" class="mybtns" id="btnUpload" value="Upadte" />
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>
<script>

    $(document).ready(function () {

        $(".integers").numeric({decimal: false, negative: false});
        $(".decimals").numeric({decimal: ".", negative: false});
        
        var inputImage = $("#author_sign").val();
        var imgUrl = (inputImage != '') ? "~/../files/uploads/" + inputImage : "~/../img/sign.png";
        var imgText = (inputImage != '') ? "Change Signature" : "Add Signature";

        $("#image").css("background-image", "url('" + imgUrl + "')");
        $("#image strong").html(imgText);


        $("#inputImage").on("change", function (evt) {
            var imgTempPath = URL.createObjectURL(evt.target.files[0]);

            $("#image").css("background-image", "url('" + imgTempPath + "')");
            $("#image strong").html("Change Signature");
        });

        $("#btnUpload").click(function () {

            var ext = $("#inputImage").val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'doc']) == -1) {
                alert("invalid file or extension! please upload pdf or image file");
            } else {
                var file = document.getElementById("inputImage").files[0];
                var size = (file.size / 1024 / 1024);

                if (size < 2) {
                    var reader = new FileReader();
                    reader.readAsDataURL(file, "image/jpeg");

                    reader.onload = shipOff;
                } else {
                    alert("file size not more then 2MB");
                }
            }

        });
    });

    function shipOff(event) {
        var result = event.target.result;
        var fileName = document.getElementById("inputImage").files[0].name;
        var ids = $("#id").val();
        var inputSlNo = $("#slNo").val();
        var designation = $("#authorityDesignation").val();
        var authorizedName = $("#authorityName").val();
        $.ajax({
            dataType: "html",
            evalScripts: true,
            type: "POST",
            url: "~/../LookupBasicMraAuthorities/file_update",
            data: {'data': result, 'inputSlNo': inputSlNo, 'id': ids, 'name': fileName, 'inputAuthorizedName': authorizedName, 'inputDesignation': designation},
            success: function (data) {
                alert("Signature Updated Successfully !");
                $("#ajax_div").html(data);
return false;
//                var inImg = $("#inputImage").val();
//                var imPath = "~/../files/uploads/" + inImg;
//                alert(imPath);

            }
        });

        return false;
    }
</script>
