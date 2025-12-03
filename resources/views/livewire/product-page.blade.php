<div class="relative overflow-hidden">
    {{-- Navbar --}}
    <nav x-data="{ mobileMenuOpen: false }" class="absolute w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <span class="text-white font-bold text-xl">D</span>
                    </div>
                    <span class="font-bold text-2xl text-gray-900 tracking-tight">DigiCRM</span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#tech-stack" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Tech Stack</a>
                    <a href="#pricing" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Pricing</a>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">Log in</a>
                    <a href="#pricing" class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-full transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Get Started
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden bg-white border-b border-gray-100 shadow-lg absolute w-full">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#features" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">Features</a>
                <a href="#tech-stack" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">Tech Stack</a>
                <a href="#pricing" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">Pricing</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">Log in</a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <div class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        {{-- Background Blobs --}}
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-20 right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-medium mb-8 animate-fade-in-up">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600 mr-2"></span>
                v2.1 Now Available with Real-time Chat
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight mb-8 leading-tight animate-fade-in-up animation-delay-100">
                Manage your business <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">with pure elegance.</span>
            </h1>
            
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-600 mb-10 animate-fade-in-up animation-delay-200">
                The most powerful, self-hosted CRM solution built with Laravel 10. 
                Streamline projects, invoices, and team collaboration in one beautiful dashboard.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up animation-delay-300">
                <a href="#pricing" class="px-8 py-4 text-lg font-semibold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-1">
                    Buy Now for $59
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-semibold rounded-full text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm hover:shadow-md">
                    View Live Demo
                </a>
            </div>

            {{-- Dashboard Preview Mockup --}}
            <div class="mt-20 relative rounded-2xl border border-gray-200 bg-gray-50/50 p-2 md:p-4 shadow-2xl animate-fade-in-up animation-delay-500">
                <div class="rounded-xl overflow-hidden bg-white border border-gray-200 shadow-sm relative group">
                    {{-- Dashboard Image --}}
                    <img src="{{ asset('images/DigiCRM-dashboard-preview.webp') }}" 
                         alt="DigiCRM Dashboard Preview" 
                         class="w-full h-auto object-cover">
                    {{-- Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-white/10 to-transparent pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Features Grid --}}
    <div id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-indigo-600 font-semibold tracking-wide uppercase text-sm mb-3">Everything you need</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Packed with powerful features</h3>
                <p class="text-lg text-gray-600">DigiCRM comes with everything you need to run your business efficiently. No hidden fees, no monthly subscriptions.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: Client Portal & Team Management --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Client Portal & Teams</h4>
                    <p class="text-gray-600 leading-relaxed">Clients get their own login portal. Add multiple team members per client for collaboration and project discussions.</p>
                </div>

                {{-- Feature 2: Client Login & Dashboard --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-violet-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Client Login</h4>
                    <p class="text-gray-600 leading-relaxed">Dedicated client dashboard to view projects, invoices, estimates, payments, and communicate with your team.</p>
                </div>

                {{-- Feature 3: Razorpay Integration --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Razorpay Integration</h4>
                    <p class="text-gray-600 leading-relaxed">Clients can pay invoices directly from their dashboard using Razorpay. Secure and instant payment processing.</p>
                </div>

                {{-- Feature 4: Advanced Chat System --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-pink-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Advanced Chat System</h4>
                    <p class="text-gray-600 leading-relaxed">Direct messages, group chats, and project discussions. Share images, documents, audio files, and voice notes.</p>
                </div>

                {{-- Feature 5: Project Management --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Project Tracking</h4>
                    <p class="text-gray-600 leading-relaxed">Track project progress, milestones, tasks, and deadlines. Assign team members and monitor completion status.</p>
                </div>

                {{-- Feature 6: Smart Invoicing --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-green-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Smart Invoicing</h4>
                    <p class="text-gray-600 leading-relaxed">Create professional invoices with custom branding. Generate PDF, send via email, and track payment status.</p>
                </div>

                {{-- Feature 7: Estimates & Quotations --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Estimates & Quotations</h4>
                    <p class="text-gray-600 leading-relaxed">Send professional estimates to clients. Convert approved estimates to invoices with one click.</p>
                </div>

                {{-- Feature 8: Expense Management --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-red-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Expense Management</h4>
                    <p class="text-gray-600 leading-relaxed">Track business expenses by category and payer. Attach receipts and generate expense reports.</p>
                </div>

                {{-- Feature 9: Payroll System --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-yellow-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Payroll System</h4>
                    <p class="text-gray-600 leading-relaxed">Manage employee salaries, generate payslips, and track payment history. Multiple payroll types supported.</p>
                </div>

                {{-- Feature 10: Employee Management --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Employee Management</h4>
                    <p class="text-gray-600 leading-relaxed">Maintain employee records, types, and roles. Track employee assignments and performance.</p>
                </div>

                {{-- Feature 11: Multi-Branch Support --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-orange-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Multi-Branch Support</h4>
                    <p class="text-gray-600 leading-relaxed">Manage multiple office locations or business branches from a single super-admin panel with isolated data.</p>
                </div>

                {{-- Feature 12: Detailed Reports --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Detailed Reports</h4>
                    <p class="text-gray-600 leading-relaxed">Comprehensive financial and activity reports. Export data to Excel for further analysis.</p>
                </div>

                {{-- Feature 13: Reminders & Notifications --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-rose-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Reminders & Notifications</h4>
                    <p class="text-gray-600 leading-relaxed">Set reminders for important tasks and deadlines. Never miss a payment or project milestone.</p>
                </div>

                {{-- Feature 14: Role-Based Access --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-green-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Role-Based Access</h4>
                    <p class="text-gray-600 leading-relaxed">Super Admin, Branch Admin, Employee, and Client roles with granular permission control.</p>
                </div>

                {{-- Feature 15: Dark Mode --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-gray-800 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Beautiful Dark Mode</h4>
                    <p class="text-gray-600 leading-relaxed">Stunning dark theme for comfortable night-time work. Toggle between light and dark modes instantly.</p>
                </div>

                {{-- Feature 16: API Support --}}
                <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">RESTful API</h4>
                    <p class="text-gray-600 leading-relaxed">Complete REST API for mobile app development. Build iOS and Android apps with ease.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tech Stack Section --}}
    <div id="tech-stack" class="py-20 bg-gray-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h3 class="text-3xl font-bold mb-6">Built with modern technology</h3>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                        DigiCRM is built on top of the robust <span class="text-white font-semibold">Laravel 10</span> framework and styled with <span class="text-white font-semibold">Tailwind CSS</span>. 
                        It utilizes <span class="text-white font-semibold">Livewire</span> for dynamic interactions without the complexity of a separate frontend.
                    </p>
                    
                    <ul class="space-y-4">
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Clean, modular, and documented code
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Easy to customize and extend
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Secure authentication & authorization
                        </li>
                    </ul>
                </div>
                <div class="mt-12 lg:mt-0 grid grid-cols-2 gap-6">
                    <div class="p-6 bg-gray-800 rounded-2xl border border-gray-700 flex flex-col items-center justify-center text-center hover:bg-gray-750 transition-colors">
                        <span class="text-4xl font-bold text-red-500 mb-2">Laravel</span>
                        <span class="text-sm text-gray-400">Framework</span>
                    </div>
                    <div class="p-6 bg-gray-800 rounded-2xl border border-gray-700 flex flex-col items-center justify-center text-center hover:bg-gray-750 transition-colors">
                        <span class="text-4xl font-bold text-sky-400 mb-2">Tailwind</span>
                        <span class="text-sm text-gray-400">CSS</span>
                    </div>
                    <div class="p-6 bg-gray-800 rounded-2xl border border-gray-700 flex flex-col items-center justify-center text-center hover:bg-gray-750 transition-colors">
                        <span class="text-4xl font-bold text-pink-500 mb-2">Livewire</span>
                        <span class="text-sm text-gray-400">Full Stack</span>
                    </div>
                    <div class="p-6 bg-gray-800 rounded-2xl border border-gray-700 flex flex-col items-center justify-center text-center hover:bg-gray-750 transition-colors">
                        <span class="text-4xl font-bold text-yellow-400 mb-2">Alpine.js</span>
                        <span class="text-sm text-gray-400">Interactivity</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pricing Section --}}
    <div id="pricing" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-indigo-600 font-semibold tracking-wide uppercase text-sm mb-3">Simple Pricing</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">One-time payment, lifetime access</h3>
                <p class="text-lg text-gray-600">Stop paying monthly subscriptions. Get full source code and lifetime updates.</p>
            </div>

            <div class="max-w-lg mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-8 sm:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">Regular License</h4>
                            <p class="text-gray-500 mt-1">For single end product</p>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-bold text-gray-900">$59</span>
                            <span class="text-gray-500 block text-sm">USD</span>
                        </div>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-600">Full Source Code Included</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-600">Lifetime Free Updates</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-600">6 Months Premium Support</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-600">Unlimited Projects & Clients</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-600">Multi-Branch System</span>
                        </li>
                    </ul>

                    <button class="w-full py-4 px-6 rounded-xl bg-gray-900 hover:bg-gray-800 text-white font-bold text-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Purchase Now
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-4">Secure payment via Gumroad / Stripe</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center gap-2 mb-4 md:mb-0">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">D</span>
                </div>
                <span class="font-bold text-xl text-gray-900">DigiCRM</span>
            </div>
            
            <div class="flex space-x-6 text-sm text-gray-500">
                <a href="#" class="hover:text-indigo-600 transition-colors">Documentation</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Support</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Privacy</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Terms</a>
            </div>
            
            <div class="mt-4 md:mt-0 text-sm text-gray-400">
                &copy; {{ date('Y') }} DigiBayt. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- Animations Styles --}}
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translate3d(0, 20px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        .animation-delay-100 { animation-delay: 0.1s; }
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-300 { animation-delay: 0.3s; }
        .animation-delay-500 { animation-delay: 0.5s; }
    </style>
</div>
