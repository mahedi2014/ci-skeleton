<div id="container">
	<h1>Dashboard</h1>

	<div id="body">

		<?php echo form_open('main/sign_in', array('id'=>'login-form','class'=>'smart-form client-form')); ?>
		</form>

		<p>Calling api <?php echo anchor('api/','api'); ?></a></p>
	</div>

	<p class="footer"><?php echo  (ENVIRONMENT === 'development') ?  'Render time <strong>{elapsed_time}</strong>' : '' ?></p>
</div>
