<?php
header("Content-type: text/html;charset=utf-8");
//define("CORE_DIR","..");
//require(CORE_DIR."/API/LW_ORM/LW_ORM.php");
define("THINK_PATH","..");
require "TS_API.class.php";

session_start();
//$_SESSION["mid"] = 1;
?>

<link rel="stylesheet" type="text/css" href="table.css" />
<script>
function del_Notify(id) {
	if(confirm("确实要删除此项?")){
		location.href = "?do=d&id=" + id ;
	}
	
}
</script>
<center>
<h1>TS1_6 Notify 模板设置专用界面</h1>
</center>

<?php
$dao = TS_D("NotifyTemplate");

//新增
if($_GET["do"] == "a"){
	$data["type"]  = trim($_POST["type"]);
	$data["type_cn"]  = trim($_POST["type_cn"]);
	$data["deal"]  = trim($_POST["deal"]);
	$data["title"] = trim($_POST["title"]);
	$data["body"]  = trim($_POST["body"]);

	$map["type"] = $data["type"];
	$old = $dao->where($map)->find();

	if($old){
		$dao->where($map)->save($data);
	}else{
		$dao->add($data);
	}
}
//删除
if($_GET["do"] == "d"){
	$map["id"] = $_GET["id"];
	$r = $dao->where($map)->delete();
}

//修改页
if($_GET["do"] == "m"){
	$map["id"] = $_GET["id"];
	$Notify = $dao->where($map)->find();

	foreach($Notify as $k=>$v){
		$Notify["$k"] = forTag($v);
	}

$ttt = <<<EOD
<h3>修改 Notify 模板</h3>
<center>
<p>
&nbsp;
<form method="post" action="?do=dom">
	<input type="hidden" id="" name="id" size="60" value="$Notify[id]"/>
	Notify Type: <input type="text" id="" name="type" size="60" value="$Notify[type]"/><p>
	Notify Type_cn: <input type="text" id="" name="type_cn" size="60" value="$Notify[type_cn]"/><p>
	Notify Deal: <input type="text" id="" name="deal" size="60" value="$Notify[deal]"/><p>
	Notify Title: <input type="text" id="" name="title" size="60" value="$Notify[title]"/><p>
	<span style="float:left;padding-left:380px">Notify Body:  </span><br/>
	<textarea name="body" rows="10" cols="60">$Notify[body]</textarea><p>
	&nbsp;<p><input type="submit" id="" value="提交"/>
</form>
</center>
EOD;

echo $ttt;


	exit(0);
}

//修改
if($_GET["do"] == "dom"){
	$data["type"]  = trim($_POST["type"]);
	$data["type_cn"]  = trim($_POST["type_cn"]);
	$data["deal"]  = trim($_POST["deal"]);
	$data["title"] = trim($_POST["title"]);
	$data["body"]  = trim($_POST["body"]);

	$map["id"] = $_POST["id"];

	$dao->where($map)->save($data);
}
?>



<h3>1、已有Notify 模板</h3>
<center>
<table id="mytable" cellspacing="0"> 
<tr>
<th>id</th><th>type</th><th>type_cn</th><th>deal</th><th>title</th><th>body</th><th>操作</th>
</tr>
<?php

	$Notify_templates = $dao->order("id desc")->findAll();
	
	foreach($Notify_templates as $key=>$v){
		foreach($v as $kk=>$vv){
			if(!$vv) $v[$kk] = "NULL";
		}
		echo "<tr>";
		echo "<td>".$v['id']."</td><td>".$v['type']."</td><td>".$v['type_cn']."</td><td>".$v['deal']."</td><td>".$v['title']."</td><td>".$v['body']."</td><td><a href='?do=m&id=".$v["id"]."'>修改</a>&nbsp;&nbsp;<a href='javascript:del_Notify(".$v["id"].")'>删除</a></td>";
		echo "</tr>";
	}

?>
</table>
</center>
<hr>
<h3>2、已有Notify 模板举例</h3>
<?php

$NotifyDao = TS_D("Notify");
$Notifys = $NotifyDao->findAll();

foreach($Notifys as $fkey=>$Notify){

	$Notify_template = $dao->where(array("type"=>$Notify['type']))->find();


	/*-------------------------------------
	= title
	-------------------------------------*/
	$Notify_title = $Notify_template["title"]; //title的模板

	$title_data = unserialize(stripcslashes($Notify["title"]));  //title的数据
	$actor = "<a href='space.php?".$Notify["authorid"]."'>".$Notify["author"].'</a>';

	$title_data["actor"] = $actor;


	foreach($title_data as $k=>$v){   //替换
		$Notify_title = str_replace('{'.$k.'}',$v,$Notify_title);
	}

	echo $Notify_output[$fkey]["title"] = $Notify_title;

	/*-------------------------------------
	= body
	-------------------------------------*/

	$Notify_body = $Notify_template["body"]; //body的模板
	if($Notify_body){
		$body_data = unserialize(stripcslashes($Notify["body"]));  //body的数据
		foreach($body_data as $k=>$v){   //替换
			$Notify_body = str_replace('{'.$k.'}',$v,$Notify_body);
			$Notify_body = str_replace('"',"'",$Notify_body);
		}
		
		echo "<br/>";
		echo $Notify_output[$fkey]["body"] = $Notify_body;
	}
	echo "<p>";

}
	//dump($Notify_output);
?>

<hr>
<h3>3、新增 Notify模板</h3>


<center>
<p>
&nbsp;
<form method="post" action="?do=a">
	Notify Type: <input type="text" id="" name="type" size="60"/><p>
	Notify Type_cn: <input type="text" id="" name="type_cn" size="60"/><p>
	Notify Deal: <input type="text" id="" name="deal" size="60" value=""/><p>
	Notify Title: <input type="text" id="" name="title" size="60"/><p>
	<span style="float:left;padding-left:380px">Notify Body:  </span><br/>
	<textarea name="body" rows="10" cols="60"></textarea><p>
	&nbsp;<p><input type="submit" id="" value="提交"/><p>
</form>
</center>


<?php

function forTag($string)
{
	return str_replace(array('"',"'"), array('&quot;','&#039;'), $string);
}


?>
