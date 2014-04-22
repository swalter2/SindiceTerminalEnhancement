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
			$output = `$command $keyword $type $page`;		
			
			$json = json_decode($output, true);
			$num_results = $json['totalResults'];

			echo "Search results for keyword <b>$keyword</b> <br> total: $num_results <br><br>page $page <br><br>";
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
				echo "<a href='results.php?keyword=$keyword&type=$type&page=1'>first</a> "; 
				echo "<a href='results.php?keyword=$keyword&type=$type&page=$previous'>previous</a> ";
			} else {
 				echo "first ";
				echo "previous ";
			}
			if ($page < $num_pages) {
				echo "<a href='results.php?keyword=$keyword&type=$type&page=$next'>next</a> ";
				echo "<a href='results.php?keyword=$keyword&type=$type&page=$num_pages'>last</a>";
			} else {
				echo "next ";
				echo "last";
			}
			echo "<br>";
			echo "<a href='index.php'>back to search</a>";

		?>
	</body>
</html>
