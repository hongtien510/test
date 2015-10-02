<?php echo $this->Html->css('/admin/assets/admin/css/signin', array('inline' => false)) ?>
<div class="users form form-signin">
<?php echo $this->Flash->render(); ?>
<?php echo $this->Form->create('User'); ?>
	<h2 class="form-signin-heading"><?php echo __('Please log in'); ?></h2>
	<?php echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control', 'required' => true, 'autofocus' => true, 'placeholder' => 'Email address')); ?>
	<?php echo $this->Form->input('password', array('class' => 'form-control', 'required' => true, 'placeholder' => 'Password')); ?>
	<!-- <div class="checkbox">
	  <label>
	    <input type="checkbox" value="remember-me"> Remember me
	  </label>
	</div> -->
	<?php echo $this->Form->button(__('Login'), array('class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit')); ?>
</div>