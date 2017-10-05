
var actTextBox;
var isDecimal = false;

function parseUniNumbers(evt) {
    evt = evt ? evt : window.event ? window.event : event;
    var key = evt ? (evt.keyCode ? evt.keyCode : evt.which) : 0;

    if (evt.altKey || evt.ctrlKey || evt.shiftKey)
        return false;

    //Left:37 Right:39  Up:38 Down:40
    if (key == 8 || key == 9 || key == 13 || key == 32 || (34 < key && key < 41))
        return true;

    if (47 < key && key < 58) {
        num = String.fromCharCode(key + 0x09B6);
        if (num != '') {
            insertNumAtCursor(num);
            return false;
        }
        return true;
    }

    if (key == 46 || String.fromCharCode(key) == '.') {
        if ((evt.charCode === evt.which) || (typeof evt.charCode === 'undefined' && evt.keyCode === 46)) {
            if (isDecimal) {
                var cObj = document.getElementById(actTextBox);
                if (cObj.value.indexOf('.') == -1) {
                    if (getCurPos(cObj) == 0) {
                        cObj.value = '০' + cObj.value;
                        setCurPos(cObj, 1);
                        cObj.focus();
                    }
                    return true;
                } else
                    return false;
            }
        } else
            return true;
    }

    return false;
}

function insertNumAtCursor(myNum) {
    var cObj = document.getElementById(actTextBox);

    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        sel.text = myNum;
        sel.collapse(true);
        sel.select();
    } else if (cObj.selectionStart || cObj.selectionStart == 0) {
        var startPos = cObj.selectionStart;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos) + myNum +
                cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + myNum.length;
        cObj.selectionEnd = startPos + myNum.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += myNum;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function getCurPos(cObj) {
    var e = $(cObj).get(0);
    e.focus();

    if (document.selection) {
        e.focus();
        var Sel = document.selection.createRange();
        var SelLength = document.selection.createRange().text.length;
        Sel.moveStart('character', -e.value.length);
        return Sel.text.length - SelLength;
    } else if (e.selectionStart || e.selectionStart == '0')
        return e.selectionStart;
}

function setCurPos(cObj, pos) {
    var e = $(cObj).get(0);
    e.focus();
    if (e.setSelectionRange) {
        e.setSelectionRange(pos, pos);
    } else if (e.createTextRange) {
        var range = e.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
    }
}

function AsciToUniNunmer(asciStr) {
    var uniStr = asciStr.replace(/\d/g, function (dig) {
        return String.fromCharCode(dig.charCodeAt(0) + 0x09B6);
    });
    return uniStr;
}

function UniToAsciNunmer(uniStr) {
    var chCode, asciStr = '';
    if (uniStr && uniStr.length > 0)
        for (i = 0; i < uniStr.length; i++) {
            chCode = uniStr.charCodeAt(i);
            if (2533 < chCode && chCode < 2544)
                chCode -= 0x09B6;
            asciStr += String.fromCharCode(chCode);
        }
    return asciStr;
}

function checkUniNumbers(evt) {
    var val = this.value;
    if (val != '') {
        val = UniToAsciNunmer(val);
        try {
            if (parseFloat(val) == 0 || isNaN(parseFloat(val)))
                val = '0';
            else {
                val = parseFloat(val).toString(10);
                if (val.indexOf('.') == -1)
                    val += '.0';
            }
        } catch (e) {
            val = '';
        }
        this.value = AsciToUniNunmer(val);
    }
}


function makeUniNumber(numTextId, isDcml) {
    activeTextBox = document.getElementById(numTextId);
    activeTextBox.onkeypress = parseUniNumbers;
    if (isDcml)
        activeTextBox.onblur = checkUniNumbers;
    activeTextBox.onfocus = function () {
        actTextBox = numTextId;
        isDecimal = isDcml;
    };
}


