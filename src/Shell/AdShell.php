<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Core\Configure;

use Wikitude\ManagerAPI;

/*
    This shell is responsible to start the download of new ads from Nielsen's Adex Adview system
*/

class AdShell extends Shell {
    private $browser;
    private $standardHeaders = [
        "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:40.0) Gecko/20100101 Firefox/40.0",
        "Accept-Language: nl,en-US;q=0.7,en;q=0.3",
        "Host: adex.mediaxim.be",
        "Connection: keep-alive",
        "Keep-Alive: 300",
        "Accept-Encoding: gzip, deflate"
    ];
    private $numsaved = 0;
    private $numskipped = 0;
    private $numfailed = 0;

    public function initialize(){
        parent::initialize();
        $this->loadModel("Ads");
    }

    /* regular gets and post */
    private function request($url,$headers){
        curl_setopt($this->browser,CURLOPT_FILE,null);
        curl_setopt($this->browser,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->browser,CURLOPT_HEADER,true);
        curl_setopt($this->browser,CURLOPT_URL,$url);
        curl_setopt($this->browser,CURLOPT_HTTPHEADER,array_merge($this->standardHeaders,$headers));
        $response = curl_exec($this->browser);
        $header_size = curl_getinfo($this->browser, CURLINFO_HEADER_SIZE);
        $header = substr($response,0,$header_size);
        $body = substr($response,$header_size);
        return array('header'=>$header,'body'=>$body);
    }
    private function get($url,$headers){
        curl_setopt($this->browser,CURLOPT_POST,0);
        return $this->request($url,$headers);
    }
    private function post($url,$vars,$headers,$appendtovars = ""){
        curl_setopt($this->browser,CURLOPT_POST,1);
        curl_setopt($this->browser,CURLOPT_POSTFIELDS,http_build_query($vars).$appendtovars);
        return $this->request($url,$headers);
    }

    /* file downloading */
    public function downloadHeader($ch, $header_line){
        print_r($header_line);
    }
    private function downloadFile($url,$ad){
        // open the file handle
        $f = new File(TMP."incoming/".$ad->id,false);
        $filehandle = fopen($f->path,'wb');
        curl_setopt($this->browser,CURLOPT_POST,0);
        curl_setopt($this->browser,CURLOPT_HEADER,false);
        curl_setopt($this->browser,CURLOPT_FILE,$filehandle);
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Referer: http://adex.mediaxim.be/adstat/popupMaterial.do?ad_id=1629892&langCode=1&mediaType=DP&index=1&methodToCall=initialize&searchLg=2&tabSelected=tab_easy_contents',
            'Upgrade-Insecure-Requests: 1'
        ];
        curl_setopt($this->browser,CURLOPT_HTTPHEADER,array_merge($this->standardHeaders,$headers));
        $this->out('Downloading: '.$url);
        curl_setopt($this->browser,CURLOPT_URL,$url);
        $response = curl_exec($this->browser);
        // close the file handle
        fclose($filehandle);

        // update the filename based on the type
        $mimetype = $f->mime();
        switch($mimetype){
            case 'image/jpeg': $ext = '.jpg'; break;
            case 'image/png': $ext = '.png'; break;
            case 'audio/mpeg': $ext = '.mp3'; break;
            case 'video/x-msvideo': $ext = '.avi'; break;
            //case 'inode/x-empty': $ext = '.mp4'; break;
            default: echo $mimetype; break;
        }
        $finalname = $f->path.$ext;
        rename($f->path,$finalname);

        // write the progress to the database
        $ad->processed = 1;
        $ad->filename = $ad->id.$ext;
        $this->Ads->save($ad);
    }
    private function downloadAd($ad){
        $id = $ad->adex_id;
        $langcodes = ["fr"=>1,"nl"=>2,"de"=>4];
        $langcode = $langcodes[$ad->language];
        $type = strtoupper($ad->type);
        $action = 'HIGH_RES_PATH';
        if($type == 'TV') $action = 'RES_576P_PATH';
        $url = "http://adex.mediaxim.be/adstat/getFile?ad_id=%s&langCode=%d&mediaType=%s&action=%s";
        $downloadurl = sprintf($url,$id,$langcode,$type,$action);

        $this->downloadFile($downloadurl,$ad);
    }

    /* initial curl setup */
    private function setupCurl(){
        // create the cookiefile
        $cookiefile = new File(TMP."adex_cookies",true);

        // include a dom parser
        include(ROOT.DS.'vendor'.DS.'simple_html_dom'.DS.'simple_html_dom.php');

        // setup curl
        $this->browser = curl_init();
        curl_setopt($this->browser,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->browser,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($this->browser,CURLOPT_COOKIEJAR,$cookiefile->path);
        curl_setopt($this->browser,CURLOPT_COOKIEFILE,$cookiefile->path);
        curl_setopt($this->browser,CURLOPT_MAXREDIRS,5);
        curl_setopt($this->browser,CURLOPT_ENCODING,"");
    }

    /* login */
    private function login(){
        $vars = [
            "isAdminModule"=>"false",
            "sessionAlreadyExist"=>"invalidateSession",
            "alertLink"=>"false",
            "scheduleLink"=>"false",
            "login"=>"happiness",
            "password"=>"cars"        
        ];
        $headers = [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Referer: http://adex.mediaxim.be/adstat/SubmitLogin.do",
        ];
        $response = $this->post("http://adex.mediaxim.be/adstat/SubmitLogin.do",$vars,$headers);

        // check if login succeeded
        $html = str_get_html($response['body']);
        $atest = null;
        if($html) $atest = $html->find('a',0)->innertext;
        if($atest == 'Start the Adex application.'){
            $this->out("Login succesfull");
            return true;
        }else{
            $this->error("Login failed.");
        }
    }

    /* fetching */
    public function fetch(){
        $this->out("Fetching new Ads from Adex Adview");

        $this->setupCurl();

        if($this->login()){
            $this->fetchOverview();  
        } 
    }

    private function fetchOverview(){
        // do a request to the homepage so the system can initialize what it needs
        $headers = [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Referer: http://adex.mediaxim.be/adstat/SubmitLogin.do"
        ];
        $this->get("http://adex.mediaxim.be/adstat/adviewHome.do",$headers);

        // do the search
        //$this->doSearch('namedperiod');
        $this->doSearch('periodFromTo','01/01/2014','15/01/2014');
        $html = $this->indexOverview();
        
        // check if there is a pager to get the rest of the pages to collect all the pages
        $pager = $html->find('div.pagination_container',0);
        if($pager && count($pager->find('div#pagination_col_m',0)->find('a'))>1){
            $count = trim($pager->find('div#pagination_col_m',0)->find('a',-1)->innertext);
            for($i = 1; $i < $count; $i++){
                $this->otherPage($i);
            }
        }

        // show report
        $this->out('Saved: '.$this->numsaved);
        $this->out('Failed: '.$this->numfailed);
        $this->out('Skipped: '.$this->numskipped);
    }

    private function doSearch($typeperiod,$from="01/01/2015",$to="09/09/2015"){
        // call to update session state with search
        $vars = [
            "hidden"=>"search",
            "theAction"=>-1,
            "productBasketLabel"=>"",
            "errMsgDate"=>"Error in search period dates. From date must lie before to date. ",
            "errMsgDateMissing"=>"Please give both start date and end date ",
            "errMsgPeriodMissing"=>"Please select a period first ",
            "selectedMat"=>-1,
            "typePeriod"=>$typeperiod,
            "namedPeriods"=>"Last Available Day",
            "periodFrom"=>$from,
            "periodTo"=>$to,
            "tabSelected"=>"#tab_easy_contents",
            "checkedECG"=>"",
            "checkedSCT"=>"",
            "checkedSSCT"=>"",
            'treeState'=>'{"id":"ROOT_0","text":"root","checked":false,"expanded":true,"children":[{"id":"ECG_6"},{"id":"ECG_16"}]}',
            "checkedTreeInfo"=>"",
            "language_easy"=>2,
            "general"=>"",
            "language_advanced"=>2,
            "brand"=>"",
            "productLine"=>"",
            "product"=>"",
            "advertiser"=>"",
            "advertiserGroup"=>"",
            "storyboard"=>"",
            "keywords"=>"",
            "minDuration"=>"",
            "maxDuration"=>"",
            "prodBaskSharingLevel"=>"",
            "productBasket"=>"",
            "descriptionBasketCol"=>"",
            "mdbid"=>"",
            "selectedSearchType"=>1,
            "matId"=>"",
        ];
        //$append = '&media=TV&media=DP&media=MA&media=RA&media=OD&media=&selectedMatLgItems=FR&selectedMatLgItems=NL&selectedMatLgItems=EN&selectedMatLgItems=DE&selectedMatLgItems=OT&selectedMatLgItems=';
        $append = '&media=MA&media=&selectedMatLgItems=FR&selectedMatLgItems=NL&selectedMatLgItems=EN&selectedMatLgItems=DE&selectedMatLgItems=OT&selectedMatLgItems=';
        $headers = [
            "Accept: application/json, text/javascript, */*",
            "Origin: http://adex.mediaxim.be",
            "Referer: http://adex.mediaxim.be/adstat/adviewHome.do",
            "X-Requested-With: XMLHttpRequest"
        ];
        $response = $this->post("http://adex.mediaxim.be/adstat/search.do",$vars,$headers,$append);
    }

    private function otherPage($page){
        $headers = [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Referer: http://adex.mediaxim.be/adstat/search.do?hidden=redirect",
            "Upgrade-Insecure-Requests: 1"
        ];
        $vars = [
            'language_easy'=>2,
            'language_advanced'=>2,
            'period'=>0,
            'periodLabel'=>'',
            'general'=>'',
            'mdbid'=>'',
            'brand'=>'',
            'productLine'=>'',
            'product'=>'',
            'advertiser'=>'',
            'advertiserGroup'=>'',
            'storyboard'=>'',
            'keywords'=>'',
            'dateStart'=>'',
            'checkedTreeInfo'=>'',
            'dateEnd'=>'',
            'minDuration'=>'',
            'maxDuration'=>'',
            'hidden'=>'changePage',
            'matId'=>'',
            'tabSelected'=>'#tab_easy_contents',
            'namedPeriods'=>'Last Available Day',
            'productBasketLabel'=>'',
            'theAction'=>-1,
            'sorterSelected'=>'FIRST_DAY',
            'sortThen'=>'STY_ID',
            'params'=>'Media :TV,DP,MA,RA,OD
Lg :NL
Material Language :FR,NL,EN,DE,OT',
            'currentPage'=>$page,
            'selectedMat'=>-1
        ];
        $append = '&media=TV&media=DP&media=MA&media=RA&media=OD&media=&selectedMatLgItems=FR&selectedMatLgItems=NL&selectedMatLgItems=EN&selectedMatLgItems=DE&selectedMatLgItems=OT&selectedMatLgItems=';
        $response = $this->post("http://adex.mediaxim.be/adstat/search.do",$vars,$headers,$append);
        $html = str_get_html($response['body']);

        // process ads on this page
        $this->indexAds($html);
    }

    private function indexOverview(){
        $headers = [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Referer: http://adex.mediaxim.be/adstat/adviewHome.do",
            "Upgrade-Insecure-Requests: 1"
        ];
        $response = $this->get("http://adex.mediaxim.be/adstat/search.do?hidden=redirect",$headers);
        $html = str_get_html($response['body']);

        // process ads on this page
        $this->indexAds($html);
        return $html;
    }

    private function indexAds($html){
        $searchresults = $html->find('div#searchResult',0);
        foreach($searchresults->find('table.result') as $line){
            // ignore the header line
            if(!count($line->find('td.resultLine'))) continue;

            $adinfo = [];

            // get all the raw data from the field
            $adinfo['adex_id'] = $line->find('td.ranking',0)->find('input',0)->getAttribute('value');

            // sometimes there's an extra row
            if(count($line->find('td.prodInfo',0)->find('tr')) > 5){
                $brandoffset = 1;
                // also index tvtid if we have the extra row
                $adinfo['tvtid'] = trim(str_replace("Institute TVTID:&nbsp;","",$line->find('td.prodInfo',0)->find('tr',1)->find('td.prodInfoLine',0)->innertext));
            }else{
                $brandoffset = 0;
            }

            // if we don't have a numeric adex id and no tvtid we don't have a usable unique identifier so can not use
            if(!is_numeric($adinfo['adex_id']) && !isset($adinfo['tvtid'])){
                continue;
            }

            $adinfo['brand'] = trim(str_replace("Brand :&nbsp;","",$line->find('td.prodInfo',0)->find('tr',1+$brandoffset)->find('td.prodInfoLine',0)->innertext));

            $adinfo['product'] = trim(str_replace("Product :&nbsp;","",$line->find('td.prodInfo',0)->find('tr',3+$brandoffset)->find('td.prodInfoLine',0)->innertext));
            // if the product is empty, get it from the product line
            if(!$adinfo['product'] || $adinfo['product'] == '-') $adinfo['product'] = trim(str_replace("Product line :&nbsp;","",$line->find('td.prodInfo',0)->find('tr',2+$brandoffset)->find('td.prodInfoLine',0)->innertext));
            if(in_array($adinfo['product'],['AUTO','BESTELWAGEN','4X4 - SUV'])){
                $adinfo['product'] = '';
            }

            $adinfo['type'] = strtolower(trim($line->find('td.dateAndLanguage',0)->find('tr',1)->find('td',0)->innertext));

            $date = trim(str_replace("First ","",$line->find('td.dateAndLanguage',0)->find('td',0)->innertext));
            $date = explode('/',$date);
            $date = new \DateTime(date('Y-m-d',mktime(0,0,0,$date[1],$date[0],$date[2])));
            $adinfo['adex_date'] = $date;

            $adinfo['processed'] = 0;
            $adinfo['active'] = 1;

            // for each language, add a record
            $langdefs = [1=>'fr',2=>'nl',4=>'de'];
            $langs = $line->find('td.dateAndLanguage',0)->find('input');
            foreach($langs as $lang){
                $langnum = trim($lang->getAttribute('value'));
                if(!in_array($langnum,[1,2,4])) continue;
                $language = $langdefs[$langnum];

                // only add if it doesn't exist yet
                if(isset($adinfo['tvtid'])){
                    $condition = ['tvtid'=>$adinfo['tvtid'],'language'=>$language];
                }else{
                    $condition = ['adex_id'=>$adinfo['adex_id'],'language'=>$language];
                }
                if(!$this->Ads->exists($condition)){
                    $data = array_merge(['language'=>$language],$adinfo);
                    $ad = $this->Ads->newEntity($data);
                    if($this->Ads->save($ad)){
                        $this->numsaved++;
                        // now download the new ad
                        $this->downloadAd($ad);
                    }else{
                        $this->numfailed++;
                        $this->out('Failed record:');
                        print_r($data);
                        print_r($ad->errors());
                        print $line;
                    }
                }else{
                    $this->numskipped++;
                }
            }
        }
    }

    // downloading the material used to be separate, but TVC's can not be downloaded this way because the links expire
    private function download(){
        $this->setupCurl();

        if(!$this->login()) return;

        $query = $this->Ads->find('all',['conditions'=>['id'=>37]]);
        $ads = $query->toArray();

        foreach($ads as $ad){
            $this->downloadAd($ad);
        }
    }

    // process all the downloaded material into wikitude cloud recognition and audio recognition service
    public function process(){
        $query = $this->Ads->find('all',['conditions'=>['processed'=>1]]);

        $ads = $query->toArray();
        foreach($ads as $ad){
            // ad type determines how it needs to be processed
            switch($ad->type){
                case 'tv':
                case 'ra':
                    $status = $this->processAudio($ad);
                    break;
                default:
                    $status = $this->processVisual($ad);
                    break;
            }
            // set the ad as finished if it succeeded
            if($status){
                $ad->processed = 2;
                if($this->Ads->save($ad)){}
                // TODO: clean up the file in the tmp folder since we don't need it anymore
            }
        }

        // update the target collection of wikitude
        $this->updateWikitudeCollection();
    }

    private function processAudio($ad){
        $this->out("Processing: ".$ad->id." as audio");

        // load it into echoprint-codegen
        $filepath = TMP.'incoming/'.$ad->filename;
        exec(Configure::read('Echoprint.codegenPath').' '.$filepath,$output);
        $output = json_decode(implode("",$output));
        if(count($output) == 0) return false;

        // set this into the tags so we know which ad id this links to on retrieval
        $output[0]->metadata->title = $ad->id;
        
        // write it to a json file
        $tmpfile = TMP.'echoprint_audio.json';
        $f = fopen($tmpfile,'w');
        fwrite($f,json_encode($output));
        fclose($f);

        // load the new json file into the database
        exec(Configure::read('Echoprint.fastingestCommand').' '.$tmpfile,$loadingoutput);

        // TODO: error handling

        return true;
    }

    private function processVisual($ad){
        $filepath = TMP.'incoming/'.$ad->filename;

        // upload into wikitude
        $this->uploadWikitude($filepath,$ad->id);

        return false;
    }

    public function uploadWikitude($filename,$id){
        require_once(ROOT.DS."vendor".DS."wikitude".DS."ManagerApi.php");

        // token
        $api = new ManagerAPI(Configure::read('Wikitude.token'),"2");
        $collection = Configure::read('Wikitude.collection');

        // copy image into the webroot so wikitude can access it

        // load image into the collection
        $target = array(
            'name'=>$id,
            'imageUrl'=>$filename
        );
        $targetresult = $api->addTarget($collection,$target);

        return true;
    }

    private function updateWikitudeCollection(){
        $api = new ManagerAPI(Configure::read('Wikitude.token'),"2");
        $collection = Configure::read('Wikitude.collection');
        $api->generateTargetCollection($collection);
    }
}