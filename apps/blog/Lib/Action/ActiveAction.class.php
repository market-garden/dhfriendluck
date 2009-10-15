<?php
/**
 * ActiveAction
 * 日志的各种动作
 * @uses Action
 * @package
 * @version $id$
 * @copyright 2009-2011 SamPeng
 * @author SamPeng <sampeng87@gmail.com>
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
class ActiveAction extends Action {
    
    /**
     * __initialize
     * 初始化
     * @access public
     * @return void
     */
    public function _initialize() {
    //参数转义
        new_addslashes($_POST);
        new_addslashes($_GET);

        //设置心情Action的数据处理层
        $this->blog = D( 'Blog' );
    }
    /**
     * addCategory
     * 添加分类
     * @access public
     * @return void
     */
    public function addCategory() {
        $data['name'] = trim( $_POST['name'] );
        $data['uid']  = $this->mid;
        //检查是否为空
        $this->__equalTrueEchoMsg(empty($data['name']), -3);
        //检测是否有重复的分类
        $this->__checkCategory($data);
        $category   = D( 'BlogCategory' );
        $result = $category->addCategory($data);
        $this->__issetQueryToMsg($result, -1);
    }

    private function __checkCategory($data) {
        $category = $this->blog->getCategory( $this->mid );
        foreach( $category as $value ) {
            $this->__equalTrueEchoMsg($value['name'] == $data['name'], -2);
        }
    }
    private function __equalTrueEchoMsg($query,$msg) {
        if(true === $query) {
            echo $msg;
            exit();
        }

    }

    private function  __issetQueryToMsg($query,$msg) {
        if(isset($query)) {
            echo $query;
        }else {
            echo $msg;
        }
        exit();

    }
    /**
     * TODO 删除
     * editCategory
     * 修改分类
     * @access public
     * @return void
     */
    public function editCategory() {
        $category = D( 'BlogCategory' );
        $result   = $category->editCategory( $_POST['name'] );
        $this->redirect( 'my' );
    }

    /**
     * TODO 删除
     * recommend
     * 推荐操作
     * @access public
     * @return void
     */
    public function recommend(  ) {
        $name          = $this->blog->getOneName($this->mid);
        $map['blogid'] = $_POST['id'];
        $map['uid']    = $this->mid;
        $map['name']   = $name['name'];
        $map['type']   = "recommend";
        $action        = $_POST['act'];

        //添加推荐和推荐人数据。并且更新日志的推荐数
        $result = D( 'BlogMention' )->addRecommendUser( $map,$action );
        if( $result ) {
            echo 1;
        }else {
            echo -1;
        }
    }

    public function commentSuccess() {
        $result = json_decode(stripslashes($_POST['data']));  //json被反解析成了stdClass类型
        $count = $this->__setBlogCount($result->appid);
        //发送两条消息
        $data = $this->__getNotifyData($result);
        $this->api->comment_notify('blog',$data,$this->appId);
        echo $count;
    }

    private function __getNotifyData($data) {
    //发送两条消息
        $result['toUid'] = $data->toUid;
        $need  = $this->blog->where('id='.$data->appid)->field('uid,title')->find();
        $result['uids'] =$need['uid'];
        $result['url'] = sprintf('%s/Index/show/id/%s/mid/%s','{'.$this->appId.'}',$data->appid,$result['uids']);
        $result['title_body']['comment'] = $data->comment;
        $result['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$result['url'],$need['title']);
        $result['title_data']['type']  = "日志";
        return $result;
    }

    public function deleteSuccess() {
        $id = $_POST['id'];
        echo $this->__setBlogCount($id);;
    }

    private function __setBlogCount($id) {
        $count = $this->api->comment_getCount('blog',$id);
        $this->blog->setCount($id,$count);
        return $count;
    }
}
?>
