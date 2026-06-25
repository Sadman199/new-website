@extends('admin.layout.app')

@section('heading', 'Edit Broker')

@section('button')
    <a href="" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Brokers</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Edit Broker</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin_broker_update', $broker->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div id="accordion">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header" id="headingBasic">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseBasic" aria-expanded="true" aria-controls="collapseBasic">
                                    Basic Information
                                </button>
                            </h5>
                        </div>
                        <div id="collapseBasic" class="collapse show" aria-labelledby="headingBasic" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Broker Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $broker->name) }}">
                                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="url">Broker URL <span class="text-danger">*</span></label>
                                            <input type="url" name="url" id="url" class="form-control" required value="{{ old('url', $broker->url) }}">
                                            @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $broker->slug ?? '') }}">
                                            @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="title">Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $broker->title ?? '') }}">
                                            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rating">Rating (0-5)</label>
                                            <input type="number" name="rating" id="rating" class="form-control" step="0.01" min="0" max="5" value="{{ old('rating', $broker->rating ?? '') }}">
                                            @error('rating')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country">Country <span class="text-danger">*</span></label>
                                            <input type="text" name="country" id="country" class="form-control" required value="{{ old('country', $broker->country) }}">
                                            @error('country')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Uploads -->
                    <div class="card">
                        <div class="card-header" id="headingMedia">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseMedia" aria-expanded="false" aria-controls="collapseMedia">
                                    Media Uploads
                                </button>
                            </h5>
                        </div>
                        <div id="collapseMedia" class="collapse" aria-labelledby="headingMedia" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo">Logo (Optional)</label>
                                            <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                                            @error('logo')<small class="text-danger">{{ $message }}</small>@enderror
                                            @if($broker->logo)
                                                <p class="mt-2">Current Logo:</p>
                                                <img src="{{ asset('storage/' . $broker->logo) }}" alt="Current Broker Logo" class="img-fluid" style="max-width: 100px;">
                                            @endif
                                            <div class="mt-2" id="logo-preview-container" style="display: none;">
                                                <p>Preview:</p>
                                                <img id="logo-preview" src="" alt="Logo Preview" class="img-fluid" style="max-width: 100px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="banner_1">Banner 1</label>
                                            <input type="file" name="banner_1" id="banner_1" class="form-control-file" accept="image/*">
                                            @error('banner_1')<small class="text-danger">{{ $message }}</small>@enderror
                                            @if($broker->banner_1)
                                                <p class="mt-2">Current Banner 1:</p>
                                                <img src="{{ asset($broker->banner_1) }}" alt="Current Banner 1" class="img-fluid" style="max-height: 60px;">
                                            @endif
                                            <div class="mt-2" id="banner1-preview-container" style="display: none;">
                                                <p>Preview:</p>
                                                <img id="banner1-preview" src="" alt="Banner 1 Preview" class="img-fluid" style="max-height: 60px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="banner_2">Banner 2</label>
                                            <input type="file" name="banner_2" id="banner_2" class="form-control-file" accept="image/*">
                                            @error('banner_2')<small class="text-danger">{{ $message }}</small>@enderror
                                            @if($broker->banner_2)
                                                <p class="mt-2">Current Banner 2:</p>
                                                <img src="{{ asset($broker->banner_2) }}" alt="Current Banner 2" class="img-fluid" style="max-height: 60px;">
                                            @endif
                                            <div class="mt-2" id="banner2-preview-container" style="display: none;">
                                                <p>Preview:</p>
                                                <img id="banner2-preview" src="" alt="Banner 2 Preview" class="img-fluid" style="max-height: 60px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Links -->
                    <div class="card">
                        <div class="card-header" id="headingLinks">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseLinks" aria-expanded="false" aria-controls="collapseLinks">
                                    Links
                                </button>
                            </h5>
                        </div>
                        <div id="collapseLinks" class="collapse" aria-labelledby="headingLinks" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="visit_site">Visit Site (Optional)</label>
                                            <input type="text" name="visit_site" id="visit_site" class="form-control" value="{{ old('visit_site', $broker->visit_site) }}">
                                            @error('visit_site')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="open_live">Open Live Account (Optional)</label>
                                            <input type="text" name="open_live" id="open_live" class="form-control" value="{{ old('open_live', $broker->open_live) }}">
                                            @error('open_live')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="open_demo">Open Demo Account (Optional)</label>
                                            <input type="text" name="open_demo" id="open_demo" class="form-control" value="{{ old('open_demo', $broker->open_demo) }}">
                                            @error('open_demo')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Descriptions -->
                    <div class="card">
                        <div class="card-header" id="headingDescriptions">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDescriptions" aria-expanded="false" aria-controls="collapseDescriptions">
                                    Descriptions
                                </button>
                            </h5>
                        </div>
                        <div id="collapseDescriptions" class="collapse" aria-labelledby="headingDescriptions" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" class="form-control snote" rows="4">{{ old('short_description', $broker->short_description) }}</textarea>
                                            @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pros">Pros</label>
                                            <textarea name="pros" id="pros" class="form-control snote" rows="4">{{ old('pros', $broker->pros) }}</textarea>
                                            @error('pros')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cons">Cons</label>
                                            <textarea name="cons" id="cons" class="form-control snote" rows="4">{{ old('cons', $broker->cons) }}</textarea>
                                            @error('cons')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trading Details -->
                    <div class="card">
                        <div class="card-header" id="headingTrading">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTrading" aria-expanded="false" aria-controls="collapseTrading">
                                    Trading Details
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTrading" class="collapse" aria-labelledby="headingTrading" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="minimum_deposit">Minimum Deposit</label>
                                            <input type="number" name="minimum_deposit" id="minimum_deposit" class="form-control" value="{{ old('minimum_deposit', $broker->minimum_deposit) }}">
                                            @error('minimum_deposit')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spreads">Spreads</label>
                                            <input type="text" name="spreads" id="spreads" class="form-control" value="{{ old('spreads', $broker->spreads) }}">
                                            @error('spreads')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="leverage">Leverage</label>
                                            <input type="text" name="leverage" id="leverage" class="form-control" value="{{ old('leverage', $broker->leverage ?? '') }}">
                                            @error('leverage')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mobile_trading">Mobile Trading</label>
                                            <input type="text" name="mobile_trading" id="mobile_trading" class="form-control" value="{{ old('mobile_trading', $broker->mobile_trading) }}">
                                            @error('mobile_trading')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="social_trading">Social Trading</label>
                                            <input type="text" name="social_trading" id="social_trading" class="form-control" value="{{ old('social_trading', $broker->social_trading) }}">
                                            @error('social_trading')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="web_trader">Web Trader</label>
                                            <input type="text" name="web_trader" id="web_trader" class="form-control" value="{{ old('web_trader', $broker->web_trader) }}">
                                            @error('web_trader')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Platforms -->
                    <div class="card">
                        <div class="card-header" id="headingPlatforms">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsePlatforms" aria-expanded="false" aria-controls="collapsePlatforms">
                                    Platforms
                                </button>
                            </h5>
                        </div>
                        <div id="collapsePlatforms" class="collapse" aria-labelledby="headingPlatforms" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @php
                                            $selectedPlatforms = old('platforms', is_array($broker->platforms) ? $broker->platforms : json_decode($broker->platforms, true));
                                        @endphp
                                        
                                        <div class="form-group">
                                            @foreach ([
                                                'MetaTrader 4' => 'mt4',
                                                'MetaTrader 5' => 'mt5',
                                                'cTrader' => 'ctrader',
                                                'WebTrader' => 'webtrader',
                                                'xStation' => 'xstation',
                                                'ThinkorSwim' => 'thinkorswim',
                                                'NinjaTrader' => 'ninjaTrader',
                                                'TradeStation' => 'tradeStation',
                                                'TradingView' => 'tradingView',
                                                'Interactive Brokers' => 'InteractiveBrokers',
                                                'SaxoTrader' => 'SaxoTrader'
                                            ] as $platform => $id)
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="platforms[]" 
                                                           id="{{ $id }}" 
                                                           value="{{ $platform }}" 
                                                           class="form-check-input"
                                                           {{ in_array($platform, (array) $selectedPlatforms) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $id }}">{{ $platform }}</label>
                                                </div>
                                            @endforeach
                                        
                                            @error('platforms')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regulation -->
                    <div class="card">
                        <div class="card-header" id="headingRegulation">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseRegulation" aria-expanded="false" aria-controls="collapseRegulation">
                                    Regulation
                                </button>
                            </h5>
                        </div>
                        <div id="collapseRegulation" class="collapse" aria-labelledby="headingRegulation" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $selectedRegulation = old(
                                            'regulation',
                                            is_array($broker->regulation) ? $broker->regulation : json_decode($broker->regulation, true)
                                        );
                                    @endphp
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Regulation</label>
                                    
                                            @foreach ([
                                                // Top-Tier / Highly Trusted
                                                'FCA (UK)' => 'fca',
                                                'ASIC (Australia)' => 'asic',
                                                'CySEC (Cyprus)' => 'cysec',
                                                'NFA/CFTC (USA)' => 'nfacftc',
                                                'MAS (Singapore)' => 'mas',
                                                'JFSA (Japan)' => 'jfsa',
                                                'BaFin (Germany)' => 'bafin',
                                                'FINMA (Switzerland)' => 'finma',
                                                'IIROC (Canada)' => 'iiroc',
                                                'SFC (Hong Kong)' => 'sfc',
                                    
                                                // Secondary / Regional
                                                'FSCA (South Africa)' => 'fsca',
                                                'CONSOB (Italy)' => 'consob',
                                                'CNMV (Spain)' => 'cnmv',
                                                'FSC (Mauritius)' => 'fsc',
                                                'FMA (New Zealand)' => 'fma',
                                                'MFSA (Malta)' => 'mfsa',
                                                'SCB (Bahrain)' => 'scb',
                                                'DFSA (Dubai, UAE)' => 'dfsa',
                                    
                                                // Offshore / Lesser-Regulated
                                                'VFSC (Vanuatu)' => 'vfsc',
                                                'FSA (Seychelles)' => 'fsa_seychelles',
                                                'CIMA (Cayman Islands)' => 'cima',
                                                'FSA (Belize)' => 'fsa_belize',
                                                'FSA (St. Vincent & Grenadines)' => 'fsa_svg',
                                                'FSC (British Virgin Islands)' => 'fsc_bvi'
                                            ] as $regulation => $id)
                                    
                                                <div class="form-check">
                                                    <input 
                                                        type="checkbox" 
                                                        name="regulation[]" 
                                                        id="{{ $id }}" 
                                                        value="{{ $regulation }}" 
                                                        class="form-check-input" 
                                                        {{ in_array($regulation, (array) $selectedRegulation) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="{{ $id }}">{{ $regulation }}</label>
                                                </div>
                                            @endforeach
                                    
                                            @error('regulation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="regulated_jurisdictions">Regulated Jurisdictions</label>
                                            <textarea name="regulated_jurisdictions" id="regulated_jurisdictions" class="form-control snote" rows="4">{{ old('regulated_jurisdictions', $broker->regulated_jurisdictions) }}</textarea>
                                            @error('regulated_jurisdictions')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="regulatory_licenses">Regulatory Licenses</label>
                                            <textarea name="regulatory_licenses" id="regulatory_licenses" class="form-control snote" rows="4">{{ old('regulatory_licenses', $broker->regulatory_licenses) }}</textarea>
                                            @error('regulatory_licenses')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div class="card">
                        <div class="card-header" id="headingFinancial">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFinancial" aria-expanded="false" aria-controls="collapseFinancial">
                                    Financial Details
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFinancial" class="collapse" aria-labelledby="headingFinancial" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="deposit_methods">Deposit Methods</label>
                                            <textarea name="deposit_methods" id="deposit_methods" class="form-control snote" rows="4">{{ old('deposit_methods', $broker->deposit_methods) }}</textarea>
                                            @error('deposit_methods')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="withdrawal_method">Withdrawal Methods</label>
                                            <textarea name="withdrawal_method" id="withdrawal_method" class="form-control snote" rows="4">{{ old('withdrawal_method', $broker->withdrawal_method) }}</textarea>
                                            @error('withdrawal_method')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_methods">Payment Methods</label>
                                            <textarea name="payment_methods" id="payment_methods" class="form-control snote" rows="4">{{ old('payment_methods', $broker->payment_methods) }}</textarea>
                                            @error('payment_methods')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="capitalization">Capitalization</label>
                                            <textarea name="capitalization" id="capitalization" class="form-control snote" rows="4">{{ old('capitalization', $broker->capitalization) }}</textarea>
                                            @error('capitalization')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="insurance">Insurance</label>
                                            <textarea name="insurance" id="insurance" class="form-control snote" rows="4">{{ old('insurance', $broker->insurance) }}</textarea>
                                            @error('insurance')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features and Services -->
                    <div class="card">
                        <div class="card-header" id="headingFeatures">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFeatures" aria-expanded="false" aria-controls="collapseFeatures">
                                    Features and Services
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFeatures" class="collapse" aria-labelledby="headingFeatures" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="languages">Languages</label>
                                            <textarea name="languages" id="languages" class="form-control snote" rows="4">{{ old('languages', $broker->languages) }}</textarea>
                                            @error('languages')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pricing">Pricing</label>
                                            <textarea name="pricing" id="pricing" class="form-control snote" rows="4">{{ old('pricing', $broker->pricing) }}</textarea>
                                            @error('pricing')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_support">Customer Support</label>
                                            <textarea name="customer_support" id="customer_support" class="form-control snote" rows="4">{{ old('customer_support', $broker->customer_support) }}</textarea>
                                            @error('customer_support')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="educational_resources">Educational Resources</label>
                                            <textarea name="educational_resources" id="educational_resources" class="form-control snote" rows="4">{{ old('educational_resources', $broker->educational_resources) }}</textarea>
                                            @error('educational_resources')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="research_tools">Research Tools</label>
                                            <textarea name="research_tools" id="research_tools" class="form-control snote" rows="4">{{ old('research_tools', $broker->research_tools) }}</textarea>
                                            @error('research_tools')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="charting_tools">Charting Tools</label>
                                            <textarea name="charting_tools" id="charting_tools" class="form-control snote" rows="4">{{ old('charting_tools', $broker->charting_tools) }}</textarea>
                                            @error('charting_tools')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="news_and_analysis">News and Analysis</label>
                                            <textarea name="news_and_analysis" id="news_and_analysis" class="form-control snote" rows="4">{{ old('news_and_analysis', $broker->news_and_analysis) }}</textarea>
                                            @error('news_and_analysis')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="segregation_of_funds">Segregation of Funds</label>
                                            <select name="segregation_of_funds" id="segregation_of_funds" class="form-control">
                                                <option value="1" {{ old('segregation_of_funds', $broker->segregation_of_funds) == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('segregation_of_funds', $broker->segregation_of_funds) == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('segregation_of_funds')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="account_managers">Account Managers</label>
                                            <select name="account_managers" id="account_managers" class="form-control">
                                                <option value="1" {{ old('account_managers', $broker->account_managers) == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('account_managers', $broker->account_managers) == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('account_managers')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="economic_calendar">Economic Calendar</label>
                                            <select name="economic_calendar" id="economic_calendar" class="form-control">
                                                <option value="1" {{ old('economic_calendar', $broker->economic_calendar) == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('economic_calendar', $broker->economic_calendar) == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('economic_calendar')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="vps_hosting">VPS Hosting</label>
                                            <select name="vps_hosting" id="vps_hosting" class="form-control">
                                                <option value="1" {{ old('vps_hosting', $broker->vps_hosting) == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('vps_hosting', $broker->vps_hosting) == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('vps_hosting')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Types -->
                    <div class="card">
                        <div class="card-header" id="headingAccountTypes">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseAccountTypes"
                                    aria-expanded="false"
                                    aria-controls="collapseAccountTypes">
                                    Account Types
                                </button>
                            </h5>
                        </div>

                        <div id="collapseAccountTypes" class="collapse"
                            aria-labelledby="headingAccountTypes"
                            data-parent="#accordion">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        @php
                                            $selectedAccountTypes = [];

                                            if (!empty($broker->account_types)) {
                                                $selectedAccountTypes = is_array($broker->account_types)
                                                    ? $broker->account_types
                                                    : (json_decode($broker->account_types, true) ?? []);
                                            }

                                            $allAccountTypes = [
                                                'low-spread-brokers' => 'Low Spread Brokers',
                                                'free-withdrawal-brokers' => 'Free Withdrawal Brokers',
                                                'mt4-brokers' => 'MT4 Brokers',
                                                'mt5-brokers' => 'MT5 Brokers',
                                                'micro-account-brokers' => 'Micro Account Brokers',
                                                'copy-trading-brokers' => 'Copy Trading Brokers',
                                                'social-trading-brokers' => 'Social Trading Brokers',
                                                'scalping-brokers' => 'Scalping Brokers',
                                                'trading-apps-brokers' => 'Trading Apps Brokers',
                                                'beginner-friendly-brokers' => 'Beginner Friendly Brokers',
                                            ];
                                        @endphp

                                        <div class="form-group">
                                            @foreach($allAccountTypes as $slug => $label)
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="account_type_{{ $slug }}"
                                                        name="account_types[]"
                                                        value="{{ $slug }}"
                                                        {{ in_array($slug, $selectedAccountTypes) ? 'checked' : '' }}
                                                    >

                                                    <label class="form-check-label" for="account_type_{{ $slug }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @php
                        // Default selected values (edit + old input fallback)
                        $selectedCountries = old('associated_countries', []);

                        if (isset($broker)) {
                            $decoded = json_decode($broker->associated_countries, true);
                            $selectedCountries = is_array($decoded) ? $decoded : [];
                        }

                        $countries = [
                            'united-kingdom' => 'United Kingdom',
                            'india' => 'India',
                            'bangladesh' => 'Bangladesh',
                            'singapore' => 'Singapore',
                            'malaysia' => 'Malaysia',
                            'south-africa' => 'South Africa',
                            'nigeria' => 'Nigeria',
                        ];
                    @endphp

                    <!-- Associated Countries -->
                    <div class="card">
                        <div class="card-header" id="headingCountries">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseCountries"
                                    aria-expanded="false"
                                    aria-controls="collapseCountries">
                                    Associated Countries
                                </button>
                            </h5>
                        </div>

                        <div id="collapseCountries" class="collapse"
                            aria-labelledby="headingCountries"
                            data-parent="#accordion">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">

                                            @foreach($countries as $slug => $label)
                                                @php
                                                    $id = 'country_' . $slug;
                                                @endphp

                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        name="associated_countries[]"
                                                        id="{{ $id }}"
                                                        value="{{ $slug }}"
                                                        class="form-check-input"
                                                        {{ in_array($slug, $selectedCountries) ? 'checked' : '' }}
                                                    >

                                                    <label class="form-check-label" for="{{ $id }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            @endforeach

                                            @error('associated_countries')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>



                    <!-- SEO and Promotion -->
                    <div class="card">
                        <div class="card-header" id="headingSEO">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSEO" aria-expanded="false" aria-controls="collapseSEO">
                                    SEO and Promotion
                                </button>
                            </h5>
                        </div>
                        <div id="collapseSEO" class="collapse" aria-labelledby="headingSEO" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $broker->meta_title ?? '') }}">
                                            @error('meta_title')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="meta_keyword">Meta Keyword</label>
                                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control" value="{{ old('meta_keyword', $broker->meta_keyword ?? '') }}">
                                            @error('meta_keyword')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" id="meta_description" class="form-control snote" rows="4">{{ old('meta_description', $broker->meta_description ?? '') }}</textarea>
                                            @error('meta_description')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="top_feature">Top Feature</label>
                                            <textarea name="top_feature" id="top_feature" class="form-control snote" rows="4">{{ old('top_feature', $broker->top_feature ?? '') }}</textarea>
                                            @error('top_feature')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="featured_broker">Featured Broker</label>
                                            <div class="form-check">
                                                <input type="hidden" name="featured_broker" value="0">
                                                <input type="checkbox" name="featured_broker" id="featured_broker" class="form-check-input" value="1" {{ old('featured_broker', $broker->featured_broker) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="featured_broker">Check if this broker is featured</label>
                                            </div>
                                            @error('featured_broker')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="top_broker">Top Broker Number</label>
                                            <input type="number" name="top_broker" id="top_broker" class="form-control" value="{{ old('top_broker', $broker->top_broker ?? '') }}">
                                            @error('top_broker')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Update Broker</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
$(document).ready(function() {
    // Logo Preview
    $('#logo').change(function() {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#logo-preview').attr('src', e.target.result);
            $('#logo-preview-container').show();
        };
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        } else {
            $('#logo-preview-container').hide();
        }
    });

    // Banner 1 Preview
    $('#banner_1').change(function() {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#banner1-preview').attr('src', e.target.result);
            $('#banner1-preview-container').show();
        };
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        } else {
            $('#banner1-preview-container').hide();
        }
    });

    // Banner 2 Preview
    $('#banner_2').change(function() {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#banner2-preview').attr('src', e.target.result);
            $('#banner2-preview-container').show();
        };
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        } else {
            $('#banner2-preview-container').hide();
        }
    });
});
</script>
@endsection