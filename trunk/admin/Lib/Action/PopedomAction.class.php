<?php
// +----------------------------------------------------------------------
// | ThinkSnS
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Nonant <nonant@163.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * ThinkSnS 后台权限配置
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class PopedomAction extends Action {

//权限配置首页
        public function index() {

                $this->display();
        }

/*********************************** 节点管理 *******************************/
        //节点管理
        public function node() {
			//exit('Beta版暂不启动');

        //上级ID
                $intPid = intval($_GET['pid']);
                $pNode = D('SystemNode');
                if($intPid) $info = $pNode->find($intPid);
                if($info && $intPid) {
                        $map["pid"] = $intPid;
                        $strType = $info['type'];
                }else {
                        $map["pid"] = 0;
                        $strType = (h($_GET['type']))?(h($_GET['type'])):'admin';
                }

                switch ($strType) {
                        case 'apps':
                                $this->assign('appslist', D('APP')->findall());
                                break;
                }
                $map['type'] = $strType;
                $list = $pNode->where($map)->findAll();
                $this->assign('level',$info['level']+1);
                $this->assign('pid',$intPid);
                $this->assign('list',$list);
                $operateModel = $this->fetch('Nodes/'.$strType);
                $this->assign('operateModel',$operateModel);
                $this->assign('type',$strType);
                $this->display();
        }

        //添加节点
        public function addnode() {

                $pNode = D('SystemNode');

                $strType = h($_POST['type']);
                if($_POST['level']=='3' || ($_POST['level']=='2' && $strType='apps')) {
                        $getReturn =$this->_getActionList();
                        $data['containaction']  = $getReturn['arrActionList'];
                        $data['title']          = $getReturn['indexShow']['title'];
                        $data['name']           = $getReturn['indexShow']['name'];
                        $data['description']    = $getReturn['indexShow']['title'];
                }else {
                        $data['title']          = $_POST['title'];
                        $data['name']           = $_POST['name'];
                        $data['description']    = $_POST['description'];
                }


                $data['pid']                = intval($_POST['pid']);
                $data['level']              = intval($_POST['level']);
                $data['state']              = intval($_POST['state']);
                $data['type']               = $strType;
                $pNode->add($data);
                $this->success('添加成功');
        }


        //删除节点
        public function delnode() {
                $pNode = D('SystemNode');
                $intId = intval($_GET['id']);
                if($pNode->_DelNode($intId)) {
                        $this->success('删除成功');
                }else {
                        $this->error('删除失败');
                }
        }

        //修改节点show
        public function editnode() {
                $pNode = D('SystemNode');
                $intId = intval($_GET['id']);
                $info = $pNode->find($intId);
                if($info && $intId) {
                        if($info['level']=='3') {
                                $arrActionList = unserialize($info['containaction']);
                                $this->assign('actionlist',$arrActionList);
                        }

                        $this->assign('info',$info);
                        $this->display();
                }else {
                        $this->error('您提交错误参数');
                }
        }

        //修改节点操作
        public function doeditnode() {
                $intId = intval($_POST['id']);
                $pNode = D('SystemNode');
                $info = $pNode->find($intId);
                if($info && $intId) {
                        if($info['level']=='3') {
                                $getReturn =$this->_getActionList();
                                $pNode->containaction  = $getReturn['arrActionList'];
                                $pNode->title          = $getReturn['indexShow']['title'];
                                $pNode->name           = $getReturn['indexShow']['name'];
                                $pNode->description    = $getReturn['indexShow']['title'];
                        }else {
                                $pNode->title          = $_POST['title'];
                                $pNode->name           = $_POST['name'];
                                $pNode->description    = $_POST['description'];
                        }
                        $pNode->state              = intval($_POST['state']);
                        $pNode->where($intId)->save();
                        $this->success('修改成功');
                }else {
                        $this->error('您提交了错误参数');
                }
        }

        protected function _getActionList() {
                $arrAction = $_POST['action'];
                $intShowIndex = intval($_POST['showindex']);
                foreach ($_POST['action'] as $key => $val) {
                        if(!empty($val)) {
                                $arrContainaction[$key]['title'] = $_POST['description'][$key];
                                $arrContainaction[$key]['name'] = $val;
                        }
                }

                $return['arrActionList'] = serialize($arrContainaction);
                $return['indexShow']     = $arrContainaction[$intShowIndex];
                return $return;
        }
/*********************************** 节点管理结束 *******************************/

/*********************************** 用户组管理开始 *******************************/
        //用户组管理
        public function group() {

                $intGroupId = intval($_GET['groupid']);
                $pNode = D('SystemGroup');
                if($intGroupId) $info = $pNode->where('id='.$intGroupId)->find();
                if($info) {
                        $this->assign('info',$info);
                }
                $list = $pNode->findall();
                $this->assign('list',$list);
                $this->display();
        }

        //添加用户组
        public function addgroup() {
                $pGroup = D('SystemGroup');
                $intGroupId = intval($_POST['groupid']);
                if($pGroup->create()) {
                        if($intGroupId) {
                                $pGroup->id = $intGroupId;
                                $pGroup->save();
                        }else {
                                $pGroup->add();
                        }

                        $this->success('操作成功');
                }else {
                        $this->error($pGroup->getError());
                }
        }

        //删除节点
        public function delgroup() {
                $pGroup = D('SystemGroup');
                $intGroupId = intval($_GET['id']);
                if($pGroup->delete($intGroupId)) {
                        $this->success('删除成功');
                }else {
                        $this->success('删除失败');
                }
        }
/*********************************** 用户组管理结束 *******************************/

        public function rank() {
                $creditType = D('CreditType')->getCreditType();
                $rankRule     = D('SystemUserRank')->getAll();
                $this->assign('fields',$creditType);
                $this->assign('rankRule',$rankRule);
                $this->display();
        }
        public function doAdd() {
                $creditType = D('CreditType')->getCreditType();
                foreach ($_POST as $key=>$value) {
                        if(isset($creditType[$key])) {
                                $min = intval($value['min']);
                                $max = intval($value['max']);
                                if($this->_checkZero($min, $max) ) {
                                        $this->error('积分规则必须为整数');
                                }
                                if($min> $max) {
                                        $this->error($creditType[$key].'的最大值必须大于最小值');
                                }
                                $rule['min'][$key] = $min;
                                $rule['max'][$key] = $max;
                        }else {
                                $map[$key] = $value;
                        }
                }
                unset($map['thinksns_html_token']);
                $dao = D('SystemUserRank');
                if($dao->addRank($map,$rule)) {
                        $this->redirect('rank');
                //$this->success('添加成功');
                }else {
                        $this->error('添加失败');
                }
        }

        public function doEditRank() {
                $creditType = D('CreditType')->getCreditType();
                $data = $_POST;
                $id = intval($data['id']);
                unset($_POST['id']);
                $map['name'] = $data['name'];
                $map['icon']   = $data['icon'];
                if(empty($map['name'])) {
                       echo '等级名称必须填写';
                       exit;
                }
                if(empty($map['icon'])) {
                        echo '等级图标必须填写';
                        exit;
                }
                
                $rankRule = array_diff($_POST,$map);
                foreach($creditType as $key=>$value) {
                        $min = intval($rankRule[$key.'_min']);
                        $max = intval($rankRule[$key.'_max']);
                        if($min>$max) {
                                echo $value."的最大值必须大于最小值";
                                exit;
                        }
                        if($this->_checkZero($min, $max)) {
                                echo $value.'的最大值和最小值必须填写.不启用请设为0';
                                exit;
                        }
                        $map['rulemin'][$key] = $min;
                        $map['rulemax'][$key] =$max;
                }
                $map['rulemin'] = serialize($map['rulemin']);
                $map['rulemax'] = serialize(($map['rulemax']));
                $dao = D('SystemUserRank');
                echo $dao->where('id='.$id)->save($map);
                $dao->setCache();
        }

        public function doEditRankAll() {
                unset($data['thinksns_html_token']);
                $creditType = D('CreditType')->getCreditType();
                foreach($_POST['name'] as $id=>$value) {
                        $data[$id]['name'] = trim($value);
                        $data[$id]['icon']    = trim($_POST['icon'][$id]);
                        if(empty($data[$id]['name'])) {
                                $this->error('等级名称必须填写');
                        }
                        if(empty($data[$id]['icon'])) {
                                $this->error('等级图标必须填写');
                        }

                        foreach($creditType as $key=>$value) {
                                $min = intval($_POST[$key][$id]['min']);
                                $max = intval($_POST[$key][$id]['max']);
                                if($min>$max) {
                                        $this->error($value."的最大值必须大于最小值");
                                }
                                if($this->_checkZero($min, $max)) {
                                        $this->error($value.'的最大值和最小值必须填写.不启用请设为0');
                                }
                                $data[$id]['rulemin'][$key] = $min;
                                $data[$id]['rulemax'][$key] =$max;
                        }
                        $data[$id]['rulemin'] = serialize($data[$id]['rulemin']);
                        $data[$id]['rulemax'] = serialize($data[$id]['rulemax']);
                }
                
                $dao = D('SystemUserRank');
                foreach ($data as $key=>$map) {
                        $result[] = $dao->where('id='.$id)->save($map);
                }
                if(count(array_filter($result)) == count($result) ) {
                        $dao->setCache();
                        $this->success('修改成功');
                }else {
                        $this->success('修改失败');
                }
        }
        
        public function deleteRank() {
                $id = intval($_POST['id']);
                if(empty($id)) {
                        echo '请选择要删除的等级规则';
                        exit;
                }
                $dao = D('SystemUserRank');
                echo $dao->delete($id);
               $dao->setCache();
        }
        private function _checkZero($min,$max) {
                return (empty($min)  ||  empty($max)) && $min !=0 && $max !=0 ;
        }
        public function commision() {
        	//exit('beta版暂不启用');
                $intGroupId = intval($_GET['groupid']);
                $strType    = h($_GET['type'])?h($_GET['type']):'admin';
                $this->assign('groupid',$intGroupId);

                //权限列表
                $pNode = D('SystemNode');
                $nodelist = $pNode->getNodeList(0,$strType);
                $this->assign('nodelist',$nodelist);

                //用户组列表
                $pGroup = D('SystemGroup');
                $list = $pGroup->findall();

                //用户组已配置的权限
                $pSystemPopedom = D('SystemPopedom');
                $popedomList = $pSystemPopedom-> where("groupId=$intGroupId AND type='$strType'")->findall();
                $arrAction = array();

                foreach ($popedomList as $key=>$value) {
                        if(!in_array($value['menuid'],$arrUserNode['menu'])) {
                                $arrUserNode['menu'][$key] = $value['menuid'];
                        }
                        $arrUserNode['model'][$key] = $value['modelid'];
                        foreach (unserialize($value['arraction']) as $action) {
                                $arrUserNode['action'][] = $action;
                        }
                }

                $this->assign('userNode',$arrUserNode);
                $this->assign('grouplist',$list);
                $this->assign('type',$strType);
                $this->display();
        }

        //保存
        public function savecommision() {

                $pSystemPopedom = D('SystemPopedom');
                $arrPopedom = $_POST['popedom'];
                $groupId = intval($_POST['grouplist']);
                $strType = h($_POST['type']);
                $pSystemPopedom->where("groupid=$groupId AND type='$strType'")->delete();
                foreach ($arrPopedom as $key=>$value) {
                        foreach ($value as $k=>$val) {
                        //$pSystemPopedom->groupid    = $groupId;
                                $date['groupid']    = $groupId;
                                $date['menuid']     = $key;
                                $date['type']       = $strType;
                                if(count($val)>0) {
                                        $date['modelid']    = $k;
                                        $date['arraction']  = serialize($val);
                                }

                                $pSystemPopedom->add($date);
                        }
                        unset($date);
                }
        }

        public function getInfoList() {
                $strType = h($_POST['type']);
                $intId = intval($_POST['id']);
                $grounId = intval($_POST['groupid']);
                $pSystemPopedom = D('SystemPopedom');
                $pNode = D('SystemNode');
                switch ($strType) {
                        case 'menulist':
                                $savelist = $pSystemPopedom->where('groupid='.$grounId)->findall();
                                foreach ($savelist as $value) {
                                        $savelist[] = $value['menuid'];
                                }
                                $this->assign('savelist',$savelist);
                                if($intId)$list = $pNode->where('pid=0')->findall();
                                break;

                        case 'modellist':
                                $savelist = $pSystemPopedom->where('groupid='.$grounId.' AND menuid='.$intId)->findall();
                                foreach ($savelist as $value) {
                                        $savelist[] = $value['modelid'];
                                }
                                $this->assign('savelist',$savelist);
                                if($intId)$list = $pNode->where('pid='.$intId)->findall();
                                break;

                        case 'actionlist':

                                $savelist = $pSystemPopedom->where('groupid='.$grounId.' AND modelid='.$intId)->find();
                                $this->assign('savelist',unserialize($savelist['arraction']));
                                if($intId)$list = $pNode->where('pid='.$intId)->findall();
                                $fatherId = $pNode->where('id='.$intId)->find();
                                $this->assign('fatherId',$fatherId['pid']);
                                break;
                }
                $this->assign('parentId',$intId);
                $this->assign('type',$strType);
                $this->assign('list',$list);
                $this->display();
        }
}
?>