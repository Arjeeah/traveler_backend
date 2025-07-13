<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Trip;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::with('city.country')->get();

        foreach ($trips as $trip) {
            $this->createTasksForTrip($trip);
        }
    }

    private function createTasksForTrip($trip)
    {
        $tripStatus = $this->getTripStatus($trip);
        $country = $trip->city->country->name;
        $city = $trip->city->name;

        // Base tasks for all trips
        $baseTasks = [
            ['title' => 'Book accommodation', 'priority' => 'high'],
            ['title' => 'Research local attractions', 'priority' => 'medium'],
            ['title' => 'Check weather forecast', 'priority' => 'low'],
            ['title' => 'Pack luggage', 'priority' => 'high'],
            ['title' => 'Confirm travel insurance', 'priority' => 'medium'],
        ];

        // International travel tasks
        if (!in_array($country, ['Libya'])) { // Assuming Libya is domestic
            $baseTasks = array_merge($baseTasks, [
                ['title' => 'Check passport validity', 'priority' => 'high'],
                ['title' => 'Research visa requirements', 'priority' => 'high'],
                ['title' => 'Book international flights', 'priority' => 'high'],
                ['title' => 'Notify bank of travel plans', 'priority' => 'medium'],
            ]);
        }

        // City-specific tasks
        $citySpecificTasks = $this->getCitySpecificTasks($city, $country);
        $baseTasks = array_merge($baseTasks, $citySpecificTasks);

        // During trip tasks
        $duringTripTasks = [
            ['title' => 'Try local cuisine', 'priority' => 'medium'],
            ['title' => 'Take photos at landmarks', 'priority' => 'low'],
            ['title' => 'Buy souvenirs', 'priority' => 'low'],
            ['title' => 'Keep travel journal', 'priority' => 'low'],
        ];

        // Post trip tasks  
        $postTripTasks = [
            ['title' => 'Upload photos to cloud', 'priority' => 'low'],
            ['title' => 'Write travel review', 'priority' => 'low'],
            ['title' => 'Organize travel documents', 'priority' => 'low'],
        ];

        // Determine which tasks to include and their completion status
        $allTasks = [];

        switch ($tripStatus) {
            case 'past':
                // Include all tasks, most completed
                $allTasks = array_merge($baseTasks, $duringTripTasks, $postTripTasks);
                $completionRate = 0.9; // 90% completed
                break;

            case 'current':
                // Include pre-trip (completed) and during trip tasks
                $allTasks = array_merge($baseTasks, $duringTripTasks);
                $completionRate = 0.7; // 70% completed
                break;

            case 'upcoming':
                // Mostly pre-trip tasks, few completed
                $allTasks = $baseTasks;
                $completionRate = 0.3; // 30% completed
                break;
        }

        // Create tasks with appropriate completion status
        foreach ($allTasks as $index => $taskData) {
            $isCompleted = (rand(1, 100) / 100) <= $completionRate;

            Task::create([
                'trip_id' => $trip->id,
                'title' => $taskData['title'],
                'is_done' => $isCompleted,
                'priority' => $taskData['priority'],
            ]);
        }
    }

    private function getCitySpecificTasks($city, $country)
    {
        $tasks = [];

        switch ($city) {
            case 'Rome':
                $tasks = [
                    ['title' => 'Book Vatican Museum tickets', 'priority' => 'high'],
                    ['title' => 'Learn basic Italian phrases', 'priority' => 'low'],
                    ['title' => 'Visit Colosseum', 'priority' => 'high'],
                ];
                break;

            case 'Tokyo':
            case 'Kyoto':
                $tasks = [
                    ['title' => 'Get JR Pass', 'priority' => 'high'],
                    ['title' => 'Learn basic Japanese etiquette', 'priority' => 'medium'],
                    ['title' => 'Download translation app', 'priority' => 'medium'],
                ];
                break;

            case 'Cairo':
            case 'Luxor':
                $tasks = [
                    ['title' => 'Book guided pyramid tour', 'priority' => 'high'],
                    ['title' => 'Prepare for hot weather', 'priority' => 'medium'],
                    ['title' => 'Learn about ancient Egyptian history', 'priority' => 'low'],
                ];
                break;

            case 'Paris':
                $tasks = [
                    ['title' => 'Book Louvre Museum tickets', 'priority' => 'high'],
                    ['title' => 'Learn basic French phrases', 'priority' => 'low'],
                    ['title' => 'Research Paris Metro system', 'priority' => 'medium'],
                ];
                break;

            case 'Barcelona':
                $tasks = [
                    ['title' => 'Book Sagrada Familia tickets', 'priority' => 'high'],
                    ['title' => 'Learn about Gaudí architecture', 'priority' => 'low'],
                    ['title' => 'Research tapas restaurants', 'priority' => 'medium'],
                ];
                break;

            case 'Athens':
                $tasks = [
                    ['title' => 'Book Acropolis tickets', 'priority' => 'high'],
                    ['title' => 'Research Greek islands ferry schedules', 'priority' => 'medium'],
                    ['title' => 'Learn about Greek mythology', 'priority' => 'low'],
                ];
                break;

            case 'Marrakech':
                $tasks = [
                    ['title' => 'Research medina navigation', 'priority' => 'medium'],
                    ['title' => 'Learn basic Arabic/French phrases', 'priority' => 'low'],
                    ['title' => 'Prepare for haggling in souks', 'priority' => 'medium'],
                ];
                break;

            case 'Moscow':
                $tasks = [
                    ['title' => 'Apply for Russian visa', 'priority' => 'high'],
                    ['title' => 'Book Kremlin tour', 'priority' => 'high'],
                    ['title' => 'Learn Cyrillic alphabet basics', 'priority' => 'low'],
                ];
                break;

            case 'Munich':
                $tasks = [
                    ['title' => 'Book Neuschwanstein Castle tour', 'priority' => 'high'],
                    ['title' => 'Research Oktoberfest dates', 'priority' => 'medium'],
                    ['title' => 'Learn basic German phrases', 'priority' => 'low'],
                ];
                break;

            case 'Tunis':
                $tasks = [
                    ['title' => 'Research Carthage archaeological sites', 'priority' => 'medium'],
                    ['title' => 'Plan visit to Sidi Bou Said', 'priority' => 'medium'],
                    ['title' => 'Learn about Tunisian history', 'priority' => 'low'],
                ];
                break;
        }

        return $tasks;
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