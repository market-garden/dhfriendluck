// @filename   chat.js
// @version    0.2
// @author     shawphy(shawphy@gmail.com),fantasy(foxlw66@gmail.com)
// @update     2009-9-7

eval(function(E,I,A,D,J,K,L,H){function C(A){return A<62?String.fromCharCode(A+=A<26?65:A<52?71:-4):A<63?'_':A<64?'$':C(A>>6)+C(A&63)}while(A>0)K[C(D--)]=I[--A];function N(A){return K[A]==L[A]?A:K[A]}if(''.replace(/^/,String)){var M=E.match(J),B=M[0],F=E.split(J),G=0;if(E.indexOf(F[0]))F=[''].concat(F);do{H[A++]=F[G++];H[A++]=N(B)}while(B=M[G]);H[A++]=F[G]||'';return H.join('')}return E.replace(J,N)}('c Cz=BF+"/y.BA?P=/BC/getFriends",Cy=BF+"/y.BA?P=/BC/CE",Cm=BF+"/y.BA?P=/BC/receiveMsg",C2=BF+"/y.BA?P=/BC/delRecord",_$7=BF+"/y.BA?P=/BC/receiveRecord",_$9=BF+"/y.BA?P=/BC/sendRecord",C4=B9+"/CI/Bp/minimize.BP",Cx=B9+"/CI/Bp/maximize.BP",C3=B9+"/CI/Bp/CP",Ct=25000,_$2=BF+"/y.BA?P=/BC/mofa",C5=5000;(6(U){c E=Q,A=6(V){U("#errorInfoContent").r(V);U("#Bl").h("k",U(window).B0()/S-U("#Bl").B0()/S).CY([]).Bh();clearTimeout(E);E=setTimeout(6(){U("#Bl").BR()},3000)};U("#Bl>B7").t(6(){U(i).3().BR()});c H=U("#imbox");F();H.o("h1").o("j").t(6(){W(H.Bs("CA"))F("CR");Bd{H.8("CA");U(i).o("BE").m("BD",Cx);U("#BN").html("");U("#Cf").p()}});BZ("updateFriList2()",Ct);6 F(V){U.Bn(Cz,{},6(A){U("#BN").Cp();c B=Q;Cl(c G=Q,F=A.Cu;G<F;G++){B++;c D=A[G].head,E=A[G].Cw?"("+A[G].Cw+")":"";U("#BN").1(\'<Z><M Bc="#" X="\'+A[G].uid+\'"><BE B0="25" BD="\'+D+\'" /><j u="B2">\'+A[G].uname+"</j><j>"+E+"</j></M></Z>")}U("#online_num").r("\\u5728\\u7ebf\\CQ\\Ca("+B+")");C();W(V!="get_fri"){W(V=="CR"){H._("CA");U("#right_top").m("BD",C4);U("#Cf").n()}Bd{U(C6.C7).t();C6.C7="#"}K()}},"Ce")}U("#9>Z>j.BH").q(6(V){U(i).t(6(A){A.Bo();U(i).3().8("Y")._("BO").B3()._("Y");U("#$>e>e").n().CX(V).p()})});U("#closeChat").t(6(){W(U("#9>Z:5")[R]){c V=U("#9>Z.Y").n();V[V.CL(":5")[Q]?"CL":"prevAll"](":5").CX(Q).t()}2 BB});c C=6(){U("#BN>Z>M").t(6(C){C.Bo();c D=U("#9>Z.Y");U("#0"+i.X+":hidden").DA("#9").p();W(U("#0"+i.X+">j.BH").t()[Q])2 BB;c B=U(i).o(".B2").r(),V=U("#9>Z.Y").m("X");U("#9>Z")._("Y");U("#9").1(\'<Z u="Y" X="0\'+i.X+\'" ><j u="BH">\'+B+\'</j><M u="Bu" Bc="###" B5="B$(\'+i.X+\')"></M></Z>\');W(U("#9")[Q].Bk>Ci){U("#0"+i.X).Cs();U("#"+V).8("Y");A("\\Ck\\CG\\DD\\BG\\Bt\\Cn\\z\\BM\\BV\\Co\\CV\\Ch\\Cr\\BG\\Bb\\Cc\\C1\\DE\\CC\\By\\CF\\BG\\Bq\\Bq\\Cb");2}U("#$>e>e").n();U("#$>e").1(\'<e X="BI\'+i.X+\'"></e>\');U("#9>Z>j.BH").C8("t").q(6(V){U(i).t(6(A){A.Bo();c V=U(i).3().m("X").x(/\\N+/)[Q];U(i).3().8("Y")._("BO").B3()._("Y");U("#$>e>e").n();U("#BI"+V).p()})})})};U("#setFont").C_(6(V){U(i).8("v");U("#l").h({k:U(V.DC).Cg().k,b:U(V.DC).Cg().b-38}).Bh();2 BB},6(V){U(i)._("v");U("#l").BR();2 BB});U("#l").t(6(V){2 BB});U("#l CS,#l CT,#l CU").v(6(){U(i).8("v")},6(){U(i)._("v")}).C_(6(){U(i).8("BU")},6(){U(i)._("BU")});U("#fontFamily").CZ(6(){U("#f").h("l-family",U(i).g())});U("#fontSize").CZ(6(){U("#f").h("l-size",U(i).g()+"pt")});U("#l CS").t(6(){U("#f").h("l-weight",U(i).Bs("BU")?"bold":"")});U("#l CT").t(6(){U("#f").h("l-BS",U(i).Bs("BU")?"italic":"")});U("#l CU").t(6(){U("#f").h("r-decoration",U(i).Bs("BU")?"underline":"")});c J=U("#BY").g();U("#BY").t(6(){U("#Cj").3().Bh()});U("#Cj").mousemove(6(V){c C={k:V.Ba.DH||V.Ba.layerX||Q,b:V.Ba.DI||V.Ba.layerY||Q},B=[Q,Q,Q],A=189/Be;switch(C0){s C.k<(A*R):B[Q]=7(C.b<T?d:d*(C.b-a)/-T);B[R]=7(7(C.k/S)*S/A*BL);B[R]=7(C.b<T?(d*(T-C.b)/T+B[R]*C.b/T):(B[R]*(a-C.b)/T));B[S]=7(C.b<T?d*(T-C.b)/T:Q);BK;s C.k<(A*S):B[Q]=7((7(C.k/S)*S-A*S)/A*-BL);B[Q]=7(C.b<T?(d*(T-C.b)/T+B[Q]*C.b/T):(B[Q]*(a-C.b)/T));B[R]=7(C.b<T?d:d*(C.b-a)/-T);B[S]=7(C.b<T?d*(T-C.b)/T:Q);BK;s C.k<(A*DJ):B[Q]=7(C.b<T?d*(T-C.b)/T:Q);B[R]=7(C.b<T?d:d*(C.b-a)/-T);B[S]=7((7(C.k/S)*S-A*S)/A*BL);B[S]=7(C.b<T?(d*(T-C.b)/T+B[S]*C.b/T):(B[S]*(a-C.b)/T));BK;s C.k<(A*Bv):B[Q]=7(C.b<T?d*(T-C.b)/T:Q);B[R]=7((7(C.k/S)*S-A*Bv)/A*-BL);B[R]=7(C.b<T?(d*(T-C.b)/T+B[R]*C.b/T):(B[R]*(a-C.b)/T));B[S]=7(C.b<T?d:d*(C.b-a)/-T);BK;s C.k<(A*DK):B[Q]=7((7(C.k/S)*S-A*Bv)/A*BL);B[Q]=7(C.b<T?(d*(T-C.b)/T+B[Q]*C.b/T):(B[Q]*(a-C.b)/T));B[R]=7(C.b<T?d*(T-C.b)/T:Q);B[S]=7(C.b<T?d:d*(C.b-a)/-T);BK;s C.k<(A*Be):B[Q]=7(C.b<T?d:d*(C.b-a)/-T);B[R]=7(C.b<T?d*(T-C.b)/T:Q);B[S]=7((7(C.k/S)*S/-A*Be)/A*-BL);B[S]=7(C.b<T?(d*(T-C.b)/T+B[S]*C.b/T):(B[S]*(a-C.b)/T));BK;s C.b<T:B=[d,d,d]}U("#BY").g("CM("+B+")");U("#f").h("B4","CM("+B+")")}).mousedown(6(){J=U("#BY").g();U(i).3().n()}).mouseout(6(){U("#BY").g(J);U("#f").h("B4",J)});U("#CO").v(6(){U("#Cd").p().v(6(){U(i).p()},6(){U(i).n()})},6(){U("#Cd").n()});U("#CO").Bi().v(6(){U(i).8("v")},6(){U(i)._()});U("#sendFace").v(6(){U("#BX").p()},6(){U("#BX").n()});U("#BX").t(6(V){V.stopPropagation()});U("#BX>BE").t(6(A){c V="["+U(i).m("alt")+"]",B=U("#f").g();U("#f").g(B+V)});U("#BX").Bi().v(6(){U(i).8("v")},6(){U(i)._()});U("#sendImg").t(6(V){U("#CD").h("k",V.pageX+30).CY([]).Bh()});U("#CD>B7.cancel").t(6(){U("#CB").g("");U(i).3().BR()});U("#CD>B7.ok").t(6(){c D=U("#CB").g();U("#CB").g("");W(!/^(ht)|(DF)tp:\\/\\/.*V/.B6(D))D="BW://"+D;c V=D.x(/\\Bx+\\.(jpg|BP|png|bmp)V/);W(!V){A("\\Bt\\u8f93\\u5165\\z\\u56fe\\u7247url\\CG\\u6b63\\u786e\\CJ");2}U(i).3().BR();c B="["+D+"]",C=U("#f").g();U("#f").g(C+B)});U("#CE").t(6(){try{c H=U("#9").o(".Y").m("X").x(/\\N+/)[Q]}catch(B){}W(!H){A("\\Bb\\u9009\\u62e9\\u4e00\\CC\\CQ\\Ca:)");2}c K=U("#f").g();W(K.4(/(^\\P*)|(\\P*V)/O,"")=="")2;c J=U("#f").m("BS");K=D(K);BJ=K.4(/\\[(\\Bx*)\\]/O,\'<BE BD="\'+C3+\'/Bj.BP" />\');BJ=BJ.4(/\\[BW:\\/\\/(.*?)\\]/O,\'<BE BD="BW://Bj"/>\');U("#f").g("");c E=new Date(),Bw=(E.B1()<10)?"Q"+E.B1():E.B1(),I=E.getHours()+":"+Bw,C="<e BS=\'"+J+"\'>"+BJ+"</e>",F=/\\[\u9b54\u6cd5\u8868\u60c5\\N+\\]/CN,V=F.B6(BJ),G=V?BJ.x(/\\N+/)[Q]:Q;W(G)BT(G);U("#$>e>e:5").1("<j u=\'me\'>\\u6211 "+I+"\\BQ</j>"+C).q(6(){i.C$=i.Bk});U.Bn(Cy,{w:H,f:C,msg_time:I},6(V){W(!V)U("#$>e>e:5").1("<Bz/><j BS=\'B4:red;\'>\\u521a\\u624d\\z["+C+"]\\u53d1\\u9001\\u5931\\u8d25\\BG\\Bb\\u7a0d\\u540e\\By\\CF\\CJ</j><Bz/>")})});U(Bg).keydown(6(V){W(V.ctrlKey&&V.which==13)U("#CE").t()});BZ(K,C5);6 K(){c V="";U("#BN").Bi().Bi().q(6(A){V+="X["+A+"]="+U(i).m("X")+"&"});U.Bn(Cm,V,6(E){W(E){c D=BB;Cl(c J=Q;J<E.Cu;J++){c C=E[J].f.4(/\\[(\\Bx*)\\]/O,\'<BE BD="../Public/Bp/CP/Bj.BP" />\');C=C.4(/\\[BW:\\/\\/(.*)\\]/O,\'<BE BD="BW://Bj"/>\');c G=/\\[\u9b54\u6cd5\u8868\u60c5\\N+\\]/CN,V=G.B6(C),H=V?C.x(/\\N+/)[Q]:Q;w=E[J].X;W(U("#9").o(".Y")[Q]==Cv){B(w,C,E[J].Bm,R,H);W(!L)L=BZ(I,B8);CK}c A=U("#9").o(".Y").m("X").x(/\\N+/)[Q];W(w==A){c F=U("#9").o(".Y").r();W(H)BT(H);U("#$>e>e:5").1("<j u=\'Br\'>"+F+" "+E[J].Bm+"\\BQ</j>"+C);CK}c K=BB;U("#9>Z:5:not(.Y)").q(6(B){c A=U(i).m("X").x(/\\N+/)[Q];W(w==A){c V=U("#0"+w).8("BO").r();W(H)BT(H);U("#BI"+w).1("<j u=\'Br\'>"+V+" "+E[J].Bm+"\\BQ</j>"+C);W(!L)L=BZ(I,B8);K=C0}});W(K)CK;B(w,C,E[J].Bm,Cv,H);U("#0"+w).8("BO");W(!L)L=BZ(I,B8)}}U("#$>e>e").q(6(){i.C$=i.Bk})},"Ce")}6 B(E,B,F,V,D){c C=U("#"+E).o(".B2").r();W(U("#0"+E)[Q]){U("#0"+E).DA("#9").p();W(D)BT(D);U("#BI"+E).1("<j u=\'Br\'>"+C+" "+F+"\\BQ</j>"+B)}Bd{W(!V)U("#9").1(\'<Z  X="0\'+E+\'" ><j u="BH">\'+C+\'</j><M u="Bu" Bc="###" B5="B$(\'+E+\')"></M></Z>\');Bd U("#9").1(\'<Z  u="Y" X="0\'+E+\'" ><j u="BH">\'+C+\'</j><M u="Bu" Bc="###" B5="B$(\'+E+\')"></M></Z>\');W(U("#9")[Q].Bk>Ci){U("#0"+E).Cs();A("\\Ck\\CG\\DD\\BG\\Bt\\Cn\\z\\BM\\BV\\Co\\CV\\Ch\\Cr\\BG\\Bb\\Cc\\C1\\DE\\CC\\By\\CF\\BG\\Bq\\Bq\\Cb");2}W(D)BT(D);U("#$>e").1(\'<e X="BI\'+E+\'" BS="display:none"><j u="Br">\'+C+" "+F+"\\BQ</j>"+B+"</e>");U("#9>Z>j.BH").C8("t").q(6(V){U(i).t(6(A){A.Bo();c V=U(i).3().m("X").x(/\\N+/)[Q];U(i).3().8("Y")._("BO").B3()._("Y");U("#$>e>e").n();U("#BI"+V).p()})})}}6 Bw(){c V=U("#9").o(".Y").m("X").x(/\\N+/)[Q];U.Bn(C2,{w:V},6(V){W(V){U("#$>e>e:5").Cp();A("\\CH\\u4eec\\u4e4b\\u95f4\\z\\BM\\BV\\u8bb0\\u5f55\\u5df2\\u6e05\\u9664\\CJ")}})}6 D(V){2 V.4(/&/O,"&amp;").4(/</O,"&lt;").4(/>/O,"&gt;").4(/ /O,"&nbsp;").4(/\\"/O,"&quot;").4(/\\\'/O,"&apos;").4(/\\DG/O,"<Bz />")}c V=Q,Bf=Bg.B_,L;6 I(){Bg.B_=V%S?"\\u25cb\\CH\\Cq\\CW\\z\\BM\\BV\\DB\\C9 - "+Bf:"\\u25cf\\CH\\Cq\\CW\\z\\BM\\BV\\DB\\C9 - "+Bf;V++;(V>Be)?G():""}6 G(){W(L){clearInterval(L);L=Q;V=Q;Bg.B_=Bf}}})(jQuery)','M|a|d|g|s|0|1|2|7|_|$|if|id|a1|li|15|top|var|255|div|msg|val|css|this|span|left|font|attr|hide|find|show|each|text|case|click|class|hover|df_id|match|index|u7684|title_|append|return|parent|replace|visible|function|parseInt|addClass|chatfriend|removeClass|chatcontent|php|false|Chat|src|img|site_url|uff0c|switch_user|con_|dis_msg|break|256|u804a|friend_list|newmsg|gif|uff1a|fadeOut|style|play_swf|on|u5929|http|facepanel|colorHex|setInterval|originalEvent|u8bf7|href|else|6|N|document|fadeIn|children|$1|scrollHeight|errorInfo|dis_time|post|preventDefault|images|u8c22|friend|hasClass|u60a8|im_ico_close|4|L|w|u518d|br|width|getMinutes|name|siblings|color|onclick|test|button|1000|theme_url|title|close_chat_tab|min|imgurlInput|u4e2a|imgurlInputWrap|sendMsg|u8bd5|u4e0d|u4f60|im|uff01|continue|nextAll|rgb|gi|mofa_bq|face|u597d|max|b|i|u|u53e3|u65b0|eq|queue|change|u53cb|u3002|u5173|mofa_lists|json|imboxAd|offset|u592a|45|colorpicker|u5bf9|for|_$_|u5f00|u89c6|empty|u6709|u591a|remove|_$$|length|null|mini|_$8|_$5|_$6|true|u95ed|_$4|_$3|_$1|_$0|location|hash|unbind|u606f|toggle|scrollTop|appendTo|u6d88|target|u8d77|u51e0|f|n|x|y|3|5'.split('|'),192,202,/[\w\$]+/g,{},{},[]))

