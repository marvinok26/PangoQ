<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PangoQ - Group Travel Planning Made Easy</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    @vite('resources/css/app.css')
    <!-- Scripts -->
    @vite('resources/js/app.js')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-gray-50 font-sans text-gray-800">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Hero Section with Fullscreen Video Background -->
    <section class="relative h-screen">
        <!-- Video Background -->
        <div class="absolute inset-0 w-full h-full bg-black">
            <video class="absolute inset-0 w-full h-full object-cover opacity-80"
                poster="{{ asset('images/image2.jpg') }}" autoplay muted loop playsinline
                onerror="this.onerror=null; this.poster='https://via.placeholder.com/1920x1080?text=PangoQ+Travel+Planning'; this.controls=false;">
                <source src="{{ asset('videos/video4.mp4') }}" type="video/mp4">
                <!-- Fallback content -->
                <img src="{{ asset('images/image2.jpg') }}" alt="Travel planning"
                    class="absolute inset-0 w-full h-full object-cover">
            </video>
        </div>

        <!-- Overlay Content -->
        <div class="relative z-10 flex flex-col items-center justify-center h-full px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 max-w-4xl">One app for all your travel planning
                needs</h1>
            <p class="text-lg md:text-xl max-w-2xl mb-10 text-gray-100">
                Plan, save, and enjoy your group trips with PangoQ's all-in-one platform. Create itineraries, manage
                savings, and make unforgettable memories together.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('trips.plan') }}" class="px-8 py-4 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 transition transform hover:scale-105">
                    Start planning
                </a>
                <a href="#features" class="px-8 py-4 bg-white text-black bg-opacity-20 backdrop-blur-sm border border-white border-opacity-40 rounded-md hover:bg-opacity-30 transition transform hover:scale-105">
                    Learn more
                </a>
            </div>
        </div>

        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
            <a href="#features" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </a>
        </div>
    </section>

    <!-- Features Overview Section -->
    <section id="features" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8">
            <h2 class="text-2xl font-bold text-center mb-12">What travelers are raving about</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Collaborative Planning</h3>
                    <p class="text-gray-600">Plan together with your travel group. Share ideas, vote on activities, and
                        build your perfect itinerary as a team.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Smart Itineraries</h3>
                    <p class="text-gray-600">Organize your days efficiently with smart scheduling that suggests the best
                        route between activities and attractions.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Group Savings</h3>
                    <p class="text-gray-600">Track your group's budget, set savings goals, and watch as everyone
                        contributes to make the trip a reality.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Interactive Maps</h3>
                    <p class="text-gray-600">Explore destinations with our detailed maps showing points of interest,
                        restaurants, and hidden gems.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Travel Guides</h3>
                    <p class="text-gray-600">Access curated travel guides for popular destinations with insider tips and
                        must-see attractions.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Travel Memories</h3>
                    <p class="text-gray-600">Keep all your travel memories in one place. Share photos, notes, and
                        highlights from your trips.</p>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('trips.plan') }}" class="inline-block px-6 py-3 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 transition">
                    Start planning your trip
                </a>
            </div>
        </div>
    </section>


    <!-- Detailed Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 lg:px-8">
            <h2 class="text-2xl font-bold text-center mb-12">Features to make all your trips easier</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="h-16 w-16 bg-secondary-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-secondary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Interactive Maps</h3>
                    <p class="text-gray-600">Explore your destinations with detailed maps showing points of interest,
                        restaurants, and attractions.</p>
                </div>
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="h-16 w-16 bg-secondary-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-secondary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Day-by-Day Planning</h3>
                    <p class="text-gray-600">Organize your activities by day with smart scheduling that maximizes your
                        time at each destination.</p>
                </div>
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <div class="h-16 w-16 bg-secondary-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-secondary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Group Collaboration</h3>
                    <p class="text-gray-600">Plan together with everyone in your group. Share ideas, vote on activities,
                        and build your itinerary as a team.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-secondary-50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="font-bold text-xl mb-4">GUIDES</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">500+</p>
                            <p class="text-gray-600">Destinations</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">10k+</p>
                            <p class="text-gray-600">Points of Interest</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">150+</p>
                            <p class="text-gray-600">Countries</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">5k+</p>
                            <p class="text-gray-600">Travel Tips</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-xl mb-4">TRIPS</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">1M+</p>
                            <p class="text-gray-600">Trips Planned</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">4.8</p>
                            <p class="text-gray-600">Average Rating</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">200k+</p>
                            <p class="text-gray-600">Active Users</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-secondary-600">25M+</p>
                            <p class="text-gray-600">Activities Added</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- App Interface Carousel Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Experience PangoQ on any device</h2>

            <div x-data="{ 
            activeSlide: 0,
            slides: [
                {
                    image: '{{ asset('images/image18.jpg') }}',
                    title: 'Plan your trips on the go',
                    description: 'Access your travel plans anytime, anywhere with our mobile app. Available for iOS and Android.',
                    alt: 'Mobile app interface'
                },
                {
                    image: '{{ asset('images/image19.jpg') }}',
                    title: 'Collaborate in real-time',
                    description: 'Invite friends and family to your trip plans. Edit itineraries together and make group decisions easily.',
                    alt: 'Collaboration features'
                },
                {
                    image: '{{ asset('images/image1.jpg') }}',
                    title: 'Track your expenses',
                    description: 'Keep all your travel expenses organized and split costs fairly among your travel group.',
                    alt: 'Expense tracking'
                },
                {
                    image: '{{ asset('images/image2.jpg') }}',
                    title: 'Discover hidden gems',
                    description: 'Get personalized recommendations for attractions, restaurants, and activities based on your interests.',
                    alt: 'Recommendations feature'
                },
                {
                    image: '{{ asset('images/image3.jpg') }}',
                    title: 'Save your memories',
                    description: 'Upload photos and notes for each trip to create beautiful travel memories you can revisit anytime.',
                    alt: 'Travel memories feature'
                }
            ],
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            goToSlide(index) {
                this.activeSlide = index;
            }
        }" class="relative mx-auto max-w-7xl overflow-hidden bg-gradient-to-r from-secondary-50 to-white rounded-2xl shadow-xl"
                @keydown.arrow-left.window="prev()" @keydown.arrow-right.window="next()">

                <!-- Main Carousel -->
                <div class="flex flex-col md:flex-row items-center p-4 md:p-10 md:gap-12">
                    <!-- Phone mockup with carousel image -->
                    <div class="md:w-1/2 relative mb-10 md:mb-0">
                        <div class="relative mx-auto w-full max-w-sm">
                            <!-- Phone frame -->
                            <div
                                class="relative mx-auto rounded-[3rem] border-[14px] border-gray-800 bg-gray-800 shadow-xl h-[600px] w-[300px]">
                                <!-- Notch -->
                                <div
                                    class="absolute left-1/2 top-0 h-6 w-40 -translate-x-1/2 rounded-b-3xl bg-gray-800 z-10">
                                </div>

                                <!-- Screen Content -->
                                <div class="relative h-full w-full overflow-hidden rounded-[2.3rem] bg-white">
                                    <template x-for="(slide, index) in slides" :key="index">
                                        <div x-show="activeSlide === index"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            class="absolute inset-0">
                                            <!-- Use object-cover to fill the entire phone screen -->
                                            <img :src="slide . image" :alt="slide . alt"
                                                class="absolute inset-0 h-full w-full object-cover"
                                                onerror="this.src='https://via.placeholder.com/300x600?text=App+Interface';">
                                        </div>
                                    </template>
                                </div>

                                <!-- Home button for iPhone-style -->
                                <div
                                    class="absolute bottom-1 left-1/2 h-5 w-24 -translate-x-1/2 rounded-full border-2 border-gray-600 z-10">
                                </div>
                            </div>

                            <!-- Decorative elements -->
                            <div
                                class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-secondary-200 opacity-20 blur-3xl">
                            </div>
                            <div
                                class="absolute -bottom-16 -left-16 h-40 w-40 rounded-full bg-secondary-300 opacity-20 blur-3xl">
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="md:w-1/2 text-center md:text-left">
                        <!-- Active slide content with simpler transitions -->
                        <div class="relative h-32">
                            <template x-for="(slide, index) in slides" :key="index">
                                <div x-show="activeSlide === index"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-y-4"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute inset-0">
                                    <h3 class="text-2xl md:text-3xl font-bold mb-4" x-text="slide.title"></h3>
                                    <p class="text-gray-600 text-lg" x-text="slide.description"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Navigation Dots -->
                        <div class="flex justify-center md:justify-start space-x-3 mt-12 mb-8">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="goToSlide(index)" :class="activeSlide === index ? 'bg-secondary-600' : 'bg-gray-300 hover:bg-gray-400'"
                                    class="h-3 w-3 rounded-full transition-all duration-300 transform hover:scale-110"></button>
                            </template>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                            <a href="#"
                                class="inline-flex items-center justify-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition transform hover:scale-105 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                        clip-rule="evenodd" />
                                </svg>
                                Download App
                            </a>
                            <a href="#"
                                class="inline-flex items-center justify-center px-6 py-3 border border-secondary-600 text-secondary-600 rounded-lg hover:bg-secondary-50 transition transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd" />
                                </svg>
                                See more screenshots
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Arrow Navigation -->
                <button @click="prev()"
                    class="absolute left-2 md:left-4 top-1/2 z-10 -translate-y-1/2 bg-white bg-opacity-80 p-2 rounded-full shadow-md hover:bg-opacity-100 focus:outline-none focus:ring-2 focus:ring-secondary-600 focus:ring-opacity-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="next()"
                    class="absolute right-2 md:right-4 top-1/2 z-10 -translate-y-1/2 bg-white bg-opacity-80 p-2 rounded-full shadow-md hover:bg-opacity-100 focus:outline-none focus:ring-2 focus:ring-secondary-600 focus:ring-opacity-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Feature Walkthrough Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="mb-16">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                        <h3 class="text-xl font-bold mb-4">Create an itinerary for any trip</h3>
                        <p class="text-gray-600">Easily plan day-by-day itineraries for your trips. Add activities, set
                            times, and organize your perfect vacation.</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="{{ asset('images/image18.jpg') }}" alt="Itinerary planning feature"
                            class="w-full rounded-lg shadow h-[300px] object-cover"
                            onerror="this.src='https://via.placeholder.com/600x400?text=Itinerary+Feature';">
                    </div>
                </div>
            </div>

            <div class="mb-16">
                <div class="flex flex-col md:flex-row-reverse items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0 md:pl-8">
                        <h3 class="text-xl font-bold mb-4">Save and contribute as a group</h3>
                        <p class="text-gray-600">Track your group's budget, set savings goals, and watch as everyone
                            contributes to make the trip a reality. Get detailed insights into expenses and split costs
                            fairly.</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="{{ asset('images/image5.jpg') }}" alt="Group savings feature"
                            class="w-full rounded-lg shadow h-[300px] object-cover"
                            onerror="this.src='https://via.placeholder.com/600x400?text=Savings+Feature';">
                    </div>
                </div>
            </div>

            <div class="mb-16">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                        <h3 class="text-xl font-bold mb-4">Map your route and activities</h3>
                        <p class="text-gray-600">Visualize your journey with interactive maps. See all your activities
                            plotted on a map and optimize your routes to save time and avoid backtracking.</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="{{ asset('images/image6.jpg') }}" alt="Interactive map feature"
                            class="w-full rounded-lg shadow h-[300px] object-cover"
                            onerror="this.src='https://via.placeholder.com/600x400?text=Map+Feature';">
                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <section class="py-16 bg-white">
                <div class="container mx-auto px-4 lg:px-8">
                    <h2 class="text-2xl font-bold text-center mb-12">What our users are saying</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/avatar1.jpg') }}" alt="User avatar"
                                    class="h-12 w-12 rounded-full object-cover"
                                    onerror="this.src='https://via.placeholder.com/48?text=User';">
                                <div class="ml-4">
                                    <h4 class="font-semibold">Sarah J.</h4>
                                    <div class="flex text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"PangoQ made planning our Europe trip so much easier! Our
                                group of 6 could all contribute ideas and vote on what to do. The budget tracking
                                feature helped us stay on track financially."</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/avatar2.jpg') }}" alt="User avatar"
                                    class="h-12 w-12 rounded-full object-cover"
                                    onerror="this.src='https://via.placeholder.com/48?text=User';">
                                <div class="ml-4">
                                    <h4 class="font-semibold">Michael T.</h4>
                                    <div class="flex text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"I used to hate organizing group trips because of all the
                                back-and-forth messages. With PangoQ, everything is in one place. The interactive maps
                                feature is a game-changer!"</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/avatar3.jpg') }}" alt="User avatar"
                                    class="h-12 w-12 rounded-full object-cover"
                                    onerror="this.src='https://via.placeholder.com/48?text=User';">
                                <div class="ml-4">
                                    <h4 class="font-semibold">Priya K.</h4>
                                    <div class="flex text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor" opacity="0.3">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"The group savings feature is brilliant. We set up our
                                vacation fund for our Thailand trip, and everyone could see exactly how much we needed
                                and who had contributed what."</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-16 bg-secondary-600">
                <div class="container mx-auto px-4 lg:px-8 text-center">
                    <h2 class="text-3xl font-bold text-black mb-6">Ready to make your group travel dreams a reality?
                    </h2>
                    <p class="text-lg text-black text-opacity-90 max-w-2xl mx-auto mb-8">
                        Join thousands of travelers who are planning, saving, and creating unforgettable memories with
                        PangoQ.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 bg-white text-secondary-600 font-medium rounded-md hover:bg-gray-100 transition transform hover:scale-105">
                            Start for free
                        </a>
                        <a href="#"
                            class="px-8 py-4 bg-secondary-700 text-black rounded-md border border-black border-opacity-20 hover:bg-secondary-800 transition transform hover:scale-105">
                            Schedule a demo
                        </a>
                    </div>
                </div>
            </section>

            <!-- Include Footer Component -->
            @include('components.footer')

</body>

</html>