
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$show = true;
$fYear = $this->lang->line('Year');
if ($fYear != "Year") {
  $show = false;
}
?>
<div class="right_col" role="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12" style="min-height:700px;">
				<?php 
        if ($show) {
          echo $html; 
        } else {
          echo "Pas disponible";
        }
        ?>
			</div>
		</div>
	</div>
</div>
