<?php

function data_tree($data, $parent_id = 0, $level = 0)
{
    $result = [];
    foreach ($data as $item) {
        if ($item['parent_id'] == $parent_id) {
            $item['level'] = $level;
            $result[] = $item;
            $child = data_tree($data, $item['id'], $level + 1);
            $result = array_merge($result, $child);
        }
    }
    return $result;
}
function show_array($data){
if(!empty($data)){
    if(is_array($data)){
        echo "<pre>";
        print_r($data);
        echo "<pre>";
    }
}
function getWeek($date){
    $date_stamp = strtotime(date('Y-m-d', strtotime($date)));

     //check date is sunday or monday
    $stamp = date('l', $date_stamp);      
    $timestamp = strtotime($date);
    //start week
    if(date('D', $timestamp) == 'Mon'){            
        $week_start = $date;
    }else{
        $week_start = date('Y-m-d', strtotime('Last Monday', $date_stamp));
    }
    //end week
    if($stamp == 'Sunday'){
        $week_end = $date;
    }else{
        $week_end = date('Y-m-d', strtotime('Next Sunday', $date_stamp));
    }        
    return array($week_start, $week_end);
}

}
?>