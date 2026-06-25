
<div class="container px-4 max-w-7xl mx-auto w-full py-12" id="voices">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Review Form -->
        <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm mb-12">
            @php
                if (session('review_timeout_' . $broker->id) && now()->greaterThan(session('review_timeout_' . $broker->id))) {
                    session()->forget(['review_submitted_' . $broker->id, 'review_timeout_' . $broker->id]);
                }
            @endphp

            @if(session('review_submitted_' . $broker->id))
                <div class="bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Your review has been submitted successfully! Thank you for your feedback.</span>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
                    <h3 class="text-xl font-bold text-gray-700">Share Your Experience</h3>
                    <div class="flex space-x-1">
                        <i class="fas fa-circle text-gray-300"></i>
                        <i class="fas fa-circle text-blue-300"></i>
                        <i class="fas fa-circle text-green-300"></i>
                    </div>
                </div>

                <form action="{{ route('reviews.store', $broker->id) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="broker_id" value="{{ $broker->id }}">

                    <!-- Name and Email Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                            <input type="text" name="name" class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                            <input type="email" name="email" class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Country Field -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Your Country</label>
                        <input type="text" name="country" class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('country') }}" required>
                        @error('country')
                            <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rating Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} class="hidden">
                                    <label for="star{{ $i }}" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 transition-colors duration-200">
                                        <i class="far fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500 ml-2" id="ratingText">
                                {{ old('rating') ? old('rating') . '/5' : 'Select rating' }}
                            </span>
                        </div>
                        @error('rating')
                            <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Review Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Your Trading Experience</label>
                        <textarea name="description" class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="5" placeholder="Share details about your experience with spreads, execution, platform stability, customer service, etc." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-gray-50 text-gray-700 font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-md flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Review
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Review List -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-2xl font-bold text-gray-700">Trader Reviews</h3>
                <div class="text-gray-500 text-sm">
                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                    {{ $approved_reviews->count() }} verified reviews
                </div>
            </div>

            @if($approved_reviews->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <i class="fas fa-comment-slash text-4xl text-gray-400 mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">No reviews yet</h4>
                    <p class="text-gray-500">Be the first to share your trading experience with {{ $broker->name }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($approved_reviews as $review)
                        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm hover:border-blue-300 transition-colors duration-300 max-w-2xl mx-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-yellow-100 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center text-lg font-bold uppercase">
                                        {{ substr($review->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm">{{ $review->name }}</h4>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $review->country }} • {{ $review->formatted_date }}
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-blue-50 text-yellow-500 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $review->rating }}.0 <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <div class="pl-8">
                                <p class="text-gray-700 text-sm mb-2">"{{ $review->description }}"</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