var activeta;
var shift = false;
var phonetici = new Array();
phonetici['0'] = '\u09E6';
phonetici['1'] = '\u09E7';
phonetici['2'] = '\u09E8';
phonetici['3'] = '\u09E9';
phonetici['4'] = '\u09Ea';
phonetici['5'] = '\u09Eb';
phonetici['6'] = '\u09Ec';
phonetici['7'] = '\u09Ed';
phonetici['8'] = '\u09Ee';
phonetici['9'] = '\u09Ef';
phonetici['k'] = '\u0995';
phonetici['i'] = '\u09BF';
phonetici['I'] = '\u0987';
phonetici['ii'] = '\u09C0';
phonetici['II'] = '\u0988';
phonetici['e'] = '\u09C7';
phonetici['E'] = '\u098F';
phonetici['U'] = '\u0989';
phonetici['u'] = '\u09C1';
phonetici['uu'] = '\u09C2';
phonetici['UU'] = '\u098A';
phonetici['r'] = '\u09B0';
phonetici['WR'] = '\u098B';
phonetici['a'] = '\u09BE';
phonetici['A'] = '\u0986';
phonetici['ao'] = '\u0985';
phonetici['s'] = '\u09B8';
phonetici['t'] = '\u099f';
phonetici['K'] = '\u0996';
phonetici['kh'] = '\u0996';
phonetici['n'] = '\u09A8';
phonetici['N'] = '\u09A3';
phonetici['T'] = '\u09A4';
phonetici['Th'] = '\u09A5';
phonetici['d'] = '\u09A1';
phonetici['dh'] = '\u09A2';
phonetici['b'] = '\u09AC';
phonetici['bh'] = '\u09AD';
phonetici['v'] = '\u09AD';
phonetici['R'] = '\u09DC';
phonetici['Rh'] = '\u09DD';
phonetici['g'] = '\u0997';
phonetici['G'] = '\u0998';
phonetici['gh'] = '\u0998';
phonetici['h'] = '\u09B9';
phonetici['NG'] = '\u099E';
phonetici['j'] = '\u099C';
phonetici['J'] = '\u099D';
phonetici['jh'] = '\u099D';
phonetici['c'] = '\u099A';
phonetici['ch'] = '\u099B';
phonetici['C'] = '\u099B';
phonetici['th'] = '\u09A0';
phonetici['p'] = '\u09AA';
phonetici['f'] = '\u09AB';
phonetici['ph'] = '\u09AB';
phonetici['D'] = '\u09A6';
phonetici['Dh'] = '\u09A7';
phonetici['z'] = '\u09AF';
phonetici['y'] = '\u09DF';
phonetici['Ng'] = '\u0999';
phonetici['ng'] = '\u0982';
phonetici['l'] = '\u09B2';
phonetici['m'] = '\u09AE';
phonetici['sh'] = '\u09B6';
phonetici['S'] = '\u09B7';
phonetici['O'] = '\u0993';
phonetici['ou'] = '\u099C';
phonetici['OU'] = '\u0994';
phonetici['Ou'] = '\u0994';
phonetici['Oi'] = '\u0990';
phonetici['OI'] = '\u0990';
phonetici['tt'] = '\u09CE';
phonetici['H'] = '\u0983';
phonetici["."] = "\u0964";
phonetici[".."] = ".";
phonetici['HH'] = '\u09CD' + '\u200c';
phonetici['NN'] = '\u0981';
phonetici['Y'] = '\u09CD' + '\u09AF';
phonetici['w'] = '\u09CD' + '\u09AC';
phonetici['W'] = '\u09C3';
phonetici['wr'] = '\u09C3';
phonetici['x'] = "\u0995" + '\u09CD' + '\u09B8';
phonetici['rY'] = phonetici['r'] + '\u200D' + '\u09CD' + '\u09AF';
phonetici['L'] = phonetici['l'];
phonetici['Z'] = phonetici['z'];
phonetici['P'] = phonetici['p'];
phonetici['V'] = phonetici['v'];
phonetici['B'] = phonetici['b'];
phonetici['M'] = phonetici['m'];
phonetici['V'] = phonetici['v'];
phonetici['X'] = phonetici['x'];
phonetici['V'] = phonetici['v'];
phonetici['F'] = phonetici['f'];
phonetici['vowels'] = 'aIiUuoiiouueEiEu'; //dont change this pattern


