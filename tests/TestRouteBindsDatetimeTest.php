<?php

namespace Tests;

use Carbon\Carbon as BaseCarbon;
use DateTime;
use DateTimeInterface;
use Illuminate\Routing\Router;
use Illuminate\Support\Carbon;
use Orchestra\Testbench\TestCase;

class TestRouteBindsDatetimeTest extends TestCase
{
    use RegistersPackage;

    protected Router $router;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->router = $this->app['router'];
        });

        parent::setUp();
    }

    public function test_route_datetime_parses_datetime(): void
    {
        $this->travelTo($day = Carbon::create(2020, 01, 31, 16, 45, 30));

        $this->router->get('test/{datetime}/foo', function ($datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/20190101/foo')->assertSee('2019-01-01T00:00:00.000000Z');
        $this->get('test/20190101163045/foo')->assertSee('2019-01-01T16:30:45.000000Z');
        $this->get('test/2019-01-01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
        $this->get('test/2019-01-01 16:30:45/foo')->assertSee('2019-01-01T16:30:45.000000Z');
        $this->get('test/2019-01-01 16:30:45/foo')->assertSee('2019-01-01T16:30:45.000000Z');
        $this->get('test/now/foo')->assertSee($day->toJSON());
        $this->get('test/today/foo')->assertSee($day->startOfDay()->toJSON());
        $this->get('test/yesterday/foo')->assertSee($day->subDay()->toJSON());
        $this->get('test/next month/foo')->assertSee($day->addMonth()->toJSON());
    }

    public function test_route_datetime_with_base_carbon(): void
    {
        $this->router->get('test/{datetime}/foo', function (BaseCarbon $datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019-01-01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
    }

    public function test_route_datetime_with_carbon(): void
    {
        $this->router->get('test/{datetime}/foo', function (Carbon $datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019-01-01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
    }

    public function test_route_datetime_with_datetime_interface(): void
    {
        $this->router->get('test/{datetime}/foo', function (DateTimeInterface $datetime) {
            static::assertInstanceOf(Carbon::class, $datetime);
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019-01-01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
    }

    public function test_route_datetime_with_datetime(): void
    {
        $this->router->get('test/{datetime}/foo', function (DateTime $datetime) {
            static::assertInstanceOf(Carbon::class, $datetime);
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019-01-01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
    }

    public function test_route_datetime_not_found_with_invalid_date(): void
    {
        $this->router->get('test/{datetime}/foo', function ($datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/invalid/foo')->assertNotFound();
    }

    public function test_route_datetime_with_format(): void
    {
        $this->travelTo(now()->setTime(19, 30));

        $this->router->get('test/{datetime:Y_m_d}/foo', function (DateTime $datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019_01_01/foo')->assertSee('2019-01-01T19:30:00.000000Z');
    }

    public function test_route_datetime_not_found_with_invalid_format(): void
    {
        $this->travelTo(now()->setTime(19, 30));

        $this->router->get('test/{datetime:asdasd}/foo', function (DateTime $datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019_01_01/foo')->assertNotFound();
    }

    public function test_route_date_defaults_to_beginning_of_day(): void
    {
        $this->travelTo(now()->setTime(19, 30));

        $this->router->get('test/{date}/foo', function (DateTime $datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019-01-01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
    }

    public function test_route_date_with_format_defaults_to_beginning_of_day(): void
    {
        $this->travelTo(now()->setTime(19, 30));

        $this->router->get('test/{date:Y_m_d}/foo', function (DateTime $datetime) {
            return $datetime;
        })->middleware('bindings');

        $this->get('test/2019_01_01/foo')->assertSee('2019-01-01T00:00:00.000000Z');
    }
}
