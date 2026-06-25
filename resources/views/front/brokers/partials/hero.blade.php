<section class="relative bg-gradient-to-br from-gray-50 via-white to-gray-50 border-b border-gray-200 overflow-hidden">
    <!-- Subtle background decoration -->
    <div class="absolute inset-0 pointer-events-none opacity-20">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-yellow-200 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-200 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 lg:py-16 relative z-10">
        <div class="flex flex-col gap-8">
            <!-- Main Header Content -->
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <!-- Left side - Title & Description -->
                <div class="space-y-4 max-w-3xl">
                    <!-- Dynamic Title -->
                    <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 leading-tight mt-12">
                        {{ $broker->title }}
                    </h1>

                    <div>
                        <nav class="flex items-center text-sm" aria-label="Breadcrumb">
                            <ol class="flex items-center flex-wrap gap-2 text-gray-500">
                                <li>
                                    <a href="{{ route('home') }}" class="flex items-center gap-1.5 hover:text-yellow-600 transition-colors duration-200">
                                        <i class="fas fa-home text-xs"></i>
                                        <span>Home</span>
                                    </a>
                                </li>
                                <li class="text-gray-400">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </li>
                                <li class="text-gray-600">Broker Reviews</li>
                                <li class="text-gray-400">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </li>
                                <li class="font-semibold text-gray-900">{{ $broker->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Right side - Optional visual stats or badge (clean, minimal) -->
                <div class="flex-shrink-0">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl px-5 py-3 shadow-sm border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-star text-yellow-500 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">Broker Profile</div>
                                <div class="text-sm font-medium text-gray-800">{{ $broker->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>