<?php
	session_start();
	include_once("./common.php");
	include_once("./logic.php");
	
	// ログインチェック
	if (!IsLogin()) {
	    Error(NOT_LOGIN_ERROR);
	}
    // パラメータチェック
	if (!isset($_GET["mode"])) {
		Error(BAD_PARAMETA_ERROR);
	}
	
	// 画面MODE
	$mode = $_GET["mode"];
	
	// DISC ID
	$id = isset($_GET["id"]) ? $_GET["id"] : "";
	
	$logic = new Logic();
	$request["id"] = $id;
	$result = $logic->SelectEditView($request);
	$artist = $logic->SelectArtistMaster();
	$cnt = count($result);

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
    	
<?php
if ($mode == MODE_EDIT) {
	echo "	<title>CD Database [編集] : ".$result[0]["DISC_TITLE"]."</title>\n\n";
} else {
	echo "	<title>CD Database [登録]</title>\n\n";
}
?>

</head>

<body>

<div class="main">

<div class="common_header">CD Collection Database</div>

<?php
	OutputHeaderMenu();

    echo "<div class=\"mode_title\">";
	if ($mode == MODE_EDIT) {
		echo "■ データ更新\n\n";
	} else {
		echo "■ データ新規登録\n\n";
	}
	echo "</div>\n\n";
	
    echo "<div class=\"edit_form\">\n\n";
    
    echo "<form method=\"post\" action=\"update.php\">\n\n";
    
    echo "<table class=\"edit_table\">\n";
    echo "<tr>\n";
    echo "    <th>タイトル</th>\n";
    echo "    <td><input class=\"title_txtbox\" maxlength=\"128\" type=\"text\" id=\"txt_title_jp\" name=\"title_jp\" value=\"".stripslashes($result[0]["DISC_TITLE_JP"])."\" /></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>タイトル英名</th>\n";
    echo "    <td><input class=\"title_txtbox\" maxlength=\"128\" type=\"text\" id=\"txt_title\" name=\"title\" value=\"".stripslashes($result[0]["DISC_TITLE"])."\" /></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>アーティスト</th>\n";
    echo "    <td>\n";
    echo "    <select name=\"artist\" id=\"slct\">\n";
    foreach ($artist as $row) {
    	if ($result[0]["ARTIST_ID"] == $row["ARTIST_ID"]) {
	    	echo "        <option value=\"".$row["ARTIST_ID"]."\" selected=\"selected\">".TrimArtist(stripslashes($row["ARTIST_NAME"]))."</option>\n";
	    } else {
	    	echo "        <option value=\"".$row["ARTIST_ID"]."\">".TrimArtist(stripslashes($row["ARTIST_NAME"]))."</option>\n";
	    }
    }
    echo "    </select>\n";
    echo "[<a href=\"./edit_artist.php\">編集</a>]";
    echo "    </td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>カタログ番号</th>\n";
    echo "    <td><input class=\"ctlgno_txtbox\" maxlength=\"15\" type=\"text\" name=\"catalog_no\" value=\"".$result[0]["CATALOG_NO"]."\" /></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>発売日</th>\n";
    echo "    <td><input class=\"date_txtbox\" maxlength=\"12\" type=\"text\" name=\"release_date\" id=\"txt_rdate\" value=\"".$result[0]["RELEASE_DATE"]."\" /> (YYYY-MM-DD)</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>備考</th>\n";
    echo "    <td><textarea maxlength=\"512\" cols=\"70\" rows=\"4\" class=\"remark_txtarea\" name=\"remark\">".stripslashes($result[0]["REMARK"])."</textarea></td>\n";
    echo "</tr>\n";
    echo "</table>\n\n";
    
    echo "<br />\n\n";
    
    echo "<table padding=\"5px\">\n";
	    echo "<tr>\n";

	if ($mode == MODE_EDIT) {
	    echo "<td>\n";
	    echo "<input type=\"submit\" value=\"更新する\" onclick=\"return EditPageCheck()\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"" .$id. "\">\n";
		echo "<input type=\"hidden\" name=\"mode\" value=\"" .$mode. "\">\n";
		echo "</td>\n";
		echo "</form>\n";
	    echo "<form method=\"post\" action=\"update.php\">\n";
	    echo "<td>\n";
	    echo "  <input type=\"submit\" value=\"削除する\" onclick=\"return ConfirmDelete()\">\n";
	    echo "  <input type=\"hidden\" name=\"id\" value=\"" .$id. "\">\n";
		echo "  <input type=\"hidden\" name=\"mode\" value=\"" .MODE_DELETE. "\">\n";
		echo "</td>\n";
		echo "</form>\n";
	} else {
	    echo "<td>\n";
	    echo "<input type=\"submit\" value=\"登録する\" onclick=\"return EditPageCheck()\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"" .$id. "\">\n";
		echo "<input type=\"hidden\" name=\"mode\" value=\"" .$mode. "\">\n";
		echo "</td>\n";
		echo "</form>\n";
	}

	echo "</tr>\n";
	echo "</table>\n\n";
	
	echo "<div id=\"errmes\" class=\"error_msg\"></div>\n\n";
	
	echo "</div>\n\n";
	
	OutputFooter();

?>

</div>

</body>
</html>