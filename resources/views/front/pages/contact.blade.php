@extends('front.layout.app')
@section('title', 'BrokersCourt | Get in Touch with Us')
@section('canonical', route('contact'))
@section('main_content')
<div class="bg-gray-900 py-12 border-b border-gray-800 relative overflow-hidden mt-12">
    <div class="absolute top-0 left-1/4 w-64 h-64 bg-blue-500 rounded-full mix-blend-overlay filter blur-3xl"></div>
      <div class="container px-4 max-w-7xl mx-auto w-full">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <!-- Left-aligned content -->
            <div class="mb-6 md:mb-0">
                <span class="inline-block px-3 py-1 text-xs font-semibold text-gray-50 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full mb-3">GET IN TOUCH</span>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Need Help Choosing the Right Forex Broker?</h1>
                <p class="text-gray-300 max-w-2xl">
                    Brokers Court helps you choose the right forex broker with trusted reviews and comparisons.
                </p>
            </div>

            <nav class="bg-gray-800 rounded-full px-4 py-2 inline-flex items-center text-sm text-gray-300 space-x-2">
                <!-- Home Link -->
                <a href="{{ route('home') }}" class="flex items-center hover:text-white transition">
                    <i class="fas fa-home mr-2"></i> Home
                </a>

                <!-- Chevron -->
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>

                <!-- Current Page -->
                <span class="font-medium text-gray-400">
                    {{ $page_data->contact_title }}
                </span>
            </nav>
        </div>
    </div>
</div>
<div class="bg-gray-900 py-16">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <div class="flex flex-wrap lg:flex-nowrap -mx-4">
            <!-- Left Column - Contact Form -->
            <div class="w-full lg:w-1/2 px-4 mb-12 lg:mb-0 flex flex-col">
            <div class="bg-gray-800 rounded-xl shadow-xl p-8 border border-gray-700 flex-grow flex flex-col">
                <div class="flex items-center mb-6">
                <div class="bg-yellow-500 p-2 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white">Contact Trading Support</h3>
                </div>
             
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                <form action="{{ route('contact_form_submit') }}" method="post" class="space-y-6 flex-grow flex flex-col">
                    @csrf
                
                    <!-- Honeypot field (invisible to users) -->
                    <div style="position: absolute; left: -9999px;">
                        <label for="extra_field">Do not fill this field</label>
                        <input type="text" name="extra_field" id="extra_field">
                    </div>
                
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">{{ NAME }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="name" placeholder="Name" 
                                   class="pl-10 w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white placeholder-gray-400 transition duration-200">
                        </div>
                        <span class="text-sm text-red-400 error-text name_error"></span>
                    </div>
                
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">{{ EMAIL_ADDRESS }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" placeholder="your@email.com" 
                                   class="pl-10 w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white placeholder-gray-400 transition duration-200">
                        </div>
                        <span class="text-sm text-red-400 error-text email_error"></span>
                    </div>
                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-300 mb-2">{{ MESSAGE }}</label>
                        <textarea name="message" rows="4" placeholder="Describe your query in detail..." 
                                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white placeholder-gray-400 transition duration-200"></textarea>
                        <span class="text-sm text-red-400 error-text message_error"></span>
                    </div>
                
                    <!-- Terms & Conditions -->
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded bg-gray-700">
                        <label for="terms" class="ml-2 block text-sm text-gray-300">
                            I agree to the <a href="#" class="text-blue-400 hover:text-blue-300">terms and conditions</a>
                        </label>
                    </div>
                
                    <!-- Google reCAPTCHA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Verify You’re Human</label>
                        <div class="captcha-box">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                        </div>
                        <span class="text-sm text-red-400 captcha-error">Please complete the reCAPTCHA</span>
                    </div>
                
                    <!-- Submit Button -->
                    <div class="mt-auto">
                        <button type="submit" 
                                class="w-full bg-gray-600 text-gray-50 font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] shadow-lg">
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                </svg>
                                {{ SEND_MESSAGE }}
                            </div>
                        </button>
                    </div>
                </form>


               
             </div>
            </div>
            
            <!-- Right Column - Contact Options -->
            <div class="w-full lg:w-1/2 px-4 flex flex-col">
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200 mb-4 flex-grow flex flex-col">
                <div class="flex items-center mb-4">
                <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="font-bold text-white">Office Hours</h4>
                </div>
                <p class="text-white font-medium mb-2">Monday to Friday: 9:00 AM - 7:00 PM</p>
                <p class="text-gray-300 text-sm">Our team is available to assist with account setup, trading queries, and technical support during these hours. For after-hours assistance, please use our live chat or submit a ticket.</p>
            </div>
            
            <!-- Contact Channels Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 flex-grow">
                <!-- Live Chat -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    </div>
                    <h4 class="font-bold text-white">Email Us</h4>
                </div>
                <p class="text-gray-300 text-sm mb-3">Drop us an email anytime!</p>
                <div class="text-blue-400 font-medium">info@brokerscourt.com</div>
                </div>
                
                <!-- Phone Support -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    </div>
                    <h4 class="font-bold text-white">Phone Support</h4>
                </div>
                <p class="text-gray-300 text-sm mb-3">Direct line to our trading specialists</p>
                <div class="text-blue-400 font-medium">+971555315186</div>
                </div>
                
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                <div class="flex items-center mb-4">
                    <div class="bg-red-600 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.2A1 1 0 0010 9.768v4.464a1 1 0 001.555.832l3.197-2.2a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    </div>
                    <h4 class="font-bold text-white">Follow Us</h4>
                </div>
                <p class="text-gray-300 text-sm mb-3">Stay connected for updates</p>
                <a href="https://www.youtube.com/@BrokersCourt/featured" class="text-red-500 font-bold" target="_blank">
                    Visit our YouTube Channel
                </a>
                </div>                    
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-white">Connect With Us</h4>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">Follow us on our social platforms</p>
                    <div class="flex justify-around">
                        <a href="#" class="bg-gray-700 p-3 hover:bg-blue-600 transition duration-200 rounded-md w-12 h-12 flex items-center justify-center">
                            <i class="fab fa-facebook-f fa-lg text-white"></i>
                        </a>
                        <a href="#" class="bg-gray-700 p-3 hover:bg-blue-400 transition duration-200 rounded-md w-12 h-12 flex items-center justify-center">
                            <i class="fab fa-twitter fa-lg text-white"></i>
                        </a>
                        <a href="#" class="bg-gray-700 p-3 hover:bg-pink-600 transition duration-200 rounded-md w-12 h-12 flex items-center justify-center">
                            <i class="fab fa-instagram fa-lg text-white"></i>
                        </a>
                        <a href="#" class="bg-gray-700 p-3 hover:bg-blue-800 transition duration-200 rounded-md w-12 h-12 flex items-center justify-center">
                            <i class="fab fa-linkedin fa-lg text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Social Trading Community -->
            <div class="bg-gray-800 rounded-xl shadow-xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Join Our Trading Community</h3>
                <p class="text-gray-300 mb-6">Connect with thousands of traders, share strategies, and get real-time market insights.</p>
                
                <div class="flex flex-wrap gap-3">
                <a href="https://t.me/brokerscourt" target="_blank" rel="noopener noreferrer" class="flex-1 bg-gray-700 hover:bg-gray-600 rounded-lg px-4 py-3 text-center transition duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fab fa-telegram text-blue-400"></i>
                        <span class="text-white text-sm font-medium">Telegram</span>
                    </div>
                </a>

                <a href="#" class="flex-1 bg-gray-700 hover:bg-gray-600 rounded-lg px-4 py-3 text-center transition duration-200">
                    <div class="flex items-center justify-center space-x-2">
                    <i class="fab fa-discord text-indigo-400"></i>
                    <span class="text-white text-sm font-medium">Discord</span>
                    </div>
                </a>
                <a href="#" class="flex-1 bg-gray-700 hover:bg-gray-600 rounded-lg px-4 py-3 text-center transition duration-200">
                    <div class="flex items-center justify-center space-x-2">
                    <i class="fab fa-twitter text-blue-300"></i>
                    <span class="text-white text-sm font-medium">Twitter</span>
                    </div>
                </a>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- Forex Market Essentials Section -->
