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



<div class="sidebar">

    <div class="widget s_w_c">
      <h2 class="add_heading">Octa Deposit Bonus</h2>
        @foreach($global_sidebar_top_ad as $row)
            <div class="ad-sidebar">
                @if($row->sidebar_ad_url == '')
                    <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="">
                @else
                    <a href="{{ $row->sidebar_ad_url }}"><img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt=""></a>
                @endif
            </div>
        @endforeach
    </div>
    <div class="widget s_w_c">
        <div class="news">
            <div class="news-heading">
                <h2>{{ POPULAR_RECENT_NEWS }}</h2>
            </div>           

            <ul class="nav nav-pills h_c_b_nav" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{ RECENT_NEWS }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">{{ POPULAR_NEWS }}</button>
                    </li>
                </ul>
                <div class="widget">
                    <div class="news">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                @php
                                    $recent_news_data = \App\Models\Post::with('rSubCategory')
                                        ->where('language_id', $current_language_id)
                                        ->latest()
                                        ->take(4)
                                        ->get();
                                @endphp
                                @foreach($recent_news_data as $item)
                                    @php
                                        $user_data = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : \App\Models\Author::find($item->author_id);
                                        $updated_date = $item->updated_at->format('d F, Y');
                                    @endphp
                                    <div class="side_bar_news_item">
                                        <div class="left">
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="">
                                           
                                        </div>
                                        <div class="right">                                      
                                                <a class="s_c_tilte" href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                    {{ Str::limit($item->post_title, 30) }}
                                                </a>                                    
                                            <div class="date_user_side_bar">
                                                <div class="user"><a class="s_s_d" href="javascript:void;">{{ $user_data->name }}</a></div>
                                                <div class="date"><a class="s_s_d" href="javascript:void;">{{ $updated_date }}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                @php
                                    $popular_news_data = \App\Models\Post::with('rSubCategory')
                                        ->where('language_id', $current_language_id)
                                        ->orderBy('visitors', 'desc')
                                        ->take(4)
                                        ->get();
                                @endphp
                                @foreach($popular_news_data as $item)
                                    @php
                                        $user_data = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : \App\Models\Author::find($item->author_id);
                                        $updated_date = $item->updated_at->format('d F, Y');
                                    @endphp
                                    <div class="side_bar_news_item">
                                        <div class="left">
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="">
                                          
                                        </div>
                                        <div class="right">
                                                <a class="s_c_tilte" href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                    {{ Str::limit($item->post_title, 30) }}
                                                </a>   

                                            <div class="date_user_side_bar">
                                                <div class="user"><a class="s_s_d" href="javascript:void;">{{ $user_data->name }}</a></div>
                                                <div class="date"><a class="s_s_d" href="javascript:void;">{{ $updated_date }}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
        
        </div>
    </div>
    <div class="widget s_w_c">
        <div class="archive-heading">
            <h2>{{ ARCHIVE }}</h2>
        </div>
        <div class="archive">
            @php
                $archive_array = [];
                $all_post_data = \App\Models\Post::orderBy('id', 'desc')->get();
                foreach($all_post_data as $row) {
                    $ts = strtotime($row->created_at);
                    $month = date('m', $ts);
                    $month_full = date('F', $ts);
                    $year = date('Y', $ts);
                    $archive_array[] = $month . '-' . $month_full . '-' . $year;
                }
                $archive_array = array_values(array_unique($archive_array));
            @endphp
            <form action="{{ route('archive_show') }}" method="post" class="custom-archive-form">
                @csrf
                <div class="custom-select-container">
                    <select name="archive_month_year" class="custom-select" onChange="this.form.submit()">
                        <option value="">{{ SELECT_MONTH }}</option>
                        @for($i = 0; $i < count($archive_array); $i++)
                            @php
                                $temp_arr = explode('-', $archive_array[$i]);
                            @endphp
                            <option value="{{ $temp_arr[0] . '-' . $temp_arr[2] }}">
                                {{ $temp_arr[1] }}, {{ $temp_arr[2] }}
                            </option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>

    </div>
    <div class="widget s_w_c">
        @foreach($global_sidebar_bottom_ad as $row)
            <div class="ad-sidebar">
                @if($row->sidebar_ad_url == '')
                    <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="">
                @else
                    <a href="{{ $row->sidebar_ad_url }}"><img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt=""></a>
                @endif
            </div>
        @endforeach
    </div>

</div>