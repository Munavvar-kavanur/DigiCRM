<div class="min-h-screen bg-[#f5f5f5] font-sans text-[#545454]">
    {{-- Top Navigation Bar (Marketplace Style) --}}
    <nav class="bg-[#262626] text-white h-14 flex items-center px-4 lg:px-8 justify-between">
        <div class="flex items-center space-x-6">
            <a href="#" class="text-2xl font-bold tracking-tight text-white">Envato<span class="text-[#82b440]">Market</span></a>
            <div class="hidden md:flex space-x-4 text-sm text-gray-300">
                <a href="#" class="hover:text-white">Web Themes & Templates</a>
                <a href="#" class="hover:text-white">Code</a>
                <a href="#" class="hover:text-white">Video</a>
                <a href="#" class="hover:text-white">Audio</a>
                <a href="#" class="hover:text-white">Graphics</a>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-sm hover:text-white">Sign In</a>
        </div>
    </nav>

    {{-- Breadcrumbs --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center text-xs text-gray-500 space-x-2">
                <a href="#" class="hover:underline">Home</a>
                <span>/</span>
                <a href="#" class="hover:underline">Code</a>
                <span>/</span>
                <a href="#" class="hover:underline">PHP Scripts</a>
                <span>/</span>
                <a href="#" class="hover:underline">Project Management Tools</a>
                <span>/</span>
                <span class="text-gray-700">DigiCRM</span>
            </div>
            
            <h1 class="text-3xl font-bold text-[#262626] mt-4">DigiCRM - Ultimate CRM & Project Management Tool</h1>
            
            <div class="flex items-center mt-2 space-x-4 text-sm">
                <div class="flex items-center text-[#ffc107]">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    <span class="text-gray-500 ml-2 text-xs">4.95 (128 ratings)</span>
                </div>
                <div class="text-gray-500">
                    by <a href="#" class="text-[#0066cc] hover:underline font-medium">DigiBayt</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            
            {{-- Left Column (Details) --}}
            <div class="lg:col-span-2">
                {{-- Hero Banner --}}
                <div class="w-full h-80 bg-gradient-to-br from-indigo-900 to-purple-800 rounded-lg shadow-md mb-8 flex items-center justify-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    <div class="text-center z-10 p-8">
                        <h2 class="text-5xl font-extrabold text-white mb-2 tracking-tight">DigiCRM</h2>
                        <p class="text-indigo-200 text-xl font-light">Manage your business with elegance.</p>
                        <div class="mt-8 flex justify-center space-x-4">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 transform group-hover:scale-105 transition-transform duration-300">
                                <span class="block text-2xl font-bold text-white">100+</span>
                                <span class="text-xs text-indigo-200 uppercase tracking-wider">Features</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 transform group-hover:scale-105 transition-transform duration-300 delay-75">
                                <span class="block text-2xl font-bold text-white">Dark</span>
                                <span class="text-xs text-indigo-200 uppercase tracking-wider">Mode</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 transform group-hover:scale-105 transition-transform duration-300 delay-150">
                                <span class="block text-2xl font-bold text-white">Fast</span>
                                <span class="text-xs text-indigo-200 uppercase tracking-wider">Performance</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <a href="#" class="border-[#82b440] text-[#262626] whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Item Details
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Reviews
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Comments
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Support
                        </a>
                    </nav>
                </div>

                {{-- Description Content --}}
                <div class="prose max-w-none text-gray-600">
                    <p class="lead text-lg mb-6">
                        <strong>DigiCRM</strong> is the ultimate solution for managing your company's projects, clients, invoices, and expenses. Built with <strong>Laravel 10</strong> and <strong>TailwindCSS</strong>, it offers a premium, modern, and responsive user interface that works seamlessly across all devices.
                    </p>

                    <h3 class="text-2xl font-bold text-[#262626] mt-8 mb-4">Key Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <ul class="list-none space-y-2 pl-0">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Dashboard Analytics:</strong> Real-time overview of your business performance.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Client Management:</strong> Detailed client profiles with project history.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Project Tracking:</strong> Milestones, tasks, and progress monitoring.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Invoicing & Estimates:</strong> Create professional invoices and estimates in seconds.</span>
                            </li>
                        </ul>
                        <ul class="list-none space-y-2 pl-0">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Real-time Chat:</strong> Integrated messaging system for team collaboration.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Role-Based Access:</strong> Super Admin, Branch Admin, and Employee roles.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Multi-Branch Support:</strong> Manage multiple office locations from one dashboard.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span><strong>Dark Mode:</strong> Stunning dark theme for comfortable night usage.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-gray-100 p-6 rounded-lg mb-8 border-l-4 border-[#82b440]">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Why Choose DigiCRM?</h4>
                        <p class="mb-0">DigiCRM isn't just a tool; it's a complete ecosystem for your business. With its modular design, you can easily scale as your business grows. The clean code structure makes it a developer's dream to customize and extend.</p>
                    </div>

                    <h3 class="text-2xl font-bold text-[#262626] mt-8 mb-4">Changelog</h3>
                    <div class="bg-[#262626] text-gray-300 p-4 rounded-lg font-mono text-sm">
                        <p class="text-[#82b440] font-bold mb-2">v2.1.0 - 03 December 2025</p>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Added: Real-time Chat with file attachments</li>
                            <li>Added: Voice notes support in chat</li>
                            <li>Fixed: Sidebar navigation glitch on mobile</li>
                            <li>Improved: Dashboard loading performance</li>
                        </ul>
                        
                        <p class="text-[#82b440] font-bold mb-2 mt-4">v2.0.0 - 15 November 2025</p>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Major Update: Multi-branch support</li>
                            <li>Added: Payroll management system</li>
                            <li>Added: Employee attendance tracking</li>
                            <li>Redesigned: User Interface with new color palette</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Column (Sidebar) --}}
            <div class="mt-8 lg:mt-0 lg:col-span-1 space-y-6">
                
                {{-- Purchase Card --}}
                <div class="bg-white border border-gray-200 rounded shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-[#262626]">Regular License</span>
                        <span class="text-3xl font-bold text-[#262626]">$59</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-6">
                        Use, by you or one client, in a single end product which end users <strong>are not charged for</strong>. The total price includes the item price and a buyer fee.
                    </p>
                    
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Quality checked by Envato
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Future updates
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            6 months support from DigiBayt
                        </li>
                    </ul>

                    <button class="w-full bg-[#82b440] hover:bg-[#6f9a37] text-white font-bold py-3 px-4 rounded shadow-sm mb-3 transition-colors">
                        Add to Cart
                    </button>
                    <button class="w-full bg-[#007bff] hover:bg-[#0069d9] text-white font-bold py-3 px-4 rounded shadow-sm transition-colors">
                        Buy Now
                    </button>
                </div>

                {{-- Item Information --}}
                <div class="bg-white border border-gray-200 rounded shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-bold text-[#262626]">Item Information</h3>
                    </div>
                    <div class="p-4 text-sm">
                        <div class="grid grid-cols-2 gap-y-3">
                            <span class="text-gray-500">Last Update</span>
                            <span class="text-right text-[#262626]">3 December 25</span>
                            
                            <span class="text-gray-500">Published</span>
                            <span class="text-right text-[#262626]">1 January 25</span>
                            
                            <span class="text-gray-500">High Resolution</span>
                            <span class="text-right text-[#262626]">Yes</span>
                            
                            <span class="text-gray-500">Widget Ready</span>
                            <span class="text-right text-[#262626]">Yes</span>
                            
                            <span class="text-gray-500">Compatible Browsers</span>
                            <span class="text-right text-[#262626]">IE11, Firefox, Safari, Opera, Chrome, Edge</span>
                            
                            <span class="text-gray-500">Software Framework</span>
                            <span class="text-right text-[#262626]">Laravel 10.x, Tailwind CSS</span>
                            
                            <span class="text-gray-500">Files Included</span>
                            <span class="text-right text-[#262626]">JavaScript JS, HTML, CSS, PHP, SQL</span>
                        </div>
                    </div>
                </div>

                {{-- Tags --}}
                <div class="bg-white border border-gray-200 rounded shadow-sm p-4">
                    <h3 class="font-bold text-[#262626] mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['admin', 'dashboard', 'crm', 'laravel', 'project management', 'invoice', 'chat', 'saas', 'business', 'corporate'] as $tag)
                            <a href="#" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded border border-gray-300 transition-colors">
                                {{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-[#262626] text-gray-400 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 DigiBayt. All rights reserved.</p>
            <div class="mt-4 space-x-4 text-sm">
                <a href="#" class="hover:text-white">Privacy Policy</a>
                <a href="#" class="hover:text-white">Terms and Conditions</a>
                <a href="#" class="hover:text-white">Support</a>
            </div>
        </div>
    </footer>
</div>