<div class="bg-gradient-to-b from-gray-900 to-gray-800 py-16 border-t border-gray-700">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <h3 class="text-2xl md:text-3xl font-bold text-white mb-8 text-center">
            <span class="text-gray-50">Market Essentials</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Trading Sessions -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                <div class="flex items-center mb-4">
                    <div class="bg-amber-500 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-white">Trading Sessions</h4>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">London</span>
                        <span class="text-sm font-medium text-white">08:00 - 17:00 GMT</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">New York</span>
                        <span class="text-sm font-medium text-white">13:00 - 22:00 GMT</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Tokyo</span>
                        <span class="text-sm font-medium text-white">00:00 - 09:00 GMT</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Sydney</span>
                        <span class="text-sm font-medium text-white">22:00 - 07:00 GMT</span>
                    </div>
                </div>
            </div>
            
            <!-- Major Pairs -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                <div class="flex items-center mb-4">
                    <div class="bg-green-500 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-white">Major Pairs</h4>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">EUR/USD</span>
                        <span class="text-sm font-medium text-green-400">1.0854 ▲0.12%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">GBP/USD</span>
                        <span class="text-sm font-medium text-red-400">1.2701 ▼0.05%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">USD/JPY</span>
                        <span class="text-sm font-medium text-green-400">151.82 ▲0.23%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">AUD/USD</span>
                        <span class="text-sm font-medium text-red-400">0.6589 ▼0.08%</span>
                    </div>
                </div>
            </div>
            
            <!-- Market Holidays -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
                <div class="flex items-center mb-4">
                    <div class="bg-red-500 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-white">Market Holidays</h4>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Good Friday</span>
                        <span class="text-sm font-medium text-white">April 7</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Christmas Day</span>
                        <span class="text-sm font-medium text-white">Dec 25</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">New Year's Day</span>
                        <span class="text-sm font-medium text-white">Jan 1</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Independence Day</span>
                        <span class="text-sm font-medium text-white">July 4 (US)</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liquidity Notice -->
        <div class="mt-12 bg-gray-800 rounded-xl p-6 border border-dashed border-gray-600">
            <div class="flex items-start">
                <div class="bg-yellow-500 p-2 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-2">Liquidity Notice</h4>
                    <p class="text-gray-300 text-sm">
                        Market liquidity may be lower during holiday periods and between trading sessions. 
                        Spreads may widen significantly during these times. The Tokyo-London overlap 
                        (08:00-09:00 GMT) typically offers the highest liquidity for EUR/USD and GBP/USD.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection