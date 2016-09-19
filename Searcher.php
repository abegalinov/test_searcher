<?php
namespace TestTask;

class Searcher {

    private $sources = array();
    private $logFile;
    private $searchResults = array();

    public function addSource(BaseSource $source){
        $this->sources[] = $source;
    }

    public function setLogFile($logfile) {
        $this->logFile = $logfile;
    }

    public function processQuery($q) {
        foreach ($this->sources as $source) {
            $results = $source->getSearchResults($q);
            $this->searchResults = array_merge($this->searchResults, $results);
        }
        if($this->logFile) $this->writeResultsInLogFile();
        $this->sortResultsByPrice();
    }

    public function getResultsJson() {
        return json_encode($this->searchResults);
    }

    private function sortResultsByPrice(){
        usort($this->searchResults, array($this, 'sortArrayByPriceField'));
    }

    private function sortArrayByPriceField($a, $b){
        if($a['price'] == $b['price']) return 0;
        return ($this->priceConvertHelper($a['price']) < $this->priceConvertHelper($b['price'])) ? -1 : 1;
    }

    private function priceConvertHelper($price){
        return str_replace(',', '', trim(substr($price, 4)));
    }

    private function writeResultsInLogFile(){
        $fh = fopen($this->logFile, "a");
        if(!$fh) throw new \Exception('Could not open log file');
        foreach($this->searchResults as $resultLine)
            fwrite($fh, json_encode($resultLine)."\n");
        fclose($fh);
    }

}
