<?php
function getAboutUs($key){
    $page = \App\page::where('title', $key)
    ->where('status','Công khai')
    ->first();
    if (!empty($page)) {
        return $page->id;
    }
} 