<html>
	<head>
		<title>Sindice Terminal Enhancement</title>
	</head>
	<body>
		<div id="form">
		<form action="results.php" method="get">
			<p>search term: <input type="text" name="keyword" /></p>
			<p>search for <br>
    			<input type="radio" name="type" value="key" checked> resources containing search term<br>
    			<input type="radio" name="type" value="sub"> subclasses of those resources<br>
    			<input type="radio" name="type" value="super"> superclasses of those resources
                        <input type="hidden" name="page" value="-1">                                
  			</p>
			<p><input type="submit" value="search"/></p>
		</form>
		</div>
	</body>
</html>
