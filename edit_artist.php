<?php
	session_start();
	include_once("./common.php");
	include_once("./logic.php");
	
	// ログインチェック
	if (!IsLogin()) {
	    Error(NOT_LOGIN_ERROR);
	}
	
	$logic = new Logic();
	$artist = $logic->SelectArtistMaster();
	$cnt = count($artist);

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
    
	<title>CD Database [アーティスト管理]</title>

</head>

<body>

<div class="main">

<div class="common_header">CD Collection Database</div>

<?php
	OutputHeaderMenu();

	echo "<div class=\"mode_title\">■ アーティスト管理</div>\n\n";
	
    echo "<div class=\"artist_add_form\">\n\n";
    
    echo "<form method=\"post\" action=\"edit_artist_update.php\">\n\n";
    echo "<h4>アーティスト追加</h4>\n";
    
    echo "<table class=\"atadd_table\">\n<tr>\n";
    echo "<th>名前</th><td><input maxlength=\"50\" type=\"text\" name=\"add_name\" class=\"atname_txtbox\" /></td>\n";
    echo "</tr>\n<tr>\n";
    echo "<th>英名</th><td><input maxlength=\"50\" type=\"text\" name=\"add_name_e\" class=\"atname_txtbox\" /></td>\n";
    echo "</tr>\n</table>\n\n";
    
    echo "<div class=\"atadd_btn\"><input type=\"submit\" value=\"追加する\"></div>";
	echo "<input type=\"hidden\" name=\"mode\" value=\"".MODE_ARTIST_ADD."\">\n";
    
	echo "</form>\n\n";

    echo "</div>\n\n";
    
    echo "<div class=\"artist_edit_form\">\n\n";
    
    echo "<form method=\"post\" action=\"edit_artist_update.php\">\n\n";
    echo "<h4>アーティスト編集</h4>\n";
    
    echo "<table class=\"atedit_table\">\n";
    echo "<tr><th>名前</th><th>英名</th><th>レコード数</th></tr>\n";
    
    foreach ($artist as $row) {
	    echo "<tr>\n";
    	echo "    <td><input class=\"atname_txtbox\" maxlength=\"50\" type=\"text\" name=\"nameJ_".$row["ARTIST_ID"]."\" value=\"".stripslashes($row["ARTIST_NAME"])."\" /></td>\n";
    	echo "    <td><input class=\"atname_txtbox\" maxlength=\"50\" type=\"text\" name=\"nameE_".$row["ARTIST_ID"]."\" value=\"".stripslashes($row["ARTIST_NAME_E"])."\" /></td>\n";
    	echo "    <td class=\"art_count\">".$row["USE_COUNT"]."</td>\n";
	    echo "</tr>\n";
    }
    
    echo "</table>\n\n";
    
    echo "<div class=\"atedit_btn\"><input type=\"submit\" value=\"更新する\"></div>\n\n";
	echo "<input type=\"hidden\" name=\"mode\" value=\"".MODE_ARTIST_EDIT."\">\n";
    
	echo "</form>\n\n";
	
	echo "</div>\n\n";

	OutputFooter();

?>

</div>
	
</body>
</html>