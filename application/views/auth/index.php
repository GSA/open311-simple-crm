<?php $this->load->view('header');?>
<div class='mainInfo'>

	<h2>All Users</h2>

	<div id="infoMessage"><?php echo $message;?></div>

	<table class="table table-bordered tablesorter table-striped">
		<tr>
			<th>Name</th>
			<th>Company</th>
			<th>Email</th>
			<th>Groups</th>
			<th>Status</th>
			<th>Last Logged in</th>
			<th>Manage</th>
		</tr>
		<?php foreach ($users as $user):?>
            <tr class="<?php if (! $user->active) { echo 'inactive';} ?><?php echo ($user->is_admin?'admin':'')?>">
				<td><?php echo $user->first_name . ' ' . $user->last_name;?></td>
				<td><?php echo $user->company;?></td>
				<td><?php echo $user->email;?></td>
				<td>
					<?php foreach ($user->groups as $group):?>
						<span <?php if ($group->name == 'admin') {echo('class="admin"'); } ?>>
							<?php echo $group->name;?><br />
						</span>
					<?php endforeach?>
				</td>
				<td><?php echo ($user->active) ? 'Active' : 'Inactive';?></td>
                <td><?php echo (date("Y-m-d H:i:s",$user->last_login));?></td>
				<td><a class="btn btn-default" href="<?php echo site_url('auth/edit_user/'.$user->id);?>">Manage</a></td>
			</tr>
		<?php endforeach;?>
	</table>

<!--	<p><a class="add-anchor btn btn-success" href="--><?php //echo site_url('auth/create_user');?><!--"><i class="glyphicon glyphicon-plus"></i> Create a new user</a></p>-->

</div>
<?php $this->load->view('footer');?>

