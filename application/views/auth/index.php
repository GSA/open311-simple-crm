<?php $this->load->view('header');?>
<div class='mainInfo'>

	<h2>All Users</h2>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
	<table class="table table-bordered tablesorter table-striped">
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Groups</th>
			<th>Status</th>
			<th>Manage</th>
		</tr>
		<?php foreach ($users as $user):?>
			<tr class="<?php if (! $user->active) { echo 'inactive';} ?>">
				<td><?php echo $user->first_name . ' ' . $user->last_name;?></td>
				<td><?php echo $user->email;?></td>
				<td>
					<?php foreach ($user->groups as $group):?>
						<span <?php if ($group->name == 'admin') {echo('class="admin"'); } ?>>
							<?php echo $group->name;?><br />
						</span>
					<?php endforeach?>
				</td>
				<td><?php echo ($user->active) ? 'Active' : 'Inactive';?></td>
				<td><a class="btn btn-default" href="<?php echo site_url('auth/edit_user/'.$user->id);?>">Manage</a></td>
			</tr>
		<?php endforeach;?>
	</table>
	
	<p><a class="add-anchor btn btn-success" href="<?php echo site_url('auth/create_user');?>"><i class="glyphicon glyphicon-plus"></i> Create a new user</a></p>
	
</div>
<?php $this->load->view('footer');?>

