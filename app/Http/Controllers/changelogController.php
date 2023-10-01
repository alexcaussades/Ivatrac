<?php

namespace App\Http\Controllers;




class changelogController extends Controller
{
    public function localadress()
    {
        $json = database_path('changelog.json');
        $data = json_decode(file_get_contents($json), true);
        $data = array_reverse($data);
        return $data;
        
    }
}