var carry = '';
var old_len = 0;
var ctrlPressed = false;
var len_to_process_oi_kar = 0;
var first_letter = false;
var carry2 = "";
isIE = document.all ? 1 : 0;
var switched = false;

function checkKeyDown(ev) {
    var e = (window.event) ? event.keyCode : ev.which;
    if (e == '17') {
        ctrlPressed = true;
    } else if (e == 16)
        shift = true;
}

function checkKeyUp(ev) {
    var e = (window.event) ? event.keyCode : ev.which;
    if (e == '17') {
        ctrlPressed = false;
    }
}

function parsePhonetici(evnt) {
    var t = document.getElementById(activeta);
    var e = (window.event) ? event.keyCode : evnt.which;
    if (e == '113') {
        if (ctrlPressed) {
            switched = !switched;
            return true;
        }
    }
    if (switched)
        return true;
    if (ctrlPressed) {
        e = 0;
    }
    if (shift) {
        var char_e = String.fromCharCode(e).toUpperCase();
        shift = false;
    } else
        var char_e = String.fromCharCode(e);
    if (e == 8 || e == 32) {
        carry = " ";
        old_len = 1;
        return;
    }

    lastcarry = carry;
    carry += "" + char_e;

    if ((phonetici['vowels'].indexOf(lastcarry) != -1 && phonetici['vowels'].indexOf(char_e) != -1) || (lastcarry == " " && phonetici['vowels'].indexOf(char_e) != -1)) {
        if (carry == 'ii' || carry == 'uu') {
            carry = lastcarry + char_e;
        } else {
            char_e = char_e.toUpperCase();
            carry = lastcarry + char_e;
        }
    }

    bangla = parsePhoneticiCarry(carry);
    tempBangla = parsePhoneticiCarry(char_e);

    if (tempBangla == ".." || bangla == "..") {
        return false;
    }

    if (char_e == "+" || char_e == "=" || char_e == "`") {
        if (carry == "++" || carry == "==" || carry == "``") {
            insertConjunction(char_e, old_len);
            old_len = 1;
            return false;
        }
        insertAtCursor("\u09CD");
        old_len = 1;
        carry2 = carry;
        carry = char_e;
        return false;
    } else if (old_len == 0) {
        insertConjunction(bangla, 1);
        old_len = 1;
        return false;
    } else if (carry == "Ao") {
        insertConjunction(parsePhoneticiCarry("ao"), old_len);
        old_len = 1;
        return false;
    } else if (carry == "ii") {
        insertConjunction(phonetici['ii'], 1);
        old_len = 1;
        return false;
    } else if (carry == "oI") {
        insertConjunction('\u09C8', old_len);
        old_len = 1;
        return false;
    } else if (char_e == "o") {
        old_len = 1;
        insertAtCursor('\u09CB');
        carry = "o";
        return false;
    } else if (carry == "oU") {
        insertConjunction("\u09CC", old_len);
        old_len = 1;
        return false;
    } else if ((bangla == "" && tempBangla != ""))
    {//that means it has no joint equivalent
        bangla = tempBangla;
        if (bangla == "") {
            carry = "";
            return;
        } else {
            carry = char_e;
            insertAtCursor(bangla);
            old_len = bangla.length;
            return false;
        }
    } else if (bangla != "") {
        insertConjunction(bangla, old_len);
        old_len = bangla.length;
        return false;
    }
}

function parsePhoneticiCarry(code) {
    if (!phonetici[code]) {
        return '';
    } else {
        return (phonetici[code]);
    }
}

