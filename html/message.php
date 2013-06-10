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

	switch ($id){
		case 1: 
			echo "<p class='error'>You must be logged in to see this page</p>";
			break;
		case 2:
			echo "<p class='error'>You must be a teacher to see this page</p>";
			break;
		case 3:
			echo "<p class='error'>No class by this id exists!</p>";
			break;
		default: 
			echo "<p class='error'>No message found</p>";
			break;
	}
	endMainContent();
	footer();
?>