<?php 
function get_photo_url($savepath) { if(empty($savepath)) $savepath = 'default.gif'; $path = str_ireplace(UPLOAD_PATH,'',$savepath); $path = UPLOAD_URL.$path; return $path; } function get_album_cover($albumId,$albumInfo='') { if(empty($albumInfo) || $albumId!=$albumInfo['id']){ $albumInfo = D('Album')->find($albumId); } if(!empty($albumInfo['coverImagePath'])){ $cover = SITE_URL.'/thumb.php?w=120&h=100&url='.get_photo_url($albumInfo['coverImagePath']); }else{ $cover = APP_PUBLIC_URL.'/images/photo_zwzp.gif'; } return $cover; } function getfriendlist($uid) { $uid = intval($uid); return D('Friend')->field('fuid')->where('uid='.$uid)->findAll(); } function render_in($arr,$key,$array=0) { $in_str = array(); $count = count($arr); if($count == 0) return ''; for($i=0; $i<$count;$i++) { $in_arr[] = $arr[$i][$key]; } if($array) return $in_arr; $in_str = !empty($in_arr) ? '('.implode(',',$in_arr).')' : ''; return $in_str; } function getgroupinfo($id,$field) { $data = D('Group')->find($id); if(empty($data)) return ''; return $data[$field]; } function gettopic($id, $field='') { $data = D('Topic')->find($id); if(empty($data)) return ''; if($field) return $data[$field]; return $data; } function getPost($id, $field='') { $data = D('Post')->find($id); if(empty($data)) return ''; if($field) return $data[$field]; return $data; } function formatsize($fileSize) { $size = sprintf("%u", $fileSize); if($size == 0) { return("0 Bytes"); } $sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB"); return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i]; } function iscreater($uid,$gid) { return D('Member')->where("uid=$uid AND gid=$gid AND level=1")->count(); } function isadmin($uid,$gid) { $ret = D('Member')->where("uid=$uid AND gid=$gid AND (level=1 OR level=2)")->count(); return $ret; } function ismember($uid,$gid) { return D('Member')->where("uid=$uid AND gid=$gid AND level=3")->count(); } function isJoinGroup($uid,$gid) { return D('Member')->where("uid=$uid AND gid=$gid AND level != 0")->count(); } function getLevel($uid,$gid){ return D('Member')->getField('level',"uid=$uid AND gid=$gid"); } function getApplyCount($gid){ return D('Member')->where('gid='.$gid.' AND level=0')->count(); } function getCategorySelect($pid=0) { $tree = json_encode(D('Category')->_makeTree($pid)); return $tree; } function getCategoryTree($id,$onlyShowPid=false) { $tree = D('Category')->_makeParentTree($id,$onlyShowPid); return $tree; } function getCategoryName($id) { $title = D('Category')->getField('title','id='.$id); return $title; } function checkPriv($op,$value,$mid,$gid) { if($op == 'invite'){ if($value == 0 && iscreater($mid,$gid)) { return true; }elseif($value == 1) { if(isadmin($mid,$gid)) return true; }elseif($value == 2) { if(isJoinGroup($mid,$gid)) return true; } } if($op == 'review'){ if($value == 0) return true; if($value == 1){ if(isadmin($mid,$gid)) return true; } } if($op == 'say'){ if($value == 1 && isJoinGroup($mid,$gid)) return true; if($value == 0) return true; } if($op == 'browse'){ if($value == -1) return true; if($value == 1 && isJoinGroup($mid,$gid)) return true; if($value == 0) return true; } return false; } function red($string, $words, $color = 'red', $strlen = 0) { if($string == '' || $words == '') return ''; $position = $search = $replace = array(); if(!is_array($words)) $words = explode(' ', $words); foreach($words as $k=>$word) { $pos = strpos($string, $word); if($pos === false) continue; $search[$k] = $word; $replace[$k] = '<font color="'.$color.'">'.$word.'</font>'; if($k == 2) break; } if($strlen) $string = msubstr($string, $strlen); return str_replace($search, $replace, $string); } function redContent($string, $words, $color = 'red', $strlen = 0) { if($string == '' || $words == '') return ''; $position = $search = $replace = array(); if(!is_array($words)) $words = explode(' ', $words); $ret = false; foreach($words as $k=>$word) { $pos = strpos($string, $word); if($pos === false) continue; $search[$k] = $word; $replace[$k] = '<font color="'.$color.'">'.$word.'</font>'; if($k == 2) break; break; } $str = str_replace($search, $replace, $string); return msubstr($str,intval($pos/3),intval($pos/3)); } function stripGroupName($title,$appid=0){ $pattern = "/在?\s(.*?)\s+\中?(.*?)/i"; $replacement = "\${2}"; return preg_replace($pattern, $replacement, $title); } function ubb($content){ if(empty($content)) return ''; $path = __PUBLIC__."/images/biaoqing/mini/"; $smile = ts_cache( "smile_mini" ); foreach( $smile as $value ){ $img = sprintf("<img title='%s' src='%s%s'>",$value['title'],$path,$value['filename']); $content = str_replace( $value['emotion'],$img,$content ); } return $content; } function replaceSpecialChar($code){ $code = str_replace("&nbsp;", "", $code); $code = str_replace("<br>", "", $code); $code = str_replace("<br />", "", $code); $code = str_replace("<P>", "", $code); $code = str_replace("</P>","",$code); return trim($code); } function getSiteTitle(){ return array( 'my_group'=>'所在群组的最新动态-我的群组-', 'my_group_new_topic' => '最新话题-我的群组-', 'my_friend_group' => '好友的群组-', 'all_group' => '全部群组-', 'newTopic_all' => '所有话题-最新话题-', 'newTopic_my_post' => '最新话题-我发表的话题-', 'newTopic_my_reply' => '我回复的话题-最新话题-', 'newTopic_my_collect' => '我收藏的话题-最新话题-', 'issue_topic' => '发布话题-', 'create_group' => '创建群组-', 'add_topic' => '发表话题', 'search_topic' => '搜索话题', 'dist_topic' =>'精华话题', 'edit_topic' =>'编辑话题', 'topic_index'=>'话题-', 'album_index'=>'相册', 'upload_pic'=>'上传图片', 'all_photo'=>'全部照片', 'all_album'=>'群相册', 'file_index'=>'文件', 'file_upload'=>'上传文件', 'member_index'=>'成员', ); } 
return array (
  'dispatch_on' => true,
  'url_model' => 3,
  'path_model' => 2,
  'path_depr' => '/',
  'router_on' => true,
  'check_file_case' => false,
  'tag_plugin_on' => false,
  'session_auto_start' => true,
  'web_log_record' => false,
  'log_record_level' => 
  array (
    0 => 'EMERG',
    1 => 'ALERT',
    2 => 'CRIT',
    3 => 'ERR',
  ),
  'log_file_size' => 2097152,
  'debug_mode' => false,
  'error_message' => '您浏览的页面暂时发生了错误！请稍后再试～',
  'error_page' => '',
  'show_error_msg' => true,
  'var_pathinfo' => 's',
  'var_module' => 'm',
  'var_action' => 'a',
  'var_page' => 'p',
  'var_template' => 't',
  'var_language' => 'l',
  'var_ajax_submit' => 'ajax',
  'default_module' => 'Index',
  'default_action' => 'index',
  'tmpl_cache_on' => true,
  'tmpl_cache_time' => -1,
  'tmpl_switch_on' => true,
  'auto_detect_theme' => false,
  'default_template' => 'default',
  'template_suffix' => '.html',
  'cachfile_suffix' => '.php',
  'output_charset' => 'utf-8',
  'tmpl_var_identify' => 'array',
  'page_numbers' => 5,
  'list_numbers' => 20,
  'auto_name_identify' => true,
  'default_model_app' => '@',
  'html_file_suffix' => '.shtml',
  'html_cache_on' => false,
  'html_cache_time' => 60,
  'html_read_type' => 1,
  'html_url_suffix' => '',
  'time_zone' => 'PRC',
  'lang_switch_on' => true,
  'default_language' => 'zh-cn',
  'auto_detect_lang' => false,
  'db_charset' => 'utf8',
  'db_deploy_type' => 0,
  'db_rw_separate' => false,
  'db_fields_cache' => true,
  'data_cache_time' => -1,
  'data_cache_compress' => false,
  'data_cache_check' => false,
  'data_cache_type' => 'File',
  'data_cache_path' => 'E:/wamp/www/thinksns//runtime/group/Temp/',
  'data_cache_subdir' => false,
  'data_path_level' => 1,
  'cache_serial_header' => '<?php
//',
  'cache_serial_footer' => '
?>',
  'show_run_time' => false,
  'show_adv_time' => false,
  'show_db_times' => false,
  'show_cache_times' => false,
  'show_use_mem' => false,
  'show_page_trace' => false,
  'tmpl_engine_type' => 'Think',
  'tmpl_deny_func_list' => 'echo,exit',
  'tmpl_l_delim' => '{',
  'tmpl_r_delim' => '}',
  'taglib_begin' => '<',
  'taglib_end' => '>',
  'tag_nested_level' => 3,
  'taglib_list' => 'cx,html',
  'cookie_expire' => 3600,
  'cookie_domain' => '',
  'cookie_path' => '/',
  'cookie_prefix' => '',
  'ajax_return_type' => 'JSON',
  'auto_load_path' => 'Think.Util.',
  'action_jump_tmpl' => 'THEME_PATH&success',
  'action_404_tmpl' => 'Public:404',
  'app_domain_deploy' => false,
  'extend_config_list' => 
  array (
    0 => 'taglibs',
    1 => 'routes',
    2 => 'tags',
    3 => 'htmls',
    4 => 'modules',
    5 => 'actions',
  ),
  'trace_tmpl_file' => 'E:/wamp/www/thinksns//thinkphp/Tpl/PageTrace.tpl.php',
  'exception_tmpl_file' => 'E:/wamp/www/thinksns//thinkphp/Tpl/ThinkException.tpl.php',
  'db_type' => 'mysql',
  'db_host' => 'localhost',
  'db_name' => 'xysns',
  'db_user' => 'deroot',
  'db_pwd' => 'dedata',
  'db_prefix' => 'ts_',
  'cache_data' => 'E:/wamp/www/thinksns//data/cache/secache_data',
  'ts_url' => 'http://localhost/thinksns',
  'upload_path' => 'E:/wamp/www/thinksns//data/uploads/',
  'upload_url' => 'http://localhost/thinksns/data/uploads/',
  'token_on' => true,
  'token_name' => 'thinksns_html_token',
  'token_type' => 'md5',
  'other_token' => 'js',
);
?>