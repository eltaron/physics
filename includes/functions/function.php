<?php
/*start get count of items */
function countItems($item, $table) {
		global $con;
		$stmt = $con->prepare("SELECT COUNT($item) FROM $table");
		$stmt->execute();
		return $stmt->fetchColumn();
	}
/*end get count of items */
/*start Check Items Function */
function checkItem ($select, $from, $value) {
      global $con; 
      $stmt = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
      $stmt->execute(array($value));
      $count = $stmt->rowCount();
      return $count;
}
/*end Check Items Function */
/*start Get Latest Records Function */
function getLatest($select, $table, $order, $limit = 5) {
		global $con;
		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;
}
/*end Get Latest Records Function */
/* start Home Redirect Function */
function redirectHome($theMsg, $url = null, $seconds = 3) {
		if ($url === null) {
			$url = 'profile.php';
			$link = 'الصفحة الرئيسية';
		} else {
			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'اخر صفحة';
			} else {
				$url = 'profile.php';
				$link = 'الصفحة الرئيسية';
			}
		}
		echo $theMsg;
		echo "<div class='alert alert-info'>سوف يتم اعادتك $link بعد $seconds ثوان.</div>";
		header("refresh:$seconds;url=$url");
		exit();
}
/* end Home Redirect Function */
/* start Get All Function */
function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "asc") {
		global $con;
		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
		$getAll->execute();
		$all = $getAll->fetchAll();
		return $all;
}
/* end Get All Function */
