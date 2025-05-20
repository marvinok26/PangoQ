<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TripTemplate;
use App\Models\TemplateActivity;
use App\Models\Destination;

class TripTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Create an array of available image filenames for activities
        $activityImages = [
            'image14.jpeg', 'image15.jpg', 'image16.jpg', 
            'image17.jpg', 'image18.jpg', 'image19.jpg',
            'image1.jpg', 'image2.jpg', 'image3.jpg',
            'image4.jpeg', 'image5.jpg', 'image6.jpg',
            'image7.jpg', 'image8.jpeg', 'image9.jpg',
            'image10.jpg', 'image11.jpg', 'image12.jpg',
        ];
        
        // Helper function to get a random image from our pool
        $getRandomImage = function() use ($activityImages) {
            return $activityImages[array_rand($activityImages)];
        };

        // Create Kenya destination
        $kenya = Destination::firstOrCreate(
            ['name' => 'Kenya'],
            [
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'description' => 'Kenya is renowned for its classic savanna safaris, deserts, dramatic mountain ranges, cultures and beautiful beaches.',
                'image_url' => 'image13.jpg',
            ]
        );

        // Create Bali destination
        $bali = Destination::firstOrCreate(
            ['name' => 'Bali'],
            [
                'country' => 'Indonesia',
                'city' => 'Denpasar',
                'description' => 'A beautiful island paradise known for its stunning beaches, vibrant culture, and picturesque landscapes.',
                'image_url' => 'image1.jpg',
            ]
        );

        // Create Kenya Trip Templates (4 packages)
        $this->createKenyaTrips($kenya, $getRandomImage);
        
        // Create Bali Trip Templates (3 packages)
        $this->createBaliTrips($bali, $getRandomImage);
    }

    private function createKenyaTrips($kenya, $getRandomImage)
    {
        // KENYA PACKAGE 1: 7 DAY BEST OF KENYA SOPA CIRCUIT (Luxury)
        $kenyaSafari = TripTemplate::create([
            'destination_id' => $kenya->id,
            'title' => '7 DAY BEST OF KENYA SOPA CIRCUIT',
            'description' => 'Experience the best of Kenya with visits to Amboseli National Park, Lake Naivasha, Lake Nakuru National Park, and Masai Mara National Reserve. This luxury package includes stays at premium Sopa Lodges throughout the circuit.',
            'duration_days' => 7,
            'base_price' => 1800.00,
            'difficulty_level' => 'moderate',
            'trip_style' => 'safari',
            'is_featured' => true,
            'featured_image' => 'image13.jpg',
            'highlights' => json_encode([
                "Mt. Kilimanjaro views from Amboseli National Park",
                "Incredible elephant herds in their natural habitat",
                "Lake Naivasha boat safari with hippo encounters",
                "Cycling safari through Hell's Gate National Park", 
                "Big Five wildlife viewing in Masai Mara",
                "Cultural interaction with Maasai tribes"
            ]),
        ]);

        // Day 1 - Amboseli National Park
        $this->createKenyaDay1Activities($kenyaSafari, $getRandomImage);
        
        // Day 2 - Amboseli National Park
        $this->createKenyaDay2Activities($kenyaSafari, $getRandomImage);
        
        // Day 3 - Lake Naivasha
        $this->createKenyaDay3Activities($kenyaSafari, $getRandomImage);
        
        // Day 4 - Lake Nakuru National Park
        $this->createKenyaDay4Activities($kenyaSafari, $getRandomImage);
        
        // Day 5 - Masai Mara National Reserve
        $this->createKenyaDay5Activities($kenyaSafari, $getRandomImage);
        
        // Day 6 - Masai Mara National Reserve
        $this->createKenyaDay6Activities($kenyaSafari, $getRandomImage);
        
        // Day 7 - Nairobi
        $this->createKenyaDay7Activities($kenyaSafari, $getRandomImage);

        // Create optional activities for this package
        $this->createKenyaOptionalActivities($kenyaSafari, $getRandomImage);

        // KENYA PACKAGE 2: 5 DAY MAASAI MARA & LAKE NAKURU SAFARI (Budget)
        $kenyaBudget = TripTemplate::create([
            'destination_id' => $kenya->id,
            'title' => '5 DAY MAASAI MARA & LAKE NAKURU SAFARI',
            'description' => 'Perfect for travelers on a budget, this 5-day safari focuses on Kenya\'s most iconic wildlife destinations. Experience the world-famous Maasai Mara National Reserve and the flamingo-dotted Lake Nakuru while staying in comfortable tented camps.',
            'duration_days' => 5,
            'base_price' => 1200.00,
            'difficulty_level' => 'easy',
            'trip_style' => 'safari',
            'is_featured' => false,
            'featured_image' => 'image4.jpeg',
            'highlights' => json_encode([
                "Big Five game viewing in Maasai Mara",
                "Flamingo flocks at Lake Nakuru",
                "Affordable tented camp accommodations",
                "Great Rift Valley viewpoints",
                "Rhino sanctuary visit"
            ]),
        ]);

        $this->createKenyaBudgetTripActivities($kenyaBudget, $getRandomImage);
        $this->createKenyaBudgetOptionalActivities($kenyaBudget, $getRandomImage);

        // KENYA PACKAGE 3: 10 DAY ULTIMATE KENYA ADVENTURE (Premium)
        $kenyaPremium = TripTemplate::create([
            'destination_id' => $kenya->id,
            'title' => '10 DAY ULTIMATE KENYA ADVENTURE',
            'description' => 'The most comprehensive Kenya safari experience combining wildlife, culture, and adventure. From the Amboseli plains beneath Mt. Kilimanjaro to the Samburu wilderness and the Maasai Mara, this premium journey showcases Kenya\'s extraordinary diversity.',
            'duration_days' => 10,
            'base_price' => 2800.00,
            'difficulty_level' => 'moderate',
            'trip_style' => 'adventure',
            'is_featured' => true,
            'featured_image' => 'image9.jpg',
            'highlights' => json_encode([
                "Private luxury safari vehicles",
                "Samburu tribal experiences",
                "Night game drives in private conservancies",
                "Helicopter flight over the Great Rift Valley",
                "Hot air balloon safari in Maasai Mara",
                "Mount Kenya helicopter tour",
                "Premium lodge accommodation"
            ]),
        ]);

        $this->createKenyaPremiumTripActivities($kenyaPremium, $getRandomImage);
        $this->createKenyaPremiumOptionalActivities($kenyaPremium, $getRandomImage);

        // KENYA PACKAGE 4: 6 DAY KENYAN COAST & WILDLIFE (Beach+Safari)
        $kenyaCoast = TripTemplate::create([
            'destination_id' => $kenya->id,
            'title' => '6 DAY KENYAN COAST & WILDLIFE',
            'description' => 'Combine the best of Kenya\'s wildlife and pristine beaches in this perfectly balanced itinerary. Start with safari adventures in Tsavo National Park before relaxing on the white sands of Diani Beach along the Indian Ocean.',
            'duration_days' => 6,
            'base_price' => 1500.00,
            'difficulty_level' => 'easy',
            'trip_style' => 'beach',
            'is_featured' => false,
            'featured_image' => 'image11.jpg',
            'highlights' => json_encode([
                "Red elephant sightings in Tsavo East",
                "Luxurious beachfront resort stay",
                "Snorkeling in marine reserves",
                "Traditional dhow sailing trip",
                "Fresh seafood dining experiences",
                "Optional water sports activities"
            ]),
        ]);

        $this->createKenyaCoastTripActivities($kenyaCoast, $getRandomImage);
        $this->createKenyaCoastOptionalActivities($kenyaCoast, $getRandomImage);
    }

    private function createBaliTrips($bali, $getRandomImage)
    {
        // BALI PACKAGE 1: 5 DAY BALI ISLAND PARADISE EXPLORER
        $baliIsland = TripTemplate::create([
            'destination_id' => $bali->id,
            'title' => 'BALI ISLAND PARADISE EXPLORER',
            'description' => 'Experience the best of Bali with this carefully curated trip featuring pristine beaches, ancient temples, and cultural experiences.',
            'duration_days' => 5,
            'base_price' => 1200.00,
            'difficulty_level' => 'easy',
            'trip_style' => 'cultural',
            'is_featured' => true,
            'featured_image' => 'image1.jpg',
            'highlights' => json_encode([
                "Sacred Monkey Forest exploration in Ubud",
                "Traditional Balinese cultural performances",
                "Spectacular sunrise hike on Mount Batur",
                "Seaside Uluwatu Temple with Kecak Fire Dance",
                "Pristine beach time in Kuta and Jimbaran"
            ]),
        ]);

        // Day 1 Activities
        $this->createBaliDay1Activities($baliIsland, $getRandomImage);
        
        // Day 2 Activities
        $this->createBaliDay2Activities($baliIsland, $getRandomImage);
        
        // Day 3 Activities
        $this->createBaliDay3Activities($baliIsland, $getRandomImage);
        
        // Day 4 Activities
        $this->createBaliDay4Activities($baliIsland, $getRandomImage);
        
        // Day 5 Activities
        $this->createBaliDay5Activities($baliIsland, $getRandomImage);

        // Optional activities for Bali Island package
        $this->createBaliIslandOptionalActivities($baliIsland, $getRandomImage);

        // BALI PACKAGE 2: 7 DAY BALI WELLNESS & YOGA RETREAT
        $baliWellness = TripTemplate::create([
            'destination_id' => $bali->id,
            'title' => 'BALI WELLNESS & YOGA RETREAT',
            'description' => 'Rejuvenate your mind, body, and spirit with this transformative wellness journey through Bali. Featuring daily yoga, meditation, spa treatments, and healthy cuisine in serene settings from Ubud\'s jungle to Canggu\'s beaches.',
            'duration_days' => 7,
            'base_price' => 1800.00,
            'difficulty_level' => 'easy',
            'trip_style' => 'wellness',
            'is_featured' => true,
            'featured_image' => 'image5.jpg',
            'highlights' => json_encode([
                "Daily yoga and meditation sessions",
                "Traditional Balinese healing ceremonies",
                "Organic farm-to-table dining experiences",
                "Traditional water purification ritual",
                "Sacred site meditation",
                "Balinese massage and spa treatments"
            ]),
        ]);

        $this->createBaliWellnessTripActivities($baliWellness, $getRandomImage);
        $this->createBaliWellnessOptionalActivities($baliWellness, $getRandomImage);

        // BALI PACKAGE 3: 8 DAY BALI ADVENTURE & HIDDEN TREASURES
        $baliAdventure = TripTemplate::create([
            'destination_id' => $bali->id,
            'title' => 'BALI ADVENTURE & HIDDEN TREASURES',
            'description' => 'Go beyond the typical tourist experience and discover Bali\'s hidden gems. This adventure-packed itinerary takes you through secret waterfalls, off-the-beaten-path temples, remote villages, and adrenaline-pumping activities across the island.',
            'duration_days' => 8,
            'base_price' => 1600.00,
            'difficulty_level' => 'moderate',
            'trip_style' => 'adventure',
            'is_featured' => false,
            'featured_image' => 'image7.jpg',
            'highlights' => json_encode([
                "White water rafting on Ayung River",
                "Sunrise trek to hidden waterfalls",
                "Cliff jumping adventures",
                "Traditional village homestays",
                "Off-road ATV jungle experiences",
                "Secret beach discoveries",
                "Authentic local cuisine cooking classes"
            ]),
        ]);

        $this->createBaliAdventureTripActivities($baliAdventure, $getRandomImage);
        $this->createBaliAdventureOptionalActivities($baliAdventure, $getRandomImage);
    }
    
    private function createKenyaDay1Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Airport Pickup & Transfer to Amboseli',
            'description' => 'Arrival at Jomo Kenyatta International Airport and transfer to Amboseli National Park.',
            'location' => 'Nairobi to Amboseli National Park',
            'day_number' => 1,
            'time_of_day' => 'morning',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'cost' => 0,
            'category' => 'transfer',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Lunch at Amboseli Sopa Lodge',
            'description' => 'Enjoy a delicious lunch while taking in the views at Amboseli Sopa Lodge.',
            'location' => 'Amboseli Sopa Lodge',
            'day_number' => 1,
            'time_of_day' => 'afternoon',
            'start_time' => '13:00',
            'end_time' => '14:00',
            'cost' => 0,
            'category' => 'meal',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Evening Game Drive',
            'description' => 'Your first game drive in Amboseli National Park with a chance to see elephants with Mt. Kilimanjaro in the background.',
            'location' => 'Amboseli National Park',
            'day_number' => 1,
            'time_of_day' => 'evening',
            'start_time' => '16:00',
            'end_time' => '18:30',
            'cost' => 45,
            'category' => 'safari',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
    }
    
    private function createKenyaDay2Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Full Day Game Drive',
            'description' => 'Spend the whole day exploring Amboseli National Park, known for its large elephant herds and views of Mt. Kilimanjaro.',
            'location' => 'Amboseli National Park',
            'day_number' => 2,
            'time_of_day' => 'morning',
            'start_time' => '06:30',
            'end_time' => '18:00',
            'cost' => 65,
            'category' => 'safari',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Picnic Lunch',
            'description' => 'Enjoy a picnic lunch in the wild surrounded by the beautiful landscapes of Amboseli.',
            'location' => 'Amboseli National Park',
            'day_number' => 2,
            'time_of_day' => 'afternoon',
            'start_time' => '12:30',
            'end_time' => '13:30',
            'cost' => 0,
            'category' => 'meal',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
    }
    
    private function createKenyaDay3Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Transfer to Lake Naivasha',
            'description' => 'Scenic drive from Amboseli to Lake Naivasha.',
            'location' => 'Amboseli to Lake Naivasha',
            'day_number' => 3,
            'time_of_day' => 'morning',
            'start_time' => '07:30',
            'end_time' => '12:30',
            'cost' => 0,
            'category' => 'transfer',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Lunch at Lake Naivasha Sopa Resort',
            'description' => 'Lunch at the beautiful lakeside resort.',
            'location' => 'Lake Naivasha Sopa Resort',
            'day_number' => 3,
            'time_of_day' => 'afternoon',
            'start_time' => '13:00',
            'end_time' => '14:00',
            'cost' => 0,
            'category' => 'meal',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Boat Trip on Lake Naivasha',
            'description' => 'Enjoy a boat ride on Lake Naivasha to see hippos and numerous bird species.',
            'location' => 'Lake Naivasha',
            'day_number' => 3,
            'time_of_day' => 'afternoon',
            'start_time' => '15:00',
            'end_time' => '17:00',
            'cost' => 35,
            'category' => 'water activity',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Walking Safari',
            'description' => 'Take a guided walking safari to see wildlife up close in a safe environment.',
            'location' => 'Animal Sanctuary',
            'day_number' => 3,
            'time_of_day' => 'evening',
            'start_time' => '17:30',
            'end_time' => '18:30',
            'cost' => 25,
            'category' => 'safari',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
    }
    
    private function createKenyaDay4Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Transfer to Hell\'s Gate National Park',
            'description' => 'Short drive to Hell\'s Gate National Park, known for its scenic landscape and geothermal activity.',
            'location' => 'Lake Naivasha to Hell\'s Gate',
            'day_number' => 4,
            'time_of_day' => 'morning',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'cost' => 0,
            'category' => 'transfer',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Cycling Safari',
            'description' => 'Experience a unique safari on bicycles in Hell\'s Gate National Park.',
            'location' => 'Hell\'s Gate National Park',
            'day_number' => 4,
            'time_of_day' => 'morning',
            'start_time' => '09:30',
            'end_time' => '11:30',
            'cost' => 30,
            'category' => 'adventure',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Gorge Hiking',
            'description' => 'Hike through the impressive gorges of Hell\'s Gate.',
            'location' => 'Hell\'s Gate National Park',
            'day_number' => 4,
            'time_of_day' => 'morning',
            'start_time' => '11:45',
            'end_time' => '13:00',
            'cost' => 15,
            'category' => 'adventure',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Transfer to Lake Nakuru',
            'description' => 'Drive to Lake Nakuru National Park.',
            'location' => 'Hell\'s Gate to Lake Nakuru',
            'day_number' => 4,
            'time_of_day' => 'afternoon',
            'start_time' => '13:30',
            'end_time' => '15:00',
            'cost' => 0,
            'category' => 'transfer',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Afternoon Game Drive',
            'description' => 'Game drive in Lake Nakuru National Park, known for rhinos, lions, and sometimes flamingos.',
            'location' => 'Lake Nakuru National Park',
            'day_number' => 4,
            'time_of_day' => 'afternoon',
            'start_time' => '16:00',
            'end_time' => '18:30',
            'cost' => 45,
            'category' => 'safari',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
    }
    
    private function createKenyaDay5Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Transfer to Masai Mara',
            'description' => 'Scenic drive to the world-famous Masai Mara National Reserve.',
            'location' => 'Lake Nakuru to Masai Mara',
            'day_number' => 5,
            'time_of_day' => 'morning',
            'start_time' => '07:30',
            'end_time' => '12:30',
            'cost' => 0,
            'category' => 'transfer',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Lunch at Masai Mara Sopa Lodge',
            'description' => 'Enjoy lunch at the lodge with panoramic views of the savannah.',
            'location' => 'Masai Mara Sopa Lodge',
            'day_number' => 5,
            'time_of_day' => 'afternoon',
            'start_time' => '13:00',
            'end_time' => '14:00',
            'cost' => 0,
            'category' => 'meal',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Maasai Village Visit',
            'description' => 'Visit a traditional Maasai village to learn about their culture and way of life.',
            'location' => 'Maasai Village',
            'day_number' => 5,
            'time_of_day' => 'afternoon',
            'start_time' => '15:00',
            'end_time' => '16:30',
            'cost' => 25,
            'category' => 'cultural',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Evening Game Drive',
            'description' => 'Your first game drive in Masai Mara, home to the "Big Five" and many other wildlife species.',
            'location' => 'Masai Mara National Reserve',
            'day_number' => 5,
            'time_of_day' => 'evening',
            'start_time' => '17:00',
            'end_time' => '19:00',
            'cost' => 45,
            'category' => 'safari',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
    }
    
    private function createKenyaDay6Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Full Day Game Drive',
            'description' => 'Full day exploring the vast plains of Masai Mara with a chance to see lions, elephants, giraffes, and more.',
            'location' => 'Masai Mara National Reserve',
            'day_number' => 6,
            'time_of_day' => 'morning',
            'start_time' => '06:30',
            'end_time' => '18:00',
            'cost' => 85,
            'category' => 'safari',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => true,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Picnic Lunch',
            'description' => 'Enjoy a picnic lunch in the midst of the African wilderness.',
            'location' => 'Masai Mara National Reserve',
            'day_number' => 6,
            'time_of_day' => 'afternoon',
            'start_time' => '12:30',
            'end_time' => '13:30',
            'cost' => 0,
            'category' => 'meal',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
    }
    
    private function createKenyaDay7Activities($template, $getRandomImage)
    {
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Breakfast at Masai Mara Sopa Lodge',
            'description' => 'Enjoy your last morning in the Masai Mara with a delicious breakfast.',
            'location' => 'Masai Mara Sopa Lodge',
            'day_number' => 7,
            'time_of_day' => 'morning',
            'start_time' => '07:00',
            'end_time' => '08:00',
            'cost' => 0,
            'category' => 'meal',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Transfer to Nairobi',
            'description' => 'Return journey to Nairobi with a stop at a viewpoint over the Great Rift Valley.',
            'location' => 'Masai Mara to Nairobi',
            'day_number' => 7,
            'time_of_day' => 'morning',
            'start_time' => '08:30',
            'end_time' => '13:30',
            'cost' => 0,
            'category' => 'transfer',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Nairobi City Tour',
            'description' => 'Brief tour of Nairobi before heading to the airport.',
            'location' => 'Nairobi',
            'day_number' => 7,
            'time_of_day' => 'afternoon',
            'start_time' => '14:00',
            'end_time' => '16:00',
            'cost' => 20,
            'category' => 'sightseeing',
            'image_url' => $getRandomImage(),
            'is_optional' => false,
            'is_highlight' => false,
        ]);
        
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Airport Transfer',
           'description' => 'Transfer to Jomo Kenyatta International Airport for your departure.',
           'location' => 'Nairobi',
           'day_number' => 7,
           'time_of_day' => 'afternoon',
           'start_time' => '16:30',
           'end_time' => '17:30',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createKenyaOptionalActivities($template, $getRandomImage)
   {
       // Optional Activities for Kenya Safari package
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Hot Air Balloon Safari',
           'description' => 'Experience the breathtaking Masai Mara from above in a hot air balloon followed by a champagne breakfast in the bush.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 6,
           'time_of_day' => 'morning',
           'start_time' => '05:00',
           'end_time' => '09:00',
           'cost' => 500,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Professional Wildlife Photography Tour',
           'description' => 'Join a professional wildlife photographer for specialized instruction and guidance to capture incredible safari moments.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 5,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '19:00',
           'cost' => 200,
           'category' => 'special interest',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Amboseli Elephant Research Center Visit',
           'description' => 'Visit the renowned elephant research center and learn about conservation efforts from scientists studying these magnificent creatures.',
           'location' => 'Amboseli National Park',
           'day_number' => 2,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '16:00',
           'cost' => 75,
           'category' => 'educational',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Private Sunset Sundowner',
           'description' => 'Exclusive sundowner experience on a hilltop overlooking the savannah with premium drinks and appetizers.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 5,
           'time_of_day' => 'evening',
           'start_time' => '18:00',
           'end_time' => '19:30',
           'cost' => 120,
           'category' => 'luxury',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
   
   private function createKenyaBudgetTripActivities($template, $getRandomImage)
   {
       // Day 1
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Nairobi Pickup & Transfer to Lake Nakuru',
           'description' => 'Meet your guide at Jomo Kenyatta International Airport or your Nairobi hotel and depart for Lake Nakuru National Park.',
           'location' => 'Nairobi to Lake Nakuru',
           'day_number' => 1,
           'time_of_day' => 'morning',
           'start_time' => '07:30',
           'end_time' => '12:00',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Great Rift Valley Viewpoint',
           'description' => 'Stop at a scenic viewpoint overlooking the dramatic Great Rift Valley.',
           'location' => 'Great Rift Valley',
           'day_number' => 1,
           'time_of_day' => 'morning',
           'start_time' => '09:30',
           'end_time' => '10:00',
           'cost' => 0,
           'category' => 'sightseeing',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Lake Nakuru Game Drive',
           'description' => 'Afternoon game drive to see flamingos, rhinos, lions and more in this compact but wildlife-rich park.',
           'location' => 'Lake Nakuru National Park',
           'day_number' => 1,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '18:00',
           'cost' => 45,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 2
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Morning Game Drive & Rhino Sanctuary Visit',
           'description' => 'Early morning game drive focusing on the protected rhino sanctuary within Lake Nakuru National Park.',
           'location' => 'Lake Nakuru National Park',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '06:30',
           'end_time' => '09:30',
           'cost' => 35,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Transfer to Masai Mara',
           'description' => 'Journey to the world-famous Masai Mara Game Reserve through the scenic countryside.',
           'location' => 'Lake Nakuru to Masai Mara',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '10:00',
           'end_time' => '16:00',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Evening Game Drive',
           'description' => 'First game drive in the Masai Mara, timed for optimal wildlife viewing at dusk.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 2,
           'time_of_day' => 'evening',
           'start_time' => '16:30',
           'end_time' => '18:30',
           'cost' => 40,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 3
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Full Day Masai Mara Safari',
           'description' => 'Full day exploring the expansive Masai Mara in search of the Big Five and other wildlife.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '07:00',
           'end_time' => '17:00',
           'cost' => 70,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Bush Picnic Lunch',
           'description' => 'Enjoy lunch in the wilderness surrounded by the magnificent landscapes of the Mara.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 3,
           'time_of_day' => 'afternoon',
           'start_time' => '12:30',
           'end_time' => '13:30',
           'cost' => 0,
           'category' => 'meal',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       // Day 4
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Sunrise Game Drive',
           'description' => 'Early morning game drive to catch predators on their morning hunts.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 4,
           'time_of_day' => 'morning',
           'start_time' => '06:00',
           'end_time' => '09:00',
           'cost' => 45,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Maasai Village Cultural Visit',
           'description' => 'Visit a local Maasai village to learn about their traditions, daily activities, and way of life.',
           'location' => 'Maasai Village near Masai Mara',
           'day_number' => 4,
           'time_of_day' => 'afternoon',
           'start_time' => '11:00',
           'end_time' => '13:00',
           'cost' => 25,
           'category' => 'cultural',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Afternoon Wildlife Safari',
           'description' => 'Afternoon game drive focusing on finding wildlife you may have missed during earlier drives.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 4,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '18:00',
           'cost' => 45,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       // Day 5
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Morning Game Drive',
           'description' => 'Final morning game drive in the Masai Mara.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 5,
           'time_of_day' => 'morning',
           'start_time' => '06:30',
           'end_time' => '09:00',
           'cost' => 40,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Return Transfer to Nairobi',
           'description' => 'Journey back to Nairobi with comfort stops along the way.',
           'location' => 'Masai Mara to Nairobi',
           'day_number' => 5,
           'time_of_day' => 'morning',
           'start_time' => '09:30',
           'end_time' => '15:30',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Farewell Dinner',
           'description' => 'Enjoy a farewell dinner at a local restaurant in Nairobi.',
           'location' => 'Nairobi',
           'day_number' => 5,
           'time_of_day' => 'evening',
           'start_time' => '19:00',
           'end_time' => '21:00',
           'cost' => 30,
           'category' => 'meal',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createKenyaBudgetOptionalActivities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Hot Air Balloon Safari',
           'description' => 'Sunrise hot air balloon safari over the Masai Mara with champagne breakfast.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 4,
           'time_of_day' => 'morning',
           'start_time' => '05:00',
           'end_time' => '09:00',
           'cost' => 450,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Bush Breakfast',
           'description' => 'Exclusive breakfast set up in the wild after a morning game drive.',
           'location' => 'Masai Mara National Reserve',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '08:30',
           'end_time' => '10:00',
           'cost' => 65,
           'category' => 'meal',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Nairobi National Park Visit',
           'description' => 'Visit the unique wildlife park located just outside Nairobi city on your last day.',
           'location' => 'Nairobi',
           'day_number' => 5,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '18:00',
           'cost' => 60,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
   
   private function createKenyaPremiumTripActivities($template, $getRandomImage)
   {
       // Adding just a sample of activities for the 10-day premium trip
       // Day 1
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'VIP Airport Welcome',
           'description' => 'Exclusive VIP welcome at Jomo Kenyatta International Airport with fast-track immigration.',
           'location' => 'Nairobi',
           'day_number' => 1,
           'time_of_day' => 'morning',
           'start_time' => '08:00',
           'end_time' => '09:00',
           'cost' => 75,
           'category' => 'luxury',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Giraffe Center Visit',
           'description' => 'Encounter and feed endangered Rothschild giraffes at this conservation center.',
           'location' => 'Nairobi',
           'day_number' => 1,
           'time_of_day' => 'morning',
           'start_time' => '10:00',
           'end_time' => '11:30',
           'cost' => 25,
           'category' => 'wildlife',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Helicopter Flight to Amboseli',
           'description' => 'Scenic helicopter transfer from Nairobi to Amboseli National Park with aerial views of Mt. Kilimanjaro.',
           'location' => 'Nairobi to Amboseli',
           'day_number' => 1,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '15:30',
           'cost' => 650,
           'category' => 'luxury transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Additional days would be added with createKenyaPremiumDayXActivities methods
   }
   
   private function createKenyaPremiumOptionalActivities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Private Dinner with Maasai Elders',
           'description' => 'Exclusive cultural experience with traditional storytelling and knowledge sharing with Maasai tribal elders.',
           'location' => 'Masai Mara',
           'day_number' => 6,
           'time_of_day' => 'evening',
           'start_time' => '19:00',
           'end_time' => '21:30',
           'cost' => 250,
           'category' => 'cultural',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Private Bush Dinner Under the Stars',
           'description' => 'Luxury dinner experience set up in the wilderness with personal chef and waitstaff, lanterns, and champagne.',
           'location' => 'Masai Mara',
           'day_number' => 7,
           'time_of_day' => 'evening',
           'start_time' => '19:00',
           'end_time' => '22:00',
           'cost' => 300,
           'category' => 'luxury',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Private Helicopter Wildlife Tracking',
           'description' => 'Track wildlife from the air with a specialist guide to find elusive species like black rhino or leopard.',
           'location' => 'Various Parks',
           'day_number' => 5,
           'time_of_day' => 'morning',
           'start_time' => '06:30',
           'end_time' => '10:30',
           'cost' => 1200,
           'category' => 'luxury safari',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
   
   private function createKenyaCoastTripActivities($template, $getRandomImage)
   {
       // Day 1
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Nairobi to Tsavo East Transfer',
           'description' => 'Morning departure from Nairobi to Tsavo East National Park.',
           'location' => 'Nairobi to Tsavo East',
           'day_number' => 1,
           'time_of_day' => 'morning',
           'start_time' => '07:30',
           'end_time' => '12:30',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Afternoon Game Drive',
           'description' => 'First safari in Tsavo East, searching for the park\'s famous red elephants.',
           'location' => 'Tsavo East National Park',
           'day_number' => 1,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '18:00',
           'cost' => 50,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 2
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Full Day Tsavo Safari',
           'description' => 'Comprehensive exploration of Tsavo East National Park with picnic lunch.',
           'location' => 'Tsavo East National Park',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '06:30',
           'end_time' => '17:00',
           'cost' => 80,
           'category' => 'safari',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 3
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Transfer to Diani Beach',
           'description' => 'Scenic drive from Tsavo to the pristine beaches of Diani on the Kenyan coast.',
           'location' => 'Tsavo to Diani Beach',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '08:00',
           'end_time' => '13:00',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Beach Relaxation',
           'description' => 'Free time to enjoy the white sands and turquoise waters of Diani Beach.',
           'location' => 'Diani Beach',
           'day_number' => 3,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '18:00',
           'cost' => 0,
           'category' => 'beach',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 4
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Snorkeling Excursion',
           'description' => 'Half-day snorkeling trip to a marine reserve to see colorful coral and tropical fish.',
           'location' => 'Kisite-Mpunguti Marine Park',
           'day_number' => 4,
           'time_of_day' => 'morning',
           'start_time' => '08:30',
           'end_time' => '13:00',
           'cost' => 65,
           'category' => 'water activity',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Beachfront Massage',
           'description' => 'Relaxing massage in a beach cabana with ocean sounds.',
           'location' => 'Diani Beach Resort',
           'day_number' => 4,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '16:00',
           'cost' => 60,
           'category' => 'wellness',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       // Day 5
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Traditional Dhow Sailing Trip',
           'description' => 'Sail on a traditional wooden dhow boat along the coast with local seafood lunch.',
           'location' => 'Diani Coastline',
           'day_number' => 5,
           'time_of_day' => 'morning',
           'start_time' => '09:00',
           'end_time' => '14:00',
           'cost' => 70,
           'category' => 'cultural',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 6
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Final Beach Day & Transfer',
           'description' => 'Morning relaxation at the beach before transfer to Mombasa airport for departure.',
           'location' => 'Diani Beach to Mombasa',
           'day_number' => 6,
           'time_of_day' => 'morning',
           'start_time' => '09:00',
           'end_time' => '15:00',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createKenyaCoastOptionalActivities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Deep Sea Fishing',
           'description' => 'Half-day fishing expedition for marlin, sailfish, and tuna in the Indian Ocean.',
           'location' => 'Diani Waters',
           'day_number' => 5,
           'time_of_day' => 'morning',
           'start_time' => '06:00',
           'end_time' => '12:00',
           'cost' => 220,
           'category' => 'water activity',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Scuba Diving Experience',
           'description' => 'Guided scuba diving to explore vibrant coral reefs and marine life.',
           'location' => 'Diani Waters',
           'day_number' => 4,
           'time_of_day' => 'morning',
           'start_time' => '08:00',
           'end_time' => '12:00',
           'cost' => 150,
           'category' => 'water activity',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Skydiving Adventure',
           'description' => 'Tandem skydiving experience with breathtaking views of the coastline.',
           'location' => 'Diani Beach',
           'day_number' => 5,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '16:00',
           'cost' => 350,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliDay1Activities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Airport Pickup & Beach Relaxation',
           'description' => 'Arrival at Ngurah Rai International Airport and transfer to your beachfront hotel in Kuta.',
           'location' => 'Kuta Beach, Bali',
           'day_number' => 1,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '18:00',
           'cost' => 0,
           'category' => 'relaxation',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);

       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Welcome Dinner',
           'description' => 'Enjoy a traditional Balinese dinner with cultural performances.',
           'location' => 'Jimbaran Bay, Bali',
           'day_number' => 1,
           'time_of_day' => 'evening',
           'start_time' => '19:00',
           'end_time' => '21:00',
           'cost' => 45,
           'category' => 'food',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
   }
   
   private function createBaliDay2Activities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Ubud Cultural Tour',
           'description' => 'Visit the cultural heart of Bali, including the Sacred Monkey Forest and local craft villages.',
           'location' => 'Ubud, Bali',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '09:00',
           'end_time' => '13:00',
           'cost' => 65,
           'category' => 'cultural',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Rice Terrace Experience',
           'description' => 'Explore the beautiful Tegallalang Rice Terraces with a local guide.',
           'location' => 'Tegallalang, Bali',
           'day_number' => 2,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '17:00',
           'cost' => 30,
           'category' => 'nature',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliDay3Activities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Sunrise Mount Batur Hike',
           'description' => 'Early morning hike to witness the breathtaking sunrise from Mount Batur volcano.',
           'location' => 'Mount Batur, Bali',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '04:00',
           'end_time' => '10:00',
           'cost' => 85,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Hot Springs & Spa',
           'description' => 'Relax in natural hot springs and enjoy a traditional Balinese massage.',
           'location' => 'Kintamani, Bali',
           'day_number' => 3,
           'time_of_day' => 'afternoon',
           'start_time' => '12:00',
           'end_time' => '16:00',
           'cost' => 55,
           'category' => 'relaxation',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliDay4Activities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Uluwatu Temple & Kecak Dance',
           'description' => 'Visit the clifftop Uluwatu Temple and watch the mesmerizing Kecak Fire Dance at sunset.',
           'location' => 'Uluwatu, Bali',
           'day_number' => 4,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '19:00',
           'cost' => 40,
           'category' => 'cultural',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Seafood Dinner on the Beach',
           'description' => 'Enjoy fresh seafood at a romantic beachside dinner.',
           'location' => 'Jimbaran Beach, Bali',
           'day_number' => 4,
           'time_of_day' => 'evening',
           'start_time' => '19:30',
           'end_time' => '21:30',
           'cost' => 60,
           'category' => 'food',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliDay5Activities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Water Sports Adventure',
           'description' => 'Experience thrilling water activities including jet skiing, parasailing and banana boat rides.',
           'location' => 'Nusa Dua, Bali',
           'day_number' => 5,
           'time_of_day' => 'morning',
           'start_time' => '09:00',
           'end_time' => '13:00',
           'cost' => 120,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Farewell Dinner & Shopping',
           'description' => 'Enjoy a farewell dinner and shop for souvenirs at local markets.',
           'location' => 'Seminyak, Bali',
           'day_number' => 5,
           'time_of_day' => 'evening',
           'start_time' => '18:00',
           'end_time' => '21:00',
           'cost' => 50,
           'category' => 'food',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Airport Transfer',
           'description' => 'Transfer to Ngurah Rai International Airport for your departure.',
           'location' => 'Denpasar, Bali',
           'day_number' => 5,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '15:00',
           'cost' => 0,
           'category' => 'transfer',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliIslandOptionalActivities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Private Sunset Photography Tour',
           'description' => 'Professional photography tour to capture Bali\'s magnificent sunsets at the best locations.',
           'location' => 'Various Locations, Bali',
           'day_number' => 4,
           'time_of_day' => 'afternoon',
           'start_time' => '15:30',
           'end_time' => '19:30',
           'cost' => 95,
           'category' => 'special interest',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Nusa Penida Island Day Trip',
           'description' => 'Full-day excursion to the spectacular Nusa Penida island with visits to Kelingking Beach and Angel\'s Billabong.',
           'location' => 'Nusa Penida, Bali',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '07:00',
           'end_time' => '19:00',
           'cost' => 130,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Traditional Cooking Class',
           'description' => 'Learn to prepare authentic Balinese dishes with a local chef, including market visit and ingredients selection.',
           'location' => 'Ubud, Bali',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '08:00',
           'end_time' => '13:00',
           'cost' => 65,
           'category' => 'cultural',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliWellnessTripActivities($template, $getRandomImage)
   {
       // Day 1
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Welcome & Wellness Assessment',
           'description' => 'Arrival at Ngurah Rai Airport, transfer to Ubud, and personalized wellness consultation.',
           'location' => 'Ubud, Bali',
           'day_number' => 1,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '16:00',
           'cost' => 75,
           'category' => 'wellness',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Opening Ceremony & Meditation',
           'description' => 'Traditional Balinese blessing ceremony followed by guided sunset meditation.',
           'location' => 'Ubud, Bali',
           'day_number' => 1,
           'time_of_day' => 'evening',
           'start_time' => '17:30',
           'end_time' => '19:00',
           'cost' => 45,
           'category' => 'spiritual',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Day 2
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Morning Yoga Session',
           'description' => 'Energizing morning yoga session focusing on alignment and breath work.',
           'location' => 'Ubud, Bali',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '07:00',
           'end_time' => '08:30',
           'cost' => 0,
           'category' => 'yoga',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Holistic Healing Spa Treatment',
           'description' => 'Traditional Balinese healing treatment combining massage, aromatherapy, and energy work.',
           'location' => 'Ubud, Bali',
           'day_number' => 2,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '16:00',
           'cost' => 120,
           'category' => 'wellness',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Additional days would be filled out similarly
   }
   
   private function createBaliWellnessOptionalActivities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Private Yoga Instruction',
           'description' => 'One-on-one yoga session with master instructor to deepen your practice.',
           'location' => 'Ubud, Bali',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '09:00',
           'end_time' => '10:30',
           'cost' => 85,
           'category' => 'yoga',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Ayurvedic Consultation',
           'description' => 'Personal consultation with an Ayurvedic doctor for health assessment and recommendations.',
           'location' => 'Ubud, Bali',
           'day_number' => 2,
           'time_of_day' => 'afternoon',
           'start_time' => '14:00',
           'end_time' => '15:30',
           'cost' => 100,
           'category' => 'wellness',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Sound Healing Journey',
           'description' => 'Immersive sound therapy session using traditional instruments for deep relaxation and healing.',
           'location' => 'Ubud, Bali',
           'day_number' => 5,
           'time_of_day' => 'evening',
           'start_time' => '19:00',
           'end_time' => '20:30',
           'cost' => 75,
           'category' => 'spiritual',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
   
   private function createBaliAdventureTripActivities($template, $getRandomImage)
   {
       // Day 1
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Arrival & Adventure Briefing',
           'description' => 'Airport pickup, transfer to Ubud, and adventure trip orientation with experienced guides.',
           'location' => 'Ubud, Bali',
           'day_number' => 1,
           'time_of_day' => 'afternoon',
           'start_time' => '15:00',
           'end_time' => '17:00',
           'cost' => 0,
           'category' => 'orientation',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => false,
       ]);
       
       // Day 2
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Ayung River White Water Rafting',
           'description' => 'Thrilling class III rapids expedition down Bali\'s longest river through stunning rainforest gorges.',
           'location' => 'Ayung River, Bali',
           'day_number' => 2,
           'time_of_day' => 'morning',
           'start_time' => '08:00',
           'end_time' => '13:00',
           'cost' => 85,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => false,
           'is_highlight' => true,
       ]);
       
       // Additional adventure activities would be added
   }
   
   private function createBaliAdventureOptionalActivities($template, $getRandomImage)
   {
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Bali Treetop Adventure Park',
           'description' => 'High ropes course experience with zip lines, Tarzan swings, and aerial bridges in the jungle canopy.',
           'location' => 'Bedugul, Bali',
           'day_number' => 3,
           'time_of_day' => 'morning',
           'start_time' => '09:00',
           'end_time' => '12:00',
           'cost' => 65,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Bali Swing Experience',
           'description' => 'Swing over jungle ravines on giant swings with breathtaking views of the rainforest.',
           'location' => 'Ubud, Bali',
           'day_number' => 6,
           'time_of_day' => 'morning',
           'start_time' => '10:00',
           'end_time' => '12:00',
           'cost' => 45,
           'category' => 'adventure',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
       
       TemplateActivity::create([
           'trip_template_id' => $template->id,
           'title' => 'Underwater Scooter Experience',
           'description' => 'Explore underwater marine life on a motorized sea scooter without diving certification.',
           'location' => 'Sanur, Bali',
           'day_number' => 4,
           'time_of_day' => 'afternoon',
           'start_time' => '13:00',
           'end_time' => '16:00',
           'cost' => 95,
           'category' => 'water activity',
           'image_url' => $getRandomImage(),
           'is_optional' => true,
           'is_highlight' => false,
       ]);
   }
}