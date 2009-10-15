function EventAction( id,allow,action ){
  $.post( APP+'/Index/doAction/',{id:id,allow:allow,action:action},function( text ){
      if( text == 1 ){
        if( allow == 1 ){
          Alert( '您的申请已经成功提交，该活动需要发起人审核，请耐心等待...' );
        }else{
          Alert( '操作成功' );
        }
        location.reload();
      }else if( text == -3 ){
        Alert( '未知错误' );
      }else if( text == -2 ){
        if( allow == 1 ){
          Alert( '已经提交申请，请不要重复申请' );
        }else{
          Alert( '操作已经执行，请不要重复操作' );
        }
      }else if( text == -1 ){
        Alert( '这个活动已不存在，即将刷新本页面' );
        location.reload();
      }else if( text == 0 ){
        Alert( '操作失败,请稍后再试' );
      }else{
        Alert( '未知错误' );
      }
  });
}

function EventDelAction( id,allow,action ){
  $.post( APP+'/Index/doDelAction/',{id:id,allow:allow,action:action},function( text ){
      if( text == 1 ){
        if( allow == 1 ){
          Alert( '撤销申请成功' );
        }else{
          Alert( '操作成功' );
        }
        location.reload();
      }else if( text == -3 ){
        Alert( '未知错误' );
      }else if( text == -2 ){
        Alert( '您没有对本活动进行过操作' );
        location.reload();
      }else if( text == -1 ){
        Alert( '这个活动已不存在，即将刷新本页面' );
        location.reload();
      }else if( text == 0 ){
        Alert( '操作失败,请稍后再试' );
      }else{
        Alert( '未知错误' );
      }
  });
}

function agree( id,eventId,uid ){
  $.post( APP+'/Index/doAgreeAction/',{id:id,eventId:eventId,uid:uid},function( text ){
      if( text == 1 ){
        Alert( '操作成功' );
        location.reload();
      }else if( text == -3 ){
        Alert( '未知错误' );
      }else if( text == -2 ){
        Alert( '您没有对本活动进行过操作' );
        location.reload();
      }else if( text == -1 ){
        Alert( '这个活动已不存在，即将刷新本页面' );
        location.reload();
      }else if( text == 0 ){
        Alert( '操作失败,请稍后再试' );
      }else{
        Alert( '未知错误' );
      }
  });
}

function adminDelAction( id,uid,action,opts ){
  $.post( APP+'/Index/doAdminAction/',{eventId:id,uid:uid,action:action,admin:'user',opts:opts},function( text ){
      if( text == 1 ){
        Alert( '操作成功' );
        location.reload();
      }else if( text == -3 ){
        Alert( '未知错误' );
      }else if( text == -2 ){
        Alert( '您没有对本活动进行过操作' );
        location.reload();
      }else if( text == -1 ){
        Alert( '这个活动已不存在，即将刷新本页面' );
        location.reload();
      }else if( text == 0 ){
        Alert( '操作失败,请稍后再试' );
      }else{
        Alert( '未知错误' );
      }
  });

}

function endEvent( id ){
 Confirm({message:'是否提前结束此活动?',handler:function(button){
      if ( 'ok' == button ){
        $.post( APP+'/Index/doEndAction/',{id:id},function( text ){
            if( text == 1 ){
              Alert( '提前结束活动成功' );
              location.reload();
            }else if( text == -1 ){
              Alert( '非法访问' );
            }else if( text == 0 ){
              Alert( '结束活动失败。请稍后再试' );
            }else{
              Alert( '未知错误' );
            }
        });
      }
      }});

}

function getMessage(){
      ymPrompt.win({message:APP+'/Index/editDate/',width:300,height:150,title:'修改结束时间',maxBtn:false,minBtn:false,iframe:true});

}


function check(){
  var title      = $( '#title' ).val();
  var type       = $( '#type' ).val();
  var limitCount = $( '#limitCount' ).val();
  var explain    = $( '#explain' ).val();
  var stime      = $( '#sTime' ).val();
  var etime      = $( '#eTime' ).val();
  var deadline      = $( '#deadline' ).val();

  if( title.length<4 ) {Alert( '标题必须大于4个字符' ); return false;}
  if( type == 0 ) {Alert( '请选择类型' );return false;}
  if( type == 0 ) {Alert( '请选择类型' );return false;}
  //if ( limitCount.test( '/^d+$/' ) ){Alert( '人数只允许数字类型' ) return false}
  if( explain.length<10 ){Alert( '描述不得小于10个字符' );return false;}
  if( !stime ) {Alert( '请填写开始时间' );return false;}
  if( !etime ) {Alert( '请填写结束时间' );return false;}
  if( !deadline ) {Alert( '请填写截至报名时间' );return false;}
  return true;
}

function commentSuccess(json){
	//计数
	$.post(APP+'/Index/commentSuccess',{data:json},function(result){
            
        });
}
