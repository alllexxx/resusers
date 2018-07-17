<?php

namespace Grt\ResBundle\Services;

use Exception;

class SortArray
{
    public function sortArrayByKey(&$array, $key, $order){
        usort($array,function ($a, $b) use(&$key,&$order)
        {
            if($order == 'ASC') {
                return (strtolower($a->{$key}) < strtolower($b->{$key})) ? -1 : 1;
            } else {
                return (strtolower($a->{$key}) > strtolower($b->{$key})) ? -1 : 1;
            }
            if(strcmp(strtolower($a->{$key}), strtolower($b->{$key}))){ return 0;}
        });

    }
}