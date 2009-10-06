<?php
if (!defined('PROJECT_ROOT_DIR')) {
    define('PROJECT_ROOT_DIR', dirname(__FILE__));
}
require_once PROJECT_ROOT_DIR . '/config.php';

final class friendfeed {
    const BLOG_163   = '163_blog';
    const BLOG_BAIDU = 'baidu_space';
    const BLOG_MSN   = 'msn_space';
    const BLOG_SINA  = 'sina_blog';
    const BLOG_SOHU  = 'sohu_blog';

    /* private */ const TYPE_PRIVATE  = 1;
    /* private */ const TYPE_EXTSHARE = 2;
    public static function get_popular_sources($num) {
        $sql = "SELECT *, COUNT(*) AS num FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id GROUP BY subscribe.source_id ORDER BY num DESC LIMIT {$num}";
        $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }



    public static function get_updated_subscribes($user_id) {
        $sql = "SELECT * FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id WHERE subscribe.user_id= '{$user_id}' AND subscribe.new_num > 0 ORDER BY source.last_snap DESC";
        $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public static function update_new_num($subscribe_id, $new_num) {
    	if(empty($subscribe_id)){
    		return;
    	}
        $sql = "UPDATE subscribe SET new_num = {$new_num} WHERE subscribe_id = '{$subscribe_id}'";
        mysqli_query(friendfeed_config::get_both_conn(), $sql);
    }
    public static function count_subscribe_items($user_id, $service = '', $username = '') {
        $sql = "SELECT COUNT(*) AS num FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id INNER JOIN item ON source.source_id = item.source_id WHERE subscribe.user_id = '{$user_id}' AND subscribe.type = '" . self::TYPE_PRIVATE . "'";
        if ($service !== '') {
            $sql .= " AND source.service = '{$service}'";
        }
        if ($username !== '') {
            $username = addslashes($username);
            $sql .= " AND source.username = '{$username}'";
        }
        $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
        $row = mysqli_fetch_assoc($rs);
        return $row['num'];
    }
    public static function get_items_by_item_ids(array $item_ids) {
        $item_ids = implode(', ', $item_ids);
        $sql = "SELECT * FROM item WHERE item_id IN ({$item_ids})";
        $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
        $items = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $items[] = $row;
        }
        return $items;
    }
    public static function get_new_changed_sources($page_size, $page) {
        $conn = friendfeed_config::get_read_conn();
        $base_offset = ($page - 1) * $page_size;
        $sql = "SELECT * FROM source WHERE is_new_changed = '1' LIMIT {$base_offset}, {$page_size}";
        $rs = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            if ($row['changes'] !== '') {
                $row['changes'] = explode('|', $row['changes']);
            }
            $rows[] = $row;
        }
        return $rows;
    }
    public static function get_subscribe_by_source_id($source_id) {
        $conn = friendfeed_config::get_read_conn();
        $sql = "SELECT * FROM subscribe WHERE source_id = '{$source_id}'";
        $rs = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public static function deal_source_over($source_id) {
        $conn = friendfeed_config::get_both_conn();
        $sql = "UPDATE source SET is_new_changed = '0', changes = '' WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
    }
    /**
     * 获取订阅物件
     *
     * @param  array $args - page_size => 页大小，page => 当前页，service => 服务名（已定义的常量），username => 用户名
     */
    public static function get_subscribe_items($user_id, array $args = array()) {
        return self::get_items($user_id, self::TYPE_PRIVATE, $args);
    }
    /**
     * 获取共享物件
     *
     * @param  array $args - page_size => 页大小，page => 当前页，service => 服务名（已定义的常量），username => 用户名
     */
    public static function get_outer_share_items($user_id, array $args = array()) {
        return self::get_items($user_id, self::TYPE_EXTSHARE, $args);
    }
    /**
     * 获取订阅订源
     *
     * @param  array $args - page_size => 页大小，page => 当前页
     */
    public static function get_subscribe_sources($user_id, array $args = array()) {
        return self::get_sources($user_id, self::TYPE_PRIVATE, $args);
    }
    /**
     * 获取共享订源
     *
     * @param  array $args - page_size => 页大小，page => 当前页
     */
    public static function get_outer_share_sources($user_id, array $args = array()) {
        return self::get_sources($user_id, self::TYPE_EXTSHARE, $args);
    }
    /**
     * 添加订阅
     *
     * @param  string $service - 常量，如：friendfeed::BLOG_163
     */
    public static function add_subscribe($user_id, $service, $username, $alias) {
        return self::_add_subscribe($user_id, $service, $username, 0, self::TYPE_PRIVATE, $alias);
    }
    /**
     * 更新订源
     */
    public static function snap($service, $username) {
        require_once dirname(__FILE__) . '/rss_parser.php';
        require_once dirname(__FILE__) . '/snap.php';
        $function = 'snap_' . $service . '_item';
        $username = addslashes($username);
        $rs = mysqli_query(friendfeed_config::get_read_conn(), "SELECT * FROM source WHERE service = '{$service}' AND username = '{$username}'");
        $row = mysqli_fetch_assoc($rs);
        if ($row === null) {
            return false;
        }
        $username = $row['username'];
        $source_id = $row['source_id'];
        return $function(stripslashes($username), $source_id);
    }
    /**
     * 删除订阅
     *
     * @param  string $service - 常量，如：friendfeed::BLOG_163
     */
    public static function del_subscribe($user_id, $service, $username) {
        return self::_del_subscribe($user_id, $service, $username, self::TYPE_PRIVATE);
    }
    /**
     * 添加共享
     *
     * @param  string $service - 常量，如：friendfeed::BLOG_163
     */
    public static function add_outer_share($user_id, $service, $username, $alias) {
        return self::_add_subscribe($user_id, $service, $username, $user_id, self::TYPE_EXTSHARE, $alias);
    }
    /**
     * 删除共享
     *
     * @param  string $service - 常量，如：friendfeed::BLOG_163
     */
    public static function del_outer_share($user_id, $service, $username) {
        return self::_del_subscribe($user_id, $service, $username, self::TYPE_EXTSHARE);
    }
    private static function get_sources($user_id, $type, array $args = array()) {
        $conn = friendfeed_config::get_read_conn();
        $sql = "SELECT source.*,subscribe.alias alias,subscribe.new_num new_num,subscribe.subscribe_id subscribe_id FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id WHERE subscribe.user_id = '{$user_id}' AND subscribe.type = '{$type}' ORDER BY subscribe.new_num DESC,source.source_id DESC";
        if (isset($args['page_size'])) {
            if (!isset($args['page'])) {
                throw new Exception("必须同时指定页大小和当前页");
            }
            $base_offset = ($args['page'] - 1) * $args['page_size'];
            $sql .= " LIMIT {$base_offset}, {$args['page_size']}";
        }
        $rs = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
    private static function get_items($user_id, $type, array $args = array()) {
        $conn = friendfeed_config::get_read_conn();
        $sql = "SELECT source.service, source.username, source.tx_userid as user_id,subscribe.alias alias,item.* FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id INNER JOIN item ON source.source_id = item.source_id WHERE subscribe.user_id = '{$user_id}' AND subscribe.type = '{$type}'";
        if (isset($args['service'])) {
            $sql .= " AND source.service = '{$args['service']}'";
        }
        if (isset($args['username'])) {
            $args['username'] = addslashes($args['username']);
            $sql .= " AND source.username = '{$args['username']}'";
        }
        $sql .= " ORDER BY item.pubdate DESC";
        if (isset($args['page_size'])) {
            if (!isset($args['page'])) {
                throw new Exception("必须同时指定页大小和当前页");
            }
            $base_offset = ($args['page'] - 1) * $args['page_size'];
            $sql .= " LIMIT {$base_offset}, {$args['page_size']}";
        }
        $rs = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
    private static function _del_subscribe($user_id, $service, $username, $type) {
        $conn = friendfeed_config::get_both_conn();
        $username = addslashes($username);
        // 寻找是否有该订源
        $rs = mysqli_query($conn, "SELECT * FROM source WHERE service = '{$service}' AND username = '{$username}'");
        $row = mysqli_fetch_assoc($rs);
        if ($row === null) {
            // 没有就返回
            return;
        }
        $source_id = $row['source_id'];
        // 删除订阅
        mysqli_query($conn, "DELETE FROM subscribe WHERE user_id = '{$user_id}' AND source_id = '{$source_id}' AND type = '{$type}'");
        // 检查剩下的订阅数
        $rs = mysqli_query($conn, "SELECT COUNT(*) AS num FROM subscribe WHERE source_id = '{$source_id}' AND type = '{$type}'");
        $row = mysqli_fetch_assoc($rs);
        $num = (int)$row['num'];
        // 如果没有订阅了
        if ($num === 0) {
            // 就删除订源，及其物件
            mysqli_query($conn, "DELETE FROM source WHERE source_id = '{$source_id}'");
            mysqli_query($conn, "DELETE FROM item WHERE source_id = '{$source_id}'");
        }
    }
    private static function _add_subscribe($user_id, $service, $username, $tx_userid, $type, $alias) {
        $conn = friendfeed_config::get_both_conn();
        $username = addslashes($username);
        $alias = addslashes($alias);
        // 检查是否有人订过该源
        $rs = mysqli_query($conn, "SELECT * FROM source WHERE service = '{$service}' AND username = '{$username}'");
        $row = mysqli_fetch_assoc($rs);
        if ($row === null) {
            // 还没有人订过
            mysqli_query($conn, "INSERT INTO source VALUES (NULL, '{$service}', '{$username}', '{$tx_userid}', '1970-01-01 00:00:00', '0', '')");
            $source_id = mysqli_insert_id($conn);
        } else {
            $source_id = $row['source_id'];
        }

        if (is_array($user_id)) {
            $user_ids = implode(', ', $user_id);
            // 检查是否已经订阅过该源
            $rs = mysqli_query($conn, "SELECT * FROM subscribe WHERE user_id IN ($user_ids) AND source_id = '{$source_id}' AND type = '{$type}'");
            $except_ids = array();
            while ($row = mysqli_fetch_assoc($rs)) {
                $except_ids[] = $row['user_id'];
            }
            $user_ids = array_diff($user_id, $except_ids);
            $inserts = array();
            foreach ($user_ids as $user_id) {
                $inserts[] = "(NULL, '{$user_id}', '{$source_id}', '{$type}', '{$alias}', '0')";
            }
            if ($inserts === array()) {
                return;
            }
            $inserts = implode(', ', $inserts);
            $sql = "INSERT INTO subscribe VALUES {$inserts}";
            mysqli_query($conn, $sql);
        } else {
            // 检查是否已经订阅过该源
            $rs = mysqli_query($conn, "SELECT * FROM subscribe WHERE user_id = '{$user_id}' AND source_id = '{$source_id}' AND type = '{$type}'");
            $row = mysqli_fetch_assoc($rs);
            if ($row === null) {
                // 还没有订阅过
                mysqli_query($conn, "INSERT INTO subscribe VALUES (NULL, '{$user_id}', '{$source_id}', '{$type}', '{$alias}', '0')");
            }
        }
        return $source_id;
    }

    public static function group_subscribe_items($user_id){
        $sql = "SELECT COUNT(*) AS num,source.service service,source.username username,subscribe.alias alias FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id INNER JOIN item ON source.source_id = item.source_id WHERE subscribe.user_id = '{$user_id}' AND subscribe.type = '" . self::TYPE_PRIVATE . "' GROUP BY item.source_id ORDER BY num DESC";
        $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
        $groups = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $groups[] = $row;
        }
        return $groups;
    }

    public static function group_subscribe_by_userid($user_id){
        if(!is_array($user_id)){
            $user_id = array($user_id);
        }
        $num = count($user_id);
        $groups = array();
        for($offset=0;$offset < $num;){//1000个一次
            $ids = array_slice($user_id,$offset,1000);
            if(count($ids)){
                $ids = implode(",",$ids);
                $sql = "SELECT user_id,COUNT(1) c,MAX(subscribe_id) subscribe_id FROM subscribe INNER JOIN source ON subscribe.source_id = source.source_id INNER JOIN item ON source.source_id = item.source_id WHERE subscribe.user_id IN ({$ids}) AND subscribe.type = '" . self::TYPE_PRIVATE . "' GROUP BY user_id ORDER BY subscribe_id DESC";
                $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
                while ($row = mysqli_fetch_assoc($rs)) {
                    $groups[] = $row;
                }
            }
            $offset += 1000;
        }
        return $groups;
    }

    public static function get_source_by_id($source_id){
        $sql = "SELECT * FROM source WHERE source_id={$source_id}";
        $rs = mysqli_query(friendfeed_config::get_read_conn(), $sql);
        while ($row = mysqli_fetch_assoc($rs)) {
            return $row;
        }
        return null;
    }
}
