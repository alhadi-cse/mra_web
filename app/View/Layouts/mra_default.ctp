
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>MRA :: MIS-DBMS</title>

        <link rel="shortcut icon" href="img/mra_logo.jpg" />
        <link rel="icon" type="image/ico" href="img/mra_logo.jpg" />

        <?php
        echo $this->fetch('meta');

        echo $this->Html->css("my-fonts", null, array("inline" => false));
        echo $this->Html->css("styles", null, array("inline" => false));
        echo $this->Html->css("frmstyles", null, array("inline" => false));
        echo $this->Html->css("menustyle", null, array("inline" => false));
        echo $this->Html->css("tablestyle", null, array("inline" => false));
        echo $this->Html->css("msgstyle", null, array("inline" => false));
        echo $this->Html->css("tabstyle", null, array("inline" => false));
        echo $this->Html->css("jquery-ui", null, array("inline" => false));
		
		echo $this->Html->css("fixedtablestyle", null, array("inline" => true));

        echo $this->Html->script("jquery");
        echo $this->Html->script("jquery-ui");
        echo $this->Html->script("accordion");
        echo $this->Html->script("easing");
        echo $this->Html->script("numeric");

        echo $this->Html->script("fixedtable");

        echo $this->Html->script("bangla-kbs");

        //echo $this->Html->script("map_tools/map_google_api");
        echo $this->Html->script("http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCikNZIKgnf41sAFCmoQjQ2nEp7VbLrMEU");
        //echo $this->Html->script("map_tools/geo_json");
        echo $this->Html->script("map_tools/info_box");

        echo $this->Html->script("map_data/division");
        echo $this->Html->script("map_data/district");
        echo $this->Html->script("map_data/upazila");

