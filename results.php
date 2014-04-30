<html>
	<head>
		<title>Search results</title>
		<style>
			
		</style>
	</head>
	<body>
		<?php

			$command = "python sindice.py";
			$keyword = $_GET['keyword'];
			$type = $_GET['type'];
			$page = $_GET['page'];
                        $filter = $_GET['filter'];
                        $labels = "";
                        $labels_param = "";
                        if (isset($_GET['mode'])) {
                            $labels = "labels";
                            $labels_param = "&mode=labels";
                        }
			$output = `$command $keyword $type $page $filter $labels`;                        
			$json = json_decode($output, true);
                        $num_results = $json['totalResults'];
                        
                        
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
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=1$labels_param'>first</a> "; 
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=$previous$labels_param'>previous</a> ";
                            } else {
                                echo "first ";
                                echo "previous ";
                            }
                            if ($page < $num_pages) {
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=$next$labels_param'>next</a> ";
                                echo "<a href='results.php?keyword=$keyword&type=$type&page=$num_pages$labels_param'>last</a>";
                            } else {
                                echo "next ";
                                echo "last";
                            }
                            echo "<br><a href='index.php'>back to search</a>";
                        } else {
                            if (count($json) == 0) {
                                echo "No matching resources found.";
                            } else {
                                session_start();
                                $_SESSION['results'] = $json;
                                echo "Choose ontology: <br>";
                                foreach(array_keys($json) as $k) {
                                    echo "<br><a href='results.php?keyword=$keyword&type=$type&page=1&filter=$k$labels_param'>$k</a>";
                                    echo " <a href='download.php?domain=$k'>download</a>";
                                }
                            }
                            echo "<br><br><a href='index.php'>back to search</a>";                            
                        }

		?>
	</body>
</html>
