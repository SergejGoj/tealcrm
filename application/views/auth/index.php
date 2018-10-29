<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">

      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li>
          <a href="<?php echo site_url('settings')?>">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;Back to CRM Settings
          </a>
        </li>

        <li class="active">
          <a href="<?php echo site_url('settings/users')?>">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;User Settings
          </a>
        </li>

      </ul>

    </div> <!-- /.col -->

  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade in active" id="update-profile">
<?php display_notify(); ?>
        <h3 class="content-title">User Management</h3>

		
		<div id="infoMessage"><?php echo $message;?></div>
		
		<table cellpadding=0 cellspacing=10 class="table table-striped table-bordered thumbnail-table">
			<tr>
				<th><?php echo lang('index_fname_th');?></th>
				<th><?php echo lang('index_lname_th');?></th>
				<th><?php echo lang('index_email_th');?></th>
				<th>Status</th>
				<th><?php echo lang('index_action_th');?></th>
			</tr>
			<?php foreach ($users as $user):?>
				<tr>
		            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
		            <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
		            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
		            <td>
			            <?php if ($user->active ==1){
				            ?><font color=green>Active</font><?php
			            }
			            else{
				            ?><font color=red>Inactive</font><?php
			            } ?>
		            </td>
					<td><?php echo anchor("auth/edit_user/".$user->id, 'Edit') ;?></td>
				</tr>
			<?php endforeach;?>
		</table>
		
		<p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?></p>
		
     </div> <!-- /.tab-pane -->

    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->		
		
		
		