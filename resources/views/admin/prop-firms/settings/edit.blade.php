@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Prop Firm Settings')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Prop firms</p>
                <h1 class="ab-header__title">Settings</h1>
                <p class="ab-header__sub">Default sort and which modules appear on public firm pages.</p>
            </div>
        </header>

        @include('admin.prop-firms._nav', ['active' => 'settings'])

        @if($errors->any())
            <div class="ab-banner" role="alert">
                <h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3>
                <ol>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ol>
            </div>
        @endif

        <form action="{{ route('admin_prop_firm_settings_update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="ab-form">
                <section class="ab-section" id="content">
                    <div class="ab-section__head">
                        <h2>Module settings</h2>
                        <p>These options apply to every public prop firm page.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="form-group">
                            <label for="default_sort_order">Default sort order</label>
                            <select name="default_sort_order" id="default_sort_order" class="form-control">
                                @foreach(['sort_order' => 'Manual sort order', 'name' => 'Name', 'trust_score' => 'Trust score', 'overall_rating' => 'Overall rating', 'created_at' => 'Created date'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('default_sort_order', $settings->get('default_sort_order')) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="enable_reviews" value="0">
                                <input type="checkbox" class="custom-control-input" id="enable_reviews" name="enable_reviews" value="1" @checked(old('enable_reviews', $settings->get('enable_reviews', true)))>
                                <label class="custom-control-label" for="enable_reviews">Enable reviews</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="enable_faqs" value="0">
                                <input type="checkbox" class="custom-control-input" id="enable_faqs" name="enable_faqs" value="1" @checked(old('enable_faqs', $settings->get('enable_faqs', true)))>
                                <label class="custom-control-label" for="enable_faqs">Enable FAQs</label>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="enable_programs" value="0">
                                <input type="checkbox" class="custom-control-input" id="enable_programs" name="enable_programs" value="1" @checked(old('enable_programs', $settings->get('enable_programs', true)))>
                                <label class="custom-control-label" for="enable_programs">Enable programs</label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary"><i class="fas fa-save" aria-hidden="true"></i> Save settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
