<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bridge\Twig\Command\DebugCommand;
class MixRepository
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClientInterface $githubContentClient,
        #[Autowire('%kernel.debug%')]
        private readonly bool $isDebug,
        #[Autowire(service: 'twig.command.debug')]
        private readonly DebugCommand $twigDebugCommand
    ) {
    }

    public function findAll(string $ganre = null): array
    {
        /*$output = new BufferedOutput();
        $this->twigDebugCommand->run(new ArrayInput([]), $output);
        dd($output);*/
        dd($this->githubContentClient);

        $responce = $this->githubContentClient->request('GET', '/pronto609/symfonyFundamentals/blob/main/mixes.json')->toArray();
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