
(function ($) {

    $.fn.numeric = function (config, callback) {

        config = config || {};

        if (typeof config === 'boolean') {
            config = {decimal: config};
        }
        
        config.negative = (typeof config.negative == 'undefined' || config.negative != false);

        var decimal = (config.decimal === false) ? '' : config.decimal || '.';
        //var comma = (config.comma === false) ? '' : config.comma || ',';
        var comma = (config.comma === false) ? false : true;
        //var negative = (config.negative === true) ? true : false;
        var negative = (config.negative === false) ? false : true;

        this.data('numeric.decimal', decimal).data('numeric.negative', negative).data('numeric.comma', comma);

        if (config.decimal === false)
            return this.keypress($.fn.numeric.keypress);
        else
            return this.keypress($.fn.numeric.keypress).blur($.fn.numeric.blur);
    };


    $.fn.numeric.keypress = function (e) {
        var decimal = $.data(this, 'numeric.decimal');
        var negative = $.data(this, 'numeric.negative');
        var key = e.charCode ? e.charCode : (e.keyCode ? e.keyCode : (e.which ? e.which : 0));

        if (key == 13 && this.nodeName.toLowerCase() == 'input')
            return true;
        else if (key == 13)
            return false;

        if ((e.shiftKey && key == 45) || (e.ctrlKey && (key == 65 || key == 67 || key == 86 ||
                key == 88 || key == 90 || key == 97 || key == 99 ||
                key == 122 || key == 118 || key == 120)))
            return true;

        if (e.ctrlKey || e.shiftKey || e.altKey)
            return false;

        //Left:37 Right:39  Up:38 Down:40
        if (key == 8 || key == 9 || key == 13 || (34 < key && key < 41))
            return true;

        //NumericDigit: 46 to 57
        if (47 < key && key < 58)
            return true;

        if (key == 46 || key == decimal.charCodeAt(0)) {
            if ((e.charCode === e.which) || (typeof e.charCode === 'undefined' && e.keyCode === e.which)) {
                if (decimal) {
                    if (this.value.indexOf(decimal) == -1) {
                        if ($.fn.getSelectionStart(this) == 0) {
                            this.value = '0' + this.value;
                            $.fn.setCursorPosition(this, 1);
                            this.focus();
                        }
                        return true;
                    } else
                        return false;
                }
            } else
                return true;
        }

        if (key == 45) {
            //alert(negative);
            //alert(decimal);
            //alert(negative + " : " + String.fromCharCode(key) == '-');
            //if (negative && String.fromCharCode(key) == '-') {
            if (decimal && String.fromCharCode(key) == '-') {
                if (this.value.length == 0)
                    return true;
                else if (this.value.indexOf('-') == -1) {
                    if ($.fn.getSelectionStart(this) == 0) {
                        if (this.value.indexOf(decimal) == 0) {
                            this.value = '0' + this.value;
                            $.fn.setCursorPosition(this, 0);
                            this.focus();
                        }
                        return true;
                    } else {
                        this.value = '-' + this.value;
                        return false;
                    }
                }
            }
            return false;
        }

        return false;
    };

    $.fn.numeric.blur = function () {

        var val = this.value;
        if (val != '') {
            if (val.indexOf('-') > -1) {
                $(this).css('background-color', '#f32');
            } else
                $(this).css('background-color', '#fff');

            try {
                if (parseFloat(val) == 0 || isNaN(parseFloat(val)))
                    val = '0';
                else {
                    val = parseFloat(val).toString(10);
//                    if (val.indexOf('.') == -1)
//                        val += '.0';
                }
            } catch (e) {
                val = '';
            }
            var comma = $.data(this, 'numeric.comma');

            if (comma)
                this.value = val.toLocaleString();
            else
                this.value = val;
        }
    };

    $.fn.removeNumeric = function () {
        return this.data('numeric.decimal', null).data('numeric.negative', null).unbind('keypress', $.fn.numeric.keypress).unbind('blur', $.fn.numeric.blur);
    };

    $.fn.getSelectionStart = function (o) {
        o.focus();
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate();
            r.moveEnd('character', o.value.length);
            if (r.text == '')
                return o.value.length;
            return o.value.lastIndexOf(r.text);
        } else
            return o.selectionStart;
    };

    $.fn.setCursorPosition = function (o, p) {
        o.focus();
        if (o.setSelectionRange) {
            o.setSelectionRange(p, p);
        } else if (o.createTextRange) {
            var range = o.createTextRange();
            range.collapse(true);
            range.moveEnd('character', p);
            range.moveStart('character', p);
            range.select();
        }
    };

})(jQuery);
