<?php
	session_start();
	include_once("./common.php");
	
	$errMes = $_SESSION["ERROR_MESSAGE"];
	$_SESSION["ERROR_MESSAGE"] = "";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="ja" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	
	<meta http-equiv="Refresh" content="10;URL=./index.php">
		
	<script type="text/javascript" src="./javascript.js"></script>
    <link rel=stylesheet type=text/css href="style.css">
		
	<title>CD Database [エラー]</title>
</head>

<body>

<h3>エラーです...</h3>
<h5>（10秒後にトップページに戻ります）</h5>
	
<div class="error"><?php echo $errMes; ?></div>

<?php
	OutputFooter();	
?>
</body>
</html>
