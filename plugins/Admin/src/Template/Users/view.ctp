<div class="view">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>User</h1>
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
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit User'), ['action' => 'edit', $user->id],['escape' => false]) ?> </li>
                                <li><?= $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete User'), ['action' => 'delete', $user->id], ['escape' => false], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Users'), ['action' => 'index'],['escape' => false]) ?> </li>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New User'), ['action' => 'add'],['escape' => false]) ?> </li>                                
                                    							</ul>
						</div><!-- end body -->
				</div><!-- end panel -->
			</div><!-- end actions -->
		</div><!-- end col md 3 -->
        <div class="users col-md-9">
            <table cellpadding="0" cellspacing="0" class="table table-striped">
                <tbody>
                    <tr>
                         <th><?= __('Id') ?></th>
                         <td><?= $this->Number->format($user->id) ?></td>
                    </tr>                
                    <tr>
                        <th><?= __('Firstname') ?></th>
                        <td><?= h($user->firstname) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Lastname') ?></th>
                        <td><?= h($user->lastname) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Email') ?></th>
                        <td><?= h($user->email) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Role') ?></th>
                        <td><?= h($user->role) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Username') ?></th>
                        <td><?= h($user->username) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Last Login') ?></th>
                        <td><?= h($user->last_login) ?></td>
                   </tr>
                   <tr>
                        <th><?= __('Created') ?></th>
                        <td><?= h($user->created) ?></td>
                   </tr>
                   <tr>
                        <th><?= __('Modified') ?></th>
                        <td><?= h($user->modified) ?></td>
                   </tr>
                   <tr>
                        <th><?= __('Is Active') ?></th>
                        <td><?= $user->is_active ? __('Yes') : __('No'); ?></td>
                   </tr>
                </tbody>
            </table> 
        </div>
    </div>
</div>
        
        
        
    