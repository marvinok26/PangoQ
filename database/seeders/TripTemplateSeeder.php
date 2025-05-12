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
            'image17.jpg', 'image18.jpg', 'image19.jpg'
        ];
        
        // Helper function to get a random image from our pool
        $getRandomImage = function() use ($activityImages) {
            return $activityImages[array_rand($activityImages)];
        };

        // Kenya Safari Template - based on 7 DAY BEST OF KENYA SOPA CIRCUIT
        $kenya = Destination::firstOrCreate(
            ['name' => 'Kenya'],
            [
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'description' => 'Kenya is renowned for its classic savanna safaris, deserts, dramatic mountain ranges, cultures and beautiful beaches.',
                'image_url' => 'image13.jpg',
            ]
        );

        $kenyaSafari = TripTemplate::create([
            'destination_id' => $kenya->id,
            'title' => '7 DAY BEST OF KENYA SOPA CIRCUIT',
            'description' => 'Experience the best of Kenya with visits to Amboseli National Park, Lake Naivasha, Lake Nakuru National Park, and Masai Mara National Reserve.',
            'duration_days' => 7,
            'base_price' => 1800.00,
            'difficulty_level' => 'moderate',
            'trip_style' => 'safari',
            'is_featured' => true,
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
        
        // Create Bali template
        $this->createBaliTemplate($getRandomImage);
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
        ]);
        
        TemplateActivity::create([
            'trip_template_id' => $template->id,
            'title' => 'Optional Hot Air Balloon Safari',
            'description' => 'Experience the Masai Mara from above in a hot air balloon followed by a champagne breakfast.',
            'location' => 'Masai Mara National Reserve',
            'day_number' => 6,
            'time_of_day' => 'morning',
            'start_time' => '05:00',
            'end_time' => '09:00',
            'cost' => 500,
            'category' => 'adventure',
            'image_url' => $getRandomImage(),
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
        ]);
    }
    
    private function createBaliTemplate($getRandomImage)
    {
        $bali = Destination::firstOrCreate(
            ['name' => 'Bali'],
            [
                'country' => 'Indonesia',
                'city' => 'Denpasar',
                'description' => 'A beautiful island paradise known for its stunning beaches, vibrant culture, and picturesque landscapes.',
                'image_url' => 'image1.jpg',
            ]
        );

        $baliTrip = TripTemplate::create([
            'destination_id' => $bali->id,
            'title' => 'Bali Island Paradise Explorer',
            'description' => 'Experience the best of Bali with this carefully curated trip featuring pristine beaches, ancient temples, and cultural experiences.',
            'duration_days' => 5,
            'base_price' => 1200.00,
            'difficulty_level' => 'easy',
            'trip_style' => 'cultural',
            'is_featured' => true,
        ]);

        // Day 1 Activities
        $this->createBaliDay1Activities($baliTrip, $getRandomImage);
        
        // Day 2 Activities
        $this->createBaliDay2Activities($baliTrip, $getRandomImage);
        
        // Day 3 Activities
        $this->createBaliDay3Activities($baliTrip, $getRandomImage);
        
        // Day 4 Activities
        $this->createBaliDay4Activities($baliTrip, $getRandomImage);
        
        // Day 5 Activities
        $this->createBaliDay5Activities($baliTrip, $getRandomImage);
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
        ]);
    }
}