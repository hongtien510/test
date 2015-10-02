<div class="loanRates form">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= __('Add Loan Rate') ?></h1>
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
                                                                
                                                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Loan Rates'), ['controller' => 'LoanRates', 'action' => 'index']
,['escape' => false]) ?> </li>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;
New Loan Rate'), ['controller' => 'LoanRates', 'action' => 'add']
,['escape' => false]) ?> </li>
                                            							</ul>
						</div>
					</div>
				</div>			
		</div><!-- end col md 3 -->
		<div class="col-md-9">
            <?php echo  $this->Form->create($loanRate); ?>
                             <div class="form-group"><?php echo $this->Form->input('rate',['class'=>'form-control','placeholder' => 'Rate']); ?></div>
                                         <div class="form-group"><?php echo $this->Form->input('activate',['class'=>'form-control','placeholder' => 'Activate']); ?></div>
                                <div class="form-group">
                <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                <?php echo $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-default'])?>
            </div>    
            <?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
