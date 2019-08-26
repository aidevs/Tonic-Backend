<?php $this->assign('title', 'An Internal Error Has Occurred.');?>
<div class="col-md-12 page-404">
    <div class="number" style="top: 10px;color: #ec8c8c;">
			 500
		</div>
		<div class="details">
			<h3>Oops! Something went wrong.</h3>
			<p>     We are fixing it!<br/>
                                Please come back in a while. <br/>
				<a href="<?php echo SITE_URL.$this->request->params['prefix']; ?>">
				Return home </a>
				
			</p>
                        
		</div>
               
                <?php
                if (Configure::read('debug') > 0):
                  // echo $this->element('exception_stack_trace');
                endif;
                ?>
</div>