function insertAtCursor(val) {
    var cObj = document.getElementById(activeta);
    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        sel.text = val;
        sel.collapse(true);
        sel.select();
    }
    //MOZILLA/NETSCAPE support
    else if (cObj.selectionStart || cObj.selectionStart == 0) {

        var startPos = cObj.selectionStart;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos)
                + val
                + cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + val.length;
        cObj.selectionEnd = startPos + val.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += val;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function insertConjunction(val, len) {
    var cObj = document.getElementById(activeta);
    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        if (cObj.value.length >= len) {
            sel.moveStart('character', -1 * (len));
        }
        sel.text = val;
        sel.collapse(true);
        sel.select();
    }
    //MOZILLA/NETSCAPE support
    else if (cObj.selectionStart || cObj.selectionStart == 0) {
        cObj.focus();
        var startPos = cObj.selectionStart - len;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos)
                + val
                + cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + val.length;
        cObj.selectionEnd = startPos + val.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += val;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function makePhoneticiIniKeyboard(textBoxId) {
    activeTextBox = document.getElementById(textBoxId);
    activeTextBox.onkeypress = parsePhonetici;
    activeTextBox.onkeydown = checkKeyDown;
    activeTextBox.onkeyup = checkKeyUp;
    activeTextBox.onfocus = function () {
        activeta = textBoxId;
    };
}
function makeVirtualKeyboard(textBoxId) {
    activeTextBox = document.getElementById(textBoxId);
    activeTextBox.onfocus = function () {
        activeta = textBoxId;
    };
}


var activeta;
var uniphonetic = new Array();
uniphonetic['0'] = '\u09E6';
uniphonetic['1'] = '\u09E7';
uniphonetic['2'] = '\u09E8';
uniphonetic['3'] = '\u09E9';
uniphonetic['4'] = '\u09EA';
uniphonetic['5'] = '\u09EB';
uniphonetic['6'] = '\u09EC';
uniphonetic['7'] = '\u09ED';
uniphonetic['8'] = '\u09EE';
uniphonetic['9'] = '\u09EF';
uniphonetic['k'] = "\u0995";
uniphonetic['i'] = '\u09BF';
uniphonetic['I'] = '\u0987';
uniphonetic['ii'] = '\u09C0';
uniphonetic['II'] = '\u0988';
uniphonetic['e'] = '\u09C7';
uniphonetic['E'] = '\u098F';
uniphonetic['U'] = '\u0989';
uniphonetic['u'] = '\u09C1';
uniphonetic['uu'] = '\u09C2';
uniphonetic['UU'] = '\u098A';
uniphonetic['r'] = '\u09B0';
uniphonetic['WR'] = '\u098B';
uniphonetic['a'] = '\u09BE';
uniphonetic['A'] = '\u0986';
uniphonetic['ao'] = '\u0985';
uniphonetic['s'] = '\u09B8';
uniphonetic['t'] = '\u099f';
uniphonetic['K'] = '\u0996';
uniphonetic['kh'] = '\u0996';
uniphonetic['n'] = '\u09A8';
uniphonetic['N'] = '\u09A3';
uniphonetic['T'] = '\u09A4';
uniphonetic['Th'] = '\u09A5';
uniphonetic['d'] = '\u09A1';
uniphonetic['dh'] = '\u09A2';
uniphonetic['b'] = '\u09AC';
uniphonetic['bh'] = '\u09AD';
uniphonetic['v'] = '\u09AD';
uniphonetic['R'] = '\u09DC';
uniphonetic['Rh'] = '\u09DD';
uniphonetic['g'] = '\u0997';
uniphonetic['G'] = '\u0998';
uniphonetic['gh'] = '\u0998';
uniphonetic['h'] = '\u09B9';
uniphonetic['NG'] = '\u099E';
uniphonetic['j'] = '\u099C';
uniphonetic['J'] = '\u099D';
uniphonetic['jh'] = '\u099D';
uniphonetic['c'] = '\u099A';
uniphonetic['ch'] = '\u099A';
uniphonetic['C'] = '\u099B';
uniphonetic['th'] = '\u09A0';
uniphonetic['p'] = '\u09AA';
uniphonetic['f'] = '\u09AB';
uniphonetic['ph'] = '\u09AB';
uniphonetic['D'] = '\u09A6';
uniphonetic['Dh'] = '\u09A7';
uniphonetic['z'] = '\u09AF';
uniphonetic['y'] = '\u09DF';
uniphonetic['Ng'] = '\u0999';
uniphonetic['ng'] = '\u0982';
uniphonetic['l'] = '\u09B2';
uniphonetic['m'] = '\u09AE';
uniphonetic['sh'] = '\u09B6';
uniphonetic['S'] = '\u09B7';
uniphonetic['O'] = '\u0993';
uniphonetic['ou'] = '\u099C';
uniphonetic['OU'] = '\u0994';
uniphonetic['Ou'] = '\u0994';
uniphonetic['Oi'] = '\u0990';
uniphonetic['OI'] = '\u0990';
uniphonetic['tt'] = '\u09CE';
uniphonetic['H'] = '\u0983';
uniphonetic["."] = "\u0964";
uniphonetic[".."] = ".";
uniphonetic['HH'] = '\u09CD' + '\u200c';
uniphonetic['NN'] = '\u0981';
uniphonetic['Y'] = '\u09CD' + '\u09AF';
uniphonetic['w'] = '\u09CD' + '\u09AC';
uniphonetic['W'] = '\u09C3';
uniphonetic['wr'] = '\u09C3';
uniphonetic['x'] = "\u0995" + '\u09CD' + '\u09B8';
uniphonetic['rY'] = uniphonetic['r'] + '\u200c' + '\u09CD' + '\u09AF';
uniphonetic['L'] = uniphonetic['l'];
uniphonetic['Z'] = uniphonetic['z'];
uniphonetic['P'] = uniphonetic['p'];
uniphonetic['V'] = uniphonetic['v'];
uniphonetic['B'] = uniphonetic['b'];
uniphonetic['M'] = uniphonetic['m'];
uniphonetic['V'] = uniphonetic['v'];
uniphonetic['X'] = uniphonetic['x'];
uniphonetic['V'] = uniphonetic['v'];
uniphonetic['F'] = uniphonetic['f'];

