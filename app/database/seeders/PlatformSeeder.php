<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            ['name' => 'twitter'],
            ['name' => 'instagram'],
            ['name' => 'linkedin'],
        ];

        foreach ($platforms as $platform) {
            Platform::create($platform);
        }
    }
}
