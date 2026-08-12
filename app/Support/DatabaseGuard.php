<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class DatabaseGuard
{
    /**
     * @return list<string>
     */
    public static function validateProductionConfig(bool $strict = false): array
    {
        if (! app()->environment(['production', 'staging'])) {
            return [];
        }

        $issues = [];
        $connection = (string) config('database.default');
        $username = (string) config("database.connections.{$connection}.username");
        $password = (string) config("database.connections.{$connection}.password");
        $database = (string) config("database.connections.{$connection}.database");

        if ($connection === 'mysql') {
            if ($username === '' || strtolower($username) === 'root') {
                $issues[] = 'Use a dedicated MySQL user in production — never root.';
            }

            if ($password === '') {
                $issues[] = 'DB_PASSWORD must be set in production.';
            }

            if ($database === '' || in_array($database, ['laravel', 'forge', 'test'], true)) {
                $issues[] = 'DB_DATABASE must point to the live application database.';
            }
        }

        foreach ($issues as $issue) {
            Log::warning('[DatabaseGuard] '.$issue);
        }

        $shouldFail = $strict || filter_var(env('DB_STRICT_PRODUCTION', false), FILTER_VALIDATE_BOOL);

        if ($issues !== [] && $shouldFail) {
            throw new \RuntimeException('Database configuration failed production checks: '.implode(' ', $issues));
        }

        return $issues;
    }
}
