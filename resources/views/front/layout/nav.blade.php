@if(!session()->get('session_short_name'))
@php
$current_short_name = $global_short_name;
@endphp
@else
@php
$current_short_name = session()->get('session_short_name');
@endphp
@endif
@php
$current_language_id = \App\Models\Language::where('short_name',$current_short_name)->first()->id;
@endphp

<div class="web_menu">
  @include('front.layout.partial.navbar')
</div>