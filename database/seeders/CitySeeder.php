<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $citiesByCountry = [
            'Libya' => ['Tripoli', 'Benghazi', 'Sabratha', 'Khoms'],
            'Egypt' => ['Cairo', 'Luxor', 'Aswan', 'Alexandria', 'Hurghada'],
            'Tunisia' => ['Tunis', 'Sousse', 'Hammamet', 'Djerba', 'Tozeur'],
            'Italy' => ['Rome', 'Milan', 'Venice', 'Florence', 'Naples'],
            'France' => ['Paris', 'Lyon', 'Marseille', 'Nice', 'Bordeaux'],
            'Spain' => ['Barcelona', 'Madrid', 'Seville', 'Valencia', 'Granada'],
            'Japan' => ['Tokyo', 'Kyoto', 'Osaka', 'Hiroshima', 'Nara'],
            'Greece' => ['Athens', 'Mykonos', 'Santorini', 'Thessaloniki', 'Crete'],
            'Morocco' => ['Marrakech', 'Casablanca', 'Fes', 'Rabat', 'Tangier'],
            'Germany' => ['Berlin', 'Munich', 'Hamburg', 'Frankfurt', 'Cologne'],
            'Russia' => ['Moscow', 'St. Petersburg', 'Kazan', 'Sochi', 'Vladivostok'],
        ];

        foreach ($citiesByCountry as $countryName => $cities) {
            $country = Country::where('name', $countryName)->first();

            if ($country) {
                foreach ($cities as $cityName) {
                    City::create([
                        'name' => $cityName,
                        'country_id' => $country->id,
                    ]);
                }
            }
        }
    }
}