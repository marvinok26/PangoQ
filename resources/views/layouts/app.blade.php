{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gray-100 py-8">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                <div class="md:w-1/3 mb-8 md:mb-0 pr-4">
                    <h1 class="text-4xl md:text-3xl " style="font-stretch: condensed;">Following the Adventure Trail Around the World</h1>
                </div>
                <div class="md:w-1/3 text-right">
                    <p class="text-gray-600 mb-6">Every journey is a new story waiting to be written. Start your story with us, and let us take you to amazing destinations and events around the world.</p>
                    <a href="#" class="bg-yellow-500 text-white px-6 py-2 rounded-full font-medium inline-block">Explore</a>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-4 py-8">
                <!-- Left container - Destination search box with background -->
                <div class="md:w-2/3 mb-4 md:mb-0">
                    <div class="relative rounded-lg overflow-hidden shadow-lg min-h-96" style="background-image: url('{{ asset('images/image6.jpg') }}'); background-position: left; background-attachment: fixed;">
                        <div class="absolute inset-0"></div>
                        <div class="relative z-10 flex">
                            <!-- Background area on the left -->
                            <div class="md:w-1/2 p-8">
                                <!-- This space shows the background image -->
                            </div>
                            
                            <!-- Form container on the right -->
                            <div class="md:w-1/2 p-6 relative">
                                <div class="bg-white rounded-lg shadow-lg p-5 mx-4 relative">
                                    <form action="#" method="GET">
                                        <div class="mb-4">
                                            <h3 class="text-sm text-gray-400 mb-2">DESTINATION</h3>
                                            <div class="border border-gray-200 rounded p-2 mb-4">
                                                <input type="text" name="destination" placeholder="Bur Dubai" class="w-full outline-none">
                                            </div>
                                            
                                            <h3 class="text-sm text-gray-400 mb-2">LOCATION</h3>
                                            <div class="border border-gray-200 rounded p-2 mb-4">
                                                <input type="text" name="location" placeholder="Dubai" class="w-full outline-none">
                                            </div>
                                            
                                            <div class="flex mb-4">
                                                <button type="button" class="bg-teal-600 text-white text-xs px-2 py-1 rounded mr-2">5 Star Hotel</button>
                                                <button type="button" class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded mr-2">4 Star Hotel</button>
                                                <button type="button" class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">3 Star Hotel</button>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="bg-yellow-500 text-white w-full py-2 rounded font-medium mb-4">Search</button>
                                    </form>
                                    
                                    <!-- Transparent window that shows the main container background -->
                                    <div class="h-30 rounded-lg relative overflow-hidden" style="background-image: url('{{ asset('images/image6.jpg') }}'); background-position: left; background-attachment: fixed;">
                                        <div class="absolute inset-0 bg-opacity-20"></div>
                                        <!-- This area shows the same background as the main container -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right side containers -->
                <div class="md:w-1/3 flex flex-col gap-4">
                    <!-- Taller top container -->
                    <div class="h-90 rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('images/image18.jpg') }}" alt="Hotel interior" class="w-full h-full object-cover"
                             onerror="this.src='https://via.placeholder.com/500x200?text=Hotel+Interior';">
                    </div>
                    
                    <!-- Smaller bottom container -->
                    <div class="h-24 rounded-lg overflow-hidden shadow-lg relative">
                        <img src="{{ asset('images/image19.jpg') }}" alt="Background" class="w-full h-full object-cover"
                             onerror="this.src='https://via.placeholder.com/500x100?text=Background';">
                        <div class="absolute inset-0 flex items-center justify-center space-x-4">
                            <a href="#" class="bg-yellow-500 text-white px-4 py-2 rounded-full font-medium text-sm">Explore</a>
                            <a href="{{ route('login') }}" class="bg-teal-600 text-white px-4 py-2 rounded-full font-medium text-sm">Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Discover Our Range of Services</h2>
            <p class="text-gray-600 mb-12 max-w-3xl mx-auto">Discover a range of expert event and travel services designed to make your journey seamless and enjoyable.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-50 p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-bold mb-2">Flight Booking</h3>
                    <p class="text-gray-600 text-sm">Book flights with the best rates and flexible schedules.</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="bg-blue-50 p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold mb-2">Tour Packages</h3>
                    <p class="text-gray-600 text-sm">Customized tour and event packages for various destinations worldwide.</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="bg-blue-50 p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-bold mb-2">Events</h3>
                    <p class="text-gray-600 text-sm">Know and be part of what is happening around the world</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="bg-blue-50 p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01" />
                        </svg>
                    </div>
                    <h3 class="font-bold mb-2">Insurance</h3>
                    <p class="text-gray-600 text-sm">Protect your journey and event safety with comprehensive insurance.</p>
                </div>
            </div>
            
            <a href="#" class="mt-12 inline-block border border-gray-300 text-gray-700 px-6 py-2 rounded-full font-medium">Learn More</a>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8 text-center">
            <h2 class="text-2xl md:text-4xl font-bold mb-2">Top Events from Around the World!</h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">Experience the world's must experience events, each renowned for its beauty, culture, and unique attractions.</p>
            
            <div class="flex flex-wrap justify-center items-end gap-8 mb-8">
                <!-- Left image - slanted diagonally to the right -->
                <div class="relative w-full md:w-96 h-80 rounded-lg overflow-hidden transform -rotate-6 translate-y-4">
                    <img src="{{ asset('images/image20.jpg') }}" alt="Nairobi Fashion Week" class="w-full h-full object-cover" 
                         onerror="this.src='https://via.placeholder.com/384x320?text=Fashion+Week';">
                    <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black to-transparent w-full p-4">
                        <span class="bg-pink-500 text-white text-xs px-2 py-1 rounded">Fashion Event</span>
                        <h3 class="text-white font-bold mt-2">Nairobi Fashion Week</h3>
                    </div>
                </div>
                
                <!-- Center image - displayed above and larger -->
                <div class="relative w-full md:w-112 h-96 rounded-lg overflow-hidden z-10 -translate-y-8">
                    <img src="{{ asset('images/image21.jpg') }}" alt="Kigali Cultural Fest" class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/448x384?text=Cultural+Fest';">
                    <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black to-transparent w-full p-4">
                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded">Cultural Event</span>
                        <h3 class="text-white font-bold mt-2">Kigali Cultural Fest</h3>
                    </div>
                </div>
                
                <!-- Right image - slanted diagonally to the left -->
                <div class="relative w-full md:w-96 h-80 rounded-lg overflow-hidden transform rotate-6 translate-y-4">
                    <img src="{{ asset('images/image22.jpg') }}" alt="Boston Jazz Fest" class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/384x320?text=Jazz+Fest';">
                    <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black to-transparent w-full p-4">
                        <span class="bg-purple-500 text-white text-xs px-2 py-1 rounded">Music Event</span>
                        <h3 class="text-white font-bold mt-2">Boston Jazz Fest</h3>
                    </div>
                </div>
            </div>
            
            <a href="#" class="bg-yellow-500 text-white px-6 py-2 rounded-full font-medium inline-block">View all Events</a>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 lg:px-8 flex flex-col md:flex-row items-center gap-12">
            <div class="md:w-2/5">
                <img src="{{ asset('images/image23.jpg') }}" alt="Experience moments" class="rounded-lg shadow-lg w-300"
                     onerror="this.src='https://via.placeholder.com/600x400?text=Experience';">
            </div>
            <div class="md:w-2/5">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Experience Unforgettable Moments</h2>
                <p class="text-gray-600 mb-6">Start your adventure with us and explore the world's most exciting moments. Whether it's a relaxing getaway or an action-packed event, we have everything you need to make it unforgettable.</p>
                <a href="#" class="bg-yellow-500 text-white px-6 py-2 rounded-full font-medium inline-block">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Destinations Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Exotic Destinations Await You</h2>
            <p class="text-gray-600 mb-12 max-w-3xl mx-auto">Get ready to explore exotic destinations and discover wonders in every corner of the world.</p>
            
            <div class="flex justify-center gap-4 mb-8">
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-full text-sm">Madagascar</button>
                <button class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm flex items-center">
                    <span class="mr-2">Madagascar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-16">
                @php
                $destinations = [
                    ['name' => 'Madagascar', 'desc' => 'Discover unique wildlife and landscapes', 'image' => 'image1.jpg'],
                    ['name' => 'Bali, Indonesia', 'desc' => 'Tropical paradise with rich culture', 'image' => 'image2.jpg'],
                    ['name' => 'Maldives', 'desc' => 'Crystal clear waters and luxury resorts', 'image' => 'image3.jpg'],
                    ['name' => 'Santorini, Greece', 'desc' => 'Beautiful sunsets and white architecture', 'image' => 'image5.jpg'],
                    ['name' => 'Cairo, Egypt', 'desc' => 'Ancient pyramids and rich history', 'image' => 'image6.jpg'],
                    ['name' => 'Kyoto, Japan', 'desc' => 'Traditional temples and gardens', 'image' => 'image7.jpg'],
                    ['name' => 'New Zealand', 'desc' => 'Adventure sports and stunning nature', 'image' => 'image9.jpg'],
                    ['name' => 'Iceland', 'desc' => 'Northern lights and geothermal wonders', 'image' => 'image10.jpg'],
                ];
                @endphp

                @foreach($destinations as $index => $destination)
                <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="{{ asset('images/' . $destination['image']) }}" 
                             alt="{{ $destination['name'] }}" class="w-full h-48 object-cover"
                             onerror="this.src='https://via.placeholder.com/300x200?text={{ urlencode($destination['name']) }}';">
                        <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded text-sm font-bold text-yellow-500 flex items-center">
                            4.5
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="p-4 flex justify-between items-center">
                        <div class="text-left">
                            <h3 class="font-bold mb-1">{{ $destination['name'] }}</h3>
                            <p class="text-gray-600 text-sm">{{ $destination['desc'] }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 ml-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg overflow-hidden shadow relative h-64" style="background-image: url('{{ asset('images/image11.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                    <div class="relative z-10 p-4 text-center h-full flex flex-col justify-end">
                        <h3 class="font-bold mb-2 text-white">Travel Insurance</h3>
                        <p class="text-white text-sm mb-4">Protect your journey with comprehensive travel insurance.</p>
                        <a href="#" class="bg-pink-600 text-white px-4 py-1 rounded-md text-sm font-medium flex items-center justify-center w-fit mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            More Destinations
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow relative h-64" style="background-image: url('{{ asset('images/image12.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                    <div class="relative z-10 p-4 text-center h-full flex flex-col justify-end">
                        <h3 class="font-bold mb-2 text-white">Event Insurance</h3>
                        <p class="text-white text-sm mb-4">Protect your belongings with comprehensive event insurance. Enjoy our events with ease</p>
                        <a href="#" class="bg-blue-600 text-white px-4 py-1 rounded-md text-sm font-medium flex items-center justify-center w-fit mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            More Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8">
            <h2 class="text-2xl md:text-3xl font-bold mb-2 text-left">Trusted Worldwide</h2>
            <p class="text-gray-600 mb-12 max-w-3xl text-left md:w-2/5">Their stories and feedback reflect our commitment to providing exceptional service and unforgettable moments.</p>
            
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="md:w-3/5">
                    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                        <div class="flex items-center mb-4">
                            <div class="w-3 h-3 rounded-full bg-yellow-500 mr-1"></div>
                        </div>
                        <p class="text-gray-700 italic mb-4">"The team made planning my vacation a breeze. From booking flights to arranging accommodations, and tailoring events to my needs. Everything was seamless and well organized. I couldn't be happier!"</p>
                        <div class="flex items-center">
                            <h4 class="font-bold">David Mwea</h4>
                            <span class="mx-2 text-gray-400">•</span>
                            <span class="text-gray-600">Software Engineer</span>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/4">
                    <img src="{{ asset('images/image24.jpg') }}" alt="Testimonial" class="rounded-lg shadow-lg w-full h-48 object-cover"
                         onerror="this.src='https://via.placeholder.com/280x192?text=Testimonial';">
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 lg:px-8">
            <h2 class="text-2xl md:text-3xl font-bold mb-2 text-center">Latest Travel Articles</h2>
            <p class="text-gray-600 mb-12 max-w-3xl mx-auto text-center">Discover a wealth of travel articles that offer inspiration, tips, and detailed guides.</p>
            
            <!-- Top 2 articles with images -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow flex">
                    <img src="{{ asset('images/image13.jpg') }}" alt="Tech Expo" class="w-1/3 h-32 object-cover"
                         onerror="this.src='https://via.placeholder.com/150x128?text=Tech+Expo';">
                    <div class="p-4 w-2/3">
                        <span class="text-gray-500 text-sm">August 5, 2025</span>
                        <h3 class="font-bold mb-2 mt-1">Tech Innovation Takes Center Stage at Nairobi Tech Expo</h3>
                        <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow flex">
                    <img src="{{ asset('images/image15.jpg') }}" alt="Beach Vacation" class="w-1/3 h-32 object-cover"
                         onerror="this.src='https://via.placeholder.com/150x128?text=Beach+Vacation';">
                    <div class="p-4 w-2/3">
                        <span class="text-gray-500 text-sm">August 5, 2024</span>
                        <h3 class="font-bold mb-2 mt-1">The Ultimate Guide to Beach Vacations</h3>
                        <p class="text-gray-600 text-sm">Find out where to go for the best beach vacations and how to make the most of your sunny getaway.</p>
                    </div>
                </div>
            </div>
            
           <!-- Bottom 3 articles without images -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                    <span class="text-gray-500 text-sm">August 5, 2025</span>
                    <h3 class="font-bold mb-2 mt-1">Exploring Culinary Delights at the Hilton Culinary Showcase</h3>
                    <p class="text-gray-600 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                    <span class="text-gray-500 text-sm">July 28, 2025</span>
                    <h3 class="font-bold mb-2 mt-1">Hidden Gems: Discovering Off-the-Beaten-Path Destinations</h3>
                    <p class="text-gray-600 text-sm">Uncover secret destinations that offer authentic experiences away from tourist crowds and create unforgettable memories.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                    <span class="text-gray-500 text-sm">July 20, 2025</span>
                    <h3 class="font-bold mb-2 mt-1">Budget Travel Tips: How to See the World Without Breaking the Bank</h3>
                    <p class="text-gray-600 text-sm">Smart strategies and insider tips to help you travel more while spending less on accommodation, food, and activities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-900 bg-opacity-80 relative">
        <div class="absolute inset-0">
            <img src="{{ asset('images/image25.jpg') }}" alt="CTA Background" class="w-full h-full object-cover opacity-20"
                 onerror="this.src='https://via.placeholder.com/1200x400?text=CTA+Background';">
        </div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Book with Pango Quests Today</h2>
            <p class="mb-8 max-w-2xl mx-auto">Take the first step toward your next adventure. Contact us to explore exciting events and destinations. Create memories that will last a lifetime.</p>
            <a href="{{ route('register') }}" class="bg-yellow-500 text-white px-6 py-3 rounded-full font-medium inline-block">Learn More</a>
        </div>
    </section>
@endsection