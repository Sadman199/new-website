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
$lang = \App\Models\Language::where('short_name',$current_short_name)->first();
$current_language_id = $lang ? $lang->id : 1;
@endphp

<div class="web_menu relative z-50" data-bc-persist="site-nav">
  @include('front.layout.partial.navbar')
</div>