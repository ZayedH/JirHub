<?php

namespace App\Dashboard\Handler;

use App\Dashboard\Query\PullRequestsToDeploy;
use App\Dashboard\Query\PullRequestsToMergeOnDev;
use App\Dashboard\Query\ReviewEnvironments;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class DashboardHandler
{
    const REVIEW_ENVIRONMENTS = 'review_environments';

    const PULL_REQUEST_TO_DEPLOY = 'pull_requests_to_deploy';

    const PULL_REQUEST_TO_MERGE_ON_DEV = 'pull_requests_to_merge_on_dev';

    const CACHE_KEY = 'dashboard_data';

    /** @var ReviewEnvironments */
    protected $reviewEnvironments;

    /** @var PullRequestsToDeploy */
    protected $pullRequestsToDeploy;

    /** @var PullRequestsToMergeOnDev */
    protected $pullRequestsToMergeOnDev;

    /** @var CacheItemPoolInterface */
    protected $cache;

    public function __construct(
        ReviewEnvironments $reviewEnvironments,
        PullRequestsToDeploy $pullRequestsToDeploy,
        PullRequestsToMergeOnDev $pullRequestsToMergeOnDev,
        CacheItemPoolInterface $cache
    ) {
        $this->reviewEnvironments       = $reviewEnvironments;
        $this->pullRequestsToDeploy     = $pullRequestsToDeploy;
        $this->pullRequestsToMergeOnDev = $pullRequestsToMergeOnDev;
        $this->cache                    = $cache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getData()
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);

        if (!$cacheItem->isHit()) {
            $cacheItem->set(
                [
                    self::REVIEW_ENVIRONMENTS          => $this->reviewEnvironments->fetch(),
                    self::PULL_REQUEST_TO_DEPLOY       => $this->pullRequestsToDeploy->fetch(),
                    self::PULL_REQUEST_TO_MERGE_ON_DEV => $this->pullRequestsToMergeOnDev->fetch(),
                ]
            );

            $this->cache->save($cacheItem);
        }

        return $cacheItem->get();
    }
}
