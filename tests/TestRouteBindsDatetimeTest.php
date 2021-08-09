<?php

namespace Tests;

use DarkGhostHunter\Laradate\LaradateServiceProvider;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class TestRouteBindsDatetimeTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [LaradateServiceProvider::class];
    }

    public function test_registers_package(): void
    {
        static::assertArrayHasKey(LaradateServiceProvider::class, $this->app->getLoadedProviders());
    }

    public function test_route_date_default_format(): void
    {
        $this->travelTo(Carbon::create(2020, 01, 31, 16, 45, 30));

        Route::get('test/{date}/foo', function (Request $request, $date) {
            return $date;
        })->middleware('bindings');

        $this->get('test/2019-02-03/foo')->assertSee('2019-02-03T00:00:00.000000Z');
        $this->get('test/invalid/foo')->assertNotFound();
    }

    public function test_route_date_custom_format(): void
    {
        $this->travelTo(Carbon::create(2020, 01, 31, 16, 45, 30));

        Route::get('test/{date:YmdHis}/foo', function ($date) {
            return $date;
        })->middleware('bindings');

        $this->get('test/20190203173000/foo')->assertSee('2019-02-03T17:30:00.000000Z');
        $this->get('test/20190203/foo')->assertNotFound();
        $this->get('test/2019-02-03/foo')->assertNotFound();
        $this->get('test/invalid/foo')->assertNotFound();
    }

    public function test_route_uses_carbon(): void
    {
        $assert = function ($object) {
            static::assertInstanceOf(Carbon::class, $object);
        };

        Route::get('test/{date}/foo', function (Carbon $date) use ($assert) {
            $assert($date);
            return $date;
        })->middleware('bindings');

        $this->get('test/2019-02-03/foo')->assertSee('2019-02-03T00:00:00.000000Z');
    }

    public function test_route_uses_datetime_interface(): void
    {
        $assert = function ($object) {
            static::assertInstanceOf(DateTimeInterface::class, $object);
        };

        Route::get('test/{date}/foo', function (DateTimeInterface $date) use ($assert) {
            $assert($date);
            return $date;
        })->middleware('bindings');

        $this->get('test/2019-02-03/foo')->assertSee('2019-02-03T00:00:00.000000Z');
    }

    public function test_route_between_two_dates(): void
    {
        Route::get('test/{date}/foo', function (DateTimeInterface $date) {
            return $date;
        })->middleware('bindings', 'date:yesterday,tomorrow');

        $this->get('test/' . now()->format('Y-m-d') . '/foo')->assertOk();
        $this->get('test/' . Carbon::parse('-2 days')->format('Y-m-d') . '/foo')->assertNotFound();
        $this->get('test/' . Carbon::parse('2 days')->format('Y-m-d') . '/foo')->assertNotFound();
    }

    public function test_route_between_or_equal_two_dates(): void
    {
        Route::get('test/{date}/foo', function (DateTimeInterface $date) {
            return $date;
        })->middleware('bindings', 'date:yesterday,tomorrow');

        $this->get('test/' . Carbon::parse('yesterday')->format('Y-m-d') . '/foo')->assertOk();
        $this->get('test/' . Carbon::parse('tomorrow')->format('Y-m-d') . '/foo')->assertOk();
    }

    public function test_route_between_or_equal_two_dates_with_spaces(): void
    {
        Route::get('test/{date}/foo', function (DateTimeInterface $date) {
            return $date;
        })->middleware('bindings', 'date:-3 months 00:00,3 months 00:00');

        $this->get('test/' . Carbon::parse('-3 months')->format('Y-m-d') . '/foo')->assertOk();
        $this->get('test/' . Carbon::parse('3 months')->format('Y-m-d') . '/foo')->assertOk();

        $this->get('test/' . Carbon::parse('-4 months')->format('Y-m-d') . '/foo')->assertNotFound();
        $this->get('test/' . Carbon::parse('4 months')->format('Y-m-d') . '/foo')->assertNotFound();
    }

    public function test_route_after_date(): void
    {
        Route::get('test/{date}/foo', function (DateTimeInterface $date) {
            return $date;
        })->middleware('bindings', 'date:yesterday');

        $this->get('test/' . Carbon::parse('yesterday')->format('Y-m-d') . '/foo')->assertOk();
        $this->get('test/' . Carbon::parse('yesterday - 1 second')->format('Y-m-d') . '/foo')->assertNotFound();
    }

    public function test_route_before_date(): void
    {
        Route::get('test/{date}/foo', function (DateTimeInterface $date) {
            return $date;
        })->middleware('bindings', 'date:,tomorrow');

        $this->get('test/' . Carbon::parse('tomorrow')->format('Y-m-d') . '/foo')->assertOk();
        $this->get('test/' . Carbon::parse('2 days 00:00')->format('Y-m-d') . '/foo')->assertNotFound();
    }

    public function test_exception_if_no_min_max(): void
    {
        Route::get('test/{date}/foo', function (DateTimeInterface $date) {
            return $date;
        })->middleware('bindings', 'date:');

        $this->get('test/' . now()->format('Y-m-d') . '/foo')->assertStatus(500);
    }
}
