<p>
<form name="loginForm" action="<?php echo $submitURL;?>" method="post">
<table width="100" cellspacing="1" cellpadding="1" class="sidebox">
<?php if ($this->getUserLoginID()) { ?>
<tr>
	<th align="center" class="sidebox">
	<?php
		echo $this->_('Welcome') . " " . $this->_userName;
	?>
	</th>
</tr>
<tr>
	<td align="center" class="sidebox">
	<table border="0" cellpadding="2" cellspacing="0">
    <tr>
		<td class="sideboxtext" align="center">
        	<input type="submit" name="sm_logout" value="<?php echo $this->_('logout');?>" class="button" />
		</td>
    </tr>
</table>

<?php } else { ?>
<tr>
	<th align="center" class="sidebox">
		<?php echo $this->_('Login'); ?>
	</th>
</tr>
<tr>
	<td align="center" class="sidebox">
	<table border="0" cellpadding="2" cellspacing="0">
    <tr>
		<td class="sideboxtext">
		<?php echo $this->_('Login');?>:<br />
        <input type="text" name="sm_login_id" size="10" class="field_textbox" /><br />
        <?php echo $this->_('Password');?>:<br />
        <input type="password" name="sm_password" size="10" class="field_textbox" />
		<?php
		// only show the register link if allowed to, use admin link if admin
		if ($this->isOpenRegistration())
			echo '<br /><a href="'.$regURL.'">'.$this->_('register').'</a>';
		?>
		<script type="text/javascript">
			this.document.forms.loginForm.sm_login_id.focus();
		</script>
	</td>
</tr>	
<tr>
	<td class="sideboxtext" align="center">
        <input type="submit" value="<?php echo $this->_('login');?>" class="button" />
	</td>
</tr>
</table>
	</td></tr>
<?php } ?>

</table>
</form>
</p>
