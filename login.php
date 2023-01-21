<?php
	session_start();
	include_once("./common.php");
	
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		
		// 既にログインしている
		if (IsLogin()) {
			Error(ALREADY_LOGIN_ERROR);
			exit;
		}
		
		// POSTだったら認証する
		$id = $_POST["id"];
		$psw = $_POST["pw"];
		
		if ($id === "********" && $psw === "@@@@@@@@") {
			$_SESSION["LOGIN"] = 1;
			header("Location: index.php");
			exit;
		}
		
		$message = "IDかパスワードが違うんじゃないの？";
	} else {
		// ログアウト要求なら
		if (isset($_GET["logout"])) {
			// ログアウトする
			$_SESSION["LOGIN"] = 0;
			header("Location: index.php");
			exit;
		}
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
	
	<script type="text/javascript" src="./javascript.js"></script>
    <link rel=stylesheet type=text/css href="style.css">
	
	<title>CD Database [ログイン]</title>
</head>

<body>

<div class="main">

<div class="common_header">CD Collection Database</div>

<?php OutputHeaderMenu(); ?>
	
<div class="mode_title">■ ログイン</div>

<form method="post" action="login.php">

<div class="login_form">
<table class="login_table">
	<tr><th>ID</td><td><input type="text" class="login_txtbox" name="id" id="txt_id" /></td></tr>
	<tr><th>PASS</td><td><input type="password" class="login_txtbox" name="pw" id="txt_psw" /></td></tr>
</table>
<div class="error_msg"><?php echo $message; ?></div>
<div class="login_btn"><input type="submit" value="ログイン" id="btn_submit" /></div>
</div>

</form>

<?php OutputFooter(); ?>

</div>

</body>
</html>