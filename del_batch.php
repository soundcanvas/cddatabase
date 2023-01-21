<?php
	session_start();
	include_once("./common.php");
	include_once("./logic.php");

	// DB更新ロジック
	$logic = new Logic();
	$result = $logic->DeleteBatchCommand();
	
	$pointer=fopen("./log/cronlog.txt", "a");
	flock($pointer, LOCK_EX);
	fputs($pointer, date("Y/m/d l H:i:s") . " -> " . $result . "\r\n");
	flock($pointer, LOCK_UN);
	fclose($pointer);

?>