<?php

namespace DarkGhostHunter\Laradate\Http\Middleware;

use Closure;
use DateTime;
use DateTimeInterface;
use Illuminate\Http\Request;
use RuntimeException;

class ContainDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $min
     * @param  string|null  $max
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next, string $min = null, string $max = null): mixed
    {
        if ($date = $request->route()->parameter('date')) {
            abort_unless($this->isDateBetween($date, $min, $max), 404);
        }

        return $next($request);
    }

    /**
     * Checks if the date is between two dates.
     *
     * @param  \DateTimeInterface  $date
     * @param  string|null  $min
     * @param  string|null  $max
     *
     * @return bool
     * @throws \Exception
     */
    protected function isDateBetween(DateTimeInterface $date, ?string $min, ?string $max): bool
    {
        if (!$min && !$max) {
            throw new RuntimeException("No minimum or maximum dates to compare [{$date->format('Y-m-d H:i:s')}].");
        }

        if ($min && $date < new DateTime($min)) {
            return false;
        }

        if ($max && $date > new DateTime($max)) {
            return false;
        }

        return true;
    }
}
