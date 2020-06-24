<?php
class userClass
{
	/* User Login */
	public function userLogin($usernameEmail,$password) {
		$db = new logDB();
		try {
			$hash_password= hash('sha256', $password); //Password encryption
			$usernameEmail = addslashes($usernameEmail); 
			$dbWhere = "WHERE (username='".$usernameEmail."' or email='".$usernameEmail."') AND password='".$hash_password."'";
			$count = $db->countRow('users',$dbWhere);
			$data = $db->singleQuery("uid FROM users ".$dbWhere);
			if($count == 1) {
				$_SESSION['uid']=$data->uid; // Storing user session value
				return true;
			}
			else
				return false; 
		}
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* User Registration */
	public function userRegistration($username,$password,$email,$name) {
		$db = new logDB();
		try{
			$count = $db->countRow("users","WHERE username = '".$username."' OR email = '".$email."'");
			if($count<1) {
				$hash_password= hash('sha256', $password); //Password encryption
				$dbInsert = array(
					"username" => $username,
					"password" => $hash_password,
					"email" => $email,
					"name" => $name);
				$uid=$db->lastInsertId(); // Last inserted row id
				$_SESSION['uid']=$uid;
				return true;
			}
			else
				return false;
		} 
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
		}
	}	

	/* User Details */
	public function userDetails($uid) {
		$db = new logDB();
		try{
			$uid = intval($uid);
			return $db->singleQuery("email,username,name,rang,uid,PA_Deck FROM users WHERE uid = '".$uid."'");
		}
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	
	
}
?>