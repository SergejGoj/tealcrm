<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">


      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li <?php if( 'crm-settings' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/#crm-settings" data-toggle="tab">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;CRM Settings
          </a>
        </li>
        <li <?php if( 'user-management' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/#user-management" data-toggle="tab">
          <i class="fa fa-male"></i>
          &nbsp;&nbsp;User Management
          </a>
        </li>
<!--
        <li <?php if( 'plans-billing' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/billing">
          <i class="fa fa-dollar"></i>
          &nbsp;&nbsp;Usage Information
          </a>
        </li>
-->

        <li <?php if( 'drop-down-editor' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/drop_down_editor">
          <i class="fa fa-archive"></i>
          &nbsp;&nbsp;Drop Down Editor
          </a>
        </li>

		<li <?php if( 'custom_fields' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/custom_fields">
          <i class="fa fa-archive"></i>
          &nbsp;&nbsp;Custom Fields
          </a>
        </li>
       <?php /* 
		<li <?php if( 'custom_layouts' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/#custom_layouts" data-toggle="tab">
          <i class="fa fa-barcode"></i>
          &nbsp;&nbsp;Custom Layouts
          </a>
        </li>
        */ ?>
		<li <?php if( 'custom_list_views' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('settings')?>/custom_list_views">
          <i class="fa fa-barcode"></i>
          &nbsp;&nbsp;Custom List Views
          </a>
        </li>
       
		</ul>

    </div> <!-- /.col -->


  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade <?php if( 'crm-settings' == $section ):?>in active<?php endif;?>" id="crm-settings">

        <h3 class="content-title">CRM Settings</h3>

        <div><?php display_notify('settings_crm_settings') ?> </div>

<?php

if (!empty($success)) : ?>
<div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><?php echo $success;?></div>
<?php endif;

echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div>');

$attributes = array('class' => 'form-horizontal', 'id' => 'frmprofile', 'name' => 'frmprofile');

echo form_open('settings', $attributes); ?>



          <div class="form-group">

            <label class="col-md-3">Business Name</label>

            <div class="col-md-7">
              <input type="text" name="business_name" value="<?php echo $setting->business_name ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
          
          <div class="form-group">

            <label class="col-md-3">Billing Email Address</label>

            <div class="col-md-7">
              <input type="text" name="billing_email" value="<?php echo $setting->billing_email ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->          

          <div class="form-group">

            <label class="col-md-3">Address 1</label>

            <div class="col-md-7">
              <input type="text" name="address1" value="<?php echo $setting->address1 ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

          <div class="form-group">

            <label class="col-md-3">Address 2</label>

            <div class="col-md-7">
              <input type="text" name="address2" value="<?php echo $setting->address2 ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

           <div class="form-group">

            <label class="col-md-3">Country</label>

            <div class="col-md-7">
              <?php echo form_dropdown('country', $countries, $setting->country,"class='form-control'"); ?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

           <div class="form-group">

            <label class="col-md-3">City</label>

            <div class="col-md-7">
              <input type="text" name="city" value="<?php echo $setting->city ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

           <div class="form-group">

            <label class="col-md-3">Province/State</label>

            <div class="col-md-7">
              <?php echo form_dropdown('province', $provinces, $setting->province,"class='form-control'"); ?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

           <div class="form-group">

            <label class="col-md-3">Postal Code</label>

            <div class="col-md-7">
              <input type="text" name="postal_code" value="<?php echo $setting->postal_code ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

           <div class="form-group">

            <label class="col-md-3">Timezone</label>
            <div class="col-md-7">
            <select name="timezone" class="form-control">
            <?php // display timezone list

            $timezone_identifiers = DateTimeZone::listIdentifiers();
            foreach ($timezone_identifiers as $timezone){
	            echo '<option value="'.$timezone.'"';
	            if($timezone == $setting->timezone){
	            		echo ' selected';
	            	}
	            echo '>'.$timezone.'</a>';
            }
            ?>
            </select>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

          <div class="form-group">
            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default">Cancel</button>
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->
           <input type="hidden" name="act" value="crm-settings" />
        </form>


      </div> <!-- /.tab-pane -->


      <div class="tab-pane fade <?php if( 'user-management' == $section ):?>in active<?php endif;?>" id="user-management">

        <h3 class="content-title">User Management</h3>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<div class="form-group">
						<ul id="myTab1" class="nav nav-tabs">
							<li class="<?php //if($search_tab == "basic"){ echo 'active';}?>">
							<a href="#search" data-toggle="tab">Active Users</a>
							</li>

							<li class="<?php //if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
							<a href="#advanced" data-toggle="tab">Inactive Users</a>
							</li>
						</ul>
						<div id="myTab1Content" class="tab-content">
						<div class="tab-pane fade active in<?php //if($search_tab == "basic"){ echo 'active in';}?>" id="search">
						<form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('people')?>" method="post">
			  <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>
									<th><input type="checkbox" name="select_all" value="ids[]"></th>
									<th>Name</th>
									<th>Type</th>
									<th>Email</th>
									<th>Actions</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($_SESSION['user_accounts'] as $usr){
								  
							  ?>
									<tr>
										<td><input type="checkbox" name="ids[]" value="<?php echo $usr['id']; ?>"></td>
										<td><a href="<?php echo site_url('settings/users/' . $usr['id']); ?>"><?php echo $usr['name']; ?></a></td>
										<td>GROUP</td>
										<td><?php echo $usr['email'];?></td>
										<td class="valign-middle">
											<a href="<?php echo site_url('settings/users/' . $usr['id']); ?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											&nbsp;
											<a href="javascript:delete_one( '<?php echo $usr['id']; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>

										</td>
									</tr>
							  <?php } ?>
						  </tbody>
					  </table>
					  <div>
						  <div class="list-footer-left">
							  <button type="button" class="btn btn-danger" onclick="delete_all()">Inactive</button>
							  <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('settings/add')?>'">Add New</button>
						  </div>
					  </div>
					  <input type="hidden" name="act" value="">
			  </div> <!-- /.table-responsive -->
 		    </form>


						</div>
						<div class="tab-pane fade <?php //if($search_tab == "basic"){ echo 'active in';}?>" id="advanced">
						<form class="form-horizontal" name="frmlist1" id="frmlist1" action="<?php echo site_url('people')?>" method="post">
			  <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>
									<th><input type="checkbox" name="select_all" value="ids[]"></th>
									<th>Name</th>
									<th>Type</th>
									<th>Email</th>
									<th>Actions</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($flexi_users_inactive as $usr_inactive){?>
									<tr>
										<td><input type="checkbox" name="userid[]" value="<?php echo $usr_inactive->id; ?>"></td>
										<td><a href="<?php echo site_url('settings/users/' . $usr_inactive->id); ?>"><?php echo $usr_inactive->username; ?></a></td>
										<td><?php echo $flexi_groups[$usr_inactive->uacc_group_fk];?></td>
										<td><?php echo $usr_inactive->uacc_email;?></td>
										<td class="valign-middle">
											<a href="<?php echo site_url('settings/users/' . $usr_inactive->id); ?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											&nbsp;
											<a href="javascript:delete_one( '<?php echo $usr_inactive->id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>

										</td>
									</tr>
							  <?php } ?>
						  </tbody>
					  </table>
					  <div>
						  <div class="list-footer-left">
							  <button type="button" class="btn btn-success" onclick="active_all()">Active</button>

						  </div>
					  </div>
					  <input type="hidden" name="act" value="">
			  </div> <!-- /.table-responsive -->
 		    </form>


						</div>
						</div> <!-- /.form-group -->
				</div> <!-- /.table-responsive -->
			</div> <!-- /.col -->
		</div> <!-- /.row -->
    </div>

</div>


		   <!-- /.col -->



           <!-- /.row -->

       <!-- /.tab-pane -->

	 <div class="tab-pane fade <?php if( 'custom_layouts' == $section ):?>in active<?php endif;?>" id="custom_layouts">

        <h3 class="content-title">Custom Layouts</h3>


        <?php //display_notify('settings_plans-billing') ?>

		<div class="row">

        <div class="col-sm-7 col-md-6">

          <div class="well">
            <h4>Coming Soon...!<h4>
          </div> <!-- /.well -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->

      </div> <!-- /.tab-pane -->


      <div class="tab-pane fade <?php if( 'drop-down-editor' == $section ):?>in active<?php endif;?>" id="drop-down-editor">

        <h3 class="content-title">Drop Down Editor</h3>

        <br><br>


      </div> <!-- /.tab-pane -->


    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->
<script>
  // delete single record
  delete_one=function( company_id ){
    // confirm
    Messi.ask('Are you sure you want to make this user inactive?', function(val) {
      // confirmed
      if( val == 'Y' ){
        window.location.href="<?php echo site_url('settings/delete')?>/" + company_id;
      }
    }, {modal: true, title: 'Confirm Action'});
  }

  // delete all(selected) record
  delete_all=function( ){
    size = jQuery(":input[name='ids[]']:checked").length;
    // none selected
    if( size == 0 ){
      Messi.alert('Please select a user ',function(){

      }, {modal: true, title: 'Confirm Action'});

      return;
    }
    // confirm
    Messi.ask('Do you really want to make the selected users inactive?', function(val) {
      // confirmed
      if( val == 'Y' ){
        jQuery('#frmlist').prop('action', '<?php echo site_url('settings/delete_all')?>');
        jQuery(":input[name='act']").val('delete');
        jQuery('#frmlist').submit();
      }
    }, {modal: true, title: 'Confirm Action'});
  }

  active_all=function( ){
    size = jQuery(":input[name='userid[]']:checked").length;
    // none selected
    if( size == 0 ){
      Messi.alert('Please select a user ',function(){

      }, {modal: true, title: 'Confirm Action'});

      return;
    }
    // confirm
    Messi.ask('Do you really want to make the selected users active?', function(val) {
      // confirmed
      if( val == 'Y' ){
        jQuery('#frmlist1').prop('action', '<?php echo site_url('settings/active_all')?>');
        jQuery(":input[name='act']").val('delete');
        jQuery('#frmlist1').submit();
      }
    }, {modal: true, title: 'Confirm Action'});
  }

	// document ready
	jQuery(document).ready(function(){
		jQuery(":input[name='select_all']").bind('click', function(){
			jQuery(":input[name='" + jQuery(this).val() + "']").prop('checked', jQuery(this).prop('checked'));
		});


	});
</script>