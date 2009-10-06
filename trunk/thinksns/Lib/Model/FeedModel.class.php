<?php
class FeedModel extends Model {
    public function dochange($p) {
        $num = $p * 100;
        $page = $num?$num:100;
        $result = $this->where('id>'.$page)->order('id asc')->findAll();
        foreach ($result as $value) {
            if(0 == preg_match('/(share)/i',$value['type'])) {
                $value['title_data'] = serialize($this->change(unserialize(stripcslashes($value['title_data'])),$value['appid']));
                $value['body_data'] = serialize($this->change(unserialize(stripcslashes($value['body_data'])),$value['appid']));
                $id = $value['id'];
                unset($value['id']);

                $result = $this->where('id='.$id)->save($value);
                dump("id:".$id);
            }
        }
        return $p+1;
    }
    public function change($data,$appid) {
        $result = $data;
        foreach($result as &$value) {
            if ($appid != 10) {
                $replace = '/apps/'.getAppInfo($appid,'APP_NAME').'/index.php?s=';
                if(preg_match('/({SITE_URL})/i',$value)) {
                    $value = str_replace($replace,'', $value);
                }


            }

        }
        return $result;
    }
}
?>