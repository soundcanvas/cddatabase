<?php
class Logic {
    private $dbh = null;
    
	#===================================================
	# Public
	#===================================================
	
    // コンストラクタ
    public function Logic() {
    	// や、やることがない・・
    }
    
    // アーティスト一覧取得コマンド
    public function SelectArtistMaster() {
    	$this->DBConnect();
    	
	    if (is_object($this->dbh)) {
	        try {
	            $sql = "SELECT ";
			    $sql .= "    ma.ARTIST_ID, ";
			    $sql .= "    ma.ARTIST_NAME , ";
			    $sql .= "    ma.ARTIST_NAME_E , ";
			    $sql .= "    (SELECT ";
			    $sql .= "        COUNT(DISC_ID) ";
			    $sql .= "    FROM ";
			    $sql .= "        MT_DISC md ";
			    $sql .= "    WHERE ";
			    $sql .= "        md.ARTIST_ID = ma.ARTIST_ID ";
			    $sql .= "        AND md.ENABLE = 1 ";
			    $sql .= "    ) AS USE_COUNT ";
			    $sql .= "FROM ";
			    $sql .= "    MT_ARTIST ma ";
			    $sql .= "ORDER BY ";
			    $sql .= "    ma.ARTIST_NAME";
	            $stmt = $this->dbh->query($sql);
	            $result = $stmt->fetchAll();
	            
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    	
    	return $result;
    }
    
    // 検索結果レコード数取得コマンド
    public function SelectFindViewCount($request) {
    	$para = $request["q"];
    	$type = $request["s"];
    	
    	$this->DBConnect();
    	
	    $sql = "SELECT";
	    $sql .= "    COUNT(di.DISC_ID) AS RECORD_COUNT ";
	    $sql .= "FROM";
	    $sql .= "    MT_DISC di ";
	    $sql .= "    INNER JOIN MT_ARTIST at ON di.ARTIST_ID = at.ARTIST_ID ";
	    $sql .= "WHERE";
	    $sql .= "    di.ENABLE = 1 ";
	    if (is_object($this->dbh)) {
	        try {
	            $findword = $this->dbh->quote("%$para%");
	            switch ($type) {
	                case "0":
	                    $sql .= "AND (di.DISC_TITLE LIKE $findword";
	                    $sql .= "   OR di.DISC_TITLE_JP LIKE $findword) ";
	                    break;
	                case "1":
	                    $sql .= "AND (at.ARTIST_NAME LIKE $findword ";
	                    $sql .= "   OR at.ARTIST_NAME_E LIKE $findword) ";
	                    break;
	                default:
	                    break;
	            }
	            
	            $stmt = $this->dbh->query($sql);
	            $result = $stmt->fetchAll();
	            
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    	
    	return $result[0]["RECORD_COUNT"];
    }
    
    // レコード検索結果取得コマンド
    public function SelectFindView($request) {
    	$para = $request["q"];
    	$type = $request["s"];
    	$page = $request["p"];
    	
    	$this->DBConnect();
    	
	    $sql = "SELECT";
	    $sql .= "    di.DISC_ID,di.DISC_TITLE,di.DISC_TITLE_JP,di.CATALOG_NO,at.ARTIST_NAME,di.REGIST_DATE,di.RELEASE_DATE,di.UPDATE_DATE,di.REMARK ";
	    $sql .= "FROM";
	    $sql .= "    MT_DISC di ";
	    $sql .= "    INNER JOIN MT_ARTIST at ON di.ARTIST_ID = at.ARTIST_ID ";
	    $sql .= "WHERE";
	    $sql .= "    di.ENABLE = 1 ";
	    if (is_object($this->dbh)) {
	        try {
	            $findword = $this->dbh->quote("%$para%");
	            switch ($type) {
	                case "0":
	                    $sql .= "AND (di.DISC_TITLE LIKE $findword";
	                    $sql .= "   OR di.DISC_TITLE_JP LIKE $findword) ";
	                    break;
	                case "1":
	                    $sql .= "AND (at.ARTIST_NAME LIKE $findword ";
	                    $sql .= "   OR at.ARTIST_NAME_E LIKE $findword) ";
	                    break;
	                default:
	                    break;
	            }
	            $sql .= "ORDER BY di.DISC_TITLE_JP ";
	            if ($page && $page > 0) {
	            	$sql .= "LIMIT " . MAX_PAGE_RECORD * ($page - 1) . "," . MAX_PAGE_RECORD . " ";
	            }
	            $stmt = $this->dbh->query($sql);
	            $result = $stmt->fetchAll();
	            
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    	
    	return $result;
    }
    
    // レコード参照画面取得コマンド
    public function SelectDetailView($request) {
    	$id = $request["id"];
    	if (!$id) {
    		return null;
    	}
    	
    	$this->DBConnect();
    	
	    $sql = "SELECT";
	    $sql .= "    md.DISC_TITLE,md.DISC_TITLE_JP,md.CATALOG_NO,ma.ARTIST_NAME,md.REGIST_DATE,md.RELEASE_DATE,md.REMARK ";
	    $sql .= "FROM";
	    $sql .= "    MT_DISC md ";
	    $sql .= "    INNER JOIN MT_ARTIST ma ON md.ARTIST_ID = ma.ARTIST_ID ";
	    $sql .= "WHERE ";
	    $sql .= "    md.DISC_ID = ? AND md.ENABLE = 1";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindValue(1, $id, PDO::PARAM_INT);
		        $stmt->execute();
		        
	            $result = $stmt->fetchAll();
	            
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    	
    	if (count($result) < 1) {
    		Error(DISC_NOTFOUND_ERROR);
    	}
    	
    	return $result;
    }
    
    // レコード編集画面取得コマンド
    public function SelectEditView($request) {
    	$id = $request["id"];
    	if (!$id) {
    		return null;
    	}
    	
    	$this->DBConnect();
    	
	    $sql = "SELECT";
	    $sql .= "    DISC_TITLE,DISC_TITLE_JP,CATALOG_NO,ARTIST_ID,REGIST_DATE,RELEASE_DATE,REMARK ";
	    $sql .= "FROM";
	    $sql .= "    MT_DISC ";
	    $sql .= "WHERE ";
	    $sql .= "    DISC_ID = ? AND ENABLE = 1";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindValue(1, $id, PDO::PARAM_INT);
		        $stmt->execute();
		        
	            $result = $stmt->fetchAll();
	            
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    	
    	if (count($result) < 1) {
    		Error(DISC_NOTFOUND_ERROR);
    	}
    	
    	return $result;
    }
    
    // レコード編集更新コマンド
    public function UpdateEditCommand($request) {
    	$this->DBConnect();
    	
	    $sql  = "UPDATE ";
	    $sql .= "    MT_DISC ";
	    $sql .= "SET ";
	    $sql .= "    DISC_TITLE = :disc_title, ";
	    $sql .= "    DISC_TITLE_JP = :disc_title_jp, ";
	    $sql .= "    ARTIST_ID = :artist_id, ";
	    $sql .= "    CATALOG_NO = :catalog_no, ";
	    $sql .= "    RELEASE_DATE = :release_date, ";
	    $sql .= "    UPDATE_DATE = :update_date, ";
	    $sql .= "    REMARK = :remark ";
	    $sql .= "WHERE ";
	    $sql .= "    DISC_ID = :disc_id";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindParam(':disc_title', $request["title"]);
		        $stmt->bindParam(':disc_title_jp', $request["title_jp"]);
		        $stmt->bindParam(':artist_id', $request["artist_id"]);
		        $stmt->bindParam(':catalog_no', $request["catalog_no"]);
		        $stmt->bindParam(':release_date', $request["release_date"]);
		        $stmt->bindParam(':update_date', $request["update_date"]);
		        $stmt->bindParam(':remark', $request["remark"]);
		        $stmt->bindParam(':disc_id', $request["disc_id"]);
		        $result = $stmt->execute();
		        if (!$result) {
		        	Error(DB_UPDATE_ERROR);
		        }
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    }
    
    // レコード編集新規コマンド
    public function InsertEditCommand($request) {
    	$this->DBConnect();
    	
	    $sql  = "INSERT INTO MT_DISC ( ";
	    $sql .= "DISC_TITLE, ";
	    $sql .= "DISC_TITLE_JP, ";
	    $sql .= "ARTIST_ID, ";
	    $sql .= "CATALOG_NO, ";
	    $sql .= "RELEASE_DATE, ";
	    $sql .= "UPDATE_DATE, ";
	    $sql .= "REGIST_DATE, ";
	    $sql .= "ENABLE, ";
	    $sql .= "REMARK ";
	    $sql .= ") ";
	    $sql .= "VALUES ";
	    $sql .= " ( ";
	    $sql .= ":disc_title, ";
	    $sql .= ":disc_title_jp, ";
	    $sql .= ":artist_id, ";
	    $sql .= ":catalog_no, ";
	    $sql .= ":release_date, ";
	    $sql .= ":update_date, ";
	    $sql .= ":regist_date, ";
	    $sql .= "'1', ";
	    $sql .= ":remark ";
	    $sql .= ") ";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindParam(':disc_title', $request["title"]);
		        $stmt->bindParam(':disc_title_jp', $request["title_jp"]);
		        $stmt->bindParam(':artist_id', $request["artist_id"]);
		        $stmt->bindParam(':catalog_no', $request["catalog_no"]);
		        $stmt->bindParam(':release_date', $request["release_date"]);
		        $stmt->bindParam(':update_date', $request["update_date"]);
		        $stmt->bindParam(':regist_date', $request["regist_date"]);
		        $stmt->bindParam(':remark', $request["remark"]);
		        $result = $stmt->execute();
		        if (!$result) {
		        	Error(DB_UPDATE_ERROR);
		        }
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    }
    
    // レコード編集削除コマンド
    public function DeleteEditCommand($request) {
    	$this->DBConnect();
    	
	    $sql  = "UPDATE ";
	    $sql .= "    MT_DISC ";
	    $sql .= "SET ";
	    $sql .= "    ENABLE = 0 ";
	    $sql .= "WHERE ";
	    $sql .= "    DISC_ID = :disc_id";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindParam(':disc_id', $request["disc_id"]);
		        $result = $stmt->execute();
		        if (!$result) {
		        	Error(DB_UPDATE_ERROR);
		        }
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    }
    
    // アーティスト登録コマンド
    public function InsertArtistCommand($request) {
    	$this->DBConnect();
    	
	    $sql  = "INSERT INTO MT_ARTIST (ARTIST_NAME, ARTIST_NAME_E) VALUES (:add_name, :add_name_e)";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindParam(':add_name', $request["add_name"]);
		        $stmt->bindParam(':add_name_e', $request["add_name_e"]);
		        $result = $stmt->execute();
		        if (!$result) {
		        	Error(DB_UPDATE_ERROR);
		        }
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    }
    
    // アーティスト編集コマンド
    public function UpdateArtistCommand($request) {
    	$this->DBConnect();
    	
	    $sql  = "UPDATE ";
	    $sql .= "    MT_ARTIST ";
	    $sql .= "SET ";
	    $sql .= "    ARTIST_NAME = :artist_name, ";
	    $sql .= "    ARTIST_NAME_E = :artist_name_e ";
	    $sql .= "WHERE ";
	    $sql .= "    ARTIST_ID = :artist_id";

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $stmt->bindParam(':artist_name', $request["artist_name"]);
		        $stmt->bindParam(':artist_name_e', $request["artist_name_e"]);
		        $stmt->bindParam(':artist_id', $request["artist_id"]);
		        $result = $stmt->execute();
		        if (!$result) {
		        	Error(DB_UPDATE_ERROR);
		        }
	        } catch (Exception $ex) {
	            Error($ex->getMessage());
	        }
	    } else {
	        Error(DB_ACCESS_ERROR);
	    }
	    
    	$this->DBClose();
    }
    
    // レコード物理削除バッチ
    public function DeleteBatchCommand() {
    	$this->DBConnect();
    	
	    $sql  = "DELETE FROM MT_DISC WHERE ENABLE <> 1";
	    $isSuccess = true;

	    if (is_object($this->dbh)) {
	        try {
	            $stmt = $this->dbh->prepare($sql);
		        $result = $stmt->execute();
		        if (!$result) {
		        	return DB_UPDATE_ERROR;
		        }
	        } catch (Exception $ex) {
	            return $ex->getMessage();
	        }
	    } else {
	        return DB_ACCESS_ERROR;
	    }
	    
    	$this->DBClose();
    	
    	return "Batch complete.";
    }
    
	#===================================================
	# Private
	#===================================================
	
    // DBコネクション
    private function DBConnect() {
    	if (stristr(DB_DSN, "sqlite")) {
	    	if (!file_exists(substr(DB_DSN, 7))) {
	    		Error(DB_FILE_ERROR);
	    	}
    	}
    	
    	$this->dbh = new PDO(DB_DSN, DB_USER_ID, DB_PASSWORD);
    	
    	$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$this->dbh->setAttribute(PDO::ATTR_ORACLE_NULLS, 1);
    }
    
    // DBクローズ
    private function DBClose() {
    	$this->dbh = null;
    }
}
?>