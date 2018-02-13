<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">
</head>
<body>
	<?php
		$str = "qwertyuiopasdfghjklzxcvbnm";
		$strlen = strlen($str);
		$strlen;
		if($strlen > 15) {
			$str1 = substr($str, 0, 14);
			$str1 .= "\n".substr($str, 15, $strlen);
		}
		echo $str1;
	?>
	<script src="jquery-3.2.0.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>  
</body>
</html>