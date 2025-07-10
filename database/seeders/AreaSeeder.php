<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\City;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areasByCity = [
            // Libya
            'Tripoli' => [
                ['name' => 'Old Medina', 'description' => 'Historic walled city with traditional markets', 'is_recommended' => true],
                ['name' => 'Red Castle Museum', 'description' => 'Ancient fortress housing historical artifacts', 'is_recommended' => true],
                ['name' => 'Marcus Aurelius Arch', 'description' => 'Roman triumphal arch from 2nd century AD', 'is_recommended' => true],
            ],
            'Khoms' => [
                ['name' => 'Leptis Magna', 'description' => 'Best preserved Roman city in the Mediterranean', 'is_recommended' => true],
                ['name' => 'Severan Basilica', 'description' => 'Magnificent Roman basilica with carved columns', 'is_recommended' => true],
                ['name' => 'Roman Amphitheatre', 'description' => 'Ancient entertainment venue with ocean views', 'is_recommended' => true],
            ],
            'Sabratha' => [
                ['name' => 'Roman Theatre', 'description' => 'Spectacular 3-storey Roman theatre backdrop', 'is_recommended' => true],
                ['name' => 'Temple of Isis', 'description' => 'Ancient temple by the Mediterranean shore', 'is_recommended' => true],
                ['name' => 'Forum Baths', 'description' => 'Well-preserved Roman bath mosaics', 'is_recommended' => true],
            ],

            // Egypt
            'Cairo' => [
                ['name' => 'Giza Pyramids', 'description' => 'The Great Pyramid and Sphinx complex', 'is_recommended' => true],
                ['name' => 'Egyptian Museum', 'description' => 'Tutankhamun treasures and ancient artifacts', 'is_recommended' => true],
                ['name' => 'Khan el-Khalili', 'description' => 'Historic bazaar and Islamic architecture', 'is_recommended' => true],
                ['name' => 'Citadel of Saladin', 'description' => 'Medieval fortress with Mohammed Ali Mosque', 'is_recommended' => true],
                ['name' => 'Coptic Cairo', 'description' => 'Ancient Christian quarter with historic churches', 'is_recommended' => false],
            ],
            'Luxor' => [
                ['name' => 'Valley of the Kings', 'description' => 'Pharaonic tombs including Tutankhamun', 'is_recommended' => true],
                ['name' => 'Karnak Temple', 'description' => 'Largest ancient religious site', 'is_recommended' => true],
                ['name' => 'Luxor Temple', 'description' => 'Ancient temple in city center', 'is_recommended' => true],
                ['name' => 'Temple of Hatshepsut', 'description' => 'Terraced mortuary temple', 'is_recommended' => true],
                ['name' => 'Valley of the Queens', 'description' => 'Royal wives and children tombs', 'is_recommended' => false],
            ],

            // Tunisia
            'Tunis' => [
                ['name' => 'Carthage', 'description' => 'Ancient Phoenician and Roman ruins', 'is_recommended' => true],
                ['name' => 'Sidi Bou Said', 'description' => 'Blue and white hilltop village', 'is_recommended' => true],
                ['name' => 'Medina of Tunis', 'description' => 'UNESCO World Heritage old town', 'is_recommended' => true],
                ['name' => 'Bardo Museum', 'description' => 'World-famous Roman mosaics collection', 'is_recommended' => true],
                ['name' => 'La Marsa', 'description' => 'Coastal resort area with beaches', 'is_recommended' => false],
            ],

            // Italy
            'Rome' => [
                ['name' => 'Colosseum', 'description' => 'Iconic Roman amphitheatre', 'is_recommended' => true],
                ['name' => 'Vatican City', 'description' => 'St. Peters Basilica and Sistine Chapel', 'is_recommended' => true],
                ['name' => 'Roman Forum', 'description' => 'Ancient city center ruins', 'is_recommended' => true],
                ['name' => 'Trevi Fountain', 'description' => 'Baroque masterpiece fountain', 'is_recommended' => true],
                ['name' => 'Pantheon', 'description' => 'Best preserved Roman temple', 'is_recommended' => true],
            ],
            'Milan' => [
                ['name' => 'Duomo di Milano', 'description' => 'Gothic cathedral with rooftop views', 'is_recommended' => true],
                ['name' => 'La Scala Opera House', 'description' => 'World-famous opera venue', 'is_recommended' => true],
                ['name' => 'Galleria Vittorio Emanuele II', 'description' => 'Historic luxury shopping arcade', 'is_recommended' => true],
            ],

            // France
            'Paris' => [
                ['name' => 'Eiffel Tower', 'description' => 'Iconic iron lattice tower', 'is_recommended' => true],
                ['name' => 'Louvre Museum', 'description' => 'World largest art museum', 'is_recommended' => true],
                ['name' => 'Notre-Dame Cathedral', 'description' => 'Gothic cathedral masterpiece', 'is_recommended' => true],
                ['name' => 'Arc de Triomphe', 'description' => 'Triumphal arch on Champs-Élysées', 'is_recommended' => true],
                ['name' => 'Montmartre', 'description' => 'Artistic hilltop neighborhood', 'is_recommended' => true],
            ],

            // Spain
            'Barcelona' => [
                ['name' => 'Sagrada Familia', 'description' => 'Gaudí unfinished basilica masterpiece', 'is_recommended' => true],
                ['name' => 'Park Güell', 'description' => 'Gaudí mosaic wonderland park', 'is_recommended' => true],
                ['name' => 'Las Ramblas', 'description' => 'Famous pedestrian boulevard', 'is_recommended' => true],
                ['name' => 'Gothic Quarter', 'description' => 'Medieval streets and architecture', 'is_recommended' => true],
                ['name' => 'Casa Batlló', 'description' => 'Gaudí modernist house museum', 'is_recommended' => false],
            ],

            // Japan
            'Tokyo' => [
                ['name' => 'Senso-ji Temple', 'description' => 'Ancient Buddhist temple in Asakusa', 'is_recommended' => true],
                ['name' => 'Tokyo Skytree', 'description' => 'Tallest tower with observation decks', 'is_recommended' => true],
                ['name' => 'Shibuya Crossing', 'description' => 'World busiest pedestrian crossing', 'is_recommended' => true],
                ['name' => 'Meiji Shrine', 'description' => 'Shinto shrine in forest setting', 'is_recommended' => true],
                ['name' => 'Harajuku', 'description' => 'Youth culture and fashion district', 'is_recommended' => false],
            ],
            'Kyoto' => [
                ['name' => 'Fushimi Inari Shrine', 'description' => 'Thousands of vermillion torii gates', 'is_recommended' => true],
                ['name' => 'Kinkaku-ji', 'description' => 'Golden Pavilion temple', 'is_recommended' => true],
                ['name' => 'Arashiyama Bamboo Grove', 'description' => 'Magical bamboo forest path', 'is_recommended' => true],
                ['name' => 'Gion District', 'description' => 'Traditional geisha district', 'is_recommended' => true],
                ['name' => 'Kiyomizu-dera', 'description' => 'Wooden temple with city views', 'is_recommended' => true],
            ],

            // Greece
            'Athens' => [
                ['name' => 'Acropolis', 'description' => 'Parthenon and ancient citadel', 'is_recommended' => true],
                ['name' => 'Ancient Agora', 'description' => 'Ancient marketplace ruins', 'is_recommended' => true],
                ['name' => 'Plaka', 'description' => 'Historic neighborhood below Acropolis', 'is_recommended' => true],
                ['name' => 'National Archaeological Museum', 'description' => 'Greek antiquities collection', 'is_recommended' => false],
            ],
            'Santorini' => [
                ['name' => 'Oia', 'description' => 'Sunset views and blue domed churches', 'is_recommended' => true],
                ['name' => 'Fira', 'description' => 'Capital with caldera views', 'is_recommended' => true],
                ['name' => 'Red Beach', 'description' => 'Unique volcanic red sand beach', 'is_recommended' => true],
                ['name' => 'Akrotiri', 'description' => 'Minoan Bronze Age settlement', 'is_recommended' => false],
            ],
            'Mykonos' => [
                ['name' => 'Mykonos Town', 'description' => 'Whitewashed buildings and windmills', 'is_recommended' => true],
                ['name' => 'Little Venice', 'description' => 'Waterfront houses and sunset views', 'is_recommended' => true],
                ['name' => 'Paradise Beach', 'description' => 'Famous party beach', 'is_recommended' => false],
            ],

            // Morocco
            'Marrakech' => [
                ['name' => 'Jemaa el-Fnaa', 'description' => 'Main square with performers and food', 'is_recommended' => true],
                ['name' => 'Medina Souks', 'description' => 'Traditional markets and crafts', 'is_recommended' => true],
                ['name' => 'Majorelle Garden', 'description' => 'Blue villa and botanical garden', 'is_recommended' => true],
                ['name' => 'Koutoubia Mosque', 'description' => 'Iconic minaret landmark', 'is_recommended' => true],
                ['name' => 'Bahia Palace', 'description' => '19th century palace with gardens', 'is_recommended' => false],
            ],
            'Fes' => [
                ['name' => 'Fes el-Bali', 'description' => 'Medieval medina and tanneries', 'is_recommended' => true],
                ['name' => 'Al-Qarawiyyin', 'description' => 'World oldest university', 'is_recommended' => true],
                ['name' => 'Bou Inania Madrasa', 'description' => 'Marinid architecture masterpiece', 'is_recommended' => true],
            ],

            // Germany
            'Munich' => [
                ['name' => 'Marienplatz', 'description' => 'Central square with Glockenspiel', 'is_recommended' => true],
                ['name' => 'Neuschwanstein Castle', 'description' => 'Fairy tale castle in nearby Alps', 'is_recommended' => true],
                ['name' => 'English Garden', 'description' => 'Large park with beer gardens', 'is_recommended' => true],
                ['name' => 'BMW Museum', 'description' => 'Automotive history and design', 'is_recommended' => false],
            ],
            'Berlin' => [
                ['name' => 'Brandenburg Gate', 'description' => 'Iconic neoclassical monument', 'is_recommended' => true],
                ['name' => 'Berlin Wall Memorial', 'description' => 'Cold War history site', 'is_recommended' => true],
                ['name' => 'Museum Island', 'description' => 'Five world-class museums', 'is_recommended' => true],
                ['name' => 'Reichstag', 'description' => 'Parliament building with glass dome', 'is_recommended' => false],
            ],

            // Russia
            'Moscow' => [
                ['name' => 'Red Square', 'description' => 'Iconic square with St. Basils Cathedral', 'is_recommended' => true],
                ['name' => 'Kremlin', 'description' => 'Historic fortress and government seat', 'is_recommended' => true],
                ['name' => 'Bolshoi Theatre', 'description' => 'World-famous ballet and opera', 'is_recommended' => true],
                ['name' => 'Moscow Metro', 'description' => 'Ornate underground palace stations', 'is_recommended' => false],
            ],
            'St. Petersburg' => [
                ['name' => 'Hermitage Museum', 'description' => 'Vast art collection in Winter Palace', 'is_recommended' => true],
                ['name' => 'Peterhof Palace', 'description' => 'Russian Versailles with fountains', 'is_recommended' => true],
                ['name' => 'Church of the Savior on Blood', 'description' => 'Colorful onion-domed church', 'is_recommended' => true],
                ['name' => 'Catherine Palace', 'description' => 'Baroque palace with Amber Room', 'is_recommended' => false],
            ],
        ];

        foreach ($areasByCity as $cityName => $areas) {
            $city = City::where('name', $cityName)->first();

            if ($city) {
                foreach ($areas as $areaData) {
                    Area::create([
                        'name' => $areaData['name'],
                        'city_id' => $city->id,
                        'description' => $areaData['description'],
                        'is_recommended' => $areaData['is_recommended'],
                    ]);
                }
            }
        }
    }
}