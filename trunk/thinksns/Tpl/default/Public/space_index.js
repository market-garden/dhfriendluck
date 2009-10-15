

function handlerIframe(){
   // alert(ymPrompt.getPage().contentWindow.document.body.innerHTML);
    ymPrompt.close();
}



function go_wall() {
    window.scrollTo($("#wall"),1200);
    $("#wall_con").focus();
}

//留言
function wall() {
	checkLogin();
    var con = $.trim($("#wall_con").val());
    if(!con) {Alert("留言不能为空哦~~");return;}
    if(JHshStrLen(con)>2000) {Alert("留言不能超过2000字哦~~");return;}
    con = escapeHTML(con);
    
    var privacy = $("#wall_privacy").is(":checked")?1:0;
    var uid = $("#space_uid").val();
    $("#sub_button").val("LOADING...");
   $("#sub_button").attr("disabled",true);

    
    $.post(URL+"/doWall",{uid:uid,content:con,privacy:privacy},function(wid){
        if(wid){
            $("#sub_button").val("留 言");
            $("#wall_con").val("");
            //window.location.reload();
            var qiao      =     (privacy == 1)?'<input type="checkbox" name="privacy" id="wall_privacy_{$wall.id}" value="1" checked="true" disabled="true"/>悄悄话':"";
            var qiao_font =     (privacy == 1)?'<font color="red">【悄悄话】</font>':"";
            var my_face   =     $("#my_face2").html();
            var my_name   =     $("#my_name2").val();

            var new_con = ' <div class="Gli" id="wall_item_'+wid+'">\
                            <div class="user_img"><span class="headpic50"><a href="'+APP+'/space/'+MID+'">'+my_face+'</span></div>\
                            <div class="LC"><div class="MC">\
                                    <h4 class="tit_Critique lh25 mb5 pl5"><span class="right"><a href="javascript:wall_reply_dis('+wid+')">回复</a>\
                                  <a href="javascript:wall_del('+wid+')">删除</a>\
                                  </span><a href="'+APP+'/space/'+MID+'"><strong>'+my_name+'</strong></a><span class="cGray2">刚刚</span><span>'+qiao_font+'</span></h4>\
                                    <p class="WB">\
                                        '+con+'\
                                        </p>\
                                <a id="d-{$vo.id}"href="###" onclick="deleteMini('+wid+')" class="del" title="删除" style="display:none;">删除</a></div>\
                                <div class="RC">\
                                    <span id="wall_reply_list_'+wid+'"></span>\
                                    <div class="RLI bg01" style="display:none" id="wall_reply_'+wid+'">\
                                        <div class="user_img"><span class="pic38">'+my_face+'</span></div>\
                                        <div class="RLC">\
                                            <div class="input_box">\
                                                <textarea name="textarea" cols="" style="height:50px; line-height:18px; width:99%" id="wall_reply_con_'+wid+'"></textarea>\
                                                 '+qiao+'\
                                                <input type="button" class="btn_b" value="回 复" onclick="wall_reply('+wid+')"/><input type="button" class="btn_b" value="取 消" onclick="wall_reply_cancel('+wid+')"/>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div></div></div>';
            $("#list_wall").prepend(new_con);

        }else{

        }
    });
    $("#sub_button").removeAttr("disabled");
}


//留言回复显示
function wall_reply_dis(id) {
    $("#wall_reply_"+id).show();
    $("#wall_reply_con_"+id).focus();
}

//留言回复隐藏
function wall_reply_cancel(id) {
    $("#wall_reply_"+id).hide();
}

//留言回复
function wall_reply(id) {
	checkLogin();
    var con = $.trim($("#wall_reply_con_"+id).val());
    if(!con) {Alert("留言不能为空哦~~");return;}
    if(JHshStrLen(con)>2000) {Alert("留言不能超过2000字哦~~");return;}
    con = escapeHTML(con);

    var privacy = $("#wall_privacy_"+id).is(":checked")?1:0;
    var uid = $("#space_uid").val();
    $("#reply_button").val("LOADING...");
    $.post(URL+"/doWall",{uid:uid,content:con,privacy:privacy,replyWallId:id},function(txt){
        if(txt){
            $("#reply_button").val("回 复");
            $("#wall_reply_"+id).hide();
            $("#wall_reply_con_"+id).val("");
            var my_face   =     $("#my_face2").html();
            var my_name   =     $("#my_name2").val();
            var new_con = ' <div class="RLI">\
                                <div class="user_img"><span class="pic38"><a href="'+APP+'/space/'+uid+'">'+my_face+'</a></span></div>\
                                <div class="RLC">\
                                    <h4 class="tit_Critique lh20 mb5 pl5"><a href="'+APP+'/space/'+uid+'"><strong>'+my_name+'</strong></a><span class="cGray2">刚刚</span></h4>\
                                    <p>'+con+'</p>\
                                </div>\
                            </div>';

             $("#wall_reply_list_"+id).append(new_con);

        }else{

         }
    });
}


//删除留言
function wall_del(id) {
	checkLogin();
    Confirm({
        message:'确定删除这条留言及它的回复吗？',
        handler:function(tp){
            if(tp=='ok'){
                $.post(APP+"/Space/delWall",{ id:id },function(txt){
                    if(txt){
                        $("#wall_item_"+id).hide("slow");
                    }else{
                        Alert("删除失败!");
                    }
                });
            }
            if(tp=='cancel'){
                ymPrompt.close();
            }
            if(tp=='close'){
                ymPrompt.close();
            }
        }
        });
}














