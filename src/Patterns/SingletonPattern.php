<?php

namespace Vertuoza\Patterns;

use Exception;
use Vertuoza\Exceptions\SingletonWakeupException;
use Vertuoza\Interface\SingletonInterface;

abstract class SingletonPattern implements SingletonInterface
{
    /**
     * @var array<SingletonPattern>
     */
    private static array $instances = [];

    protected function __construct() {}

    /**
     * @return void
     */
    protected function __clone() { }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function __wakeup(): void
    {
        throw new SingletonWakeupException("Cannot unserialize singleton");
    }

    /**
     * @return static
     */
    final public static function getInstance(): static
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            // Note that here we use the "static" keyword instead of the actual
            // class name. In this context, the "static" keyword means "the name
            // of the current class". That detail is important because when the
            // method is called on the subclass, we want an instance of that
            // subclass to be created here.

            self::$instances[$subclass] = static::newInstance();
        }
        return self::$instances[$subclass];
    }

    /**
     * @return static
     */
    protected static function newInstance(): static
    {
        return new static();
    }
}
