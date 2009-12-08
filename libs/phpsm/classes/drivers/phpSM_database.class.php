<?php
include_once(SECURITYMANAGER_ADODB);

class SecurityManager_database extends SecurityManager {
	// the databse connection information
	var $_databaseType = NULL;
	var $_databaseHost = NULL;
	var $_databaseUser = NULL;
	var $_databasePasswd = NULL;
	var $_databaseName = NULL;
	var $_databaseLink = NULL;
	var $_databaseGroupSeq = NULL;
	
	// The tables we query
	var $_db_table_prefix = "security_";
	var $_db_table_users = "users";
	var $_db_table_members = "members";
	var $_db_table_groups = "groups";
	
	/*
		setDataSource: sets any parameters needed in order to read user and group
			information for user management
	*/
	function setDataSource($dbtype,$host,$user,$passwd,$dbname) {
		$this->_databaseType=$dbtype;
		$this->_databaseHost=$host;
		$this->_databaseUser=$user;
		$this->_databasePasswd=$passwd;
		$this->_databaseName=$dbname;
		if ($dbtype == "postgres") {
			$this->_databaseGroupSeq="SELECT currval('security_group_id_seq')";
		} else if ($dbtype == "mysql") {
			$this->_databaseGroupSeq="SELECT LAST_INSERT_ID()";
		} // if you have another type of db then add its sequence query here
	}
	
	/*
		getDataSource: convience function so that the database connection can be reused
	*/
	function getDataSource() {
		return ($this->_databaseLink);
	}
	
	/*
		openDataSource: opens the data source to read the user and group information.
	*/
	function openDataSource() {
		$this->_databaseLink = ADONewConnection($this->_databaseType);
		$this->_databaseLink->debug = $this->_debug;
		$this->_databaseLink->PConnect($this->_databaseHost, $this->_databaseUser, $this->_databasePasswd, $this->_databaseName);
	}
	
	/*
		closeDataSource: closes any open files or connections used to gather information about
			users and groups
	*/
	function closeDataSource() {}
	
	function printDBError($sql='') {
		if ($this->_debug)
		{
			echo '<b>'. $this->_databaseLink->ErrorMsg().'</font><p>';
			echo 'SQL: ' . $sql . '<br />';
		}
		else
		{
			echo '<b>';
			echo $this->_("An SQL error occured. Please contact the administrator or switch to debug mode.");
			echo "</b>\n</p>";
		}
	}
	
	/*****************************************************
	* Group Functions
	*****************************************************/
	
	/*
		getGroups: gets an array of groups with the key of the array being the unique 
			key of the group and the value being the name of the group
	*/	
	function getGroups() {
		$groups=array();
		$sql = "SELECT group_id,group_name FROM "  . $this->_db_table_prefix . $this->_db_table_groups;
		$rc = $this->_databaseLink->Execute( $sql );
		// error check
		if (!$rc) {
			$this->printDBError();
			return NULL;
		}
		
		while (!$rc->EOF) {
			$id = $rc->fields['group_id'];
			$groups[$id] = $rc->fields['group_name'];
			$rc->MoveNext();
		}
		
		return $groups;
	}
	
	/*
		getGroupDetails: given a unique group id this function will return the name of the group. More details
			could be returned in an array if the details needed to be expanded.
	*/
	function getGroupDetails($group) {
		$groups = $this->getGroups();
		return $groups[$group];
	}
	
	/*
		getGroupMembers: gets the login ids of users that belong to the unique group id that is passed to this function
	*/
	function getGroupMembers($group) {
		$users = array();
		$sql = "SELECT member_login FROM " . $this->_db_table_prefix . $this->_db_table_members . 
			" WHERE member_group=$group";
		$rc = $this->_databaseLink->Execute( $sql );
		// error check
		if (!$rc) {
			$this->printDBError();
			return NULL;
		}
		
		while (!$rc->EOF) {
			$users[] = $rc->fields['member_login'];
			$rc->MoveNext();
		}
		// all good
		return $users;
	}
	