function close_chat_tab(id){

	$("#chatfriend>li").removeClass("a1");
	$(".msgwrap>div").hide();


	if($("#title_"+id).next()[0]){
		$("#title_"+id).next().addClass("a1");
		$("#con_"+id).next().show();
	}else{
		$("#title_"+id).prev().addClass("a1");
		$("#con_"+id).prev().show();
	}
	

	//标题
	$("#title_"+id).remove();
	//聊天内容清空
	//$("#chatcontent>div>div:visible").html("");
	//$("#chatcontent>div>div:visible").remove();
	$("#con_"+id).remove();




}

//更新好友列表
var openChatBind=function() {
	$("#friend_list>li>a").click(function(evt){
		//alert(this.id);
	  //  $(this).unbind("click");
		evt.preventDefault();
		var a1=$("#chatfriend>li.a1");
		//阻止同一个聊天口多开
		$("#title_"+this.id+":hidden").appendTo("#chatfriend").show();
		if ($("#title_"+this.id+">span.switch_user").click()[0]) return false;

		//alert(this.id+this.innerText+"被点击了");
		var name = $(this).find('.name').text();
		var remove_a1_id =  $("#chatfriend>li.a1").attr("id");
		$("#chatfriend>li").removeClass('a1');
		
		$("#chatfriend").append('<li class="a1" id="title_'+this.id+'" ><span class="switch_user">'+name+'</span><a class="im_ico_close" href="###" onclick="close_chat_tab('+this.id+')"></a></li>');
		//防止打开的窗口过多
		if ($("#chatfriend")[0].scrollHeight>45) {
			$("#title_"+this.id).remove();
			
			$("#"+remove_a1_id).addClass("a1");
			alert("对不起，您开的聊天视口太多，请关闭几个再试，谢谢。");
			return;
		};
		//内容显示其他的div hide，新建的这个显示
		$("#chatcontent>div>div").hide();
		$("#chatcontent>div").append('<div id="con_'+this.id+'"></div>');
		//重新绑定一般tab切换
		$("#chatfriend>li>span.switch_user").unbind("click").each(function(i){
			$(this).click(function(e){
                    e.preventDefault();
					var the_id = $(this).parent().attr("id").match(/\d+/)[0];
                    $(this).parent().addClass("a1").removeClass("newmsg").siblings().removeClass("a1");
                    //$("#chatcontent>div>div").hide().eq(i).show();
					$("#chatcontent>div>div").hide();
					$("#con_"+the_id).show();
			});
		});
	});
}

