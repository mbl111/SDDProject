<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if ($_SESSION['usertype']==0){
	if (isset($_POST['uid']) && ! empty($_POST['uid']) && isset($_POST['id']) && ! empty($_POST['id'])){
		$uid = mysql_real_escape_string($_POST['uid']);
		$cid = mysql_real_escape_string($_POST['id']);
		$query = dbQuery("SELECT `class`, `id` FROM users WHERE `id`={$uid} LIMIT 1");
		$query2 = dbQuery("SELECT `students` FROM classes WHERE `id`=$cid LIMIT 1");
		if (mysql_num_rows($query) == 1 && mysql_num_rows($query2) == 1){
			$user = mysql_fetch_assoc($query);
			$class = mysql_fetch_assoc($query2);
			
			$studentsInClass = explode(",", $class['students']);
			$classesForStudent = explode(",", $user['class']);
			
			if (!in_array($cid, $classesForStudent)){
				echo "Student is not in this class";
			}else{
				$studentsInClass = array_diff($studentsInClass, array($uid));
				$classesForStudent = array_diff($classesForStudent, array($cid));
				$students = implode(",", $studentsInClass);
				$class = implode(",", $classesForStudent);
				
				dbQuery("UPDATE users SET `class`='$class' WHERE `id`={$uid}");
				dbQuery("UPDATE classes SET `students`='$students' WHERE `id`=$cid");
				echo "true";
			}
		}else{
			echo "No user found";
		}
	}
}
?>