//        echo $this->Html->script("map_data/");
//        echo $this->Html->script("map_tooltip");
//        echo $this->Html->script("chart/high-charts");
//        echo $this->Html->script("map/high-maps");
//
//        echo $this->Html->script("jspdf");
//        echo $this->Html->script("html2canvas");

        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>

    </head>

    <body>
        <?php
        echo $this->Flash->render();
        echo $this->Flash->render('auth');
        ?>
        <div id="content">
            <?php echo $this->fetch('content'); ?>
        </div>


        <a href="#" class="scrollup" alt="scroll to top of the page" title="scroll to top of the page"></a>

        <div id="busy-indicator" class="pageloading">
            <div class="pageloading-body">
                <div class="loading-indicator">
                    <p>Please wait ... . . </p>
                </div>
            </div>
        </div>

        <div id="msg-modal-bg" class="msg-modal-bg" onclick="msg.closeBg();">
            <div id="msg-content" class="msg-content">
                <div id="msg-title" class="msg-title">
                    <div id="msg-title-txt" class="msg-title-txt"></div>
                    <button id="msg-title-btn" class="msg-btnclose" onclick="msg.close();"></button>
                </div>

                <div id="msg-body" class="msg-body">
                    <div id="msg-body-bg" class="msg-body-bg">
                        <span id="msg-body-msg" class="msg-body-msg"></span>
                    </div>
                </div>

                <div id="msg-footer" class="msg-footer">
                    <button id="msg-footer-btn" class="modal-btns msg-btn" onclick="msg.close();">OK</button>
                </div>
            </div>
        </div>


        <div class="modal-bg" id="modal-bg">
            <div id="modal-content" class="modal-content" id="modal-content">
                <div id="modal-title" class="modal-title">
                    <span id="modal-title-txt" class="modal-title-txt modal-title-report-bg">Modal Header</span>
                    <button id="modal-title-btn-close" class="close" onclick="modal.close();">✖</button>
                </div>

                <div id="modal-body" class="modal-body">
                    <div id="modal-body-btns" class="modal-body-btns">
                        <button id="btnPrint" class="modal-btns" onclick="print_report('my_report', 'MRA Report');">Print</button>
                    </div>

                    <div id="modal-body-content" class="modal-body-content"></div>
                </div>

                <div id="modal-footer" class="modal-footer">
                    <button id="modal-footer-btn-close" class="modal-close" onclick="modal.close();">Close</button>
                </div>
            </div>
        </div>


        <script type="text/javascript">

            $(function () {

                $(window).scroll(function () {
                    if ($(this).scrollTop() > 100) {
                        $('.scrollup').fadeIn(750);
                    } else {
                        $('.scrollup').fadeOut(750);
                    }
                });

                $('.scrollup').click(function () {
                    $('html, body').animate({scrollTop: 0}, 750);
                    return false;
                });

            });

            function open_list(element) {
                var evt = document.createEvent('MouseEvents');
                evt.initMouseEvent('mousedown', true, true, window);
                element.dispatchEvent(evt);
            }

            (function ($) {

                $.fn.sortSelectBy = function (sorting_by) {
                    var select = $(this);
                    var selected = select.val();
                    var opts_list = select.find('option').clone();

                    select.find('option').remove();
                    if (sorting_by && sorting_by.toLowerCase().indexOf("val") >= 0)
                        opts_list.sort(function (a, b) {
                            return ($(a).val() > $(b).val()) ? 1 :
                                    ($(a).val() < $(b).val()) ? -1 : 0;
                        });
                    else
                        opts_list.sort(function (a, b) {
                            return ($(a).text() > $(b).text()) ? 1 :
                                    ($(a).text() < $(b).text()) ? -1 : 0;
                        });

                    opts_list.appendTo(select);
                    select.val(selected);

                };

                $.fn.filterByText = function (textbox, selectSingleMatch) {
                    return this.each(function () {
                        var select = this;
                        var options = $(select).find('option').clone();
                        var pre_search = '';

                        $(select).on("click", function () {
                            if ($(select).prop('size') > 1) {
                                $(select).closest('div').css('width', 'auto');
                                $(select).prop('size', 1);
                                $(select).css({position: 'static', zIndex: 9});
                            }
                        }).blur(function () {
                            if ($(select).prop('size') > 1) {
                                $(select).closest('div').css('width', 'auto');
                                $(select).prop('size', 1);
                                $(select).css({position: 'static', zIndex: 9});
                            }
                        });

                        $(textbox).on("keyup change", function () {
                            var search = $.trim($(this).val().toLowerCase());
                            search = search.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
                            search = search.replace(/\s* /g, '\\s*');

                            if (!search) {
                                $(select).closest('div').css('width', 'auto');
                                $(select).prop('size', 1);
                                $(select).css({position: 'static', zIndex: 9});

                                if ($(select).children('option').length < options.length)
                                    $(select).empty().append(options);

                                $(select).scrollTop(0);
                                $(select).prop('selectedIndex', 0);
                            } else if (pre_search !== search) {
                                $("#busy-indicator").fadeIn(10);
                                $(select).empty().scrollTop(0);
                                var regex = new RegExp(search, 'gi');
                                options.filter(function (index, opt_txt) {
                                    return regex.test(opt_txt.text) !== false;
                                }).appendTo(select);

                                var no_of_opt = $(select).children('option').length;
                                if (selectSingleMatch === true && no_of_opt === 1) {
                                    $(select).closest('div').css('width', 'auto');
                                    $(select).css({position: 'static', zIndex: 9});
                                    $(select).prop({size: 1, selectedIndex: 0});
                                } else {
                                    $(select).closest('div').css('width', $(select).closest('div').width());
                                    $(select).css({position: 'absolute', zIndex: 99});
                                    $(select).prop({selectedIndex: -1, size: (no_of_opt > 10 ? 10 : no_of_opt)});
                                }
                                $("#busy-indicator").fadeOut(15);
                            }
                            pre_search = search;
                            return false;
                        }).blur(function () {
                            if (!$(select).is(':focus') && !$(select).is(':hover') && $(textbox).val() != '') {
                                $(select).closest('div').css('width', 'auto');
                                $(select).css({position: 'static', zIndex: 9});
                                $(select).prop('size', 1);
                                $(select).focus();
                            }
                            return false;
                        });
                    });
                };

                $.fn.filterGroups = function (options) {
                    var settings = $.extend({}, options);
                    return this.each(function () {
                        var $select = $(this);
                        var empty_opt = $select.find('option[value=""]').clone();
                        $select.data('all-data-groups', $select.find('optgroup').clone()).children('optgroup').remove();
                        $(settings.groupSelector).change(function () {
                            var optgroup_val = $(this).val();
                            var $optgroup = $select.data('all-data-groups').filter('optgroup[label="' + optgroup_val + '"]').clone();
                            $select.children().remove();
                            if ($optgroup) {
                                if (empty_opt && settings.emptyValue)
                                    $select.append(empty_opt);
                                $select.append($optgroup.find('option'));
                            }
                        }).change();
                    });
                };

                $.fn.hasScrollBar = function (direction) {

//                    direction = (direction === 'vertical') ? 'scrollTop' : 'scrollLeft';
//                    var result = !!this[direction];
//
//                    if (!result) {
//                        this[direction] = 1;
//                        result = !!this[direction];
//                        this[direction] = 0;
//                    }
//                    return result;

                    if (direction && direction === 'vertical')
                        return (this.get(0).scrollHeight > this.get(0).clientHeight);
                    else
                        return (this.get(0).scrollWidth > this.get(0).clientWidth);
                };
                $.fn.getScrollBarWidth = function () {
                    var a = 0;
                    if (!a)
                        if (/msie/.test(navigator.userAgent.toLowerCase())) {
                            var b = $('<textarea cols="10" rows="2"></textarea>').css({position: "absolute", top: -1E3, left: -1E3}).appendTo("body"), e = c('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>').css({position: "absolute", top: -1E3, left: -1E3}).appendTo("body"), a = b.width() - e.width() + 2;
                            b.add(e).remove();
                        } else
                            b = $("<div />").css({width: 100, height: 100, overflow: "auto", position: "absolute", top: -1E3, left: -1E3}).prependTo("body").append("<div />").find("div").css({width: "100%", height: 200}), a = 100 - b.width(), b.parent().remove();
                    return a;
                };
            })(jQuery);


            function full_screen(objId, opt) {
                if (!objId || typeof (objId) === 'undefined')
                    return;

                if (opt || opt === true || !$(objId).hasClass("full-screen"))
                    $(objId).addClass("full-screen");
                else
                    $(objId).removeClass("full-screen");
            }

            function draggable_modal(title, content, modal_bg) {
                $('body').on('mousedown', '#' + title, function (e) {
                    $('#' + content).addClass('draggable');
                    var offsetY = e.clientY - $('.draggable').offset().top;
                    var offsetX = e.clientX - $('.draggable').offset().left;
                    $('body').on('mousemove', function (e) {
                        $('.draggable').offset({
                            top: e.clientY - offsetY,
                            left: e.clientX - offsetX
                        }).on('mouseup', function () {
                            $('#' + content).removeClass('draggable');
                        });
                        e.preventDefault();
                    });
                }).on('mouseup', function () {
                    $('.draggable').removeClass('draggable');
                }).on('keyup', function (e) {
                    if (e.which === 27 || e.which === 13)
                        $('#' + modal_bg).hide();
                });
            }

            function modal_open(content, top) {
                top = top || 0;

                $("#" + content).css({top: '-375px', left: 0, opacity: 0});
                $("#" + content + "_bg").fadeIn(430, function () {
                    $("#" + content).animate({top: top, opacity: 1}, 500);
                });
            }

            function modal_close(content) {
                $("#" + content).animate({top: '-375px', opacity: 0}, 500, function () {
                    $("#" + content + "_bg").fadeOut(430);
                });
            }

            //***===My Message===***//
            //  msg = array('type'=>'error', 'title'=>'Error... . . !', 'msg'=>'Invalid MFI Information !');
            //  msg_opt.type, title, msg, autoOpen

            function MyMessage() {
                this.init = function (msg_opt, title, msg, autoOpen) {
                    if (typeof (msg_opt) === 'undefined')
                        return;
                    if (msg_opt.constructor !== String || typeof msg_opt !== 'string') {
                        title = msg_opt['title'];
                        msg = msg_opt['msg'];
                        autoOpen = msg_opt['autoOpen'];
                        msg_opt = msg_opt['type'];
                    }
                    var typeIsOk = (msg_opt && msg_opt.replace(/\s/g, '').length > 0);

                    $('#msg-title-txt').removeClass('msg-title-txt').addClass('msg-title-txt msg-information');
                    $('#msg-title-txt').text(title);

                    if (typeIsOk)
                        $('#msg-body-bg').removeClass().addClass('msg-body-bg msg-' + msg_opt);
                    $('#msg-body-msg').text(msg);

                    if (typeof (autoOpen) !== 'undefined' && !autoOpen)
                        return;

                    this.open();
                    return;
                };

                this.open = function () {
                    var msgBg = $('#msg-modal-bg');
                    var msgModal = $('#msg-content');
                    var openSpeed = 480;

                    msgBg.css({visibility: "hidden", display: "block"});
                    msgModal.css({visibility: "hidden", display: "block", height: "auto"});
                    var msgHeight = msgModal.height();
                    var msgWidth = msgModal.width();
                    msgBg.css({visibility: "", display: "none"});
                    msgModal.css({visibility: ""});

                    var msgTop = ($(window).innerHeight() - msgHeight) / 2 + 50;
                    var msgLeft = ($(window).innerWidth() - msgWidth) / 2 - 5;
                    msgModal.css({opacity: 0, height: 0, top: msgTop + "px", left: msgLeft + "px", display: "block"});
                    try {
                        msgBg.fadeIn(openSpeed / 4, function () {
                            msgModal.animate({opacity: 1, height: msgHeight, top: msgTop - msgHeight / 2 + "px"}, openSpeed, function () {
                                msgBg.css({opacity: 1});
                                msgModal.css({display: "block", opacity: 1, height: "auto", overflow: "visible"});
                            });
                        });
                    } catch (ex) {
                        msgBg.animate({opacity: 1}, function () {
                            msgModal.css({display: "block", opacity: 1, height: msgHeight, overflow: "visible"});
                        });
                    }

                    draggable_modal('msg-title', 'msg-content', 'msg-modal-bg');
                    return;
                };

                this.close = function () {
                    var msgBg = $('#msg-modal-bg');
                    var msgModal = $('#msg-content');
                    var closeSpeed = 430;
                    var msgTop = msgModal.offset().top + msgModal.outerHeight() / 2 - $(window).scrollTop();

                    msgModal.animate({opacity: 0, height: 0, top: msgTop + "px"}, closeSpeed, function () {
                        msgModal.css({opacity: 0, display: "none", height: 0});
                        msgBg.fadeOut(closeSpeed / 2);
                    });
                    return false;
                };

                this.closeBg = function () {
                    var msgBg = $('#msg-modal-bg');
                    var msgModal = $('#msg-content');
                    var closeSpeed = 430;

                    if (msgModal.css('display') === "none" || msgModal.css('display') !== "block") {
                        msgModal.animate({opacity: 0}, closeSpeed, function () {
                            msgModal.css({opacity: 0, height: 0, display: "none"});
                            msgBg.fadeOut(closeSpeed / 2);
                        });
                    }
                    return false;
                };
            }
            var msg = new MyMessage();


            //***===My Modal===***//
            function MyModal() {
                this.init = function (title, $content_id, autoOpen) {
                    if (title.toLowerCase().indexOf('report') >= 0) {
                        $("#modal-title-txt").addClass('modal-title-report-bg');
                    } else {
                        $("#modal-title-txt").removeClass('modal-title-report-bg');
                    }

                    $("#modal-title-txt").text(title);
                    $("#modal-body-content").empty().append($("#" + $content_id).html());

                    if (typeof (autoOpen) !== 'undefined' && !autoOpen)
                        return false;

                    this.open();
                    return false;
                };

                this.open = function () {
                    $("#modal-content").css({top: '-350px', left: 0, opacity: 0});
                    $("#modal-bg").fadeIn(350, function () {
                        $("#modal-content").animate({top: '0', opacity: 1}, 500);
                    });

                    draggable_modal('modal-title', 'modal-content', 'modal-bg');
                    return false;
                };

                this.close = function () {
                    $("#modal-content").animate({top: '-350px', opacity: 0}, 500, function () {
                        $("#modal-bg").fadeOut(350);
                    });
                    return false;
                };
            }
            var modal = new MyModal();

        </script>

        <?php
        if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
            echo $this->Js->writeBuffer();

        $this->Js->JqueryEngine->jQueryObject = '$j';
        echo $this->Html->scriptBlock('var $j = jQuery.noConflict();', array('inline' => false));
        ?>

    </body>

</html>
