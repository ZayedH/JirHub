<?php

namespace App\Handler;

use App\Repository\GitHub\CommitRepository;
use App\Repository\GitHub\PullRequestRepository;

class ChangelogHandler
{
    /** @var CommitRepository */
    private $commitRepository;

    /** @var PullRequestRepository */
    private $pullRequestRepository;

    public function __construct(CommitRepository $commitRepository, PullRequestRepository $pullRequestRepository)
    {
        $this->commitRepository      = $commitRepository;
        $this->pullRequestRepository = $pullRequestRepository;
    }

    public function getProductionChangelog(): array
    {
        return $this->getOrderedChangelog('master', 'dev')[0];
    }

    public function getChangelog($prev_head, $head): array
    {
        $result = $this->commitRepository->getChangelog($prev_head, $head);

        $messages = array_column(
            array_column($result['commits'], 'commit'),
            'message'
        );

        return array_map(function (string $message) {
            return explode(PHP_EOL, $message)[0];
        }, $messages);
    }

    public function getOrderedChangelog($prev_head, $head): array
    {
        $messages = $this->getChangelog($prev_head, $head);
        $links    = $this->commitsLinks($prev_head, $head);

        $messages = array_filter($messages, function ($message) {
            $prefixes = ['MEP', 'Merge branch'];

            foreach ($prefixes as $prefix) {
                if (mb_substr($message, 0, mb_strlen($prefix)) === $prefix) {
                    return false;
                }
            }

            return true;
        });

        $links = array_diff_key($links, array_diff_key($links, $messages));

        $plSections = [];
        $commits    = [];

        foreach ($messages as $key => $message) {
            $commit = ['message' => trim($message), 'labels' => [], 'link' => []];
            preg_match('/\(?#(\d+)\)?$/', $message, $matches);
            $commit['link'] = $links[$key];

            if (isset($matches[1])) {
                $commit['labels'] = $this->_getPullRequestLabels($matches[1]);

                foreach ($commit['labels'] as $label) {
                    if ('PL' === mb_substr($label, 0, 2) && !\in_array($label, $plSections)) {
                        $plSections[] = $label;
                    }
                }
            }
            $commits[] = $commit;
        }

        natsort($plSections);

        $messages = [];
        $links    = [];

        foreach ($plSections as $plSection) {
            $messages[] = $plSection;
            $messages[] = preg_replace('/.?/', '-', $plSection);
            $links[]    = $plSection;
            $links[]    = preg_replace('/.?/', '-', $plSection);

            foreach ($commits as $key => $commit) {
                if (\in_array($plSection, $commit['labels'])) {
                    $messages[] = $commit['message'];
                    $links[]    = $commit;

                    unset($commits[$key]);
                }
            }
            $messages[] = null;
        }

        $bugMessages = [];
        $bugLinks    = [];

        foreach ($commits as $key => $commit) {
            if (\in_array('bug', $commit['labels'])) {
                $bugMessages[] = $commit['message'];
                $bugLinks[]    = ['message' => $commit['message'], 'link' => $commit['link'], 'labels' => $commit['labels']]; // array 2
                unset($commits[$key]);
            }
        }

        if (\count($bugMessages) > 0) {
            $messages[] = 'Bug fixes';
            $messages[] = '---------';
            $messages   = array_merge($messages, $bugMessages);
            $messages[] = null;
            $links[]    = 'Bug fixes';
            $links[]    = '---------';
            $links      = array_merge($links, $bugLinks);
            $links[]    = null;
        }

        if (\count($commits) > 0) {
            if (\count($messages) > 0) {
                $messages[] = 'Autres';
                $messages[] = '------';
                $links[]    = 'Autres';
                $links[]    = '------';
            }
            $messages = array_merge($messages, array_column($commits, 'message'));
            $links    = array_merge($links, $commits);
        }

        if (\count($messages) > 0 && null === $messages[\count($messages) - 1]) {
            unset($messages[\count($messages) - 1], $links[\count($messages) - 1]);
        }

        return [0 => $messages, 1 => $links];
    }

    private function _getPullRequestLabels($pullRequestId): array
    {
        $pullRequest = $this->pullRequestRepository->fetch($pullRequestId);

        return $pullRequest->getLabels();
    }

    public function commitsLinks($prev_head, $head): array
    {
        $result = $this->commitRepository->getChangelog($prev_head, $head);
        $links  = array_column($result['commits'], 'html_url');

        return $links;
    }

    public function getCommitsLinks(): array
    {
        $isString = [];
        $table    = $this->getOrderedChangelog('master', 'dev')[1];

        foreach ($table as $value) {
            $isString[] = \gettype($value);
        }

        return ['num' => \count($isString), 'type' => $isString, 'messageLinks' => $this->getOrderedChangelog('master', 'dev')[1]];
    }
}
