<?php
class UploadAttachWidget extends Widget{

	public function render($data){

		$api     =    new TS_API();

        $var['mid']				=	$data['uid'];

		if(isset($data['callback']) && !empty($data['callback'])){
			$var['callback']	=	$data['callback'];
		}else{
			$var['callback']	=	'attach_upload_success';
		}
        $content = $this->renderFile("UploadAttach",$var);

        return $content;
    }
}
?>