<?php
namespace App\Model\Table;

use App\Model\Entity\Car;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CarsTable extends Table {

    public function initialize(array $config){
        parent::initialize($config);
        $this->table('cars');
        $this->displayField('id');
        $this->primaryKey('id');

    }

    public function validationDefault(Validator $validator){
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('eurostat_id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('brand', 'create')
            ->notEmpty('brand');

        $validator
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->add('price', 'valid', ['rule' => 'decimal'])
            ->add('price', 'validValue', ['rule'=>['range',0]])
            ->requirePresence('price', 'create')
            ->notEmpty('price');

        return $validator;
    }
}
