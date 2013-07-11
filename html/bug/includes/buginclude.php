<?
function getStatus($id = -1){
	switch ($id){
		case 0:
			return "New";
		case 1:
			return "Accepted";
		case 2:
			return "Denied";
		case 3:
			return "Duplicate";
		case 4:
			return "Resolved";
		default:
			return "N/A";
	}
}

?>