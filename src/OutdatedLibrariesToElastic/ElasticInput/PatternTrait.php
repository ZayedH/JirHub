<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

trait PatternTrait
{
    private function patternArray(string $project, array $value): array
    {
        return ['project' => $project, 'library' => $value[0], 'version' => $value[1], 'latestVersion' => $value[2]];
    }
}
