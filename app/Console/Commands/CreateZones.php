<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sacco;
use App\Models\Zone;
use App\Models\User;

class CreateZones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zones:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create zones and assign to collectors and members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sacco = Sacco::first();

        if (!$sacco) {
            $this->error('No SACCO found. Skipping zone creation.');
            return 1;
        }

        $this->info("SACCO: {$sacco->name} (ID: {$sacco->id})");

        // Create zones
        $zonesData = [
            ['name' => 'Zone Centre', 'code' => 'ZC', 'description' => 'Zone centrale de la ville'],
            ['name' => 'Zone Nord', 'code' => 'ZN', 'description' => 'Zone nord de la ville'],
            ['name' => 'Zone Sud', 'code' => 'ZS', 'description' => 'Zone sud de la ville'],
            ['name' => 'Zone Est', 'code' => 'ZE', 'description' => 'Zone est de la ville'],
            ['name' => 'Zone Ouest', 'code' => 'ZO', 'description' => 'Zone ouest de la ville'],
        ];

        foreach ($zonesData as $zoneData) {
            $zone = Zone::firstOrCreate(
                ['code' => $zoneData['code'], 'sacco_id' => $sacco->id],
                array_merge($zoneData, ['sacco_id' => $sacco->id, 'is_active' => true])
            );
            $this->info("Zone created: {$zone->name} ({$zone->code})");
        }

        // Assign zones to collectors
        $collectors = User::role('collector')->get();

        foreach ($collectors as $index => $collector) {
            // Assign 1-2 zones to each collector
            $zoneCount = min(5, ($index % 2) + 1);
            $assignedZones = Zone::where('sacco_id', $sacco->id)->take($zoneCount)->get();

            foreach ($assignedZones as $zone) {
                $collector->zones()->syncWithoutDetaching([$zone->id]);
            }

            $this->info("Collector {$collector->name} assigned to zones: " . $collector->zones->pluck('name')->join(', '));
        }

        // Assign zones to members (for testing)
        $members = User::role('member')->get();
        $allZones = Zone::where('sacco_id', $sacco->id)->get();

        foreach ($members as $index => $member) {
            $zone = $allZones[$index % $allZones->count()];
            $member->zone_id = $zone->id;
            $member->save();
        }

        $this->info('Members assigned to zones.');
        $this->info('Done!');

        return 0;
    }
}