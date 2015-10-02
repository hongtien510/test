<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class AdsTableTest extends TestCase {

    public $fixtures = [
        'app.ads'
    ];

    public function setUp() {
        parent::setUp();
        $config = TableRegistry::exists('Ads') ? [] : ['className' => 'App\Model\Table\AdsTable'];
        $this->Ads = TableRegistry::get('Ads', $config);
    }

    public function tearDown() {
        unset($this->Ads);
        parent::tearDown();
    }

    public function testInitialize() {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testValidationDefault() {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
