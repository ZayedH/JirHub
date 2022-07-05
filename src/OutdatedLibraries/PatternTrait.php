<?php

namespace App\OutdatedLibraries;

trait PatternTrait
{
    private function generateHeader(string $name): array
    {
        return ["| $name | version  | version disponible |", '| --- | --- | --- |'];
    }

    private function patternLigne(array $value): string
    {
        return '| ⚠️' . ' ' . " $value[0]  | $value[1] | $value[2] |";
    }
}
