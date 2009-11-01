<?php

Class InformationAction extends  Action{

	function index(){
		
		//获取总分类
		$cate = D('Information')->_makeTree(1);
		//print_r($cate);
		$categoryId = $_GET['cid'] ? intval($_GET['cid']) : $cate[0]['a'];
		$flink = isset($_GET['flink']) ? 1 : 0; 
		$this->assign('category',$cate);
		
		if($flink){
			//获取友情链接
			$dao = D("Links");
        	$links = $dao->where($map)->order("sort asc")->findAll();
        	
        	$this->assign("links",$links);
			$this->display('link');
			exit;
		}else{
			$article = D('Information')->where('category ='.$categoryId)->find();
			$this->assign('cid',$categoryId);
			$this->assign('article',$article);
		}
			
			
			$this->display();
	}
	
	function help(){
		//获取总分类
		$cate = D('Information')->_makeTree(6);
		
		$categoryId = $_GET['cid'] ? intval($_GET['cid']) : $cate[0]['a'];
	
		$this->assign('category',$cate);
		
		
		$article = D('Information')->where('category ='.$categoryId)->find();
		$this->assign('cid',$categoryId);
		$this->assign('article',$article);	
		$this->display();
	}
	
	
	//服务条款
    function service(){
    	
		$this->assign('service',$this->opts['reg_tiaokuan']);
		$this->display();
		exit;
    }
    
    //官方动态
    function news(){
    	$article = D('Information')->getInfoByCate('broadcast');
    	
    	//$article = D('Information')->where('category =11')->order('publish_time DESC')->findAll();
    	
    	$this->assign('article',$article);
    	$this->display();
		exit;
    }
}




?>