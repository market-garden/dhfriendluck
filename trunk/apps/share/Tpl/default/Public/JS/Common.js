function getSelectValues() {
	  id = [];
	  $("input[type='checkbox']:checked").each(function(){
		  id.push($(this).val());
	  });
	  return id.join(',');
}

function del(id,URL){
	var keyValue;
	if (id!=0)
	{
		keyValue = id;
	}else {
		keyValue = getSelectValues();
	}
	if (!keyValue)
	{
		alert('请选择删除项！');
		return false;
	}

	if (window.confirm('确实要删除选择项吗？'))
	{	
		location.href = URL+"/delete/id/"+keyValue;		
	}
	return true;
}

function delType(id,URL){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectValues();
	}
	if (!keyValue)
	{
		alert('请选择删除项！');
		return false;
	}

	if (window.confirm('确实要删除选择项吗？'))
	{	
		location.href = URL+"/doDelType/id/"+keyValue;
	}
	return true;
}



//选反选
function checkall(thisabout,pointname){
	if(thisabout=='true'){
	    $("input[@id='"+pointname+"']").each(function() {     
	       $(this).attr("checked", true);    
	    });
	}else{
    $("input[@id='"+pointname+"']").each(function() {     
	        $(this).attr("checked", false);    
	    });
	}
}

function delSure(id,URL){
	var keyValue;
	if (id!=0)
	{
		keyValue = id;
	}else {
		keyValue = getSelectValues();
	}
	if (!keyValue)
	{
		alert('请选择清空项！');
		return false;
	}

	if (window.confirm('确实要清空选择项吗？'))
	{	
		location.href = URL+"/delete/id/"+keyValue+"/delSure/1";
	}
	return true;
}

function revert(id,URL){
	var keyValue;
	if (id!=0)
	{
		keyValue = id;
	}else {
		keyValue = getSelectValues();
	}
	if (!keyValue)
	{
		alert('请选择还原项！');
		return false;
	}

	if (window.confirm('确实要还原选择项吗？'))
	{	
		location.href = URL+"/revert/id/"+keyValue;
	}
	return true;
}

function set0(id,field){
	location.href = URL+"/set0/field/"+field+'/id/'+id;
}
function set1(id,field){
	location.href = URL+"/set1/field/"+field+'/id/'+id;
}