var carry = '';
var old_len = 0;
var ctrlPressed = false;
var len_to_process_oi_kar = 0;
var first_letter = false;
isIE = document.all ? 1 : 0;

var switched = false;
function checkKeyDown(ev) {
    var e = (window.event) ? event.keyCode : ev.which;
    if (e == '17') {
        ctrlPressed = true;
    }
}

function checkKeyUp(ev) {
    var e = (window.event) ? event.keyCode : ev.which;
    if (e == '17')
    {
        ctrlPressed = false;
    }
}

function parseuniphonetic(evnt) {
    var t = document.getElementById(activeta);
    var e = (window.event) ? event.keyCode : evnt.which;
    if (e == '113')
    {
        if (ctrlPressed) {
            switched = !switched;
            return true;
        }
    }
    if (switched)
        return true;
    if (ctrlPressed)
    {
        e = 0;
    }
    var char_e = String.fromCharCode(e);
    if (e == 8 || e == 32)
    {
        carry = " ";
        old_len = 1;
        return;
    }
    lastcarry = carry;
    carry += "" + char_e;
    bangla = parseuniphoneticCarry(carry);
    tempBangla = parseuniphoneticCarry(char_e);
    if (tempBangla == ".." || bangla == "..")
    {
        return false;
    }
    if (char_e == "+") {
        if (carry == "++")
        {
            insertJointAtCursor("+", old_len);
            old_len = 1;
            return false;
        }
        insertAtCursor("\u09CD");
        old_len = 1;
        carry = "+";
        return false;
    } else if (old_len == 0)
    {
        insertJointAtCursor(bangla, 1);
        old_len = 1;
        return false;
    } else if (carry == "ao")
    {
        insertJointAtCursor(parseuniphoneticCarry("ao"), old_len);
        old_len = 1;
        return false;
    } else if (carry == "ii")
    {
        insertJointAtCursor(uniphonetic['ii'], 1);
        old_len = 1;
        return false;
    } else if (carry == "oi")
    {
        insertJointAtCursor('\u09C8', 1);
        return false;
    } else if (char_e == "o")
    {
        old_len = 1;
        insertAtCursor('\u09CB');
        carry = "o";
        return false;
    } else if (carry == "ou")
    {
        insertJointAtCursor("\u09CC", old_len);
        old_len = 1;
        return false;
    } else if ((bangla == "" && tempBangla != "")) {
        bangla = tempBangla;
        if (bangla == "")
        {
            carry = "";
            return;
        } else
        {
            carry = char_e;
            insertAtCursor(bangla);
            old_len = bangla.length;
            return false;
        }
    } else if (bangla != "")
    {
        insertJointAtCursor(bangla, old_len);
        old_len = bangla.length;
        return false;
    }
}

