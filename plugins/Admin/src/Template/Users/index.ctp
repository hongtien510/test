<div class="users index">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo "User"; ?></h1>
			</div>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
	<div class="row">
		<div class="col-md-3">
			<div class="actions">
				<div class="panel panel-default">
					<div class="panel-heading">Actions</div>
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New User'), ['action' => 'add'],['escape' => false]); ?></li>
                                    							</ul>
						</div><!-- end body -->
				</div><!-- end panel -->
			</div><!-- end actions -->
		</div><!-- end col md 3 -->

		<div class="col-md-9">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
                                            <th><?= $this->Paginator->sort('id') ?></th>
                                            <th><?= $this->Paginator->sort('firstname') ?></th>
                                            <th><?= $this->Paginator->sort('lastname') ?></th>
                                            <th><?= $this->Paginator->sort('email') ?></th>
                                            <th><?= $this->Paginator->sort('is_active') ?></th>
                                            <th><?= $this->Paginator->sort('role') ?></th>
                                            <th class="actions"></th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                                        <td><?= $this->Number->format($user->id) ?></td>
                                        <td><?= h($user->firstname) ?></td>
                                        <td><?= h($user->lastname) ?></td>
                                        <td><?= h($user->email) ?></td>
                                        <td><?= h( ($user->is_active) == 1 ? "Yes" : "No" ) ?></td>
                                        <td><?= h($user->role) ?></td>
                                        <td class="actions">
                            <?php 
                                if($user['id'] == $current_user['id'])
                                {
                                    echo $this->Html->link('<span title="Change password" class="glyphicon glyphicon-pencil"></span>', ['action' => 'change_password'], ['escape' => false]);
                                }
                            ?>
                            <?= $this->Html->link(__('<span class="glyphicon glyphicon-search"></span>'), ['action' => 'view', $user->id],['escape' => false]) ?>
                            <?= $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>'), ['action' => 'edit', $user->id],['escape' => false]) ?>
                            <?= $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>'), ['action' => 'delete', $user->id], ['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>        
				</tbody>
			</table>
			<p>
				<small><?= $this->Paginator->counter() ?></small>
			</p>
            
            <?php 
            $params = $this->Paginator->params();   
            if($params['pageCount'] > 1):
            ?>    
			<ul class="pagination pagination-sm">
            <?php
                echo $this->Paginator->prev(__('Previous'));
                echo $this->Paginator->numbers();
                echo $this->Paginator->next(__('Next'));
            ?>           
			</ul>
            <?php endif; ?>

		</div> <!-- end col md 9 -->
	</div><!-- end row -->


</div><!-- end containing of content -->