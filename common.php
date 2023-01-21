<?php
	
	#===================================================
	# 定数
	#===================================================
	// 接続文字列
	define("DB_DSN", "sqlite:./db/sqlite.db");
	define("DB_USER_ID", "");
	define("DB_PASSWORD", "");
	
	// MESSAGE
	define("SESSION_TIMEOUT",     "長時間操作をしなかったためログアウトしました。");
	define("DB_ACCESS_ERROR",     "データベースのアクセス時にエラーが発生しました。");
	define("DB_FILE_ERROR",       "データベースファイルに異常が発生しました。");
	define("DB_UPDATE_ERROR",     "データベースの更新に失敗しました。");
	define("DB_REGIST_ERROR",     "データベースへの登録に失敗しました。");
	define("NOT_LOGIN_ERROR",     "この操作にはログインが必要です。");
	define("BAD_PARAMETA_ERROR",  "パラメータが不正です。");
	define("DISC_NOTFOUND_ERROR", "ディスク情報が見つかりません。");
	define("ALREADY_LOGIN_ERROR", "すでにログインしています。");
	define("DATE_FORMAT_ERROR",   "不正な日付です。");
	define("TITLE_NOTEXIST_ERROR",   "タイトルを入力してください。");
	
	// MODE
	define("MODE_UNDEF",  0);
	define("MODE_FIND",   1);
	define("MODE_REGIST", 2);
	define("MODE_EDIT",   3);
	define("MODE_DELETE", 4);
	define("MODE_ARTIST_EDIT", 5);
	define("MODE_ARTIST_ADD",  6);
	define("MODE_VIEW",   7);
	
	// ファイル名
	define("TOP_PAGE",    "index.php");
	define("EDIT_PAGE",   "edit.php");
	define("LOGIN_PAGE",  "login.php");
	define("ARTIST_PAGE", "edit_artist.php");
	define("VIEW_PAGE",   "view.php");
	
	// その他
	define("VERSION", "1.02");
	define("MAX_PAGE_RECORD", 30);
	
	#===================================================
	# 関数
	#===================================================
	
	// ログインチェック
	function IsLogin() {
		if (isset($_SESSION["LOGIN"]) && ($_SESSION["LOGIN"] == 1)) {
			return true;
		} else {
			return false;
		}
	}
	
	// エラーページ遷移
	function Error($msg) {
		$_SESSION["ERROR_MESSAGE"] = $msg;
		header("Location: error.php");
	}
	
	// ヘッダーメニュー表示
	function OutputHeaderMenu() {
		$curfile = strtolower($_SERVER["PHP_SELF"]);
		
		echo "<div class=\"top_menu\">[ ";
		
		if (strpos($curfile, TOP_PAGE)) {
			// indexページの場合
			echo "<a href=\"./index.php\">トップページ</a> | ";
			if (IsLogin()) {
			    echo "<a href=\"./edit.php?mode=".MODE_REGIST."\">新規登録</a> | ";
			    echo "<a href=\"./edit_artist.php\">アーティスト管理</a> | ";
			    echo "<a href=\"./login.php?logout\">ログアウト</a>";
			} else {
			    echo "新規登録 | アーティスト管理 | ";
			    echo "<a href=\"./login.php\">ログイン</a>";
			}
			
		} elseif (strpos($curfile, EDIT_PAGE)) {
			// 編集ページの場合
			echo "<a href=\"./index.php\">トップページ</a> | ";
			echo "新規登録 | ";
			echo "<a href=\"./edit_artist.php\">アーティスト管理</a> | ";
			echo "<a href=\"./login.php?logout\">ログアウト</a>";
			
		} elseif (strpos($curfile, VIEW_PAGE)) {
			// 参照ページの場合
			echo "<a href=\"./index.php\">トップページ</a> | ";
			echo "新規登録 | アーティスト管理 | ";
			echo "<a href=\"./login.php\">ログイン</a>";
			
		}  elseif (strpos($curfile, LOGIN_PAGE)) {
			// ログインページの場合
			echo "<a href=\"./index.php\">トップページ</a> | ";
			echo "新規登録 | アーティスト管理 | ログイン";
			
		} elseif (strpos($curfile, ARTIST_PAGE)) {
			// アーティスト管理ページの場合
			echo "<a href=\"./index.php\">トップページ</a> | ";
			echo "<a href=\"./edit.php?mode=".MODE_REGIST."\">新規登録</a> | ";
			echo "アーティスト管理 | ";
			echo "<a href=\"./login.php?logout\">ログアウト</a>";
			
		}

		echo " ]";
		echo "</div>";
	}
	
	// フッター表示
	function OutputFooter() {
		echo "<div class=\"footer\" align=\"center\">\n";
		echo "CD Collection Database " . VERSION . "<br />";
		echo "</div>";
	}
	
	// ページャー
	function OutputPager($resultcount, $request) {
		$maxpage = ceil($resultcount / MAX_PAGE_RECORD);
		
		$tgturl = "./index.php?s=" . $request["s"];
		$curpage = $request["p"];
		
		if ($request["q"] != "") {
			$tgturl .= "&q=" . $request["q"];
		}
		
		// 全件数が最大ページ表示件数より多い場合
		if ($resultcount > MAX_PAGE_RECORD) {
			echo "<br />\n[ ";
			$isecho3r = false; // 3点リーダ出力フラグ
			for ($i = 1; $i < $maxpage + 1; $i++) {
				if ($i < 2 || $i + 1 > $maxpage || ($i > $curpage - 2 && $i < $curpage + 2)) {
					$isecho3r = true;
				    if ($i == $curpage) {
						echo "<b>" . $i . "</b>";
					} else {
						$seturl = $tgturl . "&p=" . $i;
						echo "<a href=\"" . $seturl . "\">" . $i . "</a>";
					}
					echo "</a>";
					if ($i != $maxpage) {
						echo " | ";
					}
				} else {
					if ($isecho3r) {
						echo "… | ";
						$isecho3r = false;
					}
				}
			}
			echo " ]";
		}
	}
	
	// 日付チェック
	function DateCheck($str) {
		if ($str != "") {
			if (!ereg("[0-9]{4}-[0-1][0-9]-[0-3][0-9]", $str)) {
				return false;
			} else {
		        $dt = explode("-", $str);
				if (!checkdate($dt[1], $dt[2], $dt[0])) {
					return false;
				}
			}
		}
		return true;
	}
	
	// 長いアーティスト名を省略する
	function TrimArtist($str) {
		return mb_strimwidth($str, 0, 18, "...", UTF8);
	}
	
	// 長いCDタイトルを省略する
	function TrimTitle($str) {
		return mb_strimwidth($str, 0, 44, "...", UTF8);
	}
	
	// 長い備考を省略する
	function TrimRemark($str) {
		return mb_strimwidth($str, 0, 20, "...", UTF8);
	}
	
	function WriteLog($str) {
		$pointer=fopen("./log/debug.txt", "a");
		flock($pointer, LOCK_EX);
		fputs($pointer, date("Y/m/d l H:i:s") . " -> " . $str . "\r\n");
		flock($pointer, LOCK_UN);
		fclose($pointer);
	}
?>