function updateFriList2() {
	//var open_df_id = $("#open_df_id").val();
	var GET_FRIS_URL_ONLINE = site_url+"/index.php?s=/Chat/getFriends_online";
	$.post(GET_FRIS_URL_ONLINE,{},function(json){
		
		$("#friend_list").empty();
		 var online_num = 0;
		//构造在线好友列表
		for(var i=0,h=json.length;i<h;i++){

				//var dis_none = '';
				//if(json[i]["uid"] == open_df_id) dis_none = "style='display:none'";

				 online_num++;

				 var head = json[i]["head"];
				 var mini = json[i]["mini"]?'('+json[i]["mini"]+')':'';
				 $("#friend_list").append('<li><a href="#" id="'+json[i]["uid"]+'"><img width="25" src="'+head+'" /><span class="name">'+json[i]["uname"]+'</span><span>'+mini+'</span></a></li>');
			
		 }
		//在线好友数
		 $("#online_num").text('在线好友('+online_num+')');

		 //重复绑定事件
		openChatBind();

	 },"json");
}
//end

function play_swf(num){

		var is_playing = $.trim($("#play_swf").html());
		if(is_playing) return;
		
		var sound = '<embed  src="' + theme_url + '/im/flash/mf_'+num+'.swf" scale="ShowAll" NAME="WMode" VALUE="Opaque" menu="menu" wmode="transparent" quality="1" type="application/x-shockwave-flash"></embed>';
		$("#play_swf").html(sound);
		setTimeout(function(){$("#play_swf").html('');},5000);

}

function send_mofa(num){
	var mofa = "[魔法表情"+num+"]";
	var new_msg = $("#msg").val() + mofa;
	$("#msg").val(new_msg);
	//$("#sendMsg").click();
	//play_swf(num);
}

function send_record(){
	try{
		var visible_id = $("#chatfriend").find(".a1").attr("id").match(/\d+/)[0];
	}catch(e){}
	var record = $("#con_"+visible_id).html();

	if(!visible_id) return;
	if(!$.trim(record)) {alert("暂无记录发送!");return;}

	$.post(SEND_RECORD_URL,{record:record},function(txt){
	    if(txt){
			alert("发送成功!");
	    }else{
			alert("发送失败!");
	    }	
	});

}