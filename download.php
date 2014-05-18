<?php

    $script = "python sindice.py";
    $keyword = $_GET['keyword'];
    $type = $_GET['type'];
    $page = $_GET['page'];

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
        $filter_param = "&filter=$filter";
    } else {
        $filter = "";
        $filter_param = "";
    }                          
    if (isset($_GET['mode'])) {
        $labels = "labels";
        $labels_param = "&mode=labels";
    } else {
        $labels = "";
        $labels_param = ""; 
    }
    $command = $script." ".$keyword." ".$type." ".$page." ".$filter." ".$labels;
    set_time_limit(0);
    exec($command);
    $name = "results.zip";    

    clearstatcache();
//    header('Content-Type: application/zip');
//    header('Content-disposition: attachment; filename=results.zip');
//    header('Content-Length: '.filesize($name));
//    readfile($name);
//    unlink($name);
    
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"".$name."\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($name));
    ob_end_flush();
    readfile($name);
    unlink($name);
?>