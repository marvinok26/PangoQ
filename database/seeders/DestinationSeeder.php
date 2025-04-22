<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Paris',
                'country' => 'France',
                'city' => 'Paris',
                'description' => 'The City of Light, famous for the Eiffel Tower, Louvre Museum, and exquisite cuisine.',
                'image_url' => 'destinations/paris.jpg',
            ],
            [
                'name' => 'Bali',
                'country' => 'Indonesia',
                'city' => 'Denpasar',
                'description' => 'A tropical paradise known for beautiful beaches, vibrant culture, and spiritual retreats.',
                'image_url' => 'destinations/bali.jpg',
            ],
            [
                'name' => 'New York City',
                'country' => 'United States',
                'city' => 'New York',
                'description' => 'The Big Apple offers iconic landmarks, world-class shopping, and diverse culture.',
                'image_url' => 'destinations/nyc.jpg',
            ],
            [
                'name' => 'Tokyo',
                'country' => 'Japan',
                'city' => 'Tokyo',
                'description' => 'A fascinating blend of ultramodern and traditional, with stunning technology and serene temples.',
                'image_url' => 'destinations/tokyo.jpg',
            ],
            [
                'name' => 'Cape Town',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'description' => 'Beautiful coastal city with Table Mountain, diverse wildlife, and rich cultural history.',
                'image_url' => 'destinations/cape-town.jpg',
            ],
        ];
        
        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}