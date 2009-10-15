var oddReplay = "";
var lastinput = "";
var touid = 0;
$(document).ready(function(){
      $( ".input_box > textarea" ).val( "添加回复" );
        $( '#mini-coment' ).click( function(){
              $( '.jishuan' ).show();
              bq_show();
           });

    $("#mini-coment").blur(function(){
      if(!$("#mini-coment").val()){
        $( ".phiz").hide();
        $( '.jishuan').hide();
      }
      })

    $( ".MC" ).mouseover( function(){
      var id = $( this ).attr( 'id' ).substring(3) ;
     $( '#d-'+id ).show();
      } );

    $( ".MC" ).mouseout( function(){
        var id = $( this ).attr( 'id' ).substring(3) ;
        $( '#d-'+id ).hide();
        } );
    $( '.inputReplay' ).click( function(){
        replayShow($( this ));
        });
    $( '.inputReplay' ).blur( function(){
        replayHide($( this ));
    })
    $( '.RLI' ).mouseover( function(){
        var id = $( this ).attr( 'id' );
        $( '#d-'+id ).show();
        });
    $( '.RLI' ).mouseout( function(){
        var id = $( this ).attr( 'id' );
        $( '#d-'+id ).hide();
        });

});

/**
 * delete 
 * 删除自己的心情
 * @param id  $id  
 * @access public
 * @return void
 */
function deleteMini( id ){
    Confirm({message:'是否删除此心情',handler:function(button){
      if ( 'ok' == button ){
        $.post( APP+'/Index/doDeleteMini/',{id:id},function( text ){
              if( text == 1 ){
                  $( '#Fli'+id ).remove();
              }else{
                  alert( "删除失败" );
              }
            });
      }
    }});
}

/**
 * bq_show 
 * 表情框显示
 * @access public
 * @return void
 */
function bq_show(){
  $(".phiz").show().mouseover(function(){
      $("#mini-coment").unbind("blur");
      }).mouseout(function(){
        $("#mini-coment").blur(function(){
          if(!$("#mini-coment").val()){
            $(".phiz").hide();
            $( ".jishuan" ).hide();
          }
          });
        });
}

function replaceReplay( vo ){

  var deleteReplay = vo.isDelete?"<a id=\"d-RLI"+vo.id+"\" style=\"display:none;\" class=\"del\" title=\"删除\" href=\"javascript:deleteComment( "+vo.id+","+vo.appid+" )\">删除</a>":"";
var result = "<div class=\"RLI btmline\" id=\"RLI"+vo.id+"\"><div class=\"user_img\"><a href=\""+TS+"/space/"+vo.uid+"\"><img src=\""+vo.face+"\" /></a></div><div class=\"RLC\"><h4><span class = \"right mt5\">"+deleteReplay+"</span><span class=\"left\"><strong><a href=\""+TS+"/space/"+vo.uid+"\" class =\"name"+vo.uid+"\">"+vo.name+"</a></strong> <span class=\"time\">"+vo.cTime+"</span></span></h4><p>"+vo.comment+"<a href=\"javascript:replay("+vo.uid+","+vo.appid+")\">回复</a></p></div><div class=\"c\"></div></div>";
return result;
}

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
 * 表情的插入
 *
 */
function insert(_this){
  var emotion = $(_this).attr("emotion");
  var old_con = $("#mini-coment").val();
  var new_con = old_con+emotion;
  $("#mini-coment").val(new_con);

}

function doAdd(){
  var content = $( '#mini-coment' ).val();
  //检测合法性

 if(!content) {alert("不能为空哦~~~");return;}
 if(content.length>mini_zishu) {alert("不能多于mini_zishu个字哦~~~");return;}

  $( '.btn_big' ).attr( 'disabled',true );
      $(".phiz").hide();
      $( ".jishuan" ).hide();

  //POST提交
  $.post( APP+"/Index/doAddMini/",{content:content},function( txt ){
      if(false == checkJsToken(txt)){
          $( '.btn_big').removeAttr('disabled');
          return true;
      }
      if( txt ){
          $( '#mini-content' ).html( txt );
          $( '#mini-time' ).html( '刚刚' );
          $( '#mini-coment' ).val('');
          $( '#mini-count' ).html( mini_zishu+"/"+mini_zishu );
          $( '.btn_big').removeAttr('disabled');

      }else{
      alert( "提交失败" );
            $(this).removeAttr('disabled');
      }
      });


}
function replayShow(_this){
        _this.attr( 'style',"height:50px; line-height:18px; width:325px;" );
        //_this.removeClass();
        id = _this.attr( 'id' ).substring(5);

        if( _this.val() == "添加回复" )
         _this.val("");
        _this.css( 'color','#000' );
        //_this.focus();
        $( '#image'+id ).css( 'display',"block" );
        $( '#button'+id ).css( 'display',"block" );

}
function replayHide(_this){
        if( !_this.val() ){
          //_this.addClass( 'cGray2' );
          _this.attr( 'style',"height:25px; line-height:25px; width:368px;" ).val("添加回复");
          id = _this.attr( 'id' ).substring(5);
          
          $( '#image'+id ).css( 'display',"none" );
          $( '#button'+id ).css( 'display',"none" );
        }
}

