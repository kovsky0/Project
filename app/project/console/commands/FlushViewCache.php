<?php
/**
 * Copyright (C) 2015 David Young
 * 
 * Defines the flush view cache command
 */
namespace Project\Console\Commands;
use RDev\Console\Commands;
use RDev\Console\Requests;
use RDev\Console\Responses;
use RDev\Views\Cache;

class FlushViewCache extends Commands\Command
{
    /** @var Cache\ICache The view cache */
    private $cache = null;

    /**
     * @param Cache\ICache $cache The view cache
     */
    public function __construct(Cache\ICache $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    protected function define()
    {
        $this->setName("views:flush")
            ->setDescription("Flushes all of the compiled views from cache");
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(Responses\IResponse $response)
    {
        $this->cache->flush();
        $response->writeln("Cache flushed");
    }
}