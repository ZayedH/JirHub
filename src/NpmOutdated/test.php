<?php

namespace App\NpmOutdated;

class test
{

    public function test()
    {
     $fh = fopen('../src/NpmOutdated/fichier.txt', 'r');
        //$fh= shell_exec('ls');
        //dd($fh);
        $array = explode("\n", file_get_contents('../src/NpmOutdated/fichier.txt'));
        //$content = json_decode(file_get_contents('../src/NpmOutdated/exemple.json'),true);
        dd(array_filter($array));
        $num=count($array);

        for ($i=1;$i<$num;$i++){
            dd(explode(' ',$array[$i]));
        }
        
       

        
       
    }
}
