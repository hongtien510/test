<?php
namespace Admin\Model\Table;

use Admin\Model\Entity\LoanRate;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LoanRates Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LoanRates
 */
class LoanRatesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('loan_rates');
        $this->displayField('loan_rate_id');
        $this->primaryKey('loan_rate_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LoanRates', [
            'foreignKey' => 'loan_rate_id',
            'joinType' => 'INNER',
            'className' => 'Admin.LoanRates'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('rate');

        $validator
            ->add('activate', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('activate');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['loan_rate_id'], 'LoanRates'));
        return $rules;
    }
}
