<?php
namespace TestTask;

class SnapdealComSource extends BaseSource {

    protected function getSearchUrl($q) {
        return sprintf("http://www.snapdeal.com/search?keyword=%s&sort=rlvncy", $q);
    }

    protected function parseResponse($response) {
        preg_match_all(
          '|<div class="product-disc">\s+<span>(\d+)% Off</span>.+?'.
          '<p class="product-title">(.+?)\s+</p>.+?'.
          '<span class="product-desc-price strike ">(.+?)</span>\s+'.
          '<span class="product-price">(.+?)</span>.+?'.
          '<img .+?class="product-image" .+?src="(.+?)" />|ims',
           $response,
           $matches,
           PREG_SET_ORDER
       );

       foreach($matches as $item){
           $results[] = [
             'percentage'=>$item[1],
             'name'=>$item[2],
             'image_url'=>$item[5],
             'msrp'=>$item[3],
             'price'=>preg_replace('|\s+|', ' ', $item[4])
           ];
       }

       return $results;
    }
}
