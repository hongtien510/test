<?php

namespace App\Model\Table;

use App\Model\Entity\Ad;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdsTable extends Table {
    public function initialize(array $config) {
        parent::initialize($config);
        $this->table('ads');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('adex_id', 'create')
            ->notEmpty('adex_id');

        $validator
            ->requirePresence('brand', 'create')
            ->notEmpty('brand');

        $validator
            ->allowEmpty('product');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->allowEmpty('language');

        $validator
            ->add('adex_date', 'valid', ['rule' => 'date'])
            ->requirePresence('adex_date', 'create')
            ->notEmpty('adex_date');

        $validator
            ->add('processed', 'valid', ['rule' => 'numeric'])
            ->requirePresence('processed', 'create')
            ->notEmpty('processed');

        $validator
            ->allowEmpty('filename');

        $validator
            ->add('active', 'valid', ['rule' => 'boolean'])
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }
}
