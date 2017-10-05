
<table cellpadding="0" cellspacing="0" style="width:100%">                    

    <tr style="background-color:#eee;">
        <td>&nbsp;</td>
        <td>
            <div class="topmenu">
                <?php echo $this->element('top_menu', array("variable_name" => "current")); ?>                    
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr style="background-color:#fff;">
        <td style="border-bottom:3px solid #053578; width:10%; background-color:#fff;">&nbsp;</td>
        <td style="border-bottom:3px solid #053578; width:80%;">
            <div class="banner"></div>
        </td>
        <td style="border-bottom:3px solid #053578; width:10%; background-color:#6fbe45;">&nbsp;</td>
    </tr>
    <tr style="background-color:#fff;">
        <td>&nbsp;</td>
        <td>
            <div id="main_content" class="main-content">
                <?php echo $this->element('main_content', array("variable_name" => "current")); ?>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr style="background-color:#08c;">
        <td>&nbsp;</td>
        <td>
            <div id="footer" class="footer-content">
                <div class="text1" style="float: left;">
                    <a href="http://www.mra.gov.bd/" target="_blank">MRA</a> © 2014: All Rights Reserved</div>
                <div class="text2" style="float: right;">
                    Designed & Developed by: <a href="http://www.cegisbd.com/" target="_blank">CEGIS</a></div>

                <div style="clear:both;"></div>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>

<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>
