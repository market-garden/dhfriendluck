//加入群组
function joingroup(id) {
	ymPrompt.win(APP+'/Group/joingroup/gid/'+id,350,200,'加入群组',null,null,null,{id:'a'})
}

//删除群组
function delgroup(gid) {

	ymPrompt.win(APP+'/Group/delgroup_dialog/gid/'+gid,300,200,'删除群组',null,null,null,{id:'a'})
}

function quitgroup(gid) {
	ymPrompt.win(APP+'/Group/quitgroup_dialog/gid/'+gid,300,200,'退出群组',null,null,null,{id:'a'})
}



function getstrlen(chars){

		chars = chars.replace(/^\s+|\s+$/g,"");
		return chars.replace(/[^\x00-\xff]/g,"xx").length;
}



function checkContent(content){
	content = content.replace(/&nbsp;/g, "");
	content = content.replace(/<br>/g,"");
	content = content.replace(/<P>/g,"");
	content = content.replace(/<\/P>/g,"");
	return getstrlen(content);
}
