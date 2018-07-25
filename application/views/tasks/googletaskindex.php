 <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('tasks/googletaskindex')?>" method="post">
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
                          <th>No.</th>
                          <th>Name</th>
                          <th>Due date</th>
                          <th class="text-center" width="10%">Status</th>
                      </tr>
                  </thead>
                  <tbody>
                   <?php if(isset($Gtask))
				    { $i = 1;
					foreach($Gtask as $task) {?>
						<tr>
                          <td><?php echo $i; ?></td>
                          <td class="valign-middle"><a href="<?php echo site_url('tasks/googletaskview/'.$task->google_task_id); ?>"><?php echo $task->subject; ?></a></td>
                          <td class="valign-middle"><?php echo $task->due_date; ?></td>
						  <td class="valign-middle">
                              <?php echo $task->status; ?>
                          </td>
						</tr>
                      <?php $i++;
						}
					  }
					  else
					  { ?>
					  <tr>
                          <td colspan="6" align="center">No Google Task</td>
                      </tr>					  
					  <?php }?>
                  </tbody>
              </table>
              <div>
            </div>
       </form>