	/*
		addNewGroup: Adds a new group as well as members into the group
	*/
	function addNewGroup($name,$members) {
		$sql = "INSERT INTO " . $this->_db_table_prefix . $this->_db_table_groups .
			" (group_name) VALUES ('$name')";
		$rc = $this->_databaseLink->Execute( $sql );
		// error check
		if (!$rc) {
			$this->printDBError();
			return false;
		}
		
		if (!count($members)) {
			echo $this->_('Group Created') . ": " . $name . "<br />";
			return true; // do we need to add members?
		}
		else {
			$sql = $this->_databaseGroupSeq;
			$rc = $this->_databaseLink->Execute( $sql );
			// error check
			if (!$rc) {
				$this->printDBError();
				return false;
			}
		
			$group_id = $rc->fields[0];
			$this->addGroupMembers($group_id,$members);
		}
		echo $this->_('Group Created') . ": " . $name . "<br />";
		return true;
	}
	
	/*
		addGroupMembers: Adds the login IDs into a given group
	*/
	function addGroupMembers($group,$members) {
		// now add the members
		if (is_array($members)) {
			foreach ($members as $member) {
				$sql = "INSERT INTO " . $this->_db_table_prefix . $this->_db_table_members .
					" (member_group,member_login) VALUES ($group,'$member')";
				$rc = $this->_databaseLink->Execute( $sql );
				// error check
				if (!$rc) {
					$this->printDBError();
					return false;
				}
			}
		}
		return true;
	}
	
	/*
		modifyGroup: modifies an existing group by changing it's name and/or list of members
	*/
	function modifyGroup($group,$name,$members) {
		$sql = "UPDATE " . $this->_db_table_prefix . $this->_db_table_groups .
			" SET group_name='$name' WHERE group_id=$group";
		$rc = $this->_databaseLink->Execute( $sql );
		// error check
		if (!$rc) {
			$this->printDBError();
			return false;
		}
		if (is_array($members)) {
			$this->removeGroupMembers($group);
			if ($this->addGroupMembers($group,$members)) {
				echo $this->_('Group Updated'). ": " . $name . "<br />";
				return true;
			}
		}
		echo $this->_('Group Updated'). ": " . $name . "<br />";
		return true;
	}
	
	/*
		removeGroupMembers: removes all the group members from a given group
	*/
	function removeGroupMembers($group) {
		// delete the old entries
		$sql = "DELETE FROM " . $this->_db_table_prefix . $this->_db_table_members .
			" WHERE member_group=$group";
		$rc = $this->_databaseLink->Execute( $sql );
		// error check
		if (!$rc) {
			$this->printDBError();
			return false;
		}
	}
	
	/*
		deleteGroup: Completely deletes a given group along with the record of what members belonged to it
	*/
	function deleteGroup($group) {
		// remove the members
		$this->removeGroupMembers($group);
		// delete the group
		$sql = "DELETE FROM " . $this->_db_table_prefix . $this->_db_table_groups . 
			" WHERE group_id=$group";
		$rc = $this->_databaseLink->Execute( $sql );
		// error check
		if (!$rc) {
			$this->printDBError();
			return false;
		}
		echo $this->_('Group Deleted') . "<br />";
		return true;
	}
	
	/*******************************************************
	** User Function
	*******************************************************/
	
	/*
		login: basic login, given a username and password this function will attempt to get the information about
			the user, if information is found then it is saved and the user is considered 'logged in', if not it fails
			(returns false on failure, returns true on success).
	*/
	function login($login='',$password='') {
		if ($login=="" && $login=="") {
			$login = $this->_autoLoginUser;
			$password = $this->_autoLoginPasswd;
		}
		$sql = "SELECT * FROM "  . $this->_db_table_prefix . $this->_db_table_users . 
			" WHERE user_login = '$login' AND user_password = '" . md5($password) . "'";
		$rc = $this->_databaseLink->Execute( $sql );
		// store the user info
		if ($rc->RecordCount()==1) {
			$this->_userLoginID = $rc->fields['user_login'];
			$this->_userName = $rc->fields['user_name'];
			$this->_userLanguage = $rc->fields['user_language'];
			$this->_userCountry = $rc->fields['user_country'];
			$this->_userAccessLevel = $rc->fields['user_access_level'];
			$this->_userDateCreated = $rc->fields['user_date_created'];
			$this->_userLastLogin = $rc->fields['user_last_login'];
			$this->_userEmail = $rc->fields['user_email'];
			// now we need to read in the groups
			$sql = "SELECT member_group FROM "  . $this->_db_table_prefix . $this->_db_table_members . 
				" WHERE member_login = '$login'";
			$rc = $this->_databaseLink->Execute( $sql );
			while (!$rc->EOF) {
				$this->_userGroups[] = $rc->fields['member_group'];
				$rc->MoveNext();
			}
			// record when the user has logged in
			$sql = "UPDATE " . $this->_db_table_prefix . $this->_db_table_users .
			   " SET user_last_login=". $this->_databaseLink->DBDate(time()) .
			   " WHERE user_login='" . $login . "'";
			$rc = $this->_databaseLink->Execute( $sql );
			// error check
			if (!$rc) {
			  $this->printDBError();
			  return NULL;
			}
			
			return true;
		}
		return false;
	}
	
