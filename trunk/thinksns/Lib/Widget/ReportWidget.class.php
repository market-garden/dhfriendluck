<?php
    /**
     * ReportWidget 
     * 举报Widget
     * @uses Widget
     * @package 
     * @version $id$
     * @copyright 2009 Nonant 
     * @author Nonant <nonant@thinksns.com> 
     * @license PHP Version 5.2
     */
    class ReportWidget extends Widget{
        public function render( $data ){
			$api		 =    new TS_API();
			$mid		 =	  $api->user_getLoggedInUser();
			
	        $site_opts = $api->option_get();
	        $reasons =  explode(",",$site_opts["report_reason"]);
	        $data['reasons'] = $reasons;
			$data['isReport']	 =	  $api->Report_check($data['appid'],$data['type'],$data['recordId'],$mid);
			$content = $this->renderFile("Report",$data);
            return $content;
        }
    } 
