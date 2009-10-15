<?php
import('AdvModel');
class MiniModel extends AdvModel
{
    private static $count;

    // 自动验证设置
    //protected $_validate     =     array(
		//array('content','checkLength','内容字数不合要求',self::MUST_VALIDATE,'callback'),
    //);


    public function setCount( $value ){
        self::$count = $value;
    }

	//function checkLength($data,$field)
	//{
		//switch($field) {
			//case "content": return ( strlen($data)>0 && strlen($data)<=self::$count )? true : false; 
		//}
		
	//}





    
    //我的心情
    function getOneMini($uid,$bq_emotion,$smiletype){

        $one_mini = $this->where("uid=$uid")->order("id desc")->find();
        if(!$one_mini["content"]) {
        	$one_mini["content"] = "什么都没做";
        	$one_mini['uid'] = $uid;
        }

        $one_mini["content"] = tt($one_mini["content"]);

        $bq_path = "<img src='".__PUBLIC__."/images/biaoqing/{$smiletype}/";
        foreach($bq_emotion as $v){
            $one_mini["content"] =  str_replace($v["emotion"], $bq_path.$v['filename']."'>", $one_mini["content"]);
        }

        return $one_mini;
    }

    public static function getMiniCache(){
       return D( 'MiniConfig' )->getConfig('mini');

    }
        public function getReplayCount( $data ){
            $map['appid'] = $data['id'];
            $map['type'] = "mini";
            $comment = D( 'Comment' );
            $result = $comment->where( $map )->count();
            return $result;
        }
}
?>