	/*
		getUserDetails: Gets a users details and returns them in an associative array
	*/
	function getUserDetails($user) {
		// do the query to get the user info
		$details = array();
		$sql = "SELECT * FROM " . $this->_db_table_prefix . $this->_db_table_users . " WHERE user_login='".$user."'";
		$rc = $this->_databaseLink->Execute($sql);
		// error check
		if (!$rc) {
			$this->printDBError();
			return NULL;
		}
		// read the values
		if ($rc->RecordCount()>0) {
			$details['login'] = $rc->fields['user_login'];
			$details['name'] = $rc->fields['user_name'];
			$details['access_level'] = $rc->fields['user_access_level'];
			$details['language'] = $rc->fields['user_language'];
			$details['country'] = $rc->fields['user_country'];
			$details['date_created'] = $rc->UserDate($rc->fields['user_date_created'],'m/d/Y');
			$details['last_login'] = $rc->UserDate($rc->fields['user_last_login'],'m/d/Y');
			$details['email'] = $rc->fields['user_email'];
		}
		return ($details);
	}

	/*
		getUserPassword: The user password should not be stored in session information, so it must be retrieved separatly when
			it needs to be compared.
	*/
	function getUserPassword($user) {
		$sql = "SELECT user_password FROM " . $this->_db_table_prefix . $this->_db_table_users . " WHERE user_login='".$user."'";
		$rc = $this->_databaseLink->Execute($sql);	
		// error check
		if (!$rc) {
			$this->printDBError();
			return NULL;
		}
		return $rc->fields['user_password'];
	}
	
	/*
		getUsers: Gets a list of users also with details
	*/
	function getUsers() {
		$users = array();
		/* you could just point to getUserDetails for each iteration, but with a database
			that would be kind of expensive.*/
		$sql = "SELECT * FROM " . $this->_db_table_prefix . $this->_db_table_users . " ORDER BY user_name";
		$rc = $this->_databaseLink->Execute($sql);
		// error check
		if (!$rc) {
			$this->printDBError();
			return NULL;
		}
		
		while (!$rc->EOF) {
			$details = array();
			// get the info
			$details['login'] = $rc->fields['user_login'];
			$details['name'] = $rc->fields['user_name'];
			$details['access_level'] = $rc->fields['user_access_level'];
			$details['language'] = $rc->fields['user_language'];
			$details['country'] = $rc->fields['user_country'];
			$details['date_created'] = $rc->UserDate($rc->fields['user_date_created'],'m/d/Y');
			$details['last_login'] = $rc->UserDate($rc->fields['user_last_login'],'m/d/Y');
			$details['email'] = $rc->fields['user_email'];
			// now save the info
			$login = $rc->fields['user_login'];
			$users[$login] = $details;
			$rc->MoveNext();
		}
		return ($users);
	}
	
