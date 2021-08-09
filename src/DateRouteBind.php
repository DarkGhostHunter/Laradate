<?php

namespace DarkGhostHunter\Laradate;

use Carbon\Exceptions\Exception;
use DateTimeInterface;
use Illuminate\Routing\Route;
use Illuminate\Support\DateFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @internal
 */
class DateRouteBind
{
    /**
     * DateFactoryBind constructor.
     *
     * @param  \Illuminate\Support\DateFactory  $factory
     */
    public function __construct(protected DateFactory $factory)
    {
        //
    }

    /**
     * Creates a date defaulting to the beginning of the day.
     *
     * @param  string  $value
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return \DateTimeInterface
     */
    public function date(string $value, Route $route): DateTimeInterface
    {
        try {
            return $this->factory->createFromFormat($route->bindingFieldFor('date') ?? '!Y-m-d', $value);
        } catch (Exception $e) {
            throw new HttpException(404, null, $e);
        }
    }
}
