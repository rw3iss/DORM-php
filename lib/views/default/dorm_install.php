<?php 
dorm()->response->insert('lib/views/shared/dorm_header.php');
?>

<div id="install">

	<h1 class="page-title"><img src="/dorm/lib/assets/img/dorm.png"/>&nbsp; Install</h1>
		
	<div class="fixer" style="height: 5px;"></div>
	<? if(isset($errors)) { ?>
		<div class="errors"> 
		<? foreach($errors as $e) { ?>
			<span class="error"><?= $e ?></span>
		<? } ?>
		</div>
	<?
	}
	
	if(dorm_is_installed()) {  ?>
		REDIRECT TO DORM
	<?  } 
		else { ?>
		<form action="/dorm/install" method="POST" class="form-horizontal">
			<input type="hidden" name="install" value="1"></input>
			<fieldset>
				<div class="section">
					<span class="label">Choose an admin username:</span>
					<input class="input" type="text" name="admin_username" value="<?= isset($admin_username) ? $admin_username : 'admin' ?>"></input>
				</div>
				<div class="section">
					<span class="label">Choose an admin password:</span>
					<input class="input" type="password" name="admin_password"></input>
				</div>
				<div class="section">
					<span class="label">Re-enter your admin password:</span>
					<input class="input" type="password" name="admin_password_confirm"></input>
				</div>
				<div class="section">
					<span class="label">Choose a database prefix:
						<span class="info">(This will be prepended to all table names)</span>
					</span>
					<input class="input" type="text" name="table_prefix" value="<?= isset($table_prefix) ? $table_prefix : 'dorm_' ?>"></input>
				</div>
				<div class="fixer" style="height: 7px;"></div>
				<div class="section center">
					<input class="btn" type="submit" value="Click to Install DORM"/>
				</div>
			</fieldset>
		</form>
	<? } ?>

</div>

<?php 
dorm()->response->insert('lib/views/shared/dorm_footer.php');
?>
