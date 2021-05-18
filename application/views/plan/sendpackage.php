<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
        <h3>Send Policy Package</h3>
      </div>
    </div>
    <div class="clearfix"></div>

    <!-- Filter Section -->
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <?php if (!empty($error_message)) {
              echo $error_message . "<br>";
            } ?>
            <form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal">
              <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
              <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
              <div class="row">
                <!-- Product select box -->
                <div class="form-group col-sm-4 col-xs-12">
                  <label class="col-sm-12">Will Send Package To this Email address:</label>
                  <div class="input-group col-sm-12">
                    <input type="text" name='emailaddr' value='<?php echo $html_model->escapeQuote($emailaddr); ?>' class="form-control" />
                  </div>
                </div>
                <?php if (($beuser['user_group_id'] < 100) || ($beuser['user_id'] == 3744)) { ?>
                  <div class="form-group col-sm-4 col-xs-12">
                    <label class="col-sm-12">Export Option:</label>
                    <div class="input-group col-sm-12">
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type='checkbox' class='withlogobox' name='withlogo' checked> With Logo &nbsp;&nbsp;
                      <input type='checkbox' class='withpricebox' name='withprice' checked> With Price
                      <?php if ($plan['batch_number'] > 0) { ?>
                      <br />
                      <input type='checkbox' class='withbatchbox' name='withbatch'> Send All whith same batch number (<?php echo $plan['batch_number']; ?>)
                      <?php } ?>
                    </div>
                  </div>
                <?php } ?>
                <!-- Policy Number input box end -->
                <?php if (!empty($plan_batch)) { ?>
                <?php } ?>
                <div class="form-group col-sm-4">
                  <label class="col-sm-12">&nbsp;</label>
                  <button class="btn btn-primary" type='button' id='form_batch_submit' style='display:none;'>Send</button>
                  <input class="btn btn-primary" type='submit' name='send' value='Send' id='form_submit'>
                </div>
              </div>
            </form>
            <div id='sending'></div>
          </div>
        </div>
      </div>
    </div><!-- End Filter Section -->
  </div>
</div>
<?php if (!empty($plan_batch)) { ?>
<script>
var plan_id_arr = [];
var plan_id_idx = 0;
var intv;
<?php
$idx = 0;
foreach ($plan_batch as $p) {
  echo "plan_id_arr[".$idx++."]=".$p['plan_id'].";\n";
} 
?>
function send_one_package() {
  if (plan_id_idx >= plan_id_arr.length) {
    clearInterval(intv);
   //XXXXX window.location.href = '<?php echo base_url("plan"); ?>';
    var html = $('#sending').html();
    html = "Sending package finished with count : " + plan_id_idx + "<br>" + html;
    $('#sending').html(html);
    return ;
  }
  var withlogo = 0;
  if($('input[name=withlogo]').is(":checked")) {
    withlogo = 1;
  }
  var withprice = 0;
  if($('input[name=withprice]').is(":checked")) {
    withprice = 1;
  }
  
  var formdata = new FormData();
  formdata.append('withlogo',withlogo);
  formdata.append('withprice',withprice);
  formdata.append('withbatch','1');
  formdata.append('emailaddr','');
  $.ajax({
			url: '<?php echo $action_url; ?>' + '/' + plan_id_arr[plan_id_idx],
			data: formdata,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 600000,	// 10 mintes 
			type: 'POST',
			dataType: 'text',
			error: function(jqXHR, textStatus, errorThrown) {
        var html = $('#sending').html();
        html += "Sending package fail<br>";
        $('#sending').html(html);
			},
			success: function(rt) {
        var html = $('#sending').html();
        html = "Sending package to plan id : " + plan_id_arr[plan_id_idx] + " " + rt + "<br>" + html;
        $('#sending').html(html);
        plan_id_idx++;
			},
    });
}

$( document ).ready(function() {
	$("#form_batch_submit").on("click",function(e) {
    e.preventDefault();
    send_one_package();
    if (plan_id_arr.length > 1) {
      intv = setInterval(send_one_package, 15000);
    }
	});
  $('.withbatchbox').on("change", function(e) {
    if($(this).is(":checked")){
      $('#form_batch_submit').show();
      $('#form_submit').hide();
    } else {
      $('#form_batch_submit').hide();
      $('#form_submit').show();
    }
  });
})
</script>
<?php } ?>
</div>
<!-- /page content -->
