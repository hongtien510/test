<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Core\Configure;

class CarShell extends Shell {

    public function initialize(){
        parent::initialize();
        $this->loadModel("Cars");
    }

    // TODO: add download

    public function process(){
        // create a lookup table of all the makes
        $f = fopen(TMP.'pricedata/make.txt','r');
        $makes = [];
        while(($data = fgetcsv($f,0,"\t",'"')) !== FALSE){
            $makes[trim($data[3])] = trim($data[6]);
        }
        fclose($f);
        
        // create a lookup table of all the models
        $f = fopen(TMP.'pricedata/model.txt','r');
        $models = [];
        while(($data = fgetcsv($f,0,"\t",'"')) !== FALSE){
            $models[trim($data[3])] = trim($data[8]);
        }
        fclose($f);

        // lookup table for all the prices
        $f = fopen(TMP.'pricedata/price.txt','r');
        $prizes =[];
        while(($data = fgetcsv($f,0,"\t",'"')) !== FALSE){
            $prizes[trim($data[2])] = trim($data[6]);
        }
        fclose($f);        

        // POSSIBLE TODO: use model level one and level 2 for greater granularit

        // go over all the types and get the make, model and price to write to db
        $saved = 0; $failed = 0;
        $f = fopen(TMP.'pricedata/type.txt','r');
        while(($data = fgetcsv($f,0,"\t",'"')) !== FALSE){
            // ignore the ones that are already inserted
            if($this->Cars->exists(['eurostat_id'=>trim($data[2])])) continue;
            // save car
            $car = $this->Cars->newEntity([
                'eurostat_id'=>trim($data[2]),
                'brand'=>$makes[trim($data[12])],
                'model'=>$models[trim($data[11])],
                'type'=>trim($data[5]),
                'price'=>$prizes[trim($data[2])]
            ]);
            if($this->Cars->save($car)){
                $saved++;
            }else{
                $failed++;
                /*print_r($data);
                print_r($car);
                print_r($car->errors());*/
            }
        }
        fclose($f);
    }
}