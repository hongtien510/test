<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

class AdsController extends AppController {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function recognize(){
        $response = [];

        if($this->request->is('post')){
            if(isset($this->request->data['audio']['tmp_name'])){
                $tmpname = $this->request->data['audio']['tmp_name'];

                // load it into Echoprint codegen
                exec(Configure::read('Echoprint.codegenPath').' '.$tmpname,$output);
                $output = json_decode(implode("",$output));

                if(count($output) && isset($output[0]->code)){
                    // load the output into the python script to check with the server
                    exec(Configure::read('Echoprint.findCommand').' '.escapeshellarg($output[0]->code),$results);
                    $result = (int)implode('',$results);

                    if($result > 0){
                        $response = ['status'=>0,'message'=>'OK','adid'=>$result];
                    }elseif($result == 0){
                        $response = ['status'=>1,'message'=>'No track found'];
                    }else{
                        $response = ['status'=>5,'message'=>'Find execution failed.'];
                    }
                }else{
                    $response = ['status'=>4,'message'=>'Codegen failed.'];
                }
            }else{
                $response = ['status'=>3,'message'=>'No file data.'];
            }
        }else{
            $response = ['status'=>2,'message'=>'Nothing submitted.'];    
        }

        // return json
        $this->set('data', $response);
        $this->set('_serialize', 'data');
    }
}
