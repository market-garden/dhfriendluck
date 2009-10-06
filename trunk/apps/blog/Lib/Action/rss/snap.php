<?php
if (!defined('PROJECT_ROOT_DIR')) {
    define('PROJECT_ROOT_DIR', dirname(__FILE__));
}
require_once PROJECT_ROOT_DIR . '/config.php';
require_once dirname(__FILE__) . '/rss_parser.php';

//snap_163_blog_item('tianchunbinghe', 2);
//snap_baidu_space_item('thinkinginlamp', 24);
//snap_msn_space_item('rickscafe06', 25);


function snap_163_blog_item($username, $source_id) {
    $content = file_get_contents("http://{$username}.blog.163.com/rss/");
    if (strpos($content, '<item') === false) {
        return false;
    }
    $content = iconv('GBK', 'UTF-8', $content);
    $content = str_replace('<?xml version="1.0" encoding="GBK" ?>', '<?xml version="1.0" encoding="UTF-8" ?>', $content);
    $rss = new MagpieRSS($content, 'UTF-8', 'UTF-8', false);
    $conn = friendfeed_config::get_both_conn();
    $snapday = date('Y-m-d H:i:s');
    array_pop($rss->items);
    $new_links = array();
    foreach ($rss->items as $item) {
        $new_links[] = $item['link'];
    }
    $sql = "SELECT * FROM item WHERE source_id = '{$source_id}'";
    $rs = mysqli_query($conn, $sql);
    $old_links = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $old_links[] = $row['link'];
    }
    $links = array_diff($new_links, $old_links);
    $change_ids = array();
    foreach ($rss->items as $item) {
        if (in_array($item['link'], $links)) {
            $item['title'] = addslashes($item['title']);
            $item['description'] = addslashes($item['description']);
            $item['pubdate'] = strtotime($item['pubdate']);
            $sql = "INSERT INTO item VALUES (NULL, '{$source_id}', '{$snapday}', '{$item['pubdate']}', '0', '{$item['title']}', '{$item['link']}', '".FEED_BLOG."', '{$item['description']}', '')";
            mysqli_query($conn, $sql);
            $change_ids[] = mysqli_insert_id($conn);
        }
    }
    if ($change_ids !== array()) {
        $new_num = count($change_ids);
        $change_ids = implode('|', $change_ids);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE source SET is_new_changed = '1', changes = '{$change_ids}',last_snap = '{$now}' WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE subscribe SET new_num = new_num + {$new_num} WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
    }
    return true;
}
function snap_baidu_space_item($username, $source_id) {
    $content = file_get_contents("http://hi.baidu.com/{$username}/rss");
    if (strpos($content, '<item') === false) {
        return false;
    }
    $content = iconv('GBK', 'UTF-8', $content);
    $content = str_replace('<?xml version="1.0" encoding="gb2312"?>', '<?xml version="1.0" encoding="UTF-8" ?>', $content);
    $rss = new MagpieRSS($content, 'UTF-8', 'UTF-8', false);
    $conn = friendfeed_config::get_both_conn();
    $snapday = date('Y-m-d H:i:s');
    $new_links = array();
    foreach ($rss->items as $item) {
        $new_links[] = $item['link'];
    }
    $sql = "SELECT * FROM item WHERE source_id = '{$source_id}'";
    $rs = mysqli_query($conn, $sql);
    $old_links = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $old_links[] = $row['link'];
    }
    $links = array_diff($new_links, $old_links);
    $change_ids = array();
    foreach ($rss->items as $item) {
        if (in_array($item['link'], $links)) {
            $item['title'] = addslashes($item['title']);
            $item['description'] = addslashes($item['description']);
            $item['pubdate'] = strtotime($item['pubdate']);
            $sql = "INSERT INTO item VALUES (NULL, '{$source_id}', '{$snapday}', '{$item['pubdate']}', '0', '{$item['title']}', '{$item['link']}', '".FEED_BLOG."', '{$item['description']}', '')";
            mysqli_query($conn, $sql);
            $change_ids[] = mysqli_insert_id($conn);
        }
    }
    if ($change_ids !== array()) {
        $new_num = count($change_ids);
        $change_ids = implode('|', $change_ids);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE source SET is_new_changed = '1', changes = '{$change_ids}',last_snap = '{$now}' WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE subscribe SET new_num = new_num + {$new_num} WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
    }
    return true;
}
function snap_msn_space_item($username, $source_id) {
    $content = file_get_contents('http://' . $username . '.spaces.live.com/feed.rss');
    if ($content === false) {
        return false;
    }
    $err = error_reporting();
    error_reporting(0);
    $rss = new MagpieRSS($content, 'UTF-8', 'UTF-8', false);
    error_reporting($err);
    $conn = friendfeed_config::get_both_conn();
    $snapday = date('Y-m-d H:i:s');
    $new_links = array();
    foreach ($rss->items as $item) {
        $new_links[] = $item['link'];
    }
    $sql = "SELECT * FROM item WHERE source_id = '{$source_id}'";
    $rs = mysqli_query($conn, $sql);
    $old_links = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $old_links[] = $row['link'];
    }
    $links = array_diff($new_links, $old_links);
    $change_ids = array();
    foreach ($rss->items as $item) {
        if (in_array($item['link'], $links)) {
            $item['title'] = addslashes($item['title']);
            $item['description'] = addslashes($item['description']);
            $item['pubdate'] = strtotime($item['pubdate']);
            $sql = "INSERT INTO item VALUES (NULL, '{$source_id}', '{$snapday}', '{$item['pubdate']}', '0', '{$item['title']}', '{$item['link']}', '".FEED_BLOG."', '{$item['description']}', '')";
            mysqli_query($conn, $sql);
            $change_ids[] = mysqli_insert_id($conn);
        }
    }
    if ($change_ids !== array()) {
        $new_num = count($change_ids);
        $change_ids = implode('|', $change_ids);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE source SET is_new_changed = '1', changes = '{$change_ids}',last_snap = '{$now}' WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE subscribe SET new_num = new_num + {$new_num} WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
    }
    return true;
}

