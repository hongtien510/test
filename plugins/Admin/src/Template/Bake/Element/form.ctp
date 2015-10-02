<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    });
%>
<div class="<%= $pluralVar %> form">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= __('<%= Inflector::humanize($action) %> <%= $singularHumanName %>') ?></h1>
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
                                <% if (strpos($action, 'add') === false): %>
                                <li><?=
                                    $this->Form->postLink(
                                    __('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete'),
                                    ['action' => 'delete', $<%= $singularVar
                                    %>-><%= $primaryKey[0] %>],['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $<%= $singularVar %>-><%= $primaryKey[0] %>)]
                                    )
                                    ?>
                                </li>
                                <% endif; %>
                                <li>
                                    <?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List <%= $pluralHumanName %>'), ['action' => 'index']
,['escape' => false]) ?>
                                </li>
                                <%
                                $done = [];
                                foreach ($associations as $type => $data) {
                                    foreach ($data as $alias => $details) {
                                        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
                                            %>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index']
,['escape' => false]) %> </li>
                                <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;
New <%= $this->_singularHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add']
,['escape' => false]) %> </li>
                                            <%
                                            $done[] = $details['controller'];
                                        }
                                    }
                                }
                                %>
							</ul>
						</div>
					</div>
				</div>			
		</div><!-- end col md 3 -->
		<div class="col-md-9">
            <?php echo  $this->Form->create($<%= $singularVar %>); ?>
        <%
            foreach ($fields as $field) {
                if (in_array($field, $primaryKey)) {
                    continue;
                }
                if (isset($keyFields[$field])) {
                    $fieldData = $schema->column($field);
                    if (!empty($fieldData['null'])) {
                    %>
                <div class="form-group"><?php echo "<?php echo \$this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true,'class'=>'form-control','placeholder' => '<%= Inflector::humanize($field) %>']);?>"; ?></div>
                    <%
                    } else {
                    %>
                <div class="form-group"><?php echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>,'class'=>'form-control','placeholder' => '<%= Inflector::humanize($field) %>']); ?></div>
                    <%
                    }
                    continue;
                }
                if (!in_array($field, ['created', 'modified', 'updated'])) {
                    $fieldData = $schema->column($field);
                    if (($fieldData['type'] === 'date') && (!empty($fieldData['null']))) {
                    %>
                 <div class="form-group"><?php echo $this->Form->input('<%= $field %>', ['empty' => true, 'default' => '','class'=>'form-control','placeholder' => '<%= Inflector::humanize($field) %>']);?>"; ?></div>
                    <%
                    } else {
                        if (empty($fieldData['null'])) {
                    %>
                     <div class="form-group"><?php echo $this->Form->input('<%= $field %>',['class'=>'form-control','placeholder' => '<%= Inflector::humanize($field) %>']); <% // Required fields %> ?></div>
                     <%
                    } else {
                    %>
                     <div class="form-group"><?php echo $this->Form->input('<%= $field %>',['class'=>'form-control','placeholder' => '<%= Inflector::humanize($field) %>']); ?></div>
                    <%
                        }
                    }
                }
            }
            if (!empty($associations['BelongsToMany'])) {
                foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
        %>
          <div class="form-group"><?php echo $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>,'class'=>'form-control','placeholder' => '<%= Inflector::humanize($field) %>'); ?></div>
        <%
                }
            }
        %>
            <div class="form-group">
                <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                <?php echo $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-default'])?>
            </div>    
            <?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
