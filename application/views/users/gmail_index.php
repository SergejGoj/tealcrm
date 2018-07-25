 <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('meetings/gmail_index')?>" method="post">
 <h2 class="text-left"><strong>Emails</strong></h2>
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
                          <th align="center" width="10%">No.</th>
                          <th align="center" width="40%">Subject</th>
                          <th align="center" width="20%">From</th>
                          <th align="center" width="20%">Date</th>
						  <th align="center" width="10%">Category</th>
                      </tr>
                  </thead>
                  <tbody>
				  <?php 
				  if(isset($Gmail))
				  {
					$i=1;
					foreach ($Gmail as $email) 
					{
					?>
						<tr>
                          <td class="valign-middle"><?php echo $i; ?></td>
                          <td class="valign-middle" ><a href="<?php echo site_url('users/gmail_view/'.$email->mail_id); ?>"><?php echo $email->subject;?></a></td>
                          <td class="valign-middle"><?php echo $email->from_name; ?></td>
						  <td class="valign-middle"><?php echo $email->received_date; ?></td>
						  <td class="valign-middle"><?php echo $email->category; ?></td>
						</tr>
				  <?php 
					$i++;
					}
				  }
				  else
				  {
					?>
					  <tr>
                          <td colspan="6" align="center">No Emails</td>
                      </tr>		
					<?php 
				  }
				  ?>
                  </tbody>
              </table>
       </form>


