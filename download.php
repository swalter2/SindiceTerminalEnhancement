<?php
    session_start();
    $results = $_SESSION['results'];
    $domain = $_GET['domain'];
    $links = $results[$domain];
    
    $ret = new ZipArchive();
    $name = "results.zip";
    $ret->open($name, ZIPARCHIVE::CREATE);
    
    foreach ($links as $l) {
        $query = "http://api.sindice.com/v2/live?url=".$l;
        $tmp = @file_get_contents(urldecode($query));
        if ($tmp !== FALSE) {
            $json = json_decode($tmp, true);
            $tuples = "";
            if ($json !== NULL) {
                foreach ($json['extractorResults']['metadata']['explicit']['bindings'] as $b) {
                    $s = $b['s']['value'];
                    $p = $b['p']['value'];
                    $o = $b['o']['value'];
                    $t = $s."\t".$p."\t".$o."\n";
                    $tuples = $tuples.$t;
                }
                $n = split("/",$l);
                $ret->addFromString($n[sizeof($n)-1], $tuples);    
            }
        }
        
    }
    
    
    $ret->close();

//    header("Pragma: public");
//    header("Expires: 0");
//    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//    header("Cache-Control: public");
//    header("Content-Description: File Transfer");
//    header("Content-type: application/octet-stream");
//    header("Content-Disposition: attachment; filename=\"".$name."\"");
//    header("Content-Transfer-Encoding: binary");
//    clearstatcache();
//    header("Content-Length: ".filesize($name));
//    readfile($name);
    
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=results.zip');
    header('Content-Length: ' . filesize($name));
    readfile("/var/www/html/".$name);
    unlink("/var/www/html/".$name);
?>