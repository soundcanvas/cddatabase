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
	
	// 登録するデータを取得
	$request["disc_id"]      = $_POST["id"];
	$request["artist_id"]    = $_POST["artist"];
	$request["title"]        = trim($_POST["title"]);
	$request["title_jp"]     = trim($_POST["title_jp"]);
	$request["release_date"] = trim($_POST["release_date"]);
	$request["update_date"]  = date("Y-m-d");
	$request["regist_date"]  = date("Y-m-d");
	$request["catalog_no"]   = trim($_POST["catalog_no"]);
	$request["remark"]       = trim($_POST["remark"]) ? $_POST["remark"] : null;
	
	// 値チェック
    if ($mode != MODE_DELETE) {
    	if ($request["title_jp"] == "") {
	    	Error(TITLE_NOTEXIST_ERROR);
	    	exit;
    	}
    	if (!DateCheck($request["release_date"])) {
	    	Error(DATE_FORMAT_ERROR);
	    	exit;
    	}
    }
	
	// DB更新ロジック
	$logic = new Logic();
	
	if ($mode == MODE_REGIST) {
		// 登録モード
		$logic->InsertEditCommand($request);
		
	} elseif ($mode == MODE_EDIT) {
		// 修正モード
		$logic->UpdateEditCommand($request);
		
	} elseif ($mode == MODE_DELETE) {
		// 削除モード
		$logic->DeleteEditCommand($request);
	} else {
		// こんなとこに来るはずがない！
		Error(BAD_PARAMETA_ERROR);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="ja" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	
	<meta http-equiv="Refresh" content="3;URL=./index.php">
		
	<script type="text/javascript" src="./javascript.js"></script>
    <link rel=stylesheet type=text/css href="style.css">
	
<?php
	if ($mode == MODE_REGIST) {
		echo "    <title>CD Database [登録完了]</title>";
	} elseif ($mode == MODE_EDIT) {
		echo "    <title>CD Database [更新完了]</title>";
	} elseif ($mode == MODE_DELETE) {
		echo "    <title>CD Database [削除完了]</title>";
	}
?>

</head>

<body>

<div class="main">

<?php
	if ($mode == MODE_REGIST) {
		echo "<h3>データベースに登録しました。</h3>";
	} elseif ($mode == MODE_EDIT) {
		echo "<h3>データベースを更新しました。</h3>";
	} elseif ($mode == MODE_DELETE) {
		echo "<h3>データベースから削除しました。</h3>";
	}
	OutputFooter();
?>

</div>
	
</body>
</html>