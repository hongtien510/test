<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class CarsController extends AppController {

    // mappings to correct spellings
    private $mappings = [
        'vw'=>'volkswagen',
        'aston martin'=>'aston-martin',
        'landrover'=>'land rover',
        'land-rover'=>'land rover',
        'mercedes'=>'mercedes-benz',
        'mercedes benz'=>'mercedes-benz',
        'rollsroyce'=>'rolls-royce',
        'rolls royce'=>'rolls-royce',
        'sangyong'=>'ssangyong',
        'porshe'=>'porsche'
    ];

    public function initialize(){
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function adtocar($adid){
        // load the ads model as well
        $this->loadModel("Ads");

        echo $this->preprocessInput("rolls royce");
        exit();

        // load the ad
        $ad = $this->Ads->find('all',['conditions'=>['id'=>$adid],'fields'=>['brand','product']])->first();
        if($ad){
            print_r($ad);
        }else{
            $this->set('data',['status'=>-1,'message'=>'Ad not found.']);
        }

        //$this->set('data',[]);
        $this->set('_serialize','data');
    }

    // preprocess search input
    private function preprocessInput($input){
        $input = trim($input);
        foreach($this->mappings as $search => $mapto){
            $input = preg_replace('/\b'.$search.'\b/i',$mapto,$input);
        }
        return $input;
    }

    // utility function to build our search strings
    private function buildMatchString($words, $notrequired=0){
        $modified = [];
        $index = 0;
        foreach($words as $word){
            $modified[] = ($index < count($words)-$notrequired ? '+' : '').$word.'*';
            $index++;
        }
        return implode(' ',$modified);
    }

    // this is the main search function that is responsible to match searches and ads to a relevant car
    private function match($string){
        // setup connection
        $connection = ConnectionManager::get('default');
        
        // preprocess
        $string = $this->preprocessInput($string);

        // split the string
        $words = explode(" ",$string);
        foreach($words as &$word){
            $word = trim($word);
        }

        // will start with requiring all words, every loop make the next word optional starting from the end
        $matches = [];
        $notrequired = 0;
        while(!count($matches)){
            $matchstring = $this->buildMatchString($words,$notrequired);

            $matches = $connection->execute('select * from cars
                where match(brand,model,type) against (:ms in boolean mode)
                order by match(brand,model,type) against (:ms in boolean mode) desc;
            ',['ms'=>$matchstring])->fetchAll('assoc');

            $notrequired++;
            if($notrequired > count($words)) break;
        }
        if($matches) return $matches;
        else return [];

        // Possible future TODO: add fuzzy matching for misspellings
    }

    public function search(){
        if($this->request->is('post')){
            if(isset($this->request->data['term'])){
                $matches = $this->match($this->request->data['term']);
                $this->set('data',$matches);
            }else{
                $this->set('data',[]);
            }
            $this->set('_serialize','data');
        }else{
            $this->set('data',[]);
        }
        $this->set('_serialize','data');
    }
}