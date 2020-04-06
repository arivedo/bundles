<?php

namespace Homepizza\ApiBundle;

use Homepizza\ApiBundle\ApiManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class ApiManager implements ApiManagerInterface
{
    /* @var HttpClientInterface */
    private $http;

    /* @var AdapterInterface */
    private $cache;

    /* @var string */
    private $uri;

    /* @var int */
    private $cacheLife;

    public function __construct(HttpClientInterface $http, AdapterInterface $cache, string $uri = '', int $cacheLife = 0)
    {
        $this->http = $http;
        $this->cache = $cache;
        $this->uri = $uri;
        $this->cacheLife = $cacheLife;
    }

    public function getSomething(): array
    {
        $result = $this->http
            ->request('GET', 'http://homepizza.web/v2.php/api/data/division')
            ->getContent()
        ;

        $item = $this->cache->getItem(sha1('test_key'));
        if (!$item->isHit()) {
            $data = '123123';
            $item->set($data);
            $this->cache->save($item);
            dump('Нет кэш API!');
            die();
        }

        return json_decode($result, true);
    }
}
