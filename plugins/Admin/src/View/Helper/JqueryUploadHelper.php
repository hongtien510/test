<?php
namespace Admin\View\Helper;

use Cake\View\View;
use Cake\View\Helper;
use Cake\Event\Event;
class JqueryUploadHelper extends Helper {
/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'Html'
	);
    
    public $upload_temp = 'files/uploads/temp/';
    public $upload_php = 'plugins/jQueryFileUpload/server/php/';


/**
 * Default values
 *
 * @var array
 */
	protected $_defaults = array(
		'script' => array(
                        '/plugins/jQueryFileUpload/js/vendor/jquery.ui.widget.js',
                        '/plugins/jQueryFileUpload/js/jquery.fileupload.js',
                        '/plugins/jQueryFileUpload/js/custom.js'
                    ),
		'loadScript' => true,
        'css' => '/plugins/jQueryFileUpload/css/jquery.fileupload.css',
        'loadCss' => true
	);

/**
 * Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
    public function __construct(View $view, $config = [])
    {
        parent::__construct($view, $config);
          $this->config = array_merge($this->_defaults, $config);
    }

    
	public function upload($name = null, $label = null, $url = null, $multiple = null) {
        $label == "" ? "Select files" : $label;
        $multiple = $multiple == 'multiple' ? $multiple : "";
        $image = "";$name_img = "";
        if($url != "")
        {
            $arr_url = explode('/', $url);
            $name_img = end($arr_url);
            
            $link_img = $this->webroot . $url;
            $url = str_replace('/thumbnail', '', $url);
            $image = '<p><a class="btn btn-danger delete" wrapper_upload="'.$name.'" data-type="DELETE" data-url="'.$url.'" href="javascript:void(0)">
            <i class="glyphicon glyphicon-trash"></i></a> 
            <img src="'.$link_img.'"></p>';
        }
        $html = '
        <div class="wrapper_upload_'.$name.'">
            <div id="files" class="files">'.$image.'</div>
            <br/>
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>'.$label.'...</span>
                <input id="'.$name.'" type="file" name="files[]" '.$multiple.'>
                
            </span>
            <input style="padding: 0px; border: medium none; width: 0px;" data-type="result" type="text" name="'.$name.'" value="'.$url.'"/>
            <br/><br/>
            <div id="progress" class="progress" style="display: none;">
                <div class="progress-bar progress-bar-success"></div>
            </div>
        </div>
      
        ';
        $this->Html->scriptBlock('
            $( document ).ready(function() {
                upload_init("'.$name.'", "'.$multiple.'");
            });', 
            array('inline' => false));
        return $html;
	}
    
                

/**
 * beforeRender callback
 * 
 * @param string $viewFile The view file that is going to be rendered
 * 
 * @return void
 */
	public function beforeRender(Event $event, $viewFile) {
        //parent::beforeRender($event);
		if ($this->config['loadScript'] === true) {
            if(is_array($this->config['script'])){
                foreach($this->config['script'] as $script){
                    echo $this->Html->script($script, ['block' => true]);
                }
            }
            else{
                echo $this->Html->script($this->config['script'], ['block' => true]);
            }
            $this->Html->scriptBlock('
                var url = "'.$this->request->webroot . $this->upload_php.'";
                var upload_temp = "'.$this->upload_temp.'";', 
                ['block' => true]);
		}
        if ($this->config['loadCss'] === true) {
            if(is_array($this->config['css'])){
                foreach($this->config['css'] as $css){
                    echo $this->Html->css($css, ['block' => true]);
                }
            }
            else{
                echo $this->Html->css($this->config['css'], ['block' => true]);
            }
		}
	}
}
/*
Usage
Add it before end of form

Method 1 : Load in controller
	$this->helpers = array('JqueryUpload`');
Method 2	: Implement in html view
	echo $this->JqueryUpload->upload('name', 'label', 'url_image');
*/