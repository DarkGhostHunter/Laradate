<?php

namespace DarkGhostHunter\Laradate;

use DateTime;
use DateTimeInterface;
use Illuminate\Routing\Route;
use Illuminate\Support\DateFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class DateFactoryBind
{
    /**
     * The Date Factory.
     *
     * @var \Illuminate\Support\DateFactory
     */
    protected DateFactory $factory;

    /**
     * DateFactoryBind constructor.
     *
     * @param  \Illuminate\Support\DateFactory  $factory
     */
    public function __construct(DateFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Handle binding the datetime
     *
     * @param  string  $value
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return \DateTimeInterface
     */
    public function datetime(string $value, Route $route): DateTimeInterface
    {
        return $this->toDatetime($value, $route->bindingFieldFor('datetime'));
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
        $begin = (new DateTime())->setTimestamp(
            $this->toDatetime($value, $route->bindingFieldFor('date'))->getTimestamp()
        );

        return $this->factory->parse($begin->format('Y-m-d 00:00:00'));
    }

    /**
     * Transforms a string into a DateTime Interface instance.
     *
     * @param  string  $value
     * @param  string|null  $format
     *
     * @return \DateTimeInterface
     */
    protected function toDatetime(string $value, string $format = null): DateTimeInterface
    {
        try {
            return $format ? $this->factory->createFromFormat($format, $value) : $this->factory->parse($value);
        } catch (Throwable $throwable) {
            throw new HttpException(404, null, $throwable);
        }
    }
}
