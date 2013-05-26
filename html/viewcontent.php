<?
	if (isset($_GET['id']) == false){
		header("Location:index.php");
	}
	$id = $_GET['id'];
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();
?>
	
<?
	echo buildContent($id);
	endMainContent();
	footer();
?>