

<div class="row">

  <div>

    <div class="portlet">

      <h2 class="portlet-title">
        <u>Add New Product</u>
      </h2>

       <?php
		echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>','</div> <!-- /.alert -->');
       ?>

      <div class="portlet-body">

        <form action="<?php echo site_url('settings/products/add')?>" method="post" class="form parsley-form">

  			<div class="form-group">
				<label for="subject">Product Name</label>
				<input type="text" name="product_name" id="product_name" class="form-control"  placeholder="Enter name of product">
			</div>

			<div class="row">
				<div class="col-sm-6">
					<label for="name">Product Type</label>
					<?php echo form_dropdown('product_type', $product_types, '',"class='form-control' id='product_type'"); ?>								</div>

				<div class="col-sm-6">
					<label for="company_type">Quantity In Stock</label>
					<input type="text" name="quantity_in_stock" id="quantity_in_stock" class="form-control"/>

				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-sm-6">
					<label for="name">Status</label>
					<?php
						$options = array(
                  '0'  => 'Active',
                  '1'    => 'Inactive'
                );

						echo form_dropdown('active', $options, '',"class='form-control' id='active'"); ?>
				</div>

				<div class="col-sm-6">
					<label for="company_type">Tax Percentage (%)</label>
					<input type="text" name="tax_percentage" id="tax_percentage" class="form-control"/>

				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-sm-6">
					<label for="name">Cost ($)</label>
					<input type="text" name="cost" id="cost" class="form-control" />							</div>

				<div class="col-sm-6">
					<label for="company_type">List Price ($)</label>
					<input type="text" name="list_price" id="list_price" class="form-control"/>

				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-sm-6">
					<label for="name">Manufacturer Part Number</label>
					<input type="text" name="manufacturer_part_number" id="manufacturer_part_number" class="form-control" placeholder="Enter a number"/>							</div>

				<div class="col-sm-6">
					<label for="company_type">Vendor Part Number</label>
					<input type="text" name="vendor_part_number" id="vendor_part_number" class="form-control" placeholder="Enter a number"/>

				</div>
			</div>
			<br/>
			<div class="row">
				<div class="form-group col-sm-12">
					<label for="description">Description</label>
					<textarea name="description" id="description" class="description form-control"></textarea>
				</div>
			</div>

			<div class="form-actions">
				<button type="submit" id="submit_btn" class="btn btn-primary">Save</button>
				<button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
			</div>
			<input type="hidden" name="act" value="save">
        </form>

      </div> <!-- /.portlet-body -->

    </div> <!-- /.portlet -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

    <!-- include summernote -->
<link rel="stylesheet" href="css/summernote.css">
<script type="text/javascript" src="js/summernote.js"></script>
  <script type="text/javascript">

  	cancel=function(elm){
		window.location.href = '<?php echo site_url('settings/products')?>';
		return false;
	}

  </script>
