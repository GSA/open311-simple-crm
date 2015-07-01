<?php $this->load->view('header');?>
<div class='mainInfo'>

	<h2>All Users</h2>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
	<table class="table table-bordered tablesorter table-striped">
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Groups</th>
			<th>Status</th>
		</tr>
		<?php foreach ($users as $user):?>
			<tr class="<?php if (! $user->active) { echo 'inactive';} ?>">
				<td><?php echo $user->first_name;?></td>
				<td><?php echo $user->last_name;?></td>
				<td><?php echo $user->email;?></td>
				<td>
					<?php foreach ($user->groups as $group):?>
						<span <?php if ($group->name == 'admin') {echo('class="admin"'); } ?>>
							<?php echo $group->name;?><br />
						</span>
					<?php endforeach?>
				</td>
				<td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, 'Active') : anchor("auth/activate/". $user->id, 'Inactive');?></td>
			</tr>
		<?php endforeach;?>
	</table>
	
	<p><a class="add-anchor btn btn-success" href="<?php echo site_url('auth/create_user');?>"><i class="glyphicon glyphicon-plus"></i> Create a new user</a></p>
	
</div>
<?php $this->load->view('footer');?>

