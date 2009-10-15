var a= '';
var js_token='';
/**
 *计算字符串长度的函数
 *
 */
function JHshStrLen(sString)
{
        var sStr,iCount,i,strTemp ;

        iCount = 0 ;
        sStr = sString.split("");
        for (i = 0 ; i < sStr.length ; i ++)
        {
                strTemp = escape(sStr[i]);
                if (strTemp.indexOf("%u",0) == -1)
                {
                        iCount = iCount + 1 ;
                }
                else
                {
                        iCount = iCount + 2 ;
                }
        }

        return iCount ;
}



/**
 * checkbox选择控制
 *
 */
//按照类来选择
function selectAll(class_name) {
        //var checked = $("#selectall").attr("checked");

        $("."+class_name).each(function() {
                var subchecked = $(this).attr("checked");
                if (subchecked != true)
                        $(this).click();
        });
}

function unSelectAll(class_name) {
        //var checked = $("#selectall").attr("checked");

        $("."+class_name).each(function() {
                var subchecked = $(this).attr("checked");
                if (subchecked == true)
                        $(this).click();
        });
}

function getSelectValues() {
        id = [];
        $("input[type='checkbox']:checked").each(function(){
                id.push($(this).val());
        });
        return id.join(',');
}


//最常用的

$(function() {
        //showCss();

        js_token = $('#js_token').val();
        //setInterval( 'cleanName()',5000 );
        $("#checkAll").click(function(){
                if(this.checked){
                        $("input[type='checkbox']").each(function(i){
                                this.checked=true;
                        });
                }else{
                        $("input[type='checkbox']").each(function(i){
                                this.checked=false;
                        });
                }
        });
//shortcuts
});
function cleanName(){
        a = '';
}

function checkLogin(){
        delCookie('log_refer');
        if(0 == MID){
                var url = location.href;
                setCookie('log_refer',url,'1000000' );
                window.location.href = APP+'/Index/login';
        }
}


function escapeHTML(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
};


function ReplaceAll(str, sptr, sptr1)
{
        while (str.indexOf(sptr) >= 0)
        {
                str = str.replace(sptr, sptr1);
        }
        return str;
}


function handlerIframe(){
        // alert(ymPrompt.getPage().contentWindow.document.body.innerHTML);
        ymPrompt.close();
}




var keyStr = "ABCDEFGHIJKLMNOP" +
"QRSTUVWXYZabcdef" +
"ghijklmnopqrstuv" +
"wxyz0123456789+/" +
"=";

function encode64(input)
{
        input = escape(input);
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        do
        {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2))
                {
                        enc3 = enc4 = 64;
                }
                else if (isNaN(chr3))
                {
                        enc4 = 64;
                }

                output = output +
                keyStr.charAt(enc1) +
                keyStr.charAt(enc2) +
                keyStr.charAt(enc3) +
                keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
        } while (i < input.length);

        return output;
}

function decode64(input)
{
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
        var base64test = /[^A-Za-z0-9\+\/\=]/g;
        if (base64test.exec(input))
        {
                alert("There were invalid base64 characters in the input text.\n" +
                        "Valid base64 characters are A-Z, a-z, 0-9, '+', '/', and '='\n" +
                        "Expect errors in decoding.");
        }
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        do
        {
                enc1 = keyStr.indexOf(input.charAt(i++));
                enc2 = keyStr.indexOf(input.charAt(i++));
                enc3 = keyStr.indexOf(input.charAt(i++));
                enc4 = keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64)
                {
                        output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64)
                {
                        output = output + String.fromCharCode(chr3);
                }

                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
        } while (i < input.length);
        return unescape(output);
}

$(function(){
        ///	$.areaopt.bind('#ts_area');
        showTips();
        
        $("[selectArea='true']").bind("click", function(){
                var type = $(this).attr('areatype');
                var typevalue = $("#ts_"+type).val();
                ymPrompt.win({
                        message:TS+'/Index/network/type/'+type+'/selected/'+typevalue,
                        width:600,
                      //  height:370,
                        height:420,
                        title:'选择地区',
                        handler:function(){
                                ymPrompt.close();
                        },
                        autoClose:false,
                        iframe:true,
                        allowRightMenu:true
                });
        });
        if(MID!=0){
                getMsgCount();
        }
//$('#regform').checkForm();
});

