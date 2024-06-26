<?php

namespace App\Prometheus;

use Prometheus\RenderTextFormat;
use Prometheus\CollectorRegistry;

class PrometheusService implements IPrometheusService
{
    protected $countersNamespace = 'inspirelink';
    public $registry;

    public function __construct()
    {
        \Prometheus\Storage\Redis::setDefaultOptions(
            [
                'host' => config('database.redis.cache.host'),
                'port' => config('database.redis.cache.port'),
                'username' => config('database.redis.cache.username'),
                'password' => config('database.redis.cache.password'),
                'timeout' => 0.1, // in seconds
                'read_timeout' => '5', // in seconds
                'persistent_connections' => false
            ]
        );

        $this->registry = CollectorRegistry::getDefault();
    }

    /**
     * @return string
     */
    public function getMetrics(): string
    {
        $renderer = new RenderTextFormat();
        return $renderer->render($this->registry->getMetricFamilySamples());
    }

    /**
     * @return void
     */
    public function clearMetrics(): void
    {
        $this->registry->wipeStorage();
    }

    /**
     * @param mixed $duration
     * @param string $routeName
     * 
     * @return void
     */
    public function addResponseTimeHistogram($duration, string $routeName): void
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            $this->countersNamespace,
            'http_response_time_seconds',
            'Duration of HTTP response in seconds',
            ['route']
        );

        $histogram->observe($duration, [$routeName]);
    }

    /**
     * @param mixed $routeName
     * 
     * @return void
     */
    public function incrementRequestCounter($routeName): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->countersNamespace,
            'http_requests_total',
            'Total HTTP requests',
            ['route']
        );

        $counter->inc([$routeName]);
    }

    /**
     * @param mixed $statusCode
     * @param mixed $routeName
     * 
     * @return void
     */
    public function incrementErrorCounter($statusCode, $routeName): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->countersNamespace,
            'http_errors_total',
            'Total HTTP errors',
            ['status_code', 'route']
        );
        $counter->inc([$statusCode, $routeName]);
    }

    /**
     * @param mixed $cacheKey
     * 
     * @return void
     */
    public function incrementCacheHit($cacheKey): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->countersNamespace,
            'cache_hits',
            'Total cache hits',
            ['cache_key']
        );
        $counter->inc([$cacheKey]);
    }

    /**
     * @param mixed $cacheKey
     * 
     * @return void
     */
    public function incrementCacheMiss($cacheKey): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->countersNamespace,
            'cache_misses',
            'Total cache misses',
            ['cache_key']
        );
        $counter->inc([$cacheKey]);
    }

    /**
     * @param mixed $source
     * 
     * @return void
     */
    public function incrementDatabaseQueryCount($source): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->countersNamespace,
            'database_queries_total',
            'Total database queries',
            ['source']
        );
        $counter->inc([$source]);
    }

    /**
     * @param mixed $duration
     * @param mixed $source
     * 
     * @return void
     */
    public function addDatabaseQueryTimeHistogram($duration, $source): void
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            $this->countersNamespace,
            'database_queries_time',
            'Duration of database query in seconds',
            ['source']
        );

        $histogram->observe($duration, ['source' => $source]);
    }
}
