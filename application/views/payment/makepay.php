<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

<!-- Plan page content -->

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
	<div class="main-div" style="padding-bottom:50px;">
		<div class="page-title">
			<div class="title_left">
				<h3>Make Payment</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Form Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2 class="col-xs-8 col-sm-8">Payment List<small></small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<form  action='<?php echo $pay_url; ?>' method='POST' id="myForm" class="form-horizontal">
							<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<div class="row">
								<div class="col-sm-12">
									<fieldset>
										<legend>Pay Information</legend>
										<div class="row">
											<div class="col-sm-3">
												<label class="col-sm-12">Invoice: </label>
												<div class="input-group col-sm-12">
													<!-- div id='totaldays' class="div-box"></div -->
													<input class="form-control" type='text' name='invoice_num' id='invoice_num' value='' >
												</div>
											</div>
											<div class="col-sm-3">
												<label class="col-sm-12">Bank Name: </label>
												<div class="input-group col-sm-12">
													<!-- div id='totaldays' class="div-box"></div -->
													<input class="form-control" type='text' name='bank_name' id='bank_name' value='' >
												</div>
											</div>
											<div class="col-sm-3">
												<label class="col-sm-12">Payer: </label>
												<div class="input-group col-sm-12">
													<input class="form-control" type='text' name='payor_name' id='payor_name' value='' >
												</div>
											</div>
											<div class="col-sm-3">
												<label class="col-sm-12">Pay Method: </label>
												<div class="input-group col-sm-12">
                          <select name='pay_mothed' id="pay_mothed" class="form-control" style="padding:6px 2px;">
                            <option value=''>-- Select Method --</option>
                            <option value='Alipay'>Alipay</option>
                            <option value='Cash'>Cash</option>
                            <option value='Checque'>Checque</option>
                            <option value='Credit Card'>Credit Card</option>
                          </select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<label class="col-sm-12">Cheque Number: </label>
												<div class="input-group col-sm-12">
													<input class="form-control" type='text' name='cheque_number' id='cheque_number' value='' >
												</div>
											</div>
											<div class="col-sm-3">
												<label class="col-sm-12">Pay To: </label>
												<div class="input-group col-sm-12">
													<input class="form-control" type='input' name='pay_to' id='pay_to' value=''>
												</div>
											</div>
											<div class="col-sm-3">
												<label class="col-sm-12">Receive Date: </label>
												<div class="input-group col-sm-12">
                          <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='cheque_cash_date'>
			                        <input class="setpremium form-control" size="16" type="text" name='cheque_cash_date' id='cheque_cash_date_real' value=''>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-12">Notes: </label>
												<div class="input-group col-sm-12">
										 			<textarea class="form-control" name="note" id="note"></textarea>
										 		</div>
										 	</div>
										</div>
										
									</fieldset>
								</div>
							</div><br />
							
							<div class="row">
								<div class="col-sm-12">
									<input class="btn btn-primary pull-right" type='submit' name='pay_submit1' id='pay_submit1' value='Make Pay' />		
									<input class="btn btn-primary pull-right" type='hidden' name='pay_submit' id='pay_submit' value='Make Pay' />		
								</div>
								</div>
							</div><br />

							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive" style="margin-right:5px;margin-left:5px;">
				                      <table class="table table-hover table-bordered">
				                      	<thead>
											<tr>
												<th>&nbsp;</th>
												<th>Policy</th>
												<th>Type</th>
												<th>Batch Pay</th>
												<th>Added Date</th>
												<th>Amount</th>
												<th>Note</th>
											</tr>
										</thead>
										<tbody>
									<?php foreach ($payments as $payment) { ?>
										<tr>
											<td><input type='checkbox' name='payment[]' value='<?php echo $payment['payment_id']; ?>' checked ></td>
											<td><?php echo $payment['policy']; ?></td>
											<td><?php echo $payment['pay_type']; ?></td>
											<td><?php if (!empty($payment['batch_number'])) { ?><input type='checkbox' name='batchpay_<?php echo $payment['payment_id']?>' value='1'><?php echo $payment['batch_number']; } ?> </td>
											<td><?php echo $payment['added']; ?></td>
											<td><?php echo $payment['amount']; ?></td>
											<td><?php echo ((strlen($payment['note']) > 40) ? $payment['note'] : (substr($payment['note'], 0, 37) . "...")); ?></td>
										</tr>
									<?php } ?>
									    </tbody>
									</table>
								  </div>
								</div>
							</div>
						</form>
					</div><!-- x_content -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Form -->
	</div>
</div>
<!-- /page content -->
<script type="text/javascript">
$(document).ready(function(){
  // $('#pay_submit').attr("disabled", true);

  $('#myForm').on('submit', function(e){
    e.preventDefault();
    var pay_method = $('#pay_mothed').val();
    if (pay_method == '') {
      alert("Please select Pay Method");
      return;
    } else if (pay_method == 'Checque') {
      var len = $('#cheque_number').val().length;
      if (len < 3) {
        alert("Please input Cheque Number");
        return;
      }
    } else if (pay_method == 'Cash') {
      var len = $('#cheque_cash_date_real').val().length;
      if (len < 1) {
        alert("Please input Receive Date");
        return;
      }
      var len = $('#note').val().length;
      if (len < 1) {
        alert("Please input Notes");
        return;
      }
    }
    this.submit();
  });
});
</script>
