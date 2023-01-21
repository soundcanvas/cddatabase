// ==================================================
// イベントハンドラ
// ==================================================

function TxtBoxEnableChange() {
	if (document.getElementById("slct").value == 9) {
		document.getElementById("txt_find").value = "(全件表示)";
		document.getElementById("txt_find").disabled = true;
	} else {
		document.getElementById("txt_find").value = "";
		document.getElementById("txt_find").disabled = false;
	}
}

function FindQueryCheck() {
	if (document.getElementById("slct").value != 9) {
		if (document.getElementById("txt_find").value == "") {
			alert("検索文字列を入れて下さい。");
			return false;
		}
	}
	return true;
}

function EditPageCheck() {
	if (document.getElementById("txt_title_jp").value == "") {
		document.getElementById("errmes").innerHTML = "タイトルを入力してください。";
		return false;
	}
	if (document.getElementById("txt_rdate").value != "") {
		if (!document.getElementById("txt_rdate").value.match(/\d{4}-\d{2}-\d{2}/)) {
			document.getElementById("errmes").innerHTML = "不正な日付です。";
			return false;
		}
	}
	return true;
}

function ConfirmDelete() {
	return confirm("削除しますか？");
}


// ==================================================
// 内部ユーティリティ
// ==================================================
function IsDate(datastr) {

}