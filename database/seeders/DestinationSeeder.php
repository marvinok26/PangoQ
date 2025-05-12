<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        // Create an array of available image filenames
        $imageFiles = [
            'image1.jpg', 'image2.jpg', 'image3.jpg', 'image4.jpeg', 'image5.jpg',
            'image6.jpg', 'image7.jpg', 'image8.jpeg', 'image9.jpg', 'image10.jpg',
            'image11.jpg', 'image12.jpg', 'image13.jpg', 'image14.jpeg', 'image15.jpg',
            'image16.jpg', 'image17.jpg', 'image18.jpg', 'image19.jpg'
        ];
        
        // Set up destinations with image files
        $destinations = [
            [
                'name' => 'Bali',
                'country' => 'Indonesia',
                'city' => 'Denpasar',
                'description' => 'A beautiful island paradise known for its stunning beaches, vibrant culture, and picturesque landscapes.',
                'image_url' => $imageFiles[0], // Use image1.jpg for Bali
            ],
            [
                'name' => 'Paris',
                'country' => 'France',
                'city' => 'Paris',
                'description' => 'The City of Light, known for its art, fashion, gastronomy, and culture.',
                'image_url' => $imageFiles[1], // Use image2.jpg for Paris
            ],
            [
                'name' => 'Tokyo',
                'country' => 'Japan',
                'city' => 'Tokyo',
                'description' => 'A bustling metropolis that blends ultramodern and traditional, with neon-lit skyscrapers and historic temples.',
                'image_url' => $imageFiles[2], // Use image3.jpg for Tokyo
            ],
            [
                'name' => 'New York',
                'country' => 'United States',
                'city' => 'New York',
                'description' => 'The Big Apple, a global center for business, art, fashion, food, and entertainment.',
                'image_url' => $imageFiles[3], // Use image4.jpeg for New York
            ],
            [
                'name' => 'Barcelona',
                'country' => 'Spain',
                'city' => 'Barcelona',
                'description' => 'A vibrant city known for its stunning architecture, Mediterranean beaches, and rich cultural heritage.',
                'image_url' => $imageFiles[4], // Use image5.jpg for Barcelona
            ],
            [
                'name' => 'Cape Town',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'description' => 'A coastal city with dramatic scenery, including Table Mountain and beautiful beaches.',
                'image_url' => $imageFiles[5], // Use image6.jpg for Cape Town
            ],
            [
                'name' => 'Sydney',
                'country' => 'Australia',
                'city' => 'Sydney',
                'description' => 'Famous for its stunning harbor, iconic Opera House, and beautiful beaches.',
                'image_url' => $imageFiles[6], // Use image7.jpg for Sydney
            ],
            [
                'name' => 'Rio de Janeiro',
                'country' => 'Brazil',
                'city' => 'Rio de Janeiro',
                'description' => 'Known for its spectacular beaches, Carnival, and the Christ the Redeemer statue.',
                'image_url' => $imageFiles[7], // Use image8.jpeg for Rio
            ],
            [
                'name' => 'Marrakech',
                'country' => 'Morocco',
                'city' => 'Marrakech',
                'description' => 'A historic city with vibrant markets, gardens, and traditional architecture.',
                'image_url' => $imageFiles[8], // Use image9.jpg for Marrakech
            ],
            [
                'name' => 'Santorini',
                'country' => 'Greece',
                'city' => 'Thira',
                'description' => 'A stunning island with white-washed buildings, blue domes, and breathtaking sunsets.',
                'image_url' => $imageFiles[9], // Use image10.jpg for Santorini
            ],
            [
                'name' => 'Kyoto',
                'country' => 'Japan',
                'city' => 'Kyoto',
                'description' => 'Japan\'s cultural capital, known for its classical Buddhist temples, gardens, and traditional wooden houses.',
                'image_url' => $imageFiles[10], // Use image11.jpg for Kyoto
            ],
            [
                'name' => 'Venice',
                'country' => 'Italy',
                'city' => 'Venice',
                'description' => 'A unique city built on a lagoon, famous for its canals, gondolas, and historic architecture.',
                'image_url' => $imageFiles[11], // Use image12.jpg for Venice
            ],
            [
                'name' => 'Kenya',
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'description' => 'Kenya is renowned for its classic savanna safaris, deserts, dramatic mountain ranges, cultures and beautiful beaches.',
                'image_url' => $imageFiles[12], // Use image13.jpg for Kenya
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