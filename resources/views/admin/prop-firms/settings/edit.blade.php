@extends('admin.layout.app')
@section('heading', 'Prop Firm Settings')
@section('main_content')
<div class="section-body"><div class="card shadow"><div class="card-body">
<form action="{{ route('admin_prop_firm_settings_update') }}" method="POST">@csrf @method('PUT')
<div class="form-group"><label>Default Sort Order</label>
<select name="default_sort_order" class="form-control">
@foreach(['sort_order' => 'Manual Sort Order', 'name' => 'Name', 'trust_score' => 'Trust Score', 'overall_rating' => 'Overall Rating', 'created_at' => 'Created Date'] as $value => $label)
<option value="{{ $value }}" @selected(old('default_sort_order', $settings->get('default_sort_order')) === $value)>{{ $label }}</option>
@endforeach
</select></div>
<div class="form-group"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="enable_reviews" name="enable_reviews" value="1" @checked(old('enable_reviews', $settings->get('enable_reviews', true)))><label class="custom-control-label" for="enable_reviews">Enable Reviews</label></div></div>
<div class="form-group"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="enable_faqs" name="enable_faqs" value="1" @checked(old('enable_faqs', $settings->get('enable_faqs', true)))><label class="custom-control-label" for="enable_faqs">Enable FAQs</label></div></div>
<div class="form-group"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="enable_programs" name="enable_programs" value="1" @checked(old('enable_programs', $settings->get('enable_programs', true)))><label class="custom-control-label" for="enable_programs">Enable Programs</label></div></div>
<button type="submit" class="btn btn-primary">Save Settings</button>
</form></div></div></div>
@endsection
