<?php

namespace App\Console\Commands;

use App\Support\DatabaseGuard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDOException;

class DbHealthCheck extends Command
{
    protected $signature = 'db:health
                            {--json : Output machine-readable JSON}
                            {--strict : Fail on production config warnings}';

    protected $description = 'Verify database connectivity and production-safe configuration';

    public function handle(): int
    {
        $result = [
            'ok' => true,
            'connection' => config('database.default'),
            'database' => null,
            'host' => null,
            'config_issues' => [],
            'error' => null,
        ];

        $connection = (string) config('database.default');
        $result['database'] = config("database.connections.{$connection}.database");
        $result['host'] = config("database.connections.{$connection}.host");

        if ($this->option('strict') || filter_var(env('DB_STRICT_PRODUCTION', false), FILTER_VALIDATE_BOOL)) {
            try {
                $result['config_issues'] = DatabaseGuard::validateProductionConfig(true);
            } catch (\Throwable $e) {
                $result['ok'] = false;
                $result['error'] = $e->getMessage();
            }
        } else {
            try {
                $result['config_issues'] = DatabaseGuard::validateProductionConfig();
            } catch (\Throwable $e) {
                $result['ok'] = false;
                $result['error'] = $e->getMessage();
            }
        }

        if ($result['ok']) {
            try {
                DB::connection()->getPdo();
                DB::connection()->select('select 1 as ok');
            } catch (PDOException $e) {
                $result['ok'] = false;
                $result['error'] = $e->getMessage();
            } catch (\Throwable $e) {
                $result['ok'] = false;
                $result['error'] = $e->getMessage();
            }
        }

        if ($this->option('json')) {
            $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $result['ok'] ? self::SUCCESS : self::FAILURE;
        }

        if ($result['ok']) {
            $this->info('Database health check passed.');
            $this->line("Connection: {$connection}");
            $this->line("Database: {$result['database']}");
            if ($result['host']) {
                $this->line("Host: {$result['host']}");
            }

            foreach ($result['config_issues'] as $issue) {
                $this->warn($issue);
            }

            return self::SUCCESS;
        }

        $this->error('Database health check failed.');
        if ($result['error']) {
            $this->line($result['error']);
        }

        foreach ($result['config_issues'] as $issue) {
            $this->warn($issue);
        }

        return self::FAILURE;
    }
}
