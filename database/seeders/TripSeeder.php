<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\User;
use App\Models\City;
use Carbon\Carbon;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $regularUser = User::where('email', 'user@example.com')->first();

        $trips = [
            // Past trips
            [
                'user' => $adminUser,
                'city' => 'Rome',
                'title' => 'Ancient Rome Discovery',
                'description' => 'Explored the Colosseum, Vatican, and Roman Forum',
                'budget' => 3500.00,
                'start_date' => Carbon::now()->subMonths(8),
                'end_date' => Carbon::now()->subMonths(8)->addDays(5),
                'number_of_people' => 2,
            ],
            [
                'user' => $regularUser,
                'city' => 'Kyoto',
                'title' => 'Japanese Cultural Journey',
                'description' => 'Temples, bamboo groves, and traditional experiences',
                'budget' => 4000.00,
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->subMonths(6)->addDays(7),
                'number_of_people' => 1,
            ],
            [
                'user' => $adminUser,
                'city' => 'Cairo',
                'title' => 'Pyramids and Pharaohs',
                'description' => 'Giza pyramids, Egyptian Museum, and Khan el-Khalili',
                'budget' => 2800.00,
                'start_date' => Carbon::now()->subMonths(4),
                'end_date' => Carbon::now()->subMonths(4)->addDays(6),
                'number_of_people' => 3,
            ],

            // Current trips
            [
                'user' => $regularUser,
                'city' => 'Barcelona',
                'title' => 'Gaudí Architecture Tour',
                'description' => 'Sagrada Familia, Park Güell, and Las Ramblas exploration',
                'budget' => 2500.00,
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(4),
                'number_of_people' => 2,
            ],

            // Upcoming trips
            [
                'user' => $adminUser,
                'city' => 'Marrakech',
                'title' => 'Moroccan Medina Adventure',
                'description' => 'Souks, palaces, and Sahara desert excursion',
                'budget' => 3200.00,
                'start_date' => Carbon::now()->addWeeks(2),
                'end_date' => Carbon::now()->addWeeks(2)->addDays(8),
                'number_of_people' => 2,
            ],
            [
                'user' => $regularUser,
                'city' => 'Athens',
                'title' => 'Greek Islands Hopping',
                'description' => 'Athens Acropolis then ferry to Mykonos and Santorini',
                'budget' => 4500.00,
                'start_date' => Carbon::now()->addMonths(1),
                'end_date' => Carbon::now()->addMonths(1)->addDays(10),
                'number_of_people' => 2,
            ],
            [
                'user' => $adminUser,
                'city' => 'Paris',
                'title' => 'City of Light Experience',
                'description' => 'Louvre, Eiffel Tower, and Versailles day trip',
                'budget' => 3800.00,
                'start_date' => Carbon::now()->addMonths(2),
                'end_date' => Carbon::now()->addMonths(2)->addDays(5),
                'number_of_people' => 2,
            ],
            [
                'user' => $regularUser,
                'city' => 'Munich',
                'title' => 'Bavarian Castles Tour',
                'description' => 'Neuschwanstein Castle, Munich old town, and beer gardens',
                'budget' => 2600.00,
                'start_date' => Carbon::now()->addMonths(3),
                'end_date' => Carbon::now()->addMonths(3)->addDays(4),
                'number_of_people' => 1,
            ],
            [
                'user' => $adminUser,
                'city' => 'Moscow',
                'title' => 'Russian Heritage Tour',
                'description' => 'Red Square, Kremlin, and St. Petersburg by train',
                'budget' => 5000.00,
                'start_date' => Carbon::now()->addMonths(4),
                'end_date' => Carbon::now()->addMonths(4)->addDays(9),
                'number_of_people' => 2,
            ],
            [
                'user' => $regularUser,
                'city' => 'Luxor',
                'title' => 'Valley of the Kings Expedition',
                'description' => 'Ancient tombs, temples, and Nile cruise',
                'budget' => 3500.00,
                'start_date' => Carbon::now()->addMonths(5),
                'end_date' => Carbon::now()->addMonths(5)->addDays(7),
                'number_of_people' => 4,
            ],
            [
                'user' => $adminUser,
                'city' => 'Tunis',
                'title' => 'Carthage and Sidi Bou Said',
                'description' => 'Ancient ruins and blue-white coastal village',
                'budget' => 2200.00,
                'start_date' => Carbon::now()->addMonths(6),
                'end_date' => Carbon::now()->addMonths(6)->addDays(5),
                'number_of_people' => 2,
            ],
        ];

        foreach ($trips as $tripData) {
            $city = City::where('name', $tripData['city'])->first();

            if ($tripData['user'] && $city) {
                Trip::create([
                    'user_id' => $tripData['user']->id,
                    'city_id' => $city->id,
                    'title' => $tripData['title'],
                    'description' => $tripData['description'],
                    'budget' => $tripData['budget'],
                    'start_date' => $tripData['start_date'],
                    'end_date' => $tripData['end_date'],
                    'number_of_people' => $tripData['number_of_people'],
                ]);
            }
        }
    }
}