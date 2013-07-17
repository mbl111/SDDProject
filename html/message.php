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
		case 6:
			echo "<p class='error'>Failed to add news!</p>";
			break;
		case 7:
			echo "<p class='error'>Failed to submit quiz!</p>";
			break;
		case 8:
			echo "<p class='error'>You cant submit this quiz! You have already done it</p>";
			break;
		case 9:
			echo "<p class='error'>No class was found.</p>";
			break;
		case 10:
			echo "<p class='error'>Error making quiz. Please try again.</p>";
			break;
		case 11:
			echo "<p class='error'>Your account has been created! Please login to get started.</p>";
			break;
		default: 
			echo "<p class='error'>No message found</p>";
			break;
	}
	endMainContent();
	footer();
?>