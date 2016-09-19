<?php
namespace TestTask;

class DarazPkSource extends BaseSource {

    protected function getSearchUrl($q) {
        return sprintf("https://www.daraz.pk/catalog/?q=%s", $q);
    }

    protected function parseResponse($response) {
        preg_match_all(
          '|<h2 class="title">.+?<span class="name"\s+dir="ltr">(.+?)</span></h2>.+?'.
          '<span class="sale-flag-percent">-(.+?)%</span>.+?'.
          '<span class="price-box">\s*<span class="price "><span data-currency-iso=".+?" >(.+?)</span>\s+'.
          '<span dir="ltr" data-price="\d+">.+?([,\d]+)</span>\s*</span>\s+'.
          '<span class="price -old "><span data-currency-iso=".+?" >.+?</span>\s+'.
          '<span dir="ltr" data-price="\d+">.+?([,\d]+)</span>\s</span>.+?'.
          '<img class="lazy image".+?data-src="(.+?)" data-placeholder=".+?">|ims',
           $response,
           $matches,
           PREG_SET_ORDER
       );

       foreach($matches as $item){
           $results[] = [
             'percentage'=>$item[2],
             'name'=>$item[1],
             'image_url'=>$item[6],
             'msrp'=>$item[3].' '.$item[5],
             'price'=>$item[3].' '.$item[4]
           ];
       }

       return $results;
    }
}
