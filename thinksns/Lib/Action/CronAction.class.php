<?php
class CronAction extends Action {

	//任务计划
	public function run() {
        // 锁定自动执行
        $lockfile	 =	 RUNTIME_PATH.'cron.lock';
        if(is_writable($lockfile) && filemtime($lockfile) > $_SERVER['REQUEST_TIME'] - 60) {
            return ;
        } else {
            touch($lockfile);
        }
        set_time_limit(1000);
        ignore_user_abort(true);

		//执行计划任务
		$this->sendEmail();

		// 解除锁定
        unlink($lockfile);
		return ;
    }

    //设置发送邮件每次发送10条
	public function sendEmail($count=10){
		$email_arr = D('Invite')->where('status=0')->limit($count)->findAll();
		$opts = $this->api->option_get();
		$options['smtp'] = $opts['email_stmp'];
		$options['port'] = $opts['email_port'];
		$options['username'] = $opts['email_address'];
		$options['password'] = $opts['email_password'];
		foreach($email_arr as $k=>$v){
			$sr = send_email($v['toemail'],$v['title'],$v['content'],'HTML',$options);
			if($sr) $ret = D('Invite')->setField('status',1,'id='.$v['id']);

		}
		return $ret;
	}
}
?>