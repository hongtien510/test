<?php
namespace Admin\Controller;

use Admin\Controller\AppController;

/**
 * LoanRates Controller
 *
 * @property \Admin\Model\Table\LoanRatesTable $LoanRates
 */
class LoanRatesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        
        $this->set('loanRates', $this->paginate($this->LoanRates));
        $this->set('_serialize', ['loanRates']);
    }

    /**
     * View method
     *
     * @param string|null $id Loan Rate id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $loanRate = $this->LoanRates->get($id);
        $this->set('loanRate', $loanRate);
        $this->set('_serialize', ['loanRate']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loanRate = $this->LoanRates->newEntity();
        if ($this->request->is('post')) {
            $loanRate = $this->LoanRates->patchEntity($loanRate, $this->request->data);
            if ($this->LoanRates->save($loanRate)) {
                $this->Flash->success(__('The loan rate has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan rate could not be saved. Please, try again.'));
            }
        }
        $loanRates = $this->LoanRates->LoanRates->find('list', ['limit' => 200]);
        $this->set(compact('loanRate', 'loanRates'));
        $this->set('_serialize', ['loanRate']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Loan Rate id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loanRate = $this->LoanRates->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loanRate = $this->LoanRates->patchEntity($loanRate, $this->request->data);
            if ($this->LoanRates->save($loanRate)) {
                $this->Flash->success(__('The loan rate has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan rate could not be saved. Please, try again.'));
            }
        }
        $loanRates = $this->LoanRates->LoanRates->find('list', ['limit' => 200]);
        $this->set(compact('loanRate', 'loanRates'));
        $this->set('_serialize', ['loanRate']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Loan Rate id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $loanRate = $this->LoanRates->get($id);
        if ($this->LoanRates->delete($loanRate)) {
            $this->Flash->success(__('The loan rate has been deleted.'));
        } else {
            $this->Flash->error(__('The loan rate could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function export() {
        $out = fopen('php://output', 'w');
        $filename = 'Recordbank-Ratemyride-LoanRates-'.date('Ymd-Hi').'.csv';
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        
		$header = array(	
			'rate' => 'Rate',
            'activate' => 'Activate',
            'created' => 'Created',
            'modified' => 'Modified'
		);
        $this->loadModel('LoanRates');
        $load_rates = $this->LoanRates->find('all', [
            'order' => ['created' => 'desc']
        ]);
  
        $temp = array();
        foreach($header as $value){
            $temp[] = $value;
        }
        fputcsv($out, $temp);

		foreach ($load_rates as $load_rate) {
			$row = array();
            foreach($header as $key => $value){
                if($key == 'activate'){
                    $row[] = $load_rate->$key == 1 ? 'Yes' : 'No';
                }
                else{
                    $row[] = $load_rate->$key;
                }
			}
            fputcsv($out, $row);
		}
        fputcsv($out, array("\n"));
        fputcsv($out, array('Total', count($load_rates->toArray())));
		fclose($out);

        $this->viewBuilder()->layout(false);
        $this->render(false);
	}
}
