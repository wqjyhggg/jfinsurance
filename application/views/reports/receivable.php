<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Receivable report page content -->

	    <!-- Content top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <!-- User section -->
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/img.jpg" alt="">John Doe
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
                <!-- User section End -->
              </ul>
            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Receivable Report</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <!-- Common Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Common Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Product:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Agent:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Date Type</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 form-group">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- Common Filter Form End -->

              <!-- Monthly Report Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Monthly Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                      
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">&nbsp;</label>
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <!-- Monthly Report Filter Form End-->

              <!-- Daily Report Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Daily Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Day</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 form-group">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <!-- Daily Report Filter Form End-->

              <!-- Duration Report Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Duration Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">From Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>              
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Day</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">To Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>    
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Day:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>


                      <div class="col-sm-12 form-group">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <!-- Duration Report Filter Form End-->

            </div>

          </div>
        </div>
        <!-- /page content -->
        