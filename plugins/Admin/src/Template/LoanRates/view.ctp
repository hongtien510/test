<div class="view">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Loan Rate</h1>
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
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit Loan Rate'), ['action' => 'edit', $loanRate->loan_rate_id],['escape' => false]) ?> </li>
                                <li><?= $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete Loan Rate'), ['action' => 'delete', $loanRate->loan_rate_id], ['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $loanRate->loan_rate_id)]) ?> </li>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Loan Rates'), ['action' => 'index'],['escape' => false]) ?> </li>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Loan Rate'), ['action' => 'add'],['escape' => false]) ?> </li>                                
                                                                                <li><?//= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Loan Rates'), ['controller' => 'LoanRates', 'action' => 'index'],['escape' => false]) ?> </li>
                                            <li><?//= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Loan Rate'), ['controller' => 'LoanRates', 'action' => 'add'],['escape' => false]) ?> </li>
                                    							</ul>
						</div><!-- end body -->
				</div><!-- end panel -->
			</div><!-- end actions -->
		</div><!-- end col md 3 -->
        <div class="loanRates col-md-9">
            <table cellpadding="0" cellspacing="0" class="table table-striped">
                <tbody>
                                
                                             
                        
                                                                                            <tr>
                            <th><?= __('Rate') ?></th>
                            <td><?= h($loanRate->rate) ?></td>
                        </tr>
                                                                                                            <tr>
                            <th><?= __('Created') ?></th>
                            <td><?= h($loanRate->created) ?></td>
                       </tr>
                                            <tr>
                            <th><?= __('Modified') ?></th>
                            <td><?= h($loanRate->modified) ?></td>
                       </tr>
                                                                                                        <tr>
                            <th><?= __('Activate') ?></th>
                            <td><?= $loanRate->activate ? __('Yes') : __('No'); ?></td>
                       </tr>
                                                                            </tbody>
            </table> 
        </div>
            </div>
</div>
        
        
        
    