function parseuniphoneticCarry(code) {
    if (!uniphonetic[code])
    {
        return '';
    } else
    {
        return (uniphonetic[code]);
    }
}

function insertAtCursor(val) {
    var cObj = document.getElementById(activeta);
    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        sel.text = val;
        sel.collapse(true);
        sel.select();
    } else if (cObj.selectionStart || cObj.selectionStart == 0) {
        var startPos = cObj.selectionStart;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos)
                + val
                + cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + val.length;
        cObj.selectionEnd = startPos + val.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += val;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function insertJointAtCursor(val, len) {
    var cObj = document.getElementById(activeta);
    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        if (cObj.value.length >= len) {
            sel.moveStart('character', -1 * (len));
        }
        sel.text = val;
        sel.collapse(true);
        sel.select();
    } else if (cObj.selectionStart || cObj.selectionStart == 0) {
        cObj.focus();
        var startPos = cObj.selectionStart - len;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos)
                + val
                + cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + val.length;
        cObj.selectionEnd = startPos + val.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += val;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function makeUniPhoneticKeyboard(textBoxId) {
    activeTextBox = document.getElementById(textBoxId);
    activeTextBox.onkeypress = parseuniphonetic;
    activeTextBox.onkeydown = checkKeyDown;
    activeTextBox.onkeyup = checkKeyUp;
    activeTextBox.onfocus = function () {
        activeta = textBoxId;
    };
}

