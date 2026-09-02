<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

#[Signature('pcl:import-legacy-sqlite {--force : Upsert into destination tables that already contain data}')]
#[Description('Import preserved PixelCraftsLab CMS data from the legacy SQLite database into MySQL')]
class ImportLegacySqlite extends Command
{
    /** @var list<string> */
    private const TABLES = [
        'users',
        'site_settings',
        'navigation_items',
        'media',
        'projects',
        'project_metrics',
        'services',
        'process_stages',
        'team_members',
        'social_links',
        'testimonials',
        'pages',
        'page_sections',
        'contact_options',
        'contact_submissions',
        'marketing_channels',
        'growth_plans',
        'growth_plan_items',
        'marketing_campaigns',
        'channel_assignments',
        'packages',
        'package_items',
        'admin_activity_logs',
        'content_revisions',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = DB::connection('sqlite_legacy');
        $destination = DB::connection();

        if ($source->getDriverName() !== 'sqlite') {
            $this->components->error('The sqlite_legacy connection must use SQLite.');

            return self::FAILURE;
        }

        if (! in_array($destination->getDriverName(), ['mysql', 'mariadb'], true)) {
            $this->components->error('The default destination connection must use MySQL or MariaDB.');

            return self::FAILURE;
        }

        $missing = collect(self::TABLES)
            ->reject(fn (string $table): bool => Schema::connection('sqlite_legacy')->hasTable($table)
                && Schema::connection(config('database.default'))->hasTable($table));

        if ($missing->isNotEmpty()) {
            $this->components->error('Missing source or destination tables: '.$missing->implode(', '));

            return self::FAILURE;
        }

        if (! $this->option('force')) {
            $populated = collect(self::TABLES)
                ->filter(fn (string $table): bool => $destination->table($table)->exists());

            if ($populated->isNotEmpty()) {
                $this->components->error('Destination data already exists in: '.$populated->implode(', '));
                $this->line('Re-run with --force only when an idempotent upsert is intended.');

                return self::FAILURE;
            }
        }

        $counts = [];
        $destination->statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $destination->transaction(function () use ($source, $destination, &$counts): void {
                foreach (self::TABLES as $table) {
                    $counts[] = $this->importTable($source, $destination, $table);
                }
            });
        } finally {
            $destination->statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->table(['Table', 'SQLite', 'MySQL'], $counts);

        $mismatches = collect($counts)->filter(fn (array $row): bool => $row[1] !== $row[2]);

        if ($mismatches->isNotEmpty()) {
            $this->components->error('Import completed with row-count mismatches.');

            return self::FAILURE;
        }

        $this->components->info('Legacy CMS data imported with matching row counts.');

        return self::SUCCESS;
    }

    /** @return array{0: string, 1: int, 2: int} */
    private function importTable(ConnectionInterface $source, ConnectionInterface $destination, string $table): array
    {
        $sourceColumns = Schema::connection('sqlite_legacy')->getColumnListing($table);
        $destinationColumns = Schema::connection(config('database.default'))->getColumnListing($table);
        $columns = array_values(array_intersect($sourceColumns, $destinationColumns));
        $primaryKey = $sourceColumns[0];

        $source->table($table)
            ->orderBy($primaryKey)
            ->chunk(250, function (Collection $rows) use ($destination, $table, $columns, $primaryKey): void {
                $records = $rows->map(function (object $row) use ($table, $columns): array {
                    $record = array_intersect_key((array) $row, array_flip($columns));

                    if ($table === 'packages') {
                        $record['price_presentation'] = $this->legacyPricePresentation($record);
                    }

                    return $record;
                })->all();

                if ($records !== []) {
                    $destination->table($table)->upsert(
                        $records,
                        [$primaryKey],
                        array_values(array_diff(array_keys($records[0]), [$primaryKey])),
                    );
                }
            });

        return [
            $table,
            $source->table($table)->count(),
            $destination->table($table)->count(),
        ];
    }

    /** @param array<string, mixed> $record */
    private function legacyPricePresentation(array $record): string
    {
        if (($record['billing_type'] ?? null) === 'custom' || ($record['price'] ?? null) === null) {
            return 'custom';
        }

        return ($record['is_starting_from'] ?? false) ? 'estimated_from' : 'estimated';
    }
}
