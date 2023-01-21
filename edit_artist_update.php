<?php
	session_start();
	include_once("./common.php");
	include_once("./logic.php");
	
	// ログインチェック
	if (!IsLogin()) {
	    Error(NOT_LOGIN_ERROR);
	}
	// 呼び出し方法確認
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		Error(BAD_PARAMETA_ERROR);
	}
	
	// 登録モード取得
	$mode = $_POST["mode"];
	
	// DB更新ロジック
	$logic = new Logic();
	
	if ($mode == MODE_ARTIST_ADD) {
		// 登録モード
	    $request["add_name"] = trim($_POST["add_name"]);
	    $request["add_name_e"] = trim($_POST["add_name_e"]);
		$logic->InsertArtistCommand($request);
		
	} elseif ($mode == MODE_ARTIST_EDIT) {
		// 修正モード
		foreach ($_POST as $key => $value) {
		    if (strpos($key, "nameJ_") === 0) {
		    	$aid = substr($key, 6);
		    	$name_e_id = "nameE_" . $aid;
		    	$request["artist_name"] = trim($value);
		    	$request["artist_name_e"] = $_POST[$name_e_id];
		    	$request["artist_id"] = $aid;
		    	
		        $logic->UpdateArtistCommand($request);
		    }
		}
		
	} else {
		// こんなとこに来るはずがない！
		Error(BAD_PARAMETA_ERROR);
	}
	
	header("Location: ./edit_artist.php");
?>