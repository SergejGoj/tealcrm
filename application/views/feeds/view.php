<div class="row">

  <div >

    <div class="portlet">

      <h2 class="portlet-title">
        <u>Notes :: View #<?php echo $note->id?></u>
      </h2>

      <div class="portlet-body">

        <form name="frmview" id="frmview" action="<?php echo site_url('notes/edit/' . $note->id)?>" method="post" class="form parsley-form">
			<div class="form-group">
				<label for="note_id">Note Id</label>
				<div style="clear:both"></div>
				<?php echo $note->note_id?>
			</div>

			<div class="form-group">
				<label for="subject">Subject</label>
				<div style="clear:both"></div>
				<?php echo $note->subject?>
			</div>

			<div class="row">
				<div class="form-group col-sm-6">
					<label for="company_id">Company</label>
					<div style="clear:both"></div>
					<?php echo $note->company_id?>
				</div>
				<div class="form-group col-sm-6">
					<label for="contact_id">Contact No</label>
					<div style="clear:both"></div>
					<?php echo $note->contact_id?>
				</div>
			</div>

			<div class="form-group">
				<label for="description">Descriptions</label>
				<div style="clear:both"></div>
				<?php echo $note->description?>
			</div>

            <div class="form-actions">
            <button class="btn btn-primary" onclick="return edit(this, '<?php echo $note->id?>')">Edit</button>
            <button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
          </div>
          <input type="hidden" name="act" value="save">
        </form>

      </div> <!-- /.portlet-body -->

    </div> <!-- /.portlet -->

  </div> <!-- /.col -->

</div> <!-- /.row -->


<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('notes')?>';
		return false;
	}

	edit=function(elm, id){
		window.location.href = '<?php echo site_url('notes/edit')?>/' + id;
		return false;
	}
</script>