var lastFeedId = 0;
//删除动态
function del_feed(id,is_me){
	checkLogin();
    is_me = is_me?is_me:0;
	$.post(APP+"/Feed/del",{id:id,is_me:is_me},function(txt){
	    if(txt){
			$("#a_feed_"+id).hide("slow");
	    }else{
			Alert("删除失败!");
	    }
	});
}

  //mini_feed start
    function pre_comment(_this,aid) {
        $("#small_con_"+aid).hide();
        $("#post_con_"+aid).show();
        $("#mini_con_"+aid).focus();

    }

    //回复某人
    function huifu_other(aid,df_id,df_name) {
    	checkLogin();
        $("#small_con_"+aid).hide();
        $("#post_con_"+aid).show();
        var hf_tip = "回复"+df_name+":";
        $("#mini_con_"+aid).val(hf_tip);
        $("#mini_con_"+aid).focus();


        $("#hf_other_uid_"+aid).val(df_id);
        
    }

    function post_mini_con(aid){
    	checkLogin();
        var uid = $("#mid").val();
        var my_name = $("#my_name").text();
        var con = $("#mini_con_"+aid).val();
        $("#mini_con_"+aid).val('');
        var face = $("#my_face").html();

        var mini_con = $("#feed_body_"+aid).find(".mini_con").val();
        var mini_id = $("#feed_body_"+aid).find(".mini_id").val();
        var mini_uid = $("#feed_body_"+aid).find(".mini_uid").val();

        //回复评论的发起人的uid
        var comm_uid = $("#hf_other_uid_"+aid).val();

        $.post(APP+"/Feed/post_mini_comment",{appid:mini_id,comment:con,mini_con:mini_con,mini_uid:mini_uid,comm_uid:comm_uid},function(txt){
            if(txt){
                //增加一条
			  //var divClass="c";
               var item = '<div class="RLI bg01 btmline">\
                                <div class="user_img">'+face+'</div>\
                                <div class="RLC">\
                                    <h4><a href="__APP__/space/'+uid+'"><strong>'+my_name+'</strong></a><span class="cGray2">刚刚</span></h4>\
                                    <p>'+con+'</p>\
                                </div> <div class="c"></div>\
                            </div>';
                 $("#mini_comment_item_"+aid).append(item);

                //隐藏和显示输入
                $("#post_con_"+aid).hide();
                $("#small_con_"+aid).show();

                //同时展开
                var is_zk = $("#zhan_kai_"+aid).text();
                if(is_zk) getOtherComm(aid);

            }else{

            }
        });
    }

    function blur_mini_con(aid){
        var con = $.trim($("#mini_con_"+aid).val());
        if(!con) {
            $("#post_con_"+aid).hide();
            $("#small_con_"+aid).show();

        }
    }


    function getOtherComm(aid){
        var mini_id = $("#feed_body_"+aid).find(".mini_id").val();

		var zk_text = $("#zhan_kai_"+aid).text();

		if(!zk_text) return;

        var loading = '<center><img src="'+PUBLIC+'/images/loading_blue_big.gif"></center>';
        $("#feed_comm_middle_"+aid).html(loading);
		$.post(APP+"/Feed/get_other_comment",{aid:mini_id},function(comm){
		    if(comm){
        var divClass="c";
                for(var i=0;i<comm.length;i++){
                   var item = '<div class="RLI bg01 btmline">\
                                    <div class="user_img"><img src="'+comm[i].face+'" /></div>\
                                    <div class="RLC">\
                                        <h4><a href='+APP+'/space/'+comm[i].uid+'><strong>'+comm[i].name+'</strong></a><span class="cGray2">'+comm[i].cTime+'</span></h4>\
                                        <p>'+comm[i].comment+'<a href="javascript:huifu_other('+aid+','+comm[i].uid+',\''+comm[i].name+'\')">回复</a></p>\
                                    </div><div class='+divClass+'></div>\
                                </div>';
                   (i == 0)?$("#feed_comm_middle_"+aid).html(item):$("#feed_comm_middle_"+aid).append(item);
                }
		    }else{

		    }
		},"json");

    }


    function hide_reply(_this){
        var aid = $(_this).parent().parent().attr("alt");
		var num = 0;
		var con = "收起回复";
		var id = $('#feed_body_'+aid).children('.bg_huifu').find(".mini_id").val();
        if($('#feed_body_'+aid).is(":visible")){
            $.post( APP+'/Feed/post_mini_count',{id:id},function( result ){
				if(result != -1){
				num = result;
            	//var num = $("#mini_comm_num_"+aid).val()?$("#mini_comm_num_"+aid).val():0;
              	con = num;
			  	$(_this).text(con);
				}else{
					var con = "收起回复";
					getOtherComm(aid);
					$(_this).text(con);
				}
            })
        }else{
            var con = "收起回复";
			getOtherComm(aid);
			$(_this).text(con);
        }
    
        $('#feed_body_'+aid).slideToggle( 'fast' );
    }
    function getNewFeed(){
        var userId = $( '#uid' ).val();
	$("#feed_content").load(APP+"/Home/newFeed",{type:type,who:who,lastId:lastFeedId},function(txt){
            dump(txt);
	});
    }
    //mini_feed_end
