<?php
$sm_group = isset( $_REQUEST['sm_group'] ) ? $_REQUEST['sm_group'] : '';
$sm_name = isset( $_REQUEST['sm_name'] ) ? $_REQUEST['sm_name'] : '';
$sm_members = isset( $_REQUEST['sm_members'] ) ? $_REQUEST['sm_members'] : NULL;

$sm_mode = isset( $_REQUEST['sm_mode'] ) ? $_REQUEST['sm_mode'] : "new";
$sm_delete = isset( $_REQUEST['sm_delete'] ) ? "yes" : "no";
$sm_submit_form = isset( $_REQUEST['sm_submit_form'] ) ? "yes" : "no";

// Only do this code if we are submiting
if ($sm_submit_form=="yes") {
	if (!$this->checkAccessLevel($this->getSuperUserLevel()))
		die($this->_('You must be an administrator in order to add/edit groups!'));
	
	if ($sm_mode == "new") {
		// create the group
		$this->addNewGroup($sm_name,$sm_members);
	} else if ($sm_mode == "edit") {
		if ($sm_delete == "no") {
			$this->modifyGroup($sm_group,$sm_name,$sm_members);		
		} else {
			// delete the group and the member lists
			$this->deleteGroup($sm_group);
		}
	}
}
?>