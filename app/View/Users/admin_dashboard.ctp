<div class="page-content-wrapper">
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <h3 class="page-title">
                        Dashboard <small>reports & statistics</small>
                    </h3>
                    <div class="page-bar">
                        <ul class="page-breadcrumb">                            
                            <li>
                                <i class="fa fa-home"></i>
                                <a href="javascript:;">Dashboard</a>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE HEADER-->                    
                 <div class="row">
                                 <?php if($this->Session->read('Auth.User.role_id')==1){ ?>
                                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
						<div class="dashboard-stat green-haze">
							<div class="visual">
								<i class="fa fa-users"></i>
							</div>
							<div class="details">
								<div class="number">
									<?php echo $total_users; ?>
								</div>
								<div class="desc">
									Total Admins
								</div>
							</div>
							
						</div>
					</div>
                                 <?php }else{?>
				     <div class="col-lg-4 col-md-4 col-sm-7 col-xs-12">
						<div class="dashboard-stat green-haze">
							<div class="visual">
								<i class="fa fa-users"></i>
							</div>
							<div class="details">
								<div class="number">
									<?php echo $total_users; ?>
								</div>
								<div class="desc">
									Total Barbers
								</div>
							</div>
							
						</div>
					</div>
					 <div class="col-lg-4 col-md-4 col-sm-7 col-xs-12">
						<div class="dashboard-stat purple-plum">
							<div class="visual">
								<i class="fa fa-mobile-phone"></i>
							</div>
							<div class="details">
								<div class="number">
									<?php echo $total_re_customers; ?>
								</div>
								<div class="desc">
									Total Reservations Customers
								</div>
							</div>
							
						</div>
					</div>					
					<div class="col-lg-4 col-md-4 col-sm-7 col-xs-12">
						<div class="dashboard-stat blue-madison">
							<div class="visual">
								<i class="fa fa-male"></i>
							</div>
							<div class="details">
								<div class="number">
									 <?php echo $total_walk_customers; ?>
								</div>
								<div class="desc">
									Total Walk-In Customers
								</div>
							</div>
							
						</div>
					</div>
                                 <?php } ?>
				</div>   
            </div>
         </div>