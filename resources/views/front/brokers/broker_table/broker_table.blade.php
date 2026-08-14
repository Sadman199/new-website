<!-- Brokers Table -->
    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-xl bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-900 to-gray-800">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Broker</th>
                    <th scope="col" class="hidden sm:table-cell px-4 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Rating</th>
                    <th scope="col" class="hidden md:table-cell px-4 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Spreads</th>
                    <th scope="col" class="hidden lg:table-cell px-4 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Leverage</th>
                    <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Min. Dep.</th>
                    <th scope="col" class="relative py-4 pl-4 pr-6">
                        <span class="sr-only">Action</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($top_brokers as $broker)
                <tr class="hover:bg-gray-50/50 transition-colors duration-150 group">
                    <!-- Broker Name & Logo -->
                    <td class="py-4 pl-6 pr-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12 rounded-md border border-gray-200 bg-white p-1 shadow-sm">
                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="h-full w-full object-contain" width="48" height="48" loading="lazy" decoding="async">
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center">
                                    <p class="text-sm sm:text-base font-bold text-gray-900 truncate">{{ $broker->name }}</p>
                                    @if($loop->first)
                                    <span class="ml-2 px-2 py-0.5 rounded bg-yellow-100 text-yellow-800 text-xs font-bold">TOP PICK</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1 hidden sm:block">
                                   {{ Str::limit(strip_tags($broker->short_description), 60) }}
                                </p>
                                <div class="sm:hidden flex items-center mt-1">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($broker->rating))
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @elseif($i - 0.5 <= $broker->rating)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ number_format($broker->rating, 1) }}/5.0</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Rating (Desktop) -->
                    <td class="hidden sm:table-cell px-4 py-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($broker->rating))
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @elseif($i - 0.5 <= $broker->rating)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($broker->rating, 1) }}</span>
                        </div>
                    </td>
                    
                    <!-- Spreads (Desktop) -->
                    <td class="hidden md:table-cell px-4 py-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-mono font-medium text-gray-900">{{ $broker->spreads }}</span>
                        </div>
                    </td>
                    
                    <!-- Leverage (Desktop) -->
                    <td class="hidden lg:table-cell px-4 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3"/>
                            </svg>
                            {{ $broker->leverage }}
                        </span>
                    </td>
                    
                    <!-- Min Deposit -->
                    <td class="px-4 py-4">
                        <div class="text-sm font-mono font-medium text-gray-900">${{ number_format($broker->minimum_deposit) }}</div>
                    </td>
                    
                    <!-- Actions -->
                    <td class="py-4 pl-4 pr-6">
                        <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0 justify-end">
                            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Review
                            </a>
                            <a href="{{ $broker->url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-700 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Trade
                            </a>
                        </div>
                    </td>
                </tr>
                
                <!-- Pros/Cons Expandable Row -->
                <tr class="bg-gray-50/50">
                    <td colspan="6" class="px-6 py-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div>
                                <div class="flex items-center text-green-600 font-semibold mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    STRENGTHS
                                </div>
                                <ul class="space-y-2">
                                    @foreach(explode('</li>', $broker->pros) as $pro)
                                        @if(trim(strip_tags($pro)))
                                            <li class="text-gray-700 flex">
                                                <svg class="flex-shrink-0 h-4 w-4 text-green-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ strip_tags($pro) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <div class="flex items-center text-red-600 font-semibold mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    CONSIDERATIONS
                                </div>
                                <ul class="space-y-2">
                                    @foreach(explode('</li>', $broker->cons) as $con)
                                        @if(trim(strip_tags($con)))
                                            <li class="text-gray-700 flex">
                                                <svg class="flex-shrink-0 h-4 w-4 text-red-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ strip_tags($con) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>