<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

class ComposerOutdated
{
    use PatternTrait;

    public function getComposerJson(string $path): string
    {
        $content = json_decode(file_get_contents($path), true);
        $tab   = [];
        $tab[] = '';

        foreach ($content['installed'] as $value) {
            $name          = $value['name'];
            $version       = $this->filterVersion($value['version']);
            $latestVersion = $this->filterVersion($value['latest']);
            $latestStatus  = $value['latest-status'];
            $isAbandoned   = $value['abandoned'];

            if ($isAbandoned || \is_string($isAbandoned)) {
                
                $tab[] =$this->pattern('Chronos (API)',$name,$version);
            } else {
                $pieces = explode('/', $name);

                if ('symfony' === $pieces[0]) {
                    if ('http-kernel' === $pieces[1]) {
                        
                        $tab[0] =$this->pattern('Chronos (API)',$pieces[0],$version,$latestVersion);
                    }
                } elseif ('semver-safe-update' !== $latestStatus) {

                    $tab[] =$this->pattern('Chronos (API)',$name,$version,$latestVersion);
                }
            }
        }
        $now   = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];
        return json_encode($tab);
    }

    private function filterVersion(string $version): string
    {
        return array_values(array_filter(explode('v', $version)))[0];
    }
}
