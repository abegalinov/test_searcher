<?php
namespace TestTask;

abstract class BaseSource {

    public function getSearchResults($q) {
        $response = $this->requestQuery($q);
        return $this->parseResponse($response);
    }

    protected function requestQuery($q) {
        $opts = [
          'http'=>[
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
            "UserAgent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36\r\n"
          ]
        ];
        $context = stream_context_create($opts);
        $url = $this->getSearchUrl($q);
        return file_get_contents($url, false, $context);
    }

    abstract protected function parseResponse($response);
    abstract protected function getSearchUrl($q);
}
