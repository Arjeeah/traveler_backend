<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            'Libya',
            'Egypt',
            'Tunisia',
            'Italy',
            'France',
            'Spain',
            'Japan',
            'Greece',
            'Morocco',
            'Germany',
            'Russia',
        ];

        foreach ($countries as $country) {
            Country::create(['name' => $country]);
        }
    }
}