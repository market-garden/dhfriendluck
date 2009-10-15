<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh" xml:lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>联系人提取</title>
<body>
<form method="post" action="?act=m">
<div style="margin-top:10px;">
		<p style="margin:10px 0px; font-size:13px;" ><b>导入<!--{if $type == 'msn'}-->MSN联系人<!--{else}--><!--{/if}-->至您的好友邀请名单</b></p>
		<p style="margin:5px 0px;"><!--{if $type == 'msn'}-->MSN<!--{else}--><!--{/if}-->帐号：<input name="account" type="text" id="account" size="30">
	</p>
		<p style="margin:5px 0px;"><!--{if $type == 'msn'}-->MSN<!--{else}--><!--{/if}-->密码：<input name="passwd" type="password" id="passwd" size="20">
	</p>
		<p style="padding-top:8px; padding-left:60px;"><input name="提交" type="submit" class="submit" value="导入">
		</p>
  </div>
</form>

<form method="post" action="?act=y">
<div style="margin-top:10px;">
  <p style="margin:10px 0px; font-size:13px;" ><b>导入邮箱的通讯录至您的好友邀请名单</b></p>
  <p style="margin:5px 0px;"> 邮箱地址：
      <input name="account" type="text" id="account" size="12" />
      <span style="font-size:14px;">&nbsp;@&nbsp;</span>
      <select name="postoffice" id="postoffice">
	   <option value="126.com" >126.com</option>
	  <option value="sohu.com" >sohu.com</option>
	    <option value="163.com" >163.com</option>
        <option value="sina.com" >sina.com</option>
       
       
        <option value="tom.com" >tom.com</option>
        <option value="gmail.com" >gmail.com</option>
        <option value="yahoo.cn" >yahoo.cn</option>
        <option value="yahoo.com" >yahoo.com</option>
        <option value="yahoo.com.cn" >yahoo.com.cn</option>
      </select>
  </p>
  <p style="margin:5px 0px;">邮箱密码：
      <input name="passwd" type="password" id="passwd" size="20" />
  </p>
  <p style="padding-top:8px; padding-left:60px;">
    <input name="button" type="submit" class="submit" value="导入" />
  </p>
</div>
</form>


<form action="?act=u" enctype="multipart/form-data" method="post">
			<input type="hidden" name="type" value="card">
			<p style="margin:10px 0px; font-size:13px;" ><b>上传邮件地址簿文件(*.CSV、*.VCF)，选择邀请名单并发送邀请邮件</b></p>
			<p>请选择地址簿文件：<input type="file" size="33" name="cardfile" value=""  style="font-family:Arial;" /></p>
			<p style="padding-top:10px; padding-left:108px; margin:10px 0px;"><input type="submit" value=" 上传 " title="上传" class="submit" /></p>
		</form>
<?php

/**
 * 导入邮箱通讯薄
 */
$act = $_GET['act'];
if ($act == "y"){
	if(!$_POST['account'] || !$_POST['passwd'] || !$_POST['postoffice']){
		die('error');
	}
	//取得联系人 UTF8
	require_once ('mailfactory.php');
	switch ($_POST['postoffice']) {
		case "126.com":
			$contact = new MailFactory(M126);
			break;
		case "sina.com":
			$contact = new MailFactory(MSINA);
			break;
		case "tom.com":
			$contact = new MailFactory(MTOM);
			break;
		case "gmail.com":
			$contact = new MailFactory(MGOOGLE);
			break;
		case "163.com":
			$contact = new MailFactory(M163);
			break;
		case "sohu.com":
			$_POST['account'] = $_POST['account'] . "@" . $_POST['postoffice'];
			$contact = new MailFactory(MSOHU);
			break;
		case "vip.sohu.com":
			$_POST['account'] = $_POST['account'] . "@" . $_POST['postoffice'];
			$contact = new MailFactory(MSOHU_VIP);
			break;
		case "yahoo.cn":
		case "yahoo.com":
		case "yahoo.com.cn":
			$_POST['account'] = $_POST['account'] . "@" . $_POST['postoffice'];
			$contact = new MailFactory(MYAHOO);
			break;
		default:
			die("error");
	}
	$contacts = $contact->getContactList($_POST['account'], $_POST['passwd']);

	if($contacts == 0) die('error');
	if(empty($contacts)) die('empty');

	if($_POST['postoffice'] == "sina.com" || $_POST['postoffice'] == "sohu.com" || $_POST['postoffice'] == "vip.sohu.com" ) {
		echo diff_contacts($contacts);
	} else {
		echo diff_contacts(array_flip($contacts));
	}
	exit;
}

/**
 * 导入邮箱通讯薄
 */
elseif ($act == "m"){
	if(!$_POST['account'] || !$_POST['passwd']){
		die('error');
	}
	include('msn.class.php');
	$msn2 = new hotmail;
	$returned_emails = $msn2->qGrab($_POST['account'], $_POST['passwd']);
	die(print_r($returned_emails));
	diff_contacts($returned_emails);
	exit;
}

/**
 * 导入FOXMAIL通讯薄
 */
elseif ($act == "u"){
	if(empty($_FILES['cardfile']) || $_FILES['cardfile']['size'] <= 0) {
		$ret = 'error';
	} else {
		$content = file_get_contents($_FILES['cardfile']['tmp_name']);
		preg_match_all("/[a-z0-9_\.\-]+@[a-z0-9\-]+\.[a-z]{2,6}/i", $content, $matches);
		if(($emails = array_unique($matches[0])) !=false) {
			$ret = diff_contacts(array_flip($emails));
		} else {
			$ret = "empty";
		}
		unset($matches);
	}
	/*echo "<script language=javascript>alert('".addslashes($ret)."');</script>";*/
	exit;
}

	function  diff_contacts($rel){
		var_dump($rel);
	}

?>
</body>
</html>