	/*
		addNewUser: Adds a new user and sets all information, this method does not check how has access to add new users
			that access level checking is left up to the form
	*/
	function addNewUser($login,$name,$password,$email,$language,$country,$groups,$access_level) {
		// new user, first check if login name exists
		$sql = "SELECT user_login FROM " . $this->_db_table_prefix . $this->_db_table_users . " WHERE user_login = '$login'";
		$rc = $this->_databaseLink->Execute( $sql );
		if ($rc->RecordCount()) {
			echo $this->_( 'Login Exists' );
		} else {
			// add the user
			$sql = "INSERT INTO " . $this->_db_table_prefix . $this->_db_table_users . " (
					user_login,
					user_password,
					user_name,
					user_email,
					user_language,
					user_country,
					user_date_created,
					user_access_level) 
				VALUES (
					'$login',
					'" . md5($password) . "',
					'$name',
					'$email',
					'$language',
					'$country'," . 
					$this->_databaseLink->DBDate(time()) . "," .
					$access_level . ")";
					
			$rc = $this->_databaseLink->Execute( $sql );
			// Check if it was successful
			if (!$rc) {
				$this->printDBError($sql);
				return false;
			}
			
			if (!count($groups)) {
				// not a fatal error
				echo $this->_('Welcome')." $name ".$this->_('You can now log in');
				return true;
			}
			// Now add the groups
			
			$this->addUsersGroups($login,$groups);
			// everything was successfull.
			echo $this->_('Welcome')." $name ".$this->_('You can now log in');
			return true;
		}
	}
	
	/*
		modifyUser: Modifies an existing user
	*/
	function modifyUser($login,$name,$password,$email,$language,$country,$groups,$access_level) {
		// we are doing an update
		$sql = "UPDATE " . $this->_db_table_prefix . $this->_db_table_users .
			   " SET user_name='$name',
					user_language='$language',
					user_country='$country',
					user_email='$email'";
		if ($access_level != "")
			$sql .= ",user_access_level=$access_level";
		if ($password!="")
			$sql .= ",user_password='". md5($password) . "'";

		$sql .= " WHERE user_login='" . $login . "'";
		$result = $this->_databaseLink->Execute($sql);
		if (!$result) {
			$this->printDBError();
			return false;
		}
		
		$this->removeUsersGroups($login);
		
		if (!count($groups)) {
			// not an error
			echo $this->_('User Updated') . ": " . $name . "<br />";
			return true;
		}
		$this->addUsersGroups($login,$groups);
		
		echo $this->_('User Updated') . ": " . $name . "<br />";
		return true;
	}
	
	/*
		removeUsersGroups: given a user ID this function will remove the user from all groups that they are a member of.
	*/
	function removeUsersGroups($login) {
		// delete the old groups
		$sql = "DELETE FROM " . $this->_db_table_prefix . $this->_db_table_members . 
			" WHERE member_login='$login'";
		$rc = $this->_databaseLink->Execute($sql);
		if (!$rc) {
			$this->printDBError();
			return false;
		}
	}
	
	/*
		getUsersGroups: returns an array of all the groups that the given login ID is a member of
	*/
	function getUsersGroups($login) {
		$groups = array();
		$sql = "SELECT member_group FROM " . $this->_db_table_prefix . $this->_db_table_members .
			" WHERE member_login='$login'";
		$rc = $this->_databaseLink->Execute($sql);
		
		if (!$rc) {
			$this->printDBError();
			return false;
		}
		
		while (!$rc->EOF) {
			$groups[] = $rc->fields['member_group'];
			$rc->MoveNext();
		}
		return $groups;
	}
	
	/*
		addUsersGroup:  adds a given user ID to a list of groups
	*/
	function addUsersGroups($login,$groups) {
		// Now add the groups
		foreach ($groups as $group) {
			$sql = "INSERT INTO " . $this->_db_table_prefix . $this->_db_table_members . 
				" (member_group,member_login) VALUES ($group,'$login')";
			$rc = $this->_databaseLink->Execute( $sql );
			if (!$rc) {
				$this->printDBError();
				return false;
			}
		}
		return true;
	}
	
	/*
		deleteUser: deletes a user.
	*/
	function deleteUser($login) {
		$this->removeUsersGroups($login);
		
		$sql = "DELETE FROM " .  $this->_db_table_prefix . $this->_db_table_users . 
			" WHERE user_login='".$login."'";
		$rc = $this->_databaseLink->Execute($sql);
		if (!$rc) {
			$this->printDBError();
			return false;
		}
		
		echo $this->_('User Deleted') . ": " . $login . "<BR>";
		return true;
	}
}

?>