function setCookie(name,value,exp) {
        var LargeExpDate = new Date ();
        expires = exp?ex:exp;
        LargeExpDate.setTime(LargeExpDate.getTime() + (3600*1000*24*30));
        path = '/';
        document.cookie = name + "="+ escape (value) + ";expires=" + LargeExpDate.toGMTString()+path;
}

function getCookie(name) {
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
}

function delCookie(name){
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval=getCookie(name);
        path = '/';
        if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString()+path;
}
/**
 *      显示名片
 */
function showTips() {
        if(TPIS =='1'){
                                   $('a.tips').cluetip({
                width: 350,
                cluetipClass: 'jtip',
                showTitle: false,
                clickThrough:true,
                arrows: false,
                dropShadow: false,
                hoverIntent: false,
                tracking: false,
                sticky: true,
                mouseOutClose: true,
                leftOffset:6,
                topOffset:5,
                cluezIndex:30,
                closePosition: 'title'
        });
        }
 
}
function getMsgCount(){
        var num = 0;
        var href = '';
        $.post(TS+'/Index/getMsgCount',{
                uid:MID
        },function(result){

                if(result != 0){
                        JSON.parse(result,function(key,value){
                                //value = parseInt(value);
                                if(value != 0 && key){
                                        num = num + value;
                                        $('#'+key+MID).html('('+value+'条'+')');
                                        switch(key){
                                                case 'friend':
                                                        href = TS+'/Notify/index/t/fri';
                                                        break;
                                                case 'wall':
                                                        href = TS+'/Space/#wall';
                                                        break;
                                                case 'message':
                                                        href = TS+'/Notify/inbox';
                                                        break;
                                                default:
                                                        href = TS+'/Notify/index/t/sys/';
                                                        break;
                                        }

                                }
                        });
                        if(num == 0){
                                $('#msg_top').remove();
                        }else{
                                $('#msg_top_href').attr('href',href).text(num);
                                $('#msg_top').css('display','block');
                        }
                }
        });
}
function showCss(){
//$(document).not('input').bind('keydown', 'ctrl+shift+c',function (evt){	loadScript('http://westciv.com/xray/thexray.js'); return false; });
//showKey();
}

function showKey(){
        $(document+':not(input)').bind('keydown', 'right',function (evt){
                nextPage(); return true;
        });
        $(document+':not(input)').bind('keydown', 'left',function (evt){
                prePage(); return true;
        });
}
function nextPage(){
        var page = $('#Pagination').html();
        if(page){
                location.href = $('#Pagination').children('a:last').attr('href');
        }
}
function prePage(){
        var page = $('#Pagination').html();
        if(page){
                location.href = $('#Pagination').children('a:first').attr('href');
        }
}
function loadScript(scriptURL){
        var scriptElem = document.createElement('SCRIPT');
        scriptElem.setAttribute('language', 'JavaScript');
        scriptElem.setAttribute('src', scriptURL);
        document.body.appendChild(scriptElem);
}
function alertAuthor(){
        var re = /[^\d]/g;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/
        var mid = a;
        var test = re.test(mid);
        if(!test){
                a ='';
                location.href = TS+'/space/'+mid;
        }
        switch(a){
                case 'sam':
                        alert('Sam');
                        break;
                case 'nonant':
                        alert('冷浩然');
                        break;
                case 'sst':
                        alert('水上铁');
                        break;
                case 'haixia':
                        alert('海虾');
                        break;
                case 'shoutaimu':
                        alert('兽汰姆');
                        break;
                default:
                        a = '';
        }
        a = '';

}
function gotoHome(){
        if(MID!=0) location.href = TS+'/Home/index';
}
function gotoMySpace(){
        if(MID!=0) location.href = TS+'/space/'+MID;
}

function checkJsToken(txt) {
        if(txt == 'error'){
                Alert('请不要频繁提交');
                $( '.btn_big').removeAttr('disabled');
                return false;
        }
        if(txt == 'fail'){
                Alert('说得太快了。休息会吧');
                $( '.btn_big').removeAttr('disabled');
                return false;
        }
        return true
}