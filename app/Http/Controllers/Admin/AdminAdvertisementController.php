<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeAdvertisement;
use App\Models\TopAdvertisement;
use App\Models\SidebarAdvertisement;

class AdminAdvertisementController extends Controller
{
    private function getOrCreateHomeAd(): HomeAdvertisement
    {
        $record = \DB::table('home_advertisements')->where('id', 1)->first();

        if (! $record) {
            \DB::table('home_advertisements')->insert([
                'above_search_ad'        => '',
                'above_search_ad_url'    => null,
                'above_search_ad_status' => 'Hide',
                'above_footer_ad'        => '',
                'above_footer_ad_url'    => null,
                'above_footer_ad_status' => 'Hide',
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);
        }

        return HomeAdvertisement::disableCache()->where('id', 1)->first();
    }

    public function home_ad_show()
    {
        $home_ad_data = $this->getOrCreateHomeAd();
        return view('admin.advertisement_home_view', compact('home_ad_data'));
    }

    public function home_ad_update(Request $request)
    {
        $request->validate([
            'above_search_ad'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'above_footer_ad'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'above_search_ad_url'    => 'nullable|url|max:500',
            'above_footer_ad_url'    => 'nullable|url|max:500',
            'above_search_ad_status' => 'required|in:Show,Hide',
            'above_footer_ad_status' => 'required|in:Show,Hide',
        ]);

        $home_ad_data = $this->getOrCreateHomeAd();

        // Handle the "above_search_ad" file upload
        if ($request->hasFile('above_search_ad')) {
            $old = $home_ad_data->above_search_ad;
            if ($old && file_exists(public_path('uploads/' . $old))) {
                @unlink(public_path('uploads/' . $old));
            }
            $ext        = $request->file('above_search_ad')->extension();
            $final_name = 'above_search_ad_' . time() . '.' . $ext;
            $request->file('above_search_ad')->move(public_path('uploads'), $final_name);
            $home_ad_data->above_search_ad = $final_name;
        }

        // Handle the "above_footer_ad" file upload
        if ($request->hasFile('above_footer_ad')) {
            $old = $home_ad_data->above_footer_ad;
            if ($old && file_exists(public_path('uploads/' . $old))) {
                @unlink(public_path('uploads/' . $old));
            }
            $ext        = $request->file('above_footer_ad')->extension();
            $final_name = 'above_footer_ad_' . time() . '.' . $ext;
            $request->file('above_footer_ad')->move(public_path('uploads'), $final_name);
            $home_ad_data->above_footer_ad = $final_name;
        }

        // Always update the non-file fields directly via DB to bypass caching
        \DB::table('home_advertisements')->where('id', 1)->update([
            'above_search_ad'        => $home_ad_data->above_search_ad,
            'above_search_ad_url'    => $request->above_search_ad_url,
            'above_search_ad_status' => $request->above_search_ad_status,
            'above_footer_ad'        => $home_ad_data->above_footer_ad,
            'above_footer_ad_url'    => $request->above_footer_ad_url,
            'above_footer_ad_status' => $request->above_footer_ad_status,
            'updated_at'             => now(),
        ]);

        return redirect()->back()->with('success', 'Advertisements updated successfully.');
    }



    private function getOrCreateTopAd(): TopAdvertisement
    {
        $record = \DB::table('top_advertisements')->where('id', 1)->first();

        if (! $record) {
            \DB::table('top_advertisements')->insert([
                'top_ad' => '',
                'top_ad_url' => null,
                'top_ad_status' => 'Hide',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return TopAdvertisement::disableCache()->where('id', 1)->firstOrFail();
    }

    public function top_ad_show()
    {
        $top_ad_data = $this->getOrCreateTopAd();

        return view('admin.advertisement_top_view', compact('top_ad_data'));
    }

    public function top_ad_update(Request $request)
    {
        $request->validate([
            'top_ad' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'top_ad_url' => 'nullable|url|max:500',
            'top_ad_status' => 'required|in:Show,Hide',
        ]);

        $top_ad_data = $this->getOrCreateTopAd();
        $imageName = $top_ad_data->top_ad ?? '';

        if ($request->hasFile('top_ad')) {
            if ($imageName && file_exists(public_path('uploads/' . $imageName))) {
                @unlink(public_path('uploads/' . $imageName));
            }

            $ext = $request->file('top_ad')->extension();
            $imageName = 'top_ad_' . time() . '.' . $ext;
            $request->file('top_ad')->move(public_path('uploads'), $imageName);
        }

        \DB::table('top_advertisements')->where('id', 1)->update([
            'top_ad' => $imageName,
            'top_ad_url' => $request->top_ad_url,
            'top_ad_status' => $request->top_ad_status,
            'updated_at' => now(),
        ]);

        \App\Services\GlobalViewDataService::flush();

        return redirect()->back()->with('success', 'Top advertisement updated successfully.');
    }



    public function sidebar_ad_show()
    {
        $sidebar_ad_data = SidebarAdvertisement::query()->orderByDesc('id')->get();
        return view('admin.advertisement_sidebar_view', compact('sidebar_ad_data'));
    }

    public function sidebar_ad_create()
    {
        return view('admin.advertisement_sidebar_create');
    }

   public function sidebar_ad_store(Request $request)
    {
        $request->validate([
            'sidebar_ad' => 'required|image|mimes:jpg,jpeg,png,gif'
        ],[],[
            'sidebar_ad' => 'Advertisement'
        ]);
    
        // Handle the new image upload
        $ext = $request->file('sidebar_ad')->extension();
        $final_name = 'sidebar_ad_' . time() . '.' . $ext;
        $request->file('sidebar_ad')->move(public_path('uploads'), $final_name);
    
        $sidebar_ad_data = new SidebarAdvertisement();
        $sidebar_ad_data->sidebar_ad = $final_name;
        $sidebar_ad_data->sidebar_ad_url = $request->sidebar_ad_url;
        $sidebar_ad_data->sidebar_ad_location = $request->sidebar_ad_location;
        $sidebar_ad_data->save();
    
        return redirect()->route('admin_sidebar_ad_show')->with('success', 'Data is created successfully.');
    }


    public function sidebar_ad_edit($id)
    {
        $sidebar_ad_data = SidebarAdvertisement::where('id',$id)->first();

        return view('admin.advertisement_sidebar_edit', compact('sidebar_ad_data'));
    }


   public function sidebar_ad_update(Request $request, $id)
    {
        $sidebar_ad_data = SidebarAdvertisement::where('id', $id)->first();
    
        if ($request->hasFile('sidebar_ad')) {
            $request->validate([
                'sidebar_ad' => 'image|mimes:jpg,jpeg,png,gif'
            ]);
    
            // Check if the old image exists and delete it using $_SERVER['DOCUMENT_ROOT']
            $oldImagePath = public_path('uploads/' . $sidebar_ad_data->sidebar_ad);
            if (is_file($oldImagePath)) {
                unlink($oldImagePath);
            }

            $ext = $request->file('sidebar_ad')->extension();
            $final_name = 'sidebar_ad_' . time() . '.' . $ext;
            $request->file('sidebar_ad')->move(public_path('uploads'), $final_name);
    
            // Update the image name in the database (no need to prepend 'uploads/' again)
            $sidebar_ad_data->sidebar_ad = $final_name;
        }
    
        // Update other fields
        $sidebar_ad_data->sidebar_ad_url = $request->sidebar_ad_url;
        $sidebar_ad_data->sidebar_ad_location = $request->sidebar_ad_location;
        $sidebar_ad_data->update();
    
        return redirect()->route('admin_sidebar_ad_show')->with('success', 'Data is updated successfully.');
    }


    
public function sidebar_ad_delete($id)
{
    $sidebar_ad_data = SidebarAdvertisement::findOrFail($id);

    $filePath = public_path('uploads/' . $sidebar_ad_data->sidebar_ad);

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $sidebar_ad_data->delete();

    return redirect()->route('admin_sidebar_ad_show')->with('success', 'Data is deleted successfully.');
}


}
