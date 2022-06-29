<?php

namespace App\NpmOutdated;

class test
{
    public function test()
    {
        //$fh= shell_exec('ls');
        //dd($fh);
        $array = explode("\n", file_get_contents('../src/NpmOutdated/ios.txt'));
        //$content = json_decode(file_get_contents('../src/NpmOutdated/exemple.json'),true);
        array_filter($array);

        dd(array_filter(explode(' ', $array[3])));
    }
}
