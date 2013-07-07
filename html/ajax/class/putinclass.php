<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if ($_SESSION['usertype']==USER_TEACHER){
	if (isset($_POST['name']) && ! empty($_POST['name']) && isset($_POST['id']) && ! empty($_POST['id'])){
		$name = mysql_real_escape_string($_POST['name']);
		$cid = mysql_real_escape_string($_POST['id']);
		$exp = explode(" ", $name);
		$query = dbQuery("SELECT `class`, `id` FROM users WHERE `firstname`='{$exp[0]}' AND `lastname`='{$exp[1]}' AND `usertype`=1 LIMIT 1");
		$query2 = dbQuery("SELECT `students`, `teacher` FROM classes WHERE `id`=$cid LIMIT 1");
		if (mysql_num_rows($query) == 1 && mysql_num_rows($query2) == 1){
			$user = mysql_fetch_assoc($query);
			$class = mysql_fetch_assoc($query2);
			if ($class['teacher'] != $_SESSION['userid']){
				echo "This is not your class";
			}else{
				$studentsInClass = explode(",", $class['students']);
				$classesForStudent = explode(",", $user['class']);
				if (in_array($cid, $classesForStudent)){
					echo "Student is already in this class";
				}else{
					$studentsInClass[] = $user['id'];
					$classesForStudent[] = $cid;
					$students = implode(",", $studentsInClass);
					$class = implode(",", $classesForStudent);
					dbQuery("UPDATE users SET `class`='$class' WHERE `id`={$user['id']}");
					dbQuery("UPDATE classes SET `students`='$students' WHERE `id`=$cid");
					echo 'true';
				}
			}
		}else{
			echo "No user found by this name";
		}
	}
}
?>