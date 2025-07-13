<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Area;

class TripAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ancient Rome Discovery
        $romeTrip = Trip::where('title', 'Ancient Rome Discovery')->first();
        if ($romeTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Rome');
            })->whereIn('name', ['Colosseum', 'Vatican City', 'Roman Forum', 'Trevi Fountain'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $romeTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Japanese Cultural Journey
        $kyotoTrip = Trip::where('title', 'Japanese Cultural Journey')->first();
        if ($kyotoTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Kyoto');
            })->whereIn('name', ['Fushimi Inari Shrine', 'Kinkaku-ji', 'Arashiyama Bamboo Grove', 'Gion District'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $kyotoTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Pyramids and Pharaohs
        $cairoTrip = Trip::where('title', 'Pyramids and Pharaohs')->first();
        if ($cairoTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Cairo');
            })->whereIn('name', ['Giza Pyramids', 'Egyptian Museum', 'Khan el-Khalili'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $cairoTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Gaudí Architecture Tour
        $barcelonaTrip = Trip::where('title', 'Gaudí Architecture Tour')->first();
        if ($barcelonaTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Barcelona');
            })->whereIn('name', ['Sagrada Familia', 'Park Güell', 'Las Ramblas', 'Gothic Quarter'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $barcelonaTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Moroccan Medina Adventure
        $marrakechTrip = Trip::where('title', 'Moroccan Medina Adventure')->first();
        if ($marrakechTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Marrakech');
            })->where('is_recommended', true)->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $marrakechTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Greek Islands Hopping
        $athensTrip = Trip::where('title', 'Greek Islands Hopping')->first();
        if ($athensTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Athens');
            })->whereIn('name', ['Acropolis', 'Ancient Agora', 'Plaka'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $athensTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // City of Light Experience
        $parisTrip = Trip::where('title', 'City of Light Experience')->first();
        if ($parisTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Paris');
            })->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $parisTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Bavarian Castles Tour
        $munichTrip = Trip::where('title', 'Bavarian Castles Tour')->first();
        if ($munichTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Munich');
            })->whereIn('name', ['Marienplatz', 'Neuschwanstein Castle', 'English Garden'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $munichTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Russian Heritage Tour
        $moscowTrip = Trip::where('title', 'Russian Heritage Tour')->first();
        if ($moscowTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Moscow');
            })->where('is_recommended', true)->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $moscowTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Valley of the Kings Expedition
        $luxorTrip = Trip::where('title', 'Valley of the Kings Expedition')->first();
        if ($luxorTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Luxor');
            })->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $luxorTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }

        // Carthage and Sidi Bou Said
        $tunisTrip = Trip::where('title', 'Carthage and Sidi Bou Said')->first();
        if ($tunisTrip) {
            $areas = Area::whereHas('city', function ($query) {
                $query->where('name', 'Tunis');
            })->whereIn('name', ['Carthage', 'Sidi Bou Said', 'Medina of Tunis'])->pluck('id')->toArray();

            foreach ($areas as $index => $areaId) {
                $tunisTrip->areas()->attach($areaId, ['order' => $index]);
            }
        }
    }
}