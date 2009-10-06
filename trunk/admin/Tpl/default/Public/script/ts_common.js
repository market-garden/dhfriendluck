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
});


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

//全站统一举报函数
function report(type,url,info){
//    Confirm({message:'确定要举报这个内容？',handler:function(tp){
//            if(tp=='ok'){
//                $.post(TS+"/Public/report",{type:type,url:url,info:info},function(txt){
//                    if(txt){
//                        Alert("举报成功!");
//                    }else{
//                        Alert("你已经举报过了!");
//                    }
//                });
//            }
//            if(tp=='cancel'){
//                ymPrompt.close();
//            }
//            if(tp=='close'){
//                ymPrompt.close();
//            }
//        }});	

url = encode64(url);
Win({message:TS+'/Public/isReport/type/'+type+"/url/"+url+"/info/"+info,width:392,height:220,title:'举报此信息',handler:handlerIframe,autoClose:false,iframe:true,allowRightMenu:true});


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


