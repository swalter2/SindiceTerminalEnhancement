<html>
	<head>
		<title>Search results</title>
		<style>
			
		</style>
	</head>
	<body>
		<?php
                        session_start();
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
                        if ($page != "-1" || (isset($_SESSION['cmd']) && $_SESSION['cmd'] != $command)) {
                            $output = `$command`;                        
                            $json = json_decode($output, true);
                        } else {
                            if (isset($_SESSION['results'])) {
                                $json = $_SESSION['results'];
                            } else {
                                $output = `$command`;                        
                                $json = json_decode($output, true);
                            }
                        }
			
                        $num_results = 0;
                        
                        if ($json != NULL && array_key_exists('totalResults', $json)) {
                            $num_results = $json['totalResults'];
                        } else {
                            $num_results = 0;
                        }                
                        
                        if ($page != "-1") {                            
                            echo "Search results for keyword <b>$keyword</b> <br>";
                            echo "domain: <b>$filter</b><br>";
                            echo "total: $num_results <br><br>page $page <br><br>";
                            foreach($json['entries'] as $i) {
                                $filename = $i['link'];
                                $link = "<a href='$filename'> $filename </a><br />";
                                echo $link;
                                echo "<br>";
                            }
                            $num_pages = min(100, ceil($num_results/10));
                            $next = intval($page) + 1;
                            $previous = intval($page) - 1;
                            echo "<br>";
                            if ($page > 1) { 
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=1$filter_param$labels_param'>first</a> "; 
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=$previous$filter_param$labels_param'>previous</a> ";
                            } else {
                                echo "first ";
                                echo "previous ";
                            }
                            if ($page < $num_pages) {
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=$next$filter_param$labels_param'>next</a> ";
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=$num_pages$filter_param$labels_param'>last</a>";
                            } else {
                                echo "next ";
                                echo "last";
                            }
                            echo "<br><a href='index.php'>back to search</a>";
                        } else {
                            if (count($json) == 0) {
                                echo "No matching resources found.";
                            } else {
                                $_SESSION['results'] = $json;
                                $_SESSION['cmd'] = $command;
                                echo "Choose ontology: <br>";
                                foreach(array_keys($json) as $k) {
                                    echo "<br><a href='results.php?keyword=$keyword&type=$type&page=1&filter=$k$labels_param'>$k</a>";
                                    echo " <a href='download.php?keyword=$keyword&type=$type&page=-1&filter=$k$labels_param'>download</a>";
                                }
                            }
                            echo "<br><br><a href='index.php'>back to search</a>";  
                        }

		?>
	</body>
</html>
