<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        // Instead of truncating, use firstOrCreate to avoid constraint violations
        $destinations = [
            [
                'name' => 'Bali',
                'country' => 'Indonesia',
                'city' => 'Denpasar',
                'description' => 'A beautiful island paradise known for its stunning beaches, vibrant culture, and picturesque landscapes.',
                'image_url' => null,
            ],
            [
                'name' => 'Paris',
                'country' => 'France',
                'city' => 'Paris',
                'description' => 'The City of Light, known for its art, fashion, gastronomy, and culture.',
                'image_url' => null,
            ],
            [
                'name' => 'Tokyo',
                'country' => 'Japan',
                'city' => 'Tokyo',
                'description' => 'A bustling metropolis that blends ultramodern and traditional, with neon-lit skyscrapers and historic temples.',
                'image_url' => null,
            ],
            [
                'name' => 'New York',
                'country' => 'United States',
                'city' => 'New York',
                'description' => 'The Big Apple, a global center for business, art, fashion, food, and entertainment.',
                'image_url' => null,
            ],
            [
                'name' => 'Barcelona',
                'country' => 'Spain',
                'city' => 'Barcelona',
                'description' => 'A vibrant city known for its stunning architecture, Mediterranean beaches, and rich cultural heritage.',
                'image_url' => null,
            ],
            [
                'name' => 'Cape Town',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'description' => 'A coastal city with dramatic scenery, including Table Mountain and beautiful beaches.',
                'image_url' => null,
            ],
            [
                'name' => 'Sydney',
                'country' => 'Australia',
                'city' => 'Sydney',
                'description' => 'Famous for its stunning harbor, iconic Opera House, and beautiful beaches.',
                'image_url' => null,
            ],
            [
                'name' => 'Rio de Janeiro',
                'country' => 'Brazil',
                'city' => 'Rio de Janeiro',
                'description' => 'Known for its spectacular beaches, Carnival, and the Christ the Redeemer statue.',
                'image_url' => null,
            ],
            [
                'name' => 'Marrakech',
                'country' => 'Morocco',
                'city' => 'Marrakech',
                'description' => 'A historic city with vibrant markets, gardens, and traditional architecture.',
                'image_url' => null,
            ],
            [
                'name' => 'Santorini',
                'country' => 'Greece',
                'city' => 'Thira',
                'description' => 'A stunning island with white-washed buildings, blue domes, and breathtaking sunsets.',
                'image_url' => null,
            ],
            [
                'name' => 'Kyoto',
                'country' => 'Japan',
                'city' => 'Kyoto',
                'description' => 'Japan\'s cultural capital, known for its classical Buddhist temples, gardens, and traditional wooden houses.',
                'image_url' => null,
            ],
            [
                'name' => 'Venice',
                'country' => 'Italy',
                'city' => 'Venice',
                'description' => 'A unique city built on a lagoon, famous for its canals, gondolas, and historic architecture.',
                'image_url' => null,
            ],
            [
                'name' => 'Kenya',
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'description' => 'Kenya is renowned for its classic savanna safaris, deserts, dramatic mountain ranges, cultures and beautiful beaches.',
                'image_url' => null,
            ],
        ];
        
        // Insert destinations using firstOrCreate
        foreach ($destinations as $destination) {
            Destination::firstOrCreate(
                ['name' => $destination['name']],
                $destination
            );
        }
    }
}