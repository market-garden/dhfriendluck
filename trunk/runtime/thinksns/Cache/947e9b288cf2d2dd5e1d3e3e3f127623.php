<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
	<title>上传结果</title>
	<script type="text/javascript">
		
	function submitImg(){		
		 var imageInput = document.getElementById('img');
		 var imgPattern = new RegExp('^.*\.(bmp|gif|jpg|png){1}$','gi');
		 if( !imageInput.value ){
			 return;
		 }
		 if(!imgPattern.test(imageInput.value)){
			 alert('该文件不是图片！');
			 return;
		 }
		 document.getElementById('uploadFrom').submit();
		 document.getElementById('uploadFrom').style.display='none';
		 document.getElementById('uploadingDiv').style.display='block';
	}
	
	</script>
	<style type="text/css">
	
	body { font-size:12px; padding:0; margin:0; background-color:#FFFFFF; }
	form {
	margin:0;
	padding: 0px;
}
	img{ vertical-align:middle;}
	
	h4 {
	font-size: 12px;
	color: #333;
	margin-bottom: 10px;
	line-height: 16px;
	padding: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-left: 0px;
}
	p {
	font-size: 12px;
	line-height: 18px;
	margin: 0px;
	padding: 0px;
	color: #999;
}
	</style>
	</head>
	<body>
	<div id="uploadingDiv" style="display: none; text-align: center;">
		正在上传，请稍等...
	</div>
	<form id="uploadFrom" enctype="multipart/form-data" action="__URL__/doUploadFaceImg" method="post">
		 <h4>上传头像</h4>
		 <p>支持JPG、GIF和PNG格式的图片文件，大小2M以内.<br>
	 	建议使用大头照，不然缩小后可能看不清楚。</p>
		 <div style="margin:20px 0 30px 0; line-height:18px;">请浏览大图，然后拖动选择头像区域。<br />
		 	<input id="img" type="file" onChange="submitImg()" value="xx" name="file" size="35"/>
	 	</div>
		 <p>请确认上传的是你自己的照片。<br>
		 上传色情、反动等照片将导致你的账号被删除。</p>
	</form>
	</body>
	</html>