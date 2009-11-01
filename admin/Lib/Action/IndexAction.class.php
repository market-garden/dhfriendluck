<?php
class IndexAction extends BaseAction{
	
	public function _initialize() {
		parent::_initialize();
	}
	
    public function index(){
   	
        $pSystemUserGroup = D('SystemUserGroup');
        
        $site_opts = $this->api->option_get();      
        $this->assign("site_opts",$site_opts);
 		$this->display();
    }

    //菜单
	function menu() {
        $pNode = D('SystemNode');
        $list = $pNode->where("pid=0 AND type='admin' AND state=1")->order('ordernum ASC')->findall();
        foreach ($list as $val){
        	$name = $val['name'];
        	$data[$name]['title'] = $val['title'];
        	$data[$name]['url']   =  __APP__.'/Index/menulist/id/'.$val['id'];
        }
		exit(json_encode($data));
	}

	//左侧列表
	function menulist(){
		$intId = intval($_GET['id']);
        $pNode = D('SystemNode');
        $list = $pNode->getNodeList($intId);
    	foreach ($list as $val){
    		foreach ($val['child'] as $key=>$cval){
    			$a['title'] = $cval['title'];
    			$a['url']   = __APP__.'/'.$val['name'].'/'.$cval['name'];
    			$node[]     = $a;
    		}
    		$b['title'] = $val['title'];
    		$b['node']  = $node;
    		unset($node);
    		$name = $val['name'];
    		$data[$name] = $b;
    	}
    	if($intId==4){
			$apps = D("App")->where("status!=2")->findAll();
			foreach($apps as $k=>$v){
				$APP_URL = str_replace('http://{APPS_URL}',SITE_URL.'/apps',$v['url']);
				$open_apps[$k]["title"] = $v["name"];
				$open_apps[$k]["url"]   = str_replace('http://{APP_URL}',$APP_URL,$v['url_admin']);
			}
			$data['Apps']['node'] = $open_apps;
    	}
    	exit(json_encode($data)); 
	}
}
?>