<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetLog;
use App\Models\Trip;
use Carbon\Carbon;

class BudgetLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::with('city.country')->get();

        foreach ($trips as $trip) {
            $this->createBudgetLogsForTrip($trip);
        }
    }

    private function createBudgetLogsForTrip($trip)
    {
        $tripStatus = $this->getTripStatus($trip);
        $originalBudget = (float) $trip->budget;
        $country = $trip->city->country->name;
        $city = $trip->city->name;
        $duration = Carbon::parse($trip->start_date)->diffInDays(Carbon::parse($trip->end_date));
        $numberOfPeople = $trip->number_of_people;

        // Calculate expense percentages based on trip status
        switch ($tripStatus) {
            case 'past':
                $expensePercentage = rand(70, 90) / 100; // Used 70-90% of budget
                break;
            case 'current':
                $expensePercentage = rand(40, 70) / 100; // Used 40-70% of budget so far
                break;
            case 'upcoming':
                $expensePercentage = rand(10, 30) / 100; // Only bookings/deposits, 10-30%
                break;
        }

        $totalExpenseAmount = $originalBudget * $expensePercentage;
        $expenses = $this->generateExpenses($trip, $tripStatus, $totalExpenseAmount, $country, $city, $duration, $numberOfPeople);

        // Create budget log entries
        $runningTotal = $originalBudget;
        foreach ($expenses as $expense) {
            $runningTotal -= $expense['amount'];

            BudgetLog::create([
                'trip_id' => $trip->id,
                'title' => $expense['title'],
                'amount' => $expense['amount'],
            ]);
        }

        // Update trip budget to remaining amount
        $trip->update(['budget' => max(0, $runningTotal)]);
    }

    private function generateExpenses($trip, $tripStatus, $totalAmount, $country, $city, $duration, $numberOfPeople)
    {
        $expenses = [];
        $remaining = $totalAmount;

        // Base expense categories with percentage allocation
        $categories = [
            'accommodation' => 0.35,  // 35%
            'flights' => 0.25,       // 25%
            'food' => 0.20,          // 20%
            'activities' => 0.15,    // 15%
            'transportation' => 0.05, // 5%
        ];

        // Adjust categories based on trip status
        if ($tripStatus === 'upcoming') {
            // Upcoming trips: mostly bookings
            $categories = [
                'accommodation' => 0.45,
                'flights' => 0.40,
                'activities' => 0.15,
                'food' => 0,
                'transportation' => 0,
            ];
        } elseif ($tripStatus === 'current') {
            // Current trips: some daily expenses
            $categories['food'] = 0.25;
            $categories['activities'] = 0.20;
            $categories['transportation'] = 0.10;
        }

        // Generate expenses for each category
        foreach ($categories as $category => $percentage) {
            if ($percentage == 0)
                continue;

            $categoryAmount = $totalAmount * $percentage;
            $categoryExpenses = $this->generateCategoryExpenses($category, $categoryAmount, $country, $city, $duration, $numberOfPeople, $tripStatus);

            foreach ($categoryExpenses as $expense) {
                if ($remaining >= $expense['amount']) {
                    $expenses[] = $expense;
                    $remaining -= $expense['amount'];
                }
            }
        }

        return $expenses;
    }

    private function generateCategoryExpenses($category, $amount, $country, $city, $duration, $numberOfPeople, $tripStatus)
    {
        $expenses = [];

        switch ($category) {
            case 'accommodation':
                if ($tripStatus === 'upcoming') {
                    $expenses[] = [
                        'title' => "Hotel booking deposit - {$city}",
                        'amount' => $amount,
                    ];
                } else {
                    $nightlyRate = $amount / $duration;
                    $nights = min(3, $duration); // Show first few nights
                    for ($i = 1; $i <= $nights; $i++) {
                        $expenses[] = [
                            'title' => "Hotel accommodation - Night {$i}",
                            'amount' => $nightlyRate,
                        ];
                    }
                    if ($nights < $duration) {
                        $expenses[] = [
                            'title' => "Hotel accommodation - Remaining nights",
                            'amount' => $amount - ($nightlyRate * $nights),
                        ];
                    }
                }
                break;

            case 'flights':
                if (in_array($country, ['Libya'])) {
                    $expenses[] = [
                        'title' => "Domestic flights to {$city}",
                        'amount' => $amount,
                    ];
                } else {
                    $expenses[] = [
                        'title' => "International flights to {$country}",
                        'amount' => $amount,
                    ];
                }
                break;

            case 'food':
                $dailyFood = $amount / $duration;
                $daysToShow = min(5, $duration);
                for ($i = 1; $i <= $daysToShow; $i++) {
                    $expenses[] = [
                        'title' => "Meals and dining - Day {$i}",
                        'amount' => $dailyFood,
                    ];
                }
                if ($daysToShow < $duration) {
                    $expenses[] = [
                        'title' => "Meals and dining - Remaining days",
                        'amount' => $amount - ($dailyFood * $daysToShow),
                    ];
                }
                break;

            case 'activities':
                $cityActivities = $this->getCityActivities($city, $amount, $tripStatus);
                $expenses = array_merge($expenses, $cityActivities);
                break;

            case 'transportation':
                $expenses[] = [
                    'title' => "Local transportation and taxis",
                    'amount' => $amount * 0.6,
                ];
                $expenses[] = [
                    'title' => "Airport transfers",
                    'amount' => $amount * 0.4,
                ];
                break;
        }

        return $expenses;
    }

    private function getCityActivities($city, $amount, $tripStatus)
    {
        $activities = [];

        switch ($city) {
            case 'Rome':
                $activities = [
                    ['title' => 'Colosseum and Roman Forum tickets', 'amount' => $amount * 0.3],
                    ['title' => 'Vatican Museums entrance', 'amount' => $amount * 0.25],
                    ['title' => 'Guided city walking tour', 'amount' => $amount * 0.25],
                    ['title' => 'Trevi Fountain area exploration', 'amount' => $amount * 0.2],
                ];
                break;

            case 'Tokyo':
            case 'Kyoto':
                $activities = [
                    ['title' => 'Japan Rail Pass', 'amount' => $amount * 0.4],
                    ['title' => 'Temple visits and entrance fees', 'amount' => $amount * 0.3],
                    ['title' => 'Tokyo Skytree observation deck', 'amount' => $amount * 0.3],
                ];
                break;

            case 'Cairo':
            case 'Luxor':
                $activities = [
                    ['title' => 'Pyramids of Giza entrance and guide', 'amount' => $amount * 0.4],
                    ['title' => 'Egyptian Museum tickets', 'amount' => $amount * 0.2],
                    ['title' => 'Khan el-Khalili bazaar shopping', 'amount' => $amount * 0.4],
                ];
                break;

            case 'Paris':
                $activities = [
                    ['title' => 'Louvre Museum tickets', 'amount' => $amount * 0.25],
                    ['title' => 'Eiffel Tower elevator access', 'amount' => $amount * 0.2],
                    ['title' => 'Seine River cruise', 'amount' => $amount * 0.25],
                    ['title' => 'Versailles day trip', 'amount' => $amount * 0.3],
                ];
                break;

            case 'Barcelona':
                $activities = [
                    ['title' => 'Sagrada Familia tickets', 'amount' => $amount * 0.3],
                    ['title' => 'Park Güell entrance', 'amount' => $amount * 0.2],
                    ['title' => 'Casa Batlló tour', 'amount' => $amount * 0.25],
                    ['title' => 'Las Ramblas and Gothic Quarter tour', 'amount' => $amount * 0.25],
                ];
                break;

            case 'Athens':
                $activities = [
                    ['title' => 'Acropolis and Parthenon tickets', 'amount' => $amount * 0.4],
                    ['title' => 'Ancient Agora entrance', 'amount' => $amount * 0.2],
                    ['title' => 'National Archaeological Museum', 'amount' => $amount * 0.2],
                    ['title' => 'Plaka neighborhood dining', 'amount' => $amount * 0.2],
                ];
                break;

            case 'Marrakech':
                $activities = [
                    ['title' => 'Majorelle Garden entrance', 'amount' => $amount * 0.2],
                    ['title' => 'Medina souks shopping', 'amount' => $amount * 0.4],
                    ['title' => 'Traditional hammam experience', 'amount' => $amount * 0.2],
                    ['title' => 'Jemaa el-Fnaa evening activities', 'amount' => $amount * 0.2],
                ];
                break;

            case 'Moscow':
                $activities = [
                    ['title' => 'Kremlin and Red Square tour', 'amount' => $amount * 0.35],
                    ['title' => 'Bolshoi Theatre tickets', 'amount' => $amount * 0.3],
                    ['title' => 'Metro stations architectural tour', 'amount' => $amount * 0.35],
                ];
                break;

            case 'Munich':
                $activities = [
                    ['title' => 'Neuschwanstein Castle day trip', 'amount' => $amount * 0.5],
                    ['title' => 'BMW Museum and factory tour', 'amount' => $amount * 0.25],
                    ['title' => 'Traditional beer garden experience', 'amount' => $amount * 0.25],
                ];
                break;

            case 'Tunis':
                $activities = [
                    ['title' => 'Carthage archaeological site tour', 'amount' => $amount * 0.3],
                    ['title' => 'Sidi Bou Said village visit', 'amount' => $amount * 0.25],
                    ['title' => 'Bardo Museum entrance', 'amount' => $amount * 0.25],
                    ['title' => 'Medina guided tour', 'amount' => $amount * 0.2],
                ];
                break;

            default:
                $activities = [
                    ['title' => 'City attractions and museums', 'amount' => $amount * 0.4],
                    ['title' => 'Guided tours and experiences', 'amount' => $amount * 0.35],
                    ['title' => 'Local entertainment', 'amount' => $amount * 0.25],
                ];
                break;
        }

        // Filter activities based on trip status
        if ($tripStatus === 'upcoming') {
            // Only keep bookable activities for upcoming trips
            $activities = array_filter($activities, function ($activity) {
                return strpos($activity['title'], 'tickets') !== false ||
                    strpos($activity['title'], 'tour') !== false ||
                    strpos($activity['title'], 'Pass') !== false;
            });
        }

        return $activities;
    }

    private function getTripStatus($trip)
    {
        $today = Carbon::now()->toDateString();

        if ($trip->end_date < $today) {
            return 'past';
        } elseif ($trip->start_date <= $today && $trip->end_date >= $today) {
            return 'current';
        } else {
            return 'upcoming';
        }
    }
}