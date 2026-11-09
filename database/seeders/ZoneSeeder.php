<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Zone;
use App\Models\User;
use App\Models\Sacco;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Force reconnect to ensure we use the correct database
        DB::reconnect('sqlite');

        $sacco = Sacco::first();

        if (!$sacco) {
            $this->command->warn('No SACCO found. Skipping zone seeding.');
            $this->command->info('Sacco count: ' . Sacco::count());
            return;
        }

        // Create zones
        $zones = [
            ['name' => 'Zone Centre', 'code' => 'ZC', 'description' => 'Zone centrale de la ville'],
            ['name' => 'Zone Nord', 'code' => 'ZN', 'description' => 'Zone nord de la ville'],
            ['name' => 'Zone Sud', 'code' => 'ZS', 'description' => 'Zone sud de la ville'],
            ['name' => 'Zone Est', 'code' => 'ZE', 'description' => 'Zone est de la ville'],
            ['name' => 'Zone Ouest', 'code' => 'ZO', 'description' => 'Zone ouest de la ville'],
        ];

        foreach ($zones as $zoneData) {
            $zone = Zone::firstOrCreate(
                ['code' => $zoneData['code'], 'sacco_id' => $sacco->id],
                array_merge($zoneData, ['sacco_id' => $sacco->id, 'is_active' => true])
            );
            $this->command->info("Zone created: {$zone->name} ({$zone->code})");
        }

        // Assign zones to collectors
        $collectors = User::role('collector')->get();

        foreach ($collectors as $index => $collector) {
            // Assign 1-2 zones to each collector
            $zoneCount = min(count($zones), ($index % 2) + 1);
            $assignedZones = Zone::where('sacco_id', $sacco->id)->take($zoneCount)->get();

            foreach ($assignedZones as $zone) {
                $collector->zones()->syncWithoutDetaching([$zone->id]);
            }

            $this->command->info("Collector {$collector->name} assigned to zones: " . $collector->zones->pluck('name')->join(', '));
        }

        // Assign zones to members (for testing)
        $members = User::role('member')->get();
        $allZones = Zone::where('sacco_id', $sacco->id)->get();

        foreach ($members as $index => $member) {
            $zone = $allZones[$index % $allZones->count()];
            $member->zone_id = $zone->id;
            $member->save();
        }

        $this->command->info('Members assigned to zones.');
    }
}