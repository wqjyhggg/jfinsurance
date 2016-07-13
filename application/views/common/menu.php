<div class="nav-m22d"><!-- Start body content -->
<div class="container body" style="padding:0;"><!-- div.container.body-->
<div class="main_container"><!-- div.main_container -->

<?php 
if (isset($menu) && is_array($menu) && (sizeof($menu)>0)) {
	$user = $this->session->userdata('user');
?>
<div id="leftMenu">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a class="site_title"><i class="fa fa-user"></i> <span><?php echo $user['username']; ?></span></a>
              <p style="padding-left:15px;">Welcome <span><?php echo $user['firstname'] . " " . $user['lastname']; ?></span></p>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <!--div class="profile">
              <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>John Doe</h2>
              </div>
            </div-->
            <!-- /menu profile quick info -->

            <br />


            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <!--h3>General</h3-->
                <ul class="nav side-menu">
                	<?php foreach ($menu as $m) { ?>
                    <!-- if is array, it's submenu -->
                    <?php if (is_array($m)) { ?>
                    <?php $parent = array_shift($m); ?>
                    <li><?php echo $parent; ?>
                        <ul class="nav child_menu">
                          <?php foreach ($m as $msub) { ?>
                          <li> <?php echo $msub; ?> </li>    
                          <?php } ?>
                        </ul>
                    </li>

                      

                    <?php }else{ ?>

                    <!-- else not array, it's parent menu -->
                		<li> <?php echo $m; ?> </li>

                    <?php } ?>

					       <?php } ?>
                  
                </ul>
              </div>
              
            </div>
            <!-- /sidebar menu -->

          </div>
        </div>
</div>
<?php } ?>