function snap_sina_blog_item($username, $source_id) {
    $content = file_get_contents('http://blog.sina.com.cn/rss/' . $username . '.xml');
    if ($content === false || $content === '') {
        return false;
    }
    $err = error_reporting();
    error_reporting(0);
    $rss = new MagpieRSS($content, 'UTF-8', 'UTF-8', false);
    error_reporting($err);
    $conn = friendfeed_config::get_both_conn();
    $snapday = date('Y-m-d H:i:s');
    $new_links = array();
    foreach ($rss->items as $item) {
        $new_links[] = $item['link'];
    }
    $sql = "SELECT * FROM item WHERE source_id = '{$source_id}'";
    $rs = mysqli_query($conn, $sql);
    $old_links = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $old_links[] = $row['link'];
    }
    $links = array_diff($new_links, $old_links);
    $change_ids = array();
    foreach ($rss->items as $item) {
        if (in_array($item['link'], $links)) {
            $item['title'] = addslashes($item['title']);
            $item['description'] = addslashes($item['description']);
            $item['pubdate'] = strtotime($item['pubdate']);
            $sql = "INSERT INTO item VALUES (NULL, '{$source_id}', '{$snapday}', '{$item['pubdate']}', '0', '{$item['title']}', '{$item['link']}', '".FEED_BLOG."', '{$item['description']}', '')";
            mysqli_query($conn, $sql);
            $change_ids[] = mysqli_insert_id($conn);
        }
    }
    if ($change_ids !== array()) {
        $new_num = count($change_ids);
        $change_ids = implode('|', $change_ids);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE source SET is_new_changed = '1', changes = '{$change_ids}',last_snap = '{$now}' WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE subscribe SET new_num = new_num + {$new_num} WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
    }
    return true;
}
function snap_sohu_blog_item($username, $source_id) {
    $content = file_get_contents('http://' . $username . '.blog.sohu.com/rss');
    if (strpos($content, '<html') !== false) {
        return false;
    }
    $rss = new MagpieRSS($content, 'UTF-8', 'UTF-8', false);
    $conn = friendfeed_config::get_both_conn();
    $snapday = date('Y-m-d H:i:s');
    $new_links = array();
    foreach ($rss->items as $item) {
        $new_links[] = $item['link'];
    }
    $sql = "SELECT * FROM item WHERE source_id = '{$source_id}'";
    $rs = mysqli_query($conn, $sql);
    $old_links = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $old_links[] = $row['link'];
    }
    $links = array_diff($new_links, $old_links);
    $change_ids = array();
    foreach ($rss->items as $item) {
        if (in_array($item['link'], $links)) {
            $item['title'] = addslashes($item['title']);
            $item['description'] = addslashes($item['description']);
            $item['pubdate'] = strtotime($item['pubdate']);
            $sql = "INSERT INTO item VALUES (NULL, '{$source_id}', '{$snapday}', '{$item['pubdate']}', '0', '{$item['title']}', '{$item['link']}', '".FEED_BLOG."', '{$item['description']}', '')";
            mysqli_query($conn, $sql);
            $change_ids[] = mysqli_insert_id($conn);
        }
    }
    if ($change_ids !== array()) {
        $new_num = count($change_ids);
        $change_ids = implode('|', $change_ids);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE source SET is_new_changed = '1', changes = '{$change_ids}',last_snap = '{$now}' WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE subscribe SET new_num = new_num + {$new_num} WHERE source_id = '{$source_id}'";
        mysqli_query($conn, $sql);
    }
    return true;
}

