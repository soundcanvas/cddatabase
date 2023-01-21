<?php
	session_start();
	include_once("./common.php");
	include_once("./logic.php");
	
	$request["q"] = isset($_GET["q"]) ? $_GET["q"] : "";
	$request["s"] = isset($_GET["s"]) ? $_GET["s"] : 9;
	$request["p"] = isset($_GET["p"]) ? $_GET["p"] : 1;
	
	$logic = new Logic();
	$cnt = $logic->SelectFindViewCount($request);
	$result = $logic->SelectFindView($request);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="ja" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	
	<script type="text/javascript" src="./javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css" />
	
	<title>CD Database</title>
</head>

<body onload="TxtBoxEnableChange()">
	
<div class="main">

<div class="common_header">CD Collection Database</div>

<?php OutputHeaderMenu(); ?>
	
<div class="search_form">
<?php

	echo "<form method=\"get\" action=\"index.php\">\n";
	echo "<select name=\"s\" id=\"slct\" onchange=\"TxtBoxEnableChange()\">\n";
	
	switch ($request["s"]) {
		case 0:
			echo "    <option value=\"0\" selected=\"selected\">タイトル</option>\n";
			echo "    <option value=\"1\">アーティスト</option>\n";
			echo "    <option value=\"9\">ALL</option>\n";
			break;
		case 1:
			echo "    <option value=\"0\">タイトル</option>\n";
			echo "    <option value=\"1\" selected=\"selected\">アーティスト</option>\n";
			echo "    <option value=\"9\">ALL</option>\n";
			break;
		default:
			echo "    <option value=\"0\">タイトル</option>\n";
			echo "    <option value=\"1\">アーティスト</option>\n";
			echo "    <option value=\"9\" selected=\"selected\">ALL</option>\n";
			break;
	}
	echo "</select>\n";
	echo "<input type=\"text\" class=\"search_txtbox\" name=\"q\" id=\"txt_find\" value=\"".$request["q"]."\" />\n";
	echo "<input type=\"submit\" value=\"検索\" id=\"btn_submit\" onclick=\"return FindQueryCheck()\" />\n";
	echo "</form>\n\n";

?>
</div>

<div class="search_result">
	
<?php
	echo $cnt . "件のデータが見つかりました。<br />\n";

	if ($cnt > 0) {
	    
	    echo "<br />\n\n";
	    
	    echo "<table class=\"result_table\">\n";
	    echo "<tr>\n";
	    echo "    <th width=\"280px\">タイトル</th>\n";
	    echo "    <th width=\"145px\">アーティスト</th>\n";
	    echo "    <th width=\"90px\">カタログ番号</th>\n";
	    echo "    <th width=\"75px\">発売日</th>\n";
	    echo "    <th width=\"135px\">備考</th>\n";
	    echo "</tr>\n";
	    
	    $linecnt = 0;
	    foreach ($result as $row) {
	    	
	    	if ($linecnt % 2 == 0) {
	    		// 偶数行
	            echo "<tr class=\"result_even\">\n";
	        } else {
	        	// 奇数行
	            echo "<tr class=\"result_odd\">\n";
	        }
	        
	        $mode = MODE_EDIT;
	        $disc_id = stripslashes($row['DISC_ID']);
	        $disc_title_jp = stripslashes($row['DISC_TITLE_JP']);
	        $artist_name = stripslashes($row['ARTIST_NAME']);
	        $catalog_no = stripslashes($row['CATALOG_NO']);
	        $release = stripslashes($row['RELEASE_DATE']);
	        $remark = stripslashes($row['REMARK']);
	        
	        if (IsLogin()) {
	        	echo "    <td><a href=\"edit.php?mode=$mode&id=$disc_id\" title=\"$disc_title_jp\">" . TrimTitle($disc_title_jp) . "</a></td>\n";
	        } else {
	        	echo "    <td><a href=\"view.php?id=$disc_id\" title=\"$disc_title_jp\">" . TrimTitle($disc_title_jp) . "</a></td>\n";
	        }
	        echo "    <td>" . TrimArtist($artist_name) . "</td>\n";
	        echo "    <td>$catalog_no</td>\n";
	        echo "    <td>$release</td>\n";
	        echo "    <td>" . TrimRemark($remark) . "</td>\n";
	        echo "</tr>\n";
	        $linecnt++;
	    }
	    echo "</table>\n";
	    
		OutputPager($cnt, $request);
	}
?>
</div>

<?php OutputFooter(); ?>

</div>
	
</body>
</html>