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
		case 4:
			echo "<p class='error'>Your account has been disabled... You have also been logged out!</p>";
			break;
		case 5:
			echo "<p class='error'>Quiz does not exist or no quiz specified!</p>";
			break;
		case 5:
			echo "<p class='error'>Failed to add news!</p>";
			break;
		default: 
			echo "<p class='error'>No message found</p>";
			break;
	}
	endMainContent();
	footer();
?>