<?php
	session_start();
	include_once("./common.php");
	include_once("./logic.php");
	
    // パラメータチェック
	if (!isset($_GET["id"])) {
		Error(BAD_PARAMETA_ERROR);
	}
	
	// DISC ID
	$id = $_GET["id"];
	
	$logic = new Logic();
	$request["id"] = $id;
	$result = $logic->SelectDetailView($request);

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
    <title>CD Database [参照] : <?php echo $result[0]["DISC_TITLE_JP"]; ?></title>

</head>

<body>

<div class="main">

<div class="common_header">CD Collection Database</div>

<?php
	OutputHeaderMenu();

	echo "<div class=\"mode_title\">■ データ参照</div>\n\n";
	
    echo "<div class=\"edit_form\">\n\n";
    
    echo "<table class=\"view_table\">\n";
    echo "<tr>\n";
    echo "    <th>タイトル</th>\n";
    echo "    <td>".stripslashes($result[0]["DISC_TITLE_JP"])."</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>アーティスト</th>\n";
    echo "    <td>".stripslashes($result[0]["ARTIST_NAME"])."</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>カタログ番号</th>\n";
    echo "    <td>".$result[0]["CATALOG_NO"]."</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>発売日</th>\n";
    echo "    <td>".$result[0]["RELEASE_DATE"]."</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "    <th>備考</th>\n";
    echo "    <td>".stripslashes($result[0]["REMARK"])."</td>\n";
    echo "</tr>\n";
    echo "</table>\n\n";
    
    echo "<span id=\"errmes\" class=\"errmes\"></span>\n\n";
    
    echo "</div>\n\n";

	OutputFooter();

?>

</div>
	
</body>
</html>