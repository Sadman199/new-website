<section class="bc-section" id="broker-features">
    <div class="bc-section__head">
        <h2 class="bc-section__title">Safety & Trading Features</h2>
        <p class="bc-section__desc">Regulation, tools, and platform capabilities</p>
    </div>
    <div class="bc-section__body space-y-6">
        @if($broker->short_description)
        <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg">
            <h3 class="font-bold text-gray-900 mb-2">Summary</h3>
            <div class="text-sm text-gray-700 leading-relaxed">{!! $broker->short_description !!}</div>
        </div>
        @endif

        <div>
            <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2"><i class="fas fa-shield-alt text-orange-500"></i> Safety & Regulation</h3>
            <div class="bc-data-grid bc-data-grid--2">
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-building"></i></div>
                    <div>
                        <div class="bc-data-row__label">Capitalization</div>
                        <div class="bc-data-row__value">{!! strip_tags($broker->capitalization ?? '—', '<a><b><strong>') !!}</div>
                    </div>
                </div>
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-file-contract"></i></div>
                    <div>
                        <div class="bc-data-row__label">Licenses</div>
                        <div class="bc-data-row__value">{!! strip_tags($broker->regulatory_licenses ?? '—', '<a><b><strong>') !!}</div>
                    </div>
                </div>
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="bc-data-row__label">Jurisdictions</div>
                        <div class="bc-data-row__value">{!! strip_tags($broker->regulated_jurisdictions ?? '—', '<a><b><strong>') !!}</div>
                    </div>
                </div>
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-umbrella"></i></div>
                    <div>
                        <div class="bc-data-row__label">Insurance</div>
                        <div class="bc-data-row__value">{!! strip_tags($broker->insurance ?? '—', '<a><b><strong>') !!}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2"><i class="fas fa-tools text-orange-500"></i> Trading Tools</h3>
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $tradingTools = [
                            ['label' => 'Social Trading', 'enabled' => $broker->social_trading],
                            ['label' => 'Economic Calendar', 'enabled' => $broker->economic_calendar],
                            ['label' => 'Account Managers', 'enabled' => $broker->account_managers],
                            ['label' => 'VPS Hosting', 'enabled' => $broker->vps_hosting],
                        ];
                    @endphp
                    @foreach($tradingTools as $tool)
                    <div class="flex items-center gap-2 p-2.5 rounded-lg border {{ $tool['enabled'] ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-100' }}">
                        <i class="fas {{ $tool['enabled'] ? 'fa-check-circle text-green-600' : 'fa-times-circle text-gray-400' }}"></i>
                        <span class="text-sm font-medium text-gray-700">{{ $tool['label'] }}</span>
                    </div>
                    @endforeach
                </div>
                @if($broker->payment_methods)
                <p class="text-sm text-gray-600 mt-3"><strong>Payments:</strong> {!! strip_tags($broker->payment_methods) !!}</p>
                @endif
            </div>

            <div>
                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2"><i class="fas fa-laptop text-orange-500"></i> Platforms</h3>
                <div class="space-y-2">
                    <div class="bc-data-row">
                        <div class="bc-data-row__icon"><i class="fas fa-mobile-alt"></i></div>
                        <div>
                            <div class="bc-data-row__label">Mobile Trading</div>
                            <div class="bc-data-row__value">{!! $broker->mobile_trading ?? '—' !!}</div>
                        </div>
                    </div>
                    <div class="bc-data-row">
                        <div class="bc-data-row__icon"><i class="fas fa-globe"></i></div>
                        <div>
                            <div class="bc-data-row__label">Web Trader</div>
                            <div class="bc-data-row__value">{!! $broker->web_trader ?? '—' !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