var activeta;
var unijoy = new Array();
unijoy['j'] = "\u0995";
unijoy['d'] = '\u09BF';
unijoy['gd'] = '\u0987';
unijoy['D'] = '\u09C0';
unijoy['gD'] = '\u0988';
unijoy['c'] = '\u09C7';
unijoy['gc'] = '\u098F';
unijoy['gs'] = '\u0989';
unijoy['s'] = '\u09C1';
unijoy['S'] = '\u09C2';
unijoy['gS'] = '\u098A';
unijoy['v'] = '\u09B0';
unijoy['a'] = '\u098B';
unijoy['f'] = '\u09BE';
unijoy['gf'] = '\u0986';
unijoy['F'] = '\u0985';
unijoy['n'] = '\u09B8';
unijoy['t'] = '\u099f';
unijoy['J'] = '\u0996';
unijoy['b'] = '\u09A8';
unijoy['B'] = '\u09A3';
unijoy['k'] = '\u09A4';
unijoy['K'] = '\u09A5';
unijoy['e'] = '\u09A1';
unijoy['E'] = '\u09A2';
unijoy['h'] = '\u09AC';
unijoy['H'] = '\u09AD';
unijoy['p'] = '\u09DC';
unijoy['P'] = '\u09DD';
unijoy['o'] = '\u0997';
unijoy['O'] = '\u0998';
unijoy['i'] = '\u09B9';
unijoy['I'] = '\u099E';
unijoy['u'] = '\u099C';
unijoy['U'] = '\u099D';
unijoy['y'] = '\u099A';
unijoy['Y'] = '\u099B';
unijoy['T'] = '\u09A0';
unijoy['r'] = '\u09AA';
unijoy['R'] = '\u09AB';
unijoy['l'] = '\u09A6';
unijoy['L'] = '\u09A7';
unijoy['w'] = '\u09AF';
unijoy['W'] = '\u09DF';
unijoy['q'] = '\u0999';
unijoy['Q'] = '\u0982';
unijoy['V'] = '\u09B2';
unijoy['m'] = '\u09AE';
unijoy['M'] = '\u09B6';
unijoy['N'] = '\u09B7';
unijoy['gx'] = '\u0993';
unijoy['X'] = '\u09CC';
unijoy['gX'] = '\u0994';
unijoy['gC'] = '\u0990';
unijoy['\\'] = '\u09CE';
unijoy['|'] = '\u0983';
unijoy["G"] = "\u0964";
unijoy['g'] = ' ';
unijoy['&'] = '\u0981';
unijoy['Z'] = '\u09CD' + '\u09AF';
unijoy['gh'] = '\u09CD' + '\u09AC';
unijoy['ga'] = '\u098B';
unijoy['a'] = '\u09C3';
unijoy['rZ'] = unijoy['r'] + '\u200c' + '\u09CD' + '\u09AF';
unijoy['z'] = '\u09CD' + unijoy['v'];
unijoy['x'] = '\u09CB';
unijoy['C'] = '\u09C8';
unijoy['0'] = '\u09E6';
unijoy['1'] = '\u09E7';
unijoy['2'] = '\u09E8';
unijoy['3'] = '\u09E9';
unijoy['4'] = '\u09EA';
unijoy['5'] = '\u09EB';
unijoy['6'] = '\u09EC';
unijoy['7'] = '\u09ED';
unijoy['8'] = '\u09EE';
unijoy['9'] = '\u09EF';
var carry = '';
var old_len = 0;
var ctrlPressed = false;
var altPressed = false;
var first_letter = false;
var lastInserted;
isIE = document.all ? 1 : 0;
var switched = false;
function checkKeyDown(ev) {
    var e = (window.event) ? event.keyCode : ev.which;
    if (e == '17') {
        ctrlPressed = true;
    } else if (e == '18') {
        altPressed = true;
    }
}

function checkKeyUp(ev) {
    var e = (window.event) ? event.keyCode : ev.which;
    if (e == '17') {
        ctrlPressed = false;
    } else if (e == '18') {
        altPressed = false;
    }
}

function parseunijoy(evnt) {
    var t = document.getElementById(activeta);
    var e = (window.event) ? event.keyCode : evnt.which;
    if (e == '113') {
        if (ctrlPressed) {
            switched = !switched;
            return true;
        }
    }
    if (switched)
        return true;
    if (ctrlPressed) {
        e = 0;
    }
    var char_e = String.fromCharCode(e);
    if (e == 8 || e == 32) {
        carry = " ";
        old_len = 1;
        return;
    }
    lastcarry = carry;
    carry += "" + char_e;
    if (typeof isEnglishOn != 'undefined') {
        if (isEnglishOn == "true") {
            if (char_e.length != 0 && e != 0) {
                insertAtCursor(char_e);
                old_len = lastInserted.length;
            }
            return false;
        }
    }
    bangla = parseunijoyCarry(carry);
    tempBangla = parseunijoyCarry(char_e);
    if (tempBangla == ".." || bangla == "..") {
        return false;
    }
    if (char_e == "g") {
        if (carry == "gg") {
            insertConjunction('\u09CD' + '\u200c', old_len);
            old_len = 1;
            return false;
        }
        insertAtCursor("\u09CD");
        old_len = 1;
        carry = "g";
        return false;
    } else if (old_len == 0) {
        insertConjunction(bangla, 1);
        old_len = 1;
        return false;
    } else if (char_e == "A") {
        newChar = unijoy['v'] + '\u09CD' + lastInserted;
        insertConjunction(newChar, lastInserted.length);
        old_len = lastInserted.length;
        return false;
    } else if ((bangla == "" && tempBangla != "")) {
        bangla = tempBangla;
        if (bangla == "") {
            carry = "";
            return;
        } else {
            carry = char_e;
            insertAtCursor(bangla);
            old_len = bangla.length;
            return false;
        }
    } else if (bangla != "") {
        insertConjunction(bangla, old_len);
        old_len = bangla.length;
        return false;
    }
}

