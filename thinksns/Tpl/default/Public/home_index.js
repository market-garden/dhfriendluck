var lastinput = "";
$(function(){
	if( null == getCookie('gonggao_'+MID)){
	    $('#gonggao').show();
	}else{
	    $('#gonggao').hide();
	}
	
   $("#xq_con").blur(function(){
       if(!$("#xq_con").val()) $("#smileylist").hide();
   });

	//feed_item切换
	$(".feed_item").click(function() {
		var _this = this;
		type = $(this).attr("rel");
		var loading = '<div align="center" style="padding-top:50px"><img src="'+PUBLIC+'/images/loading_blue_big.gif"></div>';
		$("#feed_content").html(loading);
    var who = $( '#who' ).val();
		$("#feed_content").load(APP+"/Home/feed",{type:type,user:who},function(txt){

			$(".feed_item").removeClass("on");
			$(_this).addClass("on");
			if(!txt){
				$("#feed_more").hide();
				$("#feed_content").html("<div style='font-size:20px;padding-top:20px' align='center'>暂无相关动态...</div>");
			}
			
		});
	});

  $( '.feed_item1' ).click( function(){
      var _this = this;
      var clickType = $( this ).attr( 'rel' );

      var href = location.href;
      var re = /type/;
      var re2 = /who/g;
      var re3 = /uid/g;
      var newhref = "";
      var test1 = re.test( href );
      var test2 = re2.test( href );
      var test3 = re3.test( href );
      var userId = $( '#uid' ).val();

      if( test3 ){
        var temp_href = href.split( '/uid/' );
        href = APP+'/Home/allFeed';
      }

      if( (test1 && test2) ) {
        var temp_array = href.split( '/type/' );
        var temp_array2 = href.split( '/who/' );
        newhref = temp_array[0]+"/type/"+clickType+'/who/'+temp_array2[1];
      }else if( ( !test1 && !test2 ) ){
        newhref=temp_array[0]+"/type/"+clickType;
      }else if( (test1 && !test2) ){
        var temp_array = href.split( '/type/' );
        newhref = temp_array[0]+'/type/'+clickType;
      }else{
      alert( href );
        alert( re.test( href ) );
        alert( re2.test( href ) );
      }

      if( test3 ){
        newhref = newhref+'/uid/'+temp_href[1];
      }
      location.href = newhref;
      
  });


	//more...
    $("#getMoreFeed").click(function() {
      var url=APP+"/Home/allFeed/type/"+$( '#feed_type' ).val();
      //alert( href );
      document.location.href = url;
	});

	//载入首页的时候执行,以获取所有的动态
	if( typeof(type)   ==   'undefined' || type == '' ) {
    $("#feed_all_item").click();
  }else{
    getFeed();
  };

	//当<30条的时候隐藏more...
	var feed_count = $(".Fli").size();
	if(feed_count < 30) $("#feed_more").hide();
});

function closeGongGao(uid){
	if(!getCookie('gonggao_'+uid)){
		setCookie('gonggao_'+uid,'1');
	    $('#gonggao').hide('slow');
	}
}

function getFeed(){
    var userId = $( '#uid' ).val();
		var loading = '<div align="center" style="padding-top:50px"><img src="'+PUBLIC+'/images/loading_blue_big.gif"></div>';
                var page = $('#page_count').val();
		$("#feed_content").html(loading);
		$("#feed_content").load(APP+"/Home/feed",{type:type,who:group,user:userId,p:page},function(txt){

			$(".feed_item").removeClass("on");
			if(!txt){
				$("#feed_more").hide();
				$("#feed_content").html("<div style='font-size:20px;padding-top:20px' align='center'>暂无相关动态...</div>");
			}
			
		});
}




function friendsFeed( value ){
  var href = location.href;
  var re = /who/g;
  if( re.test( href ) ) {
    var temp_array = href.split( '/who/' );
    location.href=temp_array[0]+"/who/"+value;
  }else{
    location.href=href+"/who/"+value;
  }
}
function bq_show(){
    $("#smileylist").show().mouseover(function(){
         $("#xq_con").unbind("blur");
    }).mouseout(function(){
       $("#xq_con").blur(function(){
           if(!$("#xq_con").val()) $("#smileylist").hide();
       });
    });
}

function insertBQ(_this,bid){
    var emotion = $(_this).attr("emotion");
    var old_con = $("#xq_con").val();
    var new_con = old_con+emotion;
    $("#xq_con").val(new_con);
}

//添加心情
function doAddMini(){
  var content = $( '#xq_con' ).val();
  //检测合法性

 if(!content) {alert("不能为空哦~~~");return;}
 if(content.length>mini_zishu) {alert("不能多于mini_zishu个字哦~~~");return;}

  $( '.btn_big' ).attr( 'disabled',true );
      $(".phiz").hide();
      $( ".jishuan" ).hide();

  //POST提交
  $.post( ROOT+"/apps/mini/index.php?s=Index/doAddMini/",{content:content},function( txt ){
      if(false == checkJsToken(txt)){
          $( '.btn_big').removeAttr('disabled');
          return true;
      }
      if( txt ){
          $( '#mini-content' ).html( txt );
          $( '#mini-time' ).html( '刚刚' );
          $( '#xq_con' ).val('');
          $( '#mini-count' ).html( mini_zishu+"/"+mini_zishu );
          $( '.btn_big').removeAttr('disabled');

      }else{
      alert( "提交失败" );
            $(this).removeAttr('disabled');
      }
      });


}

 //字数递减和限制字数
 function fot(e){
        //递减字数
        var d = document.getElementById("zishu");
        var c = e.value.length;
        var xxx = mini_zishu - c;
        if(c<=mini_zishu){
            d.innerHTML =xxx;
        }else{
           e.value = lastinput;
          $( '#xq_con' ).focus();
          return false;
        }
        lastinput = e.value;
   }


  
 










