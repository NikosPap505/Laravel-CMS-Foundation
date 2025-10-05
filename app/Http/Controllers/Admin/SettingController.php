<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'footer_about_text' => 'nullable|string',
            'copyright_text' => 'nullable|string',
        ];
        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Clear all CMS cache when settings are updated
        clear_cms_cache();

        return redirect()->back()->with('success', 'Settings saved successfully!');
    }
}