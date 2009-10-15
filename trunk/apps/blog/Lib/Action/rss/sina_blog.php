<?php
require_once dirname(__FILE__) . '/snap.php';

while (true) {
    $conn = friendfeed_config::get_both_conn();
    $rs = mysqli_query($conn, "SELECT * FROM source WHERE service = 'sina_blog'");
    $sources = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $sources[] = $row;
    }
    foreach ($sources as $source) {
        snap_sina_blog_item($source['username'], $source['source_id']);
    }
    $now = date('Y-m-d H:i:s');
    mysqli_query($conn, "UPDATE source SET last_snap = '{$now}' WHERE service = 'sina_blog'");
    break;
}
