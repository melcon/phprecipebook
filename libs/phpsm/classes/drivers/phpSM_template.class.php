<?php
/*
	This is a template of the required functions in order to implement a custom authentication class
*/

SecurityManager_template extends SecurityManager {
	/*
		setDataSource: sets any parameters needed in order to read user and group
			information for user management
	*/
	function setDataSource($dbtype,$host,$user,$passwd,$dbname) {}
	
	/*
		getDataSource: convience function so that the database connection can be reused
	*/
	function &getDataSource() {}
	
	/*
		openDataSource: opens the data source to read the user and group information.
	*/
	function openDataSource() {}
	
	/*
		closeDataSource: closes any open files or connections used to gather information about
			users and groups
	*/
	function closeDataSource() {}
	
	/*****************************************************
	* Group Functions
	*****************************************************/
	
	/*
		getGroups: gets an array of groups with the key of the array being the unique 
			key of the group and the value being the name of the group
	*/	
	function getGroups() {}
	
	/*
		getGroupDetails: given a unique group id this function will return the name of the group. More details
			could be returned in an array if the details needed to be expanded.
	*/
	function getGroupDetails($group) {}
	
	/*
		getGroupMembers: gets the login ids of users that belong to the unique group id that is passed to this function
	*/
	function getGroupMembers($group) {}
	
	/*
		addNewGroup: Adds a new group as well as members into the group
	*/
	function addNewGroup($name,$members) {}
	
	/*
		addGroupMembers: Adds the login IDs into a given group
	*/
	function addGroupMembers($group,$members) {}
	
	/*
		modifyGroup: modifies an existing group by changing it's name and/or list of members
	*/
	function modifyGroup($group,$name,$members) {}
	
	/*
		removeGroupMembers: removes all the group members from a given group
	*/
	function removeGroupMembers($group) {}
	
	/*
		deleteGroup: Completely deletes a given group along with the record of what members belonged to it
	*/
	function deleteGroup($group) {}
	
	/*******************************************************
	** User Function
	*******************************************************/
	
	/*
		login: basic login, given a username and password this function will attempt to get the information about
			the user, if information is found then it is saved and the user is considered 'logged in', if not it fails
			(returns false on failure, returns true on success).
	*/
	function login($login,$password) {}
	
	/*
		getUserPassword: The user password should not be stored in session information, so it must be retrieved separatly when
			it needs to be compared.
	*/
	function getUserPassword($user) {}
		
	/*
		getUserDetails: Gets a users details and returns them in an associative array
	*/
	function getUserDetails($user) {}
	
	/*
		getUsers: Gets a list of users also with details
	*/
	function getUsers() {}
	
	/*
		addNewUser: Adds a new user and sets all information, this method does not check how has access to add new users
			that access level checking is left up to the form
	*/
	function addNewUser($login,$name,$password,$email,$language,$groups,$access_level) {}
	
	/*
		modifyUser: Modifies an existing user
	*/
	function modifyUser($login,$name,$password,$email,$language,$groups,$access_level) {}
	
	/*
		removeUsersGroups: given a user ID this function will remove the user from all groups that they are a member of.
	*/
	function removeUsersGroups($login) {}
	
	/*
		getUsersGroups: returns an array of all the groups that the given login ID is a member of
	*/
	function getUsersGroups($login) {}
	
	/*
		addUsersGroup:  adds a given user ID to a list of groups
	*/
	function addUsersGroups($login,$groups) {}
	
	/*
		deleteUser: deletes a user.
	*/
	function deleteUser($login) {}
}
	
