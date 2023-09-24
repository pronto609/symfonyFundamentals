<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Cache\CacheItemInterface;
class MixRepository
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClientInterface $client,
        private readonly bool $isDebug
    ) {
    }

    public function findAll(string $ganre = null): array
    {
        $responce = $this->client->request('GET', 'https://github.com/pronto609/symfonyFundamentals/blob/main/mixes.json')->toArray();
        $mixes =  $this->cache->get('mixes_data', function (CacheItemInterface $cacheItem) use ($responce){
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $mixes = json_decode(implode('',$responce['payload']['blob']['rawLines']));
            $res = [];
            array_walk( $mixes,function ($item) use (&$res) {
                $track = [];
                $track['title'] = $item->title;
                $track['trackCount'] = $item->trackCount;
                $track['genre'] = $item->genre;

                $track['createdAt'] = $item->createdAt;
                $res[] = $track;
            });
            return $res;
        });
        if ($ganre) {
            return array_filter($mixes, function ($item) use ($ganre) {
                return $item['genre'] == $ganre;
            });
        }
        return $mixes;
    }
}