<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Plan page content -->
<?php if (empty($combined)) { ?>
	    <!-- Content top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main">
          
            <div class="page-title">
              <div class="title_left">
                <h3>Claim Item List</h3>
              </div>

            </div>
            <div class="clearfix"></div>
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
<?php if (!empty($error_message)) { ?>
                  <div class="x_content">
                  	<div class='alert-error'><?php echo $error_message; ?></div>
                  </div>
<?php } ?>
                  <div class="x_content">
                   
                    <form method="post" class="form-horizontal" action='<?php echo $add_url; ?>'>
                        <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    	<input type="hidden" name="claim_id" value='<?php echo $claim['claim_id']; ?>'>
                      <!-- personal information search -->
                      <div class="row">
                        <div class="form-group col-sm-2">
                           <label class="inline">Claim No.: </label> <?php echo $claim['claim_number']; ?>
                        </div>
                        <div class="form-group col-sm-2">
                           <label class="inline">Product : </label> <?php echo $claim['product_short']; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Policy No.: </label> <?php echo $claim['policy_number']; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Finish Claim: </label> <?php if ($claim['done'] == 1) { echo "Finished"; } else { echo "Processing"; } ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-2">
                           <label class="inline">Sum Insured : </label> <?php echo empty($plan['sum_insured']) ? '0' : $plan['sum_insured']; ?>
                        </div>
                        <div class="form-group col-sm-2">
                           <label class="inline">Deductible : </label> <?php echo empty($plan['deductible_amount']) ? '0' : $plan['deductible_amount']; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Agent : </label> <?php echo $agent['firstname'] . " " . $agent['lastname']; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Agent Company : </label> <?php echo $agent['business']; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-2">
                           <label class="inline">Claim Date : </label> <?php echo $claim['claim_date']; ?>
                        </div>
                        <div class="form-group col-sm-2">
                           <label class="inline">Last Name : </label> <?php echo $claim['lastname']; ?>
                        </div>
                        <div class="form-group col-sm-2">
                          <label class="inline">First Name : </label> <?php echo $claim['firstname']; ?>
                        </div>
                        <div class="form-group col-sm-2">
                          <label class="inline">Birthday : </label> <?php echo $claim['birthday']; ?>
                        </div>
                        <div class="form-group col-sm-2">
                          <label class="inline">Gender : </label> <?php echo $claim['gender']; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-10">
                          <label class="inline">Total Claimed Amount:</label> $<?php echo number_format($claimed_amount,2); ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-10">
                          <label class="inline">Note:</label> <?php echo $claim['note']; ?>
                        </div>
                      </div>
                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-primary pull-right">Add New Claim Item</button>
                          </div> 
                        <!-- submit button -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
<?php } else { ?>
            <div class="page-title">
              <div class="title_left">
                <h3>Claim Item List
                <form method="post" action='<?php echo $add_url; ?>' style='display: initial;'>
                  <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                  <input type="hidden" name="claim_id" value='<?php echo $claim['claim_id']; ?>'>
                  <button class="btn btn-primary pull-right">Add New Claim Item</button>
                </form>
                </h3>
              </div>

            </div>
            <div class="clearfix"></div>
<?php } ?>

<?php if (!empty($lists)) { ?>
            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th style='text-align:center'><button class="btn btn-primary" id='print-items'>Print</button></th>
                            <th>Action</th>
                            <th>Claim Date</th>
                            <th>Diagnosis</th>
                            <th>Claim Amount</th>
                            <th>Paid</th>
                            <th>Pay To</th>
                            <th>Cheque Number</th>
                            <th>Recieved</th>
                            <th>Service Date</th>
                            <th>Notes</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lists as $c) { ?>
                            <tr>
                              <td style='text-align:center'><input class='item-print' type='checkbox' data-item-id='<?php echo $c['citem_id']; ?>'></td>
                              <td style='vertical-align: middle;'><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['citem_id']?>">Edit</a></td>
                              <td><?php echo $c['claim_date']; ?></td>
                              <td><?php echo $c['diagnosis']; ?></td>
                              <td><?php echo $c['claimed']; ?></td>
                              <td><?php echo $c['paid']; ?></td>
                              <td><?php echo $c['pay_to']; ?></td>
                              <td><?php echo $c['cheque_number']; ?></td>
                              <td><?php echo $c['received']; ?></td>
                              <td><?php echo $c['service_date']; ?></td>
                              <td><?php echo str_replace("\n", "<br />", $c['internal_note']); ?></td>
                            </tr>
                        <?php } ?>    
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->
<?php } else { ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              	No Data for list
              </div>
            </div><!-- End List Section -->
<?php } ?>
<div id='claim-letter-popup'></div>
<script type="text/javascript">
$( document ).ready(function() {
	$("#print-items").on("click",function(e) {
		e.preventDefault();
		var href = '<?php echo $letter_url; ?>' + '?claim_id=' + '<?php echo $claim['claim_id']; ?>';
		var ids = '';
		$('.item-print:checked').each(function() { ids += '&citem_id[]=' + $(this).attr('data-item-id'); });
		if (ids == '') {
			alert('Please select item(s) to print');
		} else {
			//window.location.href = href; 
			$.ajax({
				url: href + ids,
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
					$('#claim-letter-popup').html(data);
					$('#claim-letter-popup').addClass('show-pop');
					$('#claim-letter-popup').show();
					$('#claim-letter-close').on("click", function(e) {
						e.preventDefault();
						$('#claim-letter-popup').hide();
					});
				},
			});
		}
	});
	$('#claim-letter-popup').hide();
	/*
	$("#print-items").on("click",function(e) {
		e.preventDefault();
		var data = { 'claim_ids[]' : [] };
		$('.item-print:checked').each(function() { data['claim_ids[]'].push($(this).attr('data-item-id')); });
		$.ajax({
			url: '<?php echo $deductible_amount_url; ?>',
			type: 'POST',
			data: data,
			success: function(data, textStatus, jqXHR) {
				$('#deductible_amount_div').html(data);
			},
		});
	});
	*/
})
</script>          
<?php if (empty($combined)) { ?>
        </div>
        <!-- /page content -->
<?php } ?>