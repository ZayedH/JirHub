<?php

namespace App\NpmOutdated;

class test
{
    public function test()
    {
        //$fh= shell_exec('ls');
        //dd($fh);
        $array = explode("\n", file_get_contents('../src/NpmOutdated/android.txt'));
        //$content = json_decode(file_get_contents('../src/NpmOutdated/exemple.json'),true);
        $array=array_filter($array);
        $k=0;
        $num=count($array);
        $tab=[];
        for($i=0;$i<$num;$i++){
            if($array[$i]=='Gradle release-candidate updates:'){
                break;
            }
            if($k==1){
                $tab[]=$array[$i];
             }
            if($array[$i]=='The following dependencies have later milestone versions:'){
                $k=1;
            }
            
            

        }
        //dd($tab);
        dd(explode(' ',$tab[0]));
    }
}
