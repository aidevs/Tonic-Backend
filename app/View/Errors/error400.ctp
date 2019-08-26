<?php $this->assign('title', 'Oops! Page not found.');?>
<div class="col-md-12 page-404">
    <div class="number" style="top: 22px;">
			 404
		</div>
		<div class="details">
			<h3>Oops! You're lost.</h3>
			<p>
				 We can not find the page you're looking for.<br/>
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