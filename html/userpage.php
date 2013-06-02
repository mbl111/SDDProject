<?
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();
?>
	
<?
	$id = -1;
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}

	dbQuery();
	endMainContent();
	footer();
?>