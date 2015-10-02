<div class="users form">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= __('Edit User') ?></h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="actions">
				<div class="panel panel-default">
					<div class="panel-heading">Actions</div>
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
                                                                <li><?=
                                    $this->Form->postLink(
                                    __('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete'),
                                    ['action' => 'delete', $user->id],['escape' => false],
                                    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
                                    )
                                    ?>
                                </li>
                                                                <li>
                                    <?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Users'), ['action' => 'index']
,['escape' => false]) ?>
                                </li>
                                							</ul>
						</div>
					</div>
				</div>			
		</div><!-- end col md 3 -->
		<div class="col-md-9">
            <?php echo  $this->Form->create($user); ?>
                    <div class="form-group"><?php echo $this->Form->input('firstname',['class'=>'form-control','placeholder' => 'Firstname']); ?></div>
                    <div class="form-group"><?php echo $this->Form->input('lastname',['class'=>'form-control','placeholder' => 'Lastname']); ?></div>
                    <div class="form-group"><?php echo $this->Form->input('email',['class'=>'form-control','placeholder' => 'Email']); ?></div>
                    <div class="form-group">
                        <label for="UserRole">Role</label>
                        <?php echo $this->Form->select('role', array('admin' => 'Admin', 'editor' => 'Editor', 'user' => 'User'), array('class' => 'form-control', 'required' => 'true'));?>
                    </div>                                         
                    <div class="form-group"><?php echo $this->Form->input('is_active',['class'=>'form-control','placeholder' => 'Is Active']); ?></div>
                    <div class="form-group">
                        <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                        <?php echo $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-default'])?>
                    </div>    
            <?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