function parseunijoyCarry(code) {
    if (!unijoy[code]) {
        return '';
    } else {
        return (unijoy[code]);
    }
}

function insertAtCursor(val) {
    if (val.length == 1 && (val.charCodeAt(0) == "0" || val.charCodeAt(0) == "27")) {
        return;
    }
    lastInserted = val;
    var cObj = document.getElementById(activeta);
    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        sel.text = val;
        sel.collapse(true);
        sel.select();
    } else if (cObj.selectionStart || cObj.selectionStart == 0) {
        var startPos = cObj.selectionStart;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos) + val + cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + val.length;
        cObj.selectionEnd = startPos + val.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += val;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function insertConjunction(val, len) {
    lastInserted = val;
    var cObj = document.getElementById(activeta);
    if (document.selection) {
        cObj.focus();
        sel = document.selection.createRange();
        if (cObj.value.length >= len) {
            sel.moveStart('character', -1 * (len));
        }
        sel.text = val;
        sel.collapse(true);
        sel.select();
    } else if (cObj.selectionStart || cObj.selectionStart == 0) {
        cObj.focus();
        var startPos = cObj.selectionStart - len;
        var endPos = cObj.selectionEnd;
        var scrollTop = cObj.scrollTop;
        startPos = (startPos == -1 ? cObj.value.length : startPos);
        cObj.value = cObj.value.substring(0, startPos) + val + cObj.value.substring(endPos, cObj.value.length);
        cObj.focus();
        cObj.selectionStart = startPos + val.length;
        cObj.selectionEnd = startPos + val.length;
        cObj.scrollTop = scrollTop;
    } else {
        var scrollTop = cObj.scrollTop;
        cObj.value += val;
        cObj.focus();
        cObj.scrollTop = scrollTop;
    }
}

function makeUnijoyKeyboard(textBoxId) {
    activeTextBox = document.getElementById(textBoxId);
    activeTextBox.onkeypress = parseunijoy;
    activeTextBox.onkeydown = checkKeyDown;
    activeTextBox.onkeyup = checkKeyUp;
    activeTextBox.onfocus = function () {
        activeta = textBoxId;
    };
}

function noEvent(evt) {
    return;
}
function makeEnglishKeyboard(textBoxId) {
    activeTextBox = document.getElementById(textBoxId);
    activeTextBox.onkeypress = noEvent;
    activeTextBox.onkeydown = noEvent;
    activeTextBox.onkeyup = noEvent;
    activeTextBox.onfocus = function () {
        activeta = textBoxId;
    };
}

function resSetKeyboard(textBoxId) {
    activeTextBox = document.getElementById(textBoxId);
    activeTextBox.onkeypress = noEvent;
    activeTextBox.onkeydown = noEvent;
    activeTextBox.onkeyup = noEvent;
    activeTextBox.onfocus = function () {
        activeta = textBoxId;
    };
}

function SetKeyboard(activeta, keyboard) {

    if (activeta === 'undefined' || !activeta || !document.getElementById(activeta) || document.getElementById(activeta).readOnly)
        return;
    if (!keyboard || keyboard === 'undefined' || keyboard === '' || keyboard === 'english') {
        resSetKeyboard(activeta);
        return;
    }

    if (!keyboard || keyboard === 'unijoy') {
        makeUnijoyKeyboard(activeta);
    } else if (keyboard === 'phonetici') {
        makePhoneticiIniKeyboard(activeta);
    } else if (keyboard === 'uniphonetic') {
        makeUniPhoneticKeyboard(activeta);
    }
//    else if (keyboard == 'english') {
//        makeEnglishKeyboard(activeta);
//    }
}
