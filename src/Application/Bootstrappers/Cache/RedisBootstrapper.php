<?php
namespace Project\Application\Bootstrappers\Cache;

use Exception;
use Opulence\Ioc\Bootstrappers\LazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Redis\Redis;
use Opulence\Redis\Types\TypeMapper;
use Redis as Client;
use RuntimeException;

/**
 * Defines the Redis bootstrapper
 */
class RedisBootstrapper extends LazyBootstrapper
{
    /**
     * @inheritdoc
     */
    public function getBindings() : array
    {
        return [Redis::class, TypeMapper::class];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container) : void
    {
        try {
            $client = new Client();
            $client->connect(
                getenv('REDIS_HOST'),
                getenv('REDIS_PORT')
            );
            $client->select(getenv('REDIS_DATABASE'));
            $redis = new Redis($client);
            $container->bindInstance(Redis::class, $redis);
            $container->bindInstance(TypeMapper::class, new TypeMapper());
        } catch (Exception $ex) {
            throw new RuntimeException('Failed to register Redis bindings', 0, $ex);
        }
    }
}