function replay( uid,mini_id ){
  $( '.inputReplay' ).blur();
  touid = uid;
  var obj = $( '#input'+mini_id );
  replayShow(obj) ;
  if( uid != "false" ){
    old_con = obj.val();
    //通过:号分割，不能同时回复几个人
    array = old_con.split(":");
    new_con = "回复"+$( '.name'+uid ).html() ;
    if( array[0] != new_con ){
      if( array[1] ){
        obj.val( new_con+":"+array[1] );
      }else{
        obj.val( new_con+":");
      }
    }
  }else{
    obj.focus();
  }

}
function getFilePath( num ){
  var dis = num.replace(/\[f\](\d*)\[\/f\]/g,'$1');
  return "<img wigth ='20' height='20' src='"+$( '#'+dis ).attr( 'src' )+"'/>";
}

function showMore( id,uid ){
  $( '#showMore'+id ).html("loading........");
  $.post( APP+"/Index/getReplay",{id:id,uid:uid},function( text ){
      if( text != -1 ){
           var result = JSON.parse(text);
            var newReplay = replaceReplay( result.OddReplay );
            var oddReplay="";
            //处理以往的数据信息。
            for( var one in result.OddReplay ){
               oddReplay += replaceReplay( result.OddReplay[one] );
            }
        $( '#showMore'+id ).hide();
        $( '#first'+id ).after( oddReplay );


        deleteMouse();
        $( '#last'+id ).hide();
        $( '#button'+id ).attr( 'showmore',false );
      }else{
        Alert( "无法加载全部回复" );
      }
      })
}

function replayComment( appid , more,mid ){
  //获得数据
  //评论内容 
  var content = $( '#input'+appid ).val();
  var name    = $( '#name' ).val();
  var page = $('#page').val();
  

  showmore = $( '#button'+id ).attr( 'showmore' );
  $( "#button"+appid ).attr( 'readonly',true ).val( "loading..." );
  //如果留言数大于2。则要加载隐藏的留言信息
  if( more && 'true' == showmore){
      //ajax发送
      $.post( APP+"/Index/doAddReplay",{toUid:touid,content:content,id:appid,more:showmore,mid:mid,page:page,js_token:js_token},function( txt ){
            if( txt != -1 ){
            var result = JSON.parse(txt);
            var newReplay = replaceReplay( result.newReplay );
            var oddReplay="";
            //处理以往的数据信息。
            for( var one in result.OddReplay ){
               oddReplay += replaceReplay( result.OddReplay[one] );
            }
              $( "#button"+appid ).attr( 'readonly',false ).val( "回复" );
              $( "#button"+id ).attr( 'showmore',false );
              $( '#first'+appid ).after( oddReplay );
              $( '#last'+appid ).hide();
              $( '#last'+appid ).after( newReplay );
              $( '#showMore'+appid ).hide();
              $( '#input'+appid ).val("");
              replayHide($( '#input'+appid ));
              $( '#RC'+appid ).slideDown( 'normal' );
              deleteMouse();
            }else{
              Alert( "心情发布失败" );
            }
          });

  }else{
    //只需要直接返回新的回复html格式就可以
    $.post( APP+"Index/doAddReplay",{toUid:touid,content:content,id:appid,more:showmore,mid:mid,page:page,js_token:js_token},function( txt ){
      if( txt != -1 ){
              var result = JSON.parse(txt);
              newReplay = replaceReplay( result );
              $( "#button"+appid ).attr( 'readonly',false ).val( "回复" );
              $( '#showMore'+appid ).hide();
              $( '#input'+appid ).val("");
              $( "#button"+id ).attr( 'showmore',false );
              replayHide($( '#input'+appid ));
              $( "#RC"+appid).append( newReplay );
              $( '#RC'+appid ).slideDown( "normal" );
              deleteMouse();
      }else{
            Alert( "心情添加失败" );
      }
    });

  }
  touid = 0;
}
function deleteMouse(){
              $( '.RLI' ).bind( "mouseover",function(){
                  var id = $( this ).attr( 'id' );
                 $( '#d-'+id ).show();
                });

             $( '.RLI' ).bind( "mouseout",function(){
                  var id = $( this ).attr( 'id' );
                  $( '#d-'+id ).hide();
                });
}
function deleteComment( id,appid,where,uid ){
  Confirm({message:'是否删除此评论?',handler:function(button){
      if ( 'ok' == button ){
      if( where ){
      //加载全部
      $.post( APP+"/Index/getReplay",{id:appid,uid:uid},function( text ){
        if( text != -1 ){
        var result = JSON.parse(text);
        var newReplay = replaceReplay( result.OddReplay );
        var oddReplay="";
        //处理以往的数据信息。
        for( var one in result.OddReplay ){
          oddReplay += replaceReplay( result.OddReplay[one] );
        }
        var temp = "<div id=\"first"+appid+"\" class=\"RLI\">"+$( '#first'+appid ).clone().html()+"</div>";
          $( '#first' ).remove();
          $( '#RC'+appid ).html(temp+oddReplay);
          deleteMouse();
          $( '#button'+appid ).attr( 'showmore',false );
        }else{
          Alert( "无法加载全部回复" );
        }
        });
      }
    $.post( APP+'Index/doDeleteReplay',{id:id,appid:appid},function( txt ){
      if( txt != -1 ){
        if( where == 'first' ){
            $( '#first'+appid ).remove();
        }else{
          $( '#RLI'+id ).remove();
        }
      }else{
        Alert( "删除失败" );
      }
      });
      }}})


  }

 //字数递减和限制字数
