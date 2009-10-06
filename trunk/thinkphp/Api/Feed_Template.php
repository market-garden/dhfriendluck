<?php
header("Content-type: text/html;charset=utf-8");
//define("CORE_DIR","..");
//require(CORE_DIR."/API/LW_ORM/LW_ORM.php");
define("THINK_PATH","..");
require "TS_API.class.php";

session_start();


//$_SESSION["mid"] = 1;
//
$_POST = new_stripcslashes($_POST);
//
//dump($_POST);
?>

<link rel="stylesheet" type="text/css" href="table.css" />
<script>
function del_feed(id) {
	if(confirm("确实要删除此项?")){
		location.href = "?do=d&id=" + id ;
	}
	
}
</script>
<center>
<h1>TS1_6 Feed 模板设置专用界面</h1>
</center>

<?php
$dao = TS_D("FeedTemplate");

//新增
if($_GET["do"] == "a"){
	
	

	$data["type"]  = trim($_POST["type"]);
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
	$feed = $dao->where($map)->find();

	foreach($feed as $k=>$v){
		$feed["$k"] = forTag($v);
	}


$ttt = <<<EOD
<h3>修改 Feed 模板</h3>
<center>
<p>
&nbsp;
<form method="post" action="?do=dom">
	<input type="hidden" id="" name="id" size="60" value="$feed[id]"/>
	Feed Type: <input type="text" id="" name="type" size="60" value="$feed[type]"/><p>
	Feed Title: <input type="text" id="" name="title" size="60" value="$feed[title]"/><p>
	<span style="float:left;padding-left:380px">Feed Body:  </span><br/>
	<textarea name="body" rows="10" cols="60">$feed[body]</textarea>
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
	$data["title"] = trim($_POST["title"]);
	$data["body"]  = trim($_POST["body"]);

	$map["id"] = $_POST["id"];

	$dao->where($map)->save($data);
}
?>



<h3>1、已有Feed 模板</h3>
<center>
<table id="mytable" cellspacing="0"> 
<tr>
<th>id</th><th>type</th><th>title</th><th>body</th><th>操作</th>
</tr>
<?php

	$feed_templates = $dao->order("id desc")->findAll();

	foreach($feed_templates as $key=>$v){
		foreach($v as $kk=>$vv){
			if(!$vv) $v[$kk] = "NULL";
		}
		echo "<tr>";
		echo "<td>".$v['id']."</td><td>".$v['type']."</td><td>".htmlspecialchars($v['title'])."</td><td>".htmlspecialchars($v['body'])."</td>"."<td><a href='?do=m&id=".$v["id"]."'>修改</a>&nbsp;&nbsp;<a href='javascript:del_feed(".$v["id"].")'>删除</a></td>";
		echo "</tr>";
	}

?>
</table>
</center>
<hr>
<h3>2、已有Feed 模板举例</h3>
<?php

$feedDao = TS_D("Feed");
$feeds = $feedDao->findAll();

foreach($feeds as $fkey=>$feed){

	$feed_template = $dao->where(array("type"=>$feed['type']))->find();

	/*-------------------------------------
	= title
	-------------------------------------*/
	$feed_title = $feed_template["title"]; //title的模板

	$title_data = unserialize(stripcslashes($feed["title_data"]));  //title的数据
	$actor = "<a href='space.php?".$feed["uid"]."'>".$feed["username"].'</a>';
	$title_data["actor"] = $actor;

	foreach($title_data as $k=>$v){   //替换
		$feed_title = str_replace('{'.$k.'}',$v,$feed_title);
	}

	$feed_output[$fkey]["title"] = $feed_title;

	/*-------------------------------------
	= body
	-------------------------------------*/

	$feed_body = $feed_template["body"]; //body的模板
	if($feed_body){
		$body_data = unserialize(stripcslashes($feed["body_data"]));  //body的数据
		if($body_data){
			foreach($body_data as $k=>$v){   //替换
				$feed_body = str_replace('{'.$k.'}',$v,$feed_body);
				$feed_body = str_replace('"',"'",$feed_body);
			}		
		}
		

		
		//echo "<br/>";
		//echo $feed_output[$fkey]["body"] = $feed_body;
	}
	echo "<p>";

}
	//dump($feed_output);
?>

<hr>
<h3>3、新增 Feed模板</h3>


<center>
<p>
&nbsp;
<form method="post" action="?do=a">
	Feed Type: <input type="text" id="" name="type" size="60"/><p>
	Feed Title: <input type="text" id="" name="title" size="60"/><p>
	<span style="float:left;padding-left:380px">Feed Body:  </span><br/>
	<textarea name="body" rows="10" cols="60"></textarea>
	&nbsp;<p><input type="submit" id="" value="提交"/>
</form>
</center>



<?php

//入库前的过滤
function  new_addslashes($string)
{
    if(!is_array($string)) return addslashes(trim($string));
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}

function  new_stripcslashes($string)
{
    if(!is_array($string)) return stripcslashes(trim($string));
    foreach($string as $key => $val) $string[$key] = new_stripcslashes($val);
    return $string;
}



function forTag($string)
{
	return str_replace(array('"',"'"), array('&quot;','&#039;'), $string);
}


function dump($var, $echo=true,$label=null, $strict=true)
{
    echo '<div style="border:1px solid #dbdbdb; padding:5px; margin:5px; width:auto; color:#003300">';
    $label = ($label===null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'
                    . $label
                    . htmlspecialchars($output, ENT_QUOTES)
                    . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
		echo '</div>';
        return null;
    }else {
        return $output;
    }
}


?>