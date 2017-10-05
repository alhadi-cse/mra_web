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
            <table cellpadding="5" cellspacing="7" border="0" style="width:95%;">
                <tr>
                    <td style="width:215px;">Sl. No.</td>
                    <td class="colons">:</td>
                    <td><input type="text" name="slNo" id="slNo"/></td>
                </tr> 
                <tr>
                    <td>Name of Authorized Person</td>
                    <td class="colons">:</td>
                    <td><input type="text" name="authorizedName" id="authorizedName"/></td>
                </tr> 
                <tr>
                    <td>Designation of Authorized Person</td>
                    <td class="colons">:</td>
                    <td><input type="text" name="designation" id="designation"/></td>
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

                        <label style="float:left; margin-right:5px;">
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
                    <td style="text-align: center;">
                        <input type="button" class="mybtns" id="btnUpload" value="Save" />
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

        $("#image").css("background-image", "url('~/../img/sign.png')");
        $("#image strong").html("Add Signature");


        $("#inputImage").on("change", function (evt) {
            var imgTempPath = URL.createObjectURL(evt.target.files[0]);

            $("#image").css("background-image", "url('" + imgTempPath + "')");
            $("#image strong").html("Change Signature");
        });

        $("#btnUpload").click(function () {

            var ext = $('#inputImage').val().split('.').pop().toLowerCase();

            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'doc']) == -1) {
                alert('invalid file or  extension! please upload pdf or image file');
            } else {
                var file = document.getElementById('inputImage').files[0];

                var size = (file.size / 1024 / 1024);
                //alert(size);
                if (size < 2) {
                    //alert("Size");
                    var reader = new FileReader();
                    reader.readAsDataURL(file, 'image/jpeg');
                    reader.onload = shipOff;
                } else {
                    alert("file size not more then 2MB");
                }
            }

        });

    });


    function shipOff(event) {
        //alert("Ok shipOff");
        var result = event.target.result;
        var fileName = document.getElementById('inputImage').files[0].name; //Should be 'picture.jpg'
        var inputSlNo = document.getElementById('slNo').value;
        var authorizedName = document.getElementById('authorizedName').value;
        var designation = document.getElementById('designation').value;
        //alert(designation);

        $.ajax({
            dataType: "html",
            evalScripts: true,
            type: "POST",
            url: '~/../LookupBasicMraAuthorities/file_upload',
            data: {'data': result, 'inputSlNo': inputSlNo, 'name': fileName, 'inputAuthorizedName': authorizedName, 'inputDesignation': designation},
            success: function (data) {
                alert("Signature Upload Successfully !");
                $("#ajax_div").html(data);

                //$("#aaa").html(data);
                //console.log(data);
            }
        });

        return false;
    }
</script>