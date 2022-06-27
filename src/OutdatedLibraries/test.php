<?php

namespace App\Garbage;


class test
{



    public function test()
    {
        // $output = shell_exec(' cd .. / &&  composer outdated --format=json > src/Garbage/exemple.json'); // on doit pouvoir excuter cette ligne sur le repertoire du projet
        
        $content = json_decode(file_get_contents('../src/Garbage/exemple.json'),true);
       
        return $content;
    }
}