function fot(e){
//递减字数
 // var d = document.getElementById("mini-count");
  var c = e.value.length;
  var xxx = mini_zishu - c;
    if(c<=mini_zishu){
      //d.innerHTML =xxx+"/"+mini_zishu;
    }else{
      e.value = lastinput;
      $( '#mini-coment' ).focus();
      return false;
    }
  lastinput = e.value;
}
  /*
     function doReplay( appid ){
  var newInput = "<div class=\"RC\" id=\"RC\""+appid+" style=\"margin-top: 10px; margin-bottom: 5px;\" ></div><div class=\"RLI bg01\" id =\"RLI"+appid+"\"> <div class=\"user_img\" id=\"image"+appid+"\"><img src=\"http://www.qiniao.com/product/thinksns/Tpl/default/Public/images/userimg.jpg\" /></div><div class=\"RLC\"><div class=\"input_box\"><textarea id=\"input"+appid+"\" name=\"comment\" cols=\"\" rows=\"3\" style=\"height:50px; line-height:18px; width:99%\" class=\"cGray2\"></textarea><input id = \"button"+appid+"\" type=\"button\" class=\"btn2\" onclick=\"replayComment("+appid+",false)\" value=\"回 复\" /></div></div></div>";
    $( '#Fli'+appid+"> .LC").append( newInput );
    var obj = $( '#input'+appid );
    obj.bind('click', replayShow( obj ));
    obj.bind('blur', replayHide( obj ) );
}
*/
var openReplay = true;
function closeReplay( appid,uid ){
  //获取回复数
  if( openReplay ){
    $.post(APP+"/Index/getReplayCount/",{id:appid},function(count){
        if( count != -1 ){
          $( '#closeReplay'+appid ).text(count);
        }else{
          Alert( '意外的网路异常' );
        }
        });
  }else{
    $( '#closeReplay'+appid ).text( '收起回复' );
  }

  $( '#RC'+appid ).slideToggle( 'normal',function(){
      $( '#showMore'+appid ).hide();
      if ( "none" == $( this ).css('display') ){
          openReplay = false;
      }else{
            //加载全部
            $.post( APP+"/Index/getReplay",{id:appid,uid:uid},function( text ){
            if( text != -1 ){
                 var result = JSON.parse(text);
                  var newReplay = replaceReplay( result.OddReplay );
                  var oddReplay="";
                  //处理以往的数据信息。
                  for( var one in result.OddReplay ){
                     oddReplay += replaceReplay( result.OddReplay[one] );
                  }

              var temp = "<div id=\"first"+appid+"\" class=\"RLI btmline\">"+$( '#first'+appid ).clone().html()+"</div>";
              $( '#first'+appid ).remove();
              $( '#RC'+appid ).html(temp+oddReplay);
              deleteMouse();
              $( '#button'+appid ).attr( 'showmore',false );
            }else{
              Alert( "无法加载全部回复" );
            }
            })
            openReplay = true;
      }
      } );
  $( '#RLI'+appid ).slideToggle( 'normal' );
}
