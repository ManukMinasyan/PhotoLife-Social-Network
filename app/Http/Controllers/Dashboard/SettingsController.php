<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SettingRequest;
use Illuminate\Http\Request;
use Intervention\Image\Image;

class SettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $settings = [
            'site_title' => setting('site_title'),
            'site_description' => setting('site_description'),
            'site_logo' => setting('site_logo'),
            'site_favicon' => setting('site_favicon'),
            'google_analytics_code' => setting('google_analytics_code'),
        ];

        return view('dashboard.settings.index', compact('settings'));
    }

    /**
     * @param SettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingRequest $request)
    {
        $settings = $request->validated();

        foreach ($settings as $key => $value) {
            if ($key === 'site_logo') {
                //Move Uploaded File
                $destinationPath = 'images';
                $value->move($destinationPath, 'logo.png');
            } elseif ($key === 'site_favicon') {
                //Move Uploaded File
                $destinationPath = '';
                $value->move($destinationPath, 'favicon.ico');
            } else {
                setting([$key => $value])->save();
            }
        }

        return redirect()->back()->with('status', 'Settings updated successfully!');
    }
}
