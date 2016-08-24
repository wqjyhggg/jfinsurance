<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Plan List page content -->

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
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>Refund Policy</h3>
              </div>
            </div>
            <div class="clearfix"></div>

           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                  <?php if (!empty($error_message)) { ?>
                    <div class="alert-error"> 
                    <?php echo $error_message . "<br>"; ?>
                    <br />
                  </div>
                  <?php } ?>
                    <form action='<?php echo $action_url; ?>' method='POST'  class="form-horizontal">
              				  <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
              				  <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
          					  <div class="row">
                        <div class="form-group col-sm-4 col-xs-12">
                          <label>Effective Date:</label> <?php echo $plan['effective_date']; ?>
                        </div>

                        <div class="form-group col-sm-4 col-xs-12">
                          <label>Expiry Date:</label> <?php echo $plan['expiry_date']; ?>
                        </div>
                        <div class="form-group col-sm-4 col-xs-12">
                          <label>Total Premium:</label> <?php echo $plan['premium']; ?>
                        </div>
                      </div>
                      <div class="row">  
                        <div class="form-group col-sm-4 col-xs-12">
                          <label>Refund Date:</label>
                          <div class="inline-date">
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                              <input class="form-control" size="16" type="text" name='refund_date' id='refund_date' value='<?php echo $plan['expiry_date']; ?>' min='<?php echo $plan['effective_date']; ?>' max='<?php echo $plan['expiry_date']; ?>'>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                          </div>
                          <br />
                        </div>

                        <div class="form-group col-sm-4 col-xs-12 pull-right">
                          <label>Used Premium:</label> <span id="used_amount"></span>
                         
                        </div>
                      </div>

                      <div class="row">  
                        <div class="form-group col-sm-8 col-xs-12">
                            <label class="radio-inline"><input type="radio" name="admin_fee" value="0" checked>$0 Adminstration Fee</label>
                            <label class="radio-inline"><input type="radio" name="admin_fee" value="40">$40 Adminstration Fee</label>
                        </div>
                        <div class="form-group col-sm-4 col-xs-12">
                          <label>Refund Premium:</label> <!--span id="refund_amount"></span-->
                          <input type="number" step="any" name='refund_amount' id='refund_amount' value='' class="form-control" readonly />
                        </div>
      			            
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-8 col-xs-12"></div>       
                        <div class="form-group col-sm-4 col-xs-12">
                          <label>Total Refund:</label> 
                          <input type="number" step="any" name='total_refund' id='total_refund' value='' class="form-control" />
                          <div class="alert-error">Manually changing "Total Refund" will result in a difference in "Un-used Premium" field of the "Refund Letter". Please pay extra attention for your changes.</div>
                        </div>
                      </div>
                      <br />
                      <div class="row">
                        <div class="form-group col-sm-12 text-right">
                          <label class="inline">Are you sure you want to refund this policy? </label>
                         <input class="btn btn-primary inline" type='submit' name='send' value='YES'>
                         <a class="btn btn-default inline" href="<?php echo $url_back_to_policy;?>">NO</a>
                        </div>  
                      </div>
			        </form>
				  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
          </div>
        </div>
      </div>
      <!-- /page content -->
<script type="text/javascript">
<!--
$( document ).ready(function() {
	$('#refund_date').change(get_refund_amount); 
	$('input[name="admin_fee"]').change(get_refund_amount); 
	get_refund_amount();
});

function get_refund_amount() {
	var refund_date = $('input[name="refund_date"]').val();
	
	$.ajax({
		url: '<?php echo $refund_amount_url; ?>',
		type: 'get',
		data: {	refund_date: refund_date },
		success: function(data, textStatus, jqXHR) {
			if (data['status'] == 'OK') {
        		$('input[name="refund_amount"]').val(data['refund_amount']);
            //$('#refund_amount').text(data['refund_amount']);
            $('#used_amount').text(data['used_amount']);
            var adminfee = $('input[name="admin_fee"]:checked').val();
            var total_refund = parseFloat(data['refund_amount'] - adminfee).toFixed(2);
    		$('input[name="total_refund"]').val(total_refund);
			}
    	},
	});
} 
//-->
</script>
