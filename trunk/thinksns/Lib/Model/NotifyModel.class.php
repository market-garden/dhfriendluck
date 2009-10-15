<?php
import('AdvModel');
class NotifyModel extends AdvModel {
    public function dochange($p) {
        $result = $this->order('id asc')->findAll();
        foreach ($result as $value) {
            $value['title'] = serialize($this->change(unserialize(stripcslashes($value['title'])),$value['appid']));
            $value['body'] = serialize($this->change(unserialize(stripcslashes($value['body'])),$value['appid']));
            //$value['url'] = serialize($this->change(unserialize(stripcslashes($value['url'])),$value['appid']));
            $id = $value['id'];
            unset($value['id']);
            
            $result = $this->where('id='.$id)->save($value);
            dump("id:".$id.'--'.$result);
        }
    }
    public function doChangeUrl() {
        $result = $this->where("url <> 'b:0;'")->field('id,url,appid')->order('id asc')->findAll();
        foreach ($result as $value) {
            $value['url'] = $this->changeUrl(stripcslashes($value['url']),$value['appid']);
            $id = $value['id'];
            unset($value['id']);
            $save['url'] = $value['url'];
            //dump($save['url']);
            $result = $this->where('id='.$id)->save($save);
            dump("===========================b=====================");
            dump("id:".$id.'--'.$result);
            dump($this->getLastSql());
            dump("==========================e======================");
        }
        dump('ok');
    }
    public function changeUrl($url,$appid) {
        $value = $url;
        $replace = 'http://i.thinksns.com/apps/'.getAppInfo($appid,'APP_NAME').'/index.php?s=';
        $value = str_replace($replace,'{SITE_URL}', $value);
        
        $replace = '/apps/'.getAppInfo($appid,'APP_NAME').'/index.php?s=';
        if( 1 == preg_match('/({SITE_URL})/i',$value)) {
            $value = str_replace($replace,'', $value);
        }

        return $value;
    }
    public function change($data,$appid) {
        $result = $data;
        foreach($result as &$value) {
            $replace = '/apps/'.getAppInfo($appid,'APP_NAME').'/index.php?s=';
            if(preg_match('/({SITE_URL})/i',$value)) {
                $value = str_replace($replace,'', $value);
            }
        }
        return $result;
    }

}
?>