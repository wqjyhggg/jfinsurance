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
                        <!-- claim_number box -->
                        <div class="form-group col-sm-3">
                           <label class="inline">Policy No.: </label> <?php echo $claim['policy_number']; ?>
                        </div>
                        <!-- claim_number box end -->
                        <!-- claim_number box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Claim No.: </label> <?php echo $claim['claim_number']; ?>
                        </div>
                        <!-- claim_number box end -->
                        <!-- First Name input box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Name: </label> <?php echo $claim['firstname']; ?> <?php echo $claim['lastname']; ?>
                        </div>
                        <!-- First Name input box end -->
                      </div>
                      <div class="row">
                        <!-- Policy Number input box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Birthday: </label> <?php echo $claim['birthday']; ?>
                        </div>
                        <!-- Policy Number input box end -->
                        <!-- Claim Number input box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Gender:</label> <?php echo $claim['gender']; ?>
                        </div>
                        <!-- Claim Number input box end -->
                        <!-- Claim Amount box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Total Claimed Amount:</label> $<?php echo number_format($claimed_amount); ?>
                        </div>
                        <!-- Claim Amount box end -->
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
                            <th><a style="color:#46b8da;" id='print-items' href="<?php echo $letter_url; ?>">Print Letter</a></th>
                            <th>Action</th>
                            <th>Claim Date</th>
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
                              <td><input class='item-print' type='checkbox' data-item-id='<?php echo $c['citem_id']; ?>'></td>
                              <td style='vertical-align: middle;'><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['citem_id']?>">Edit</a></td>
                              <td><?php echo $c['claim_date']; ?></td>
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
<script type="text/javascript">
$( document ).ready(function() {
	$("#print-items").on("click",function(e) {
		e.preventDefault();
		var href = $('#print-items').attr('href') + '?claim_id=' + '<?php echo $claim['claim_id']; ?>';
		$('.item-print:checked').each(function() { href += '&citem_id[]=' + $(this).attr('data-item-id'); });
		window.location.href = href; 
	});
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