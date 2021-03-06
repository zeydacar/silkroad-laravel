<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\SiteSettings;
use Illuminate\Http\Request;
use Validator;

class SiteSettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.settings.index', [
            'settings' => SiteSettings::first()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'sro_silk_name' => 'required|min:1|max:16',
            'discord_id' => 'required|numeric',
            'sro_content_id' => 'required|numeric',
            'sro_max_server' => 'required|numeric',
            'sro_cap' => 'required|numeric',
            'sro_exp' => 'required|numeric',
            'sro_exp_gold' => 'required|numeric',
            'sro_exp_drop' => 'required|numeric',
            'sro_exp_job' => 'required|numeric',
            'sro_exp_party' => 'required|numeric',
            'sro_ip_limit' => 'required|numeric',
            'sro_hwid_limit' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator);
        }

        $requestToJsonArray = [
            'sro_silk_name' => $request->get('sro_silk_name') ?? 'Silk',
            'discord_id' => $request->get('discord_id') ?? '674395399011827712',
            'registration_close' => $request->get('registration_close') ? true : false,
            'jangan_fortress' => $request->get('jangan_fortress') ? true : false,
            'bandit_fortress' => $request->get('bandit_fortress') ? true : false,
            'hotan_fortress' => $request->get('hotan_fortress') ? true : false,
            'sro_content_id' => $request->get('sro_content_id') ?? '22',
            'sro_max_server' => $request->get('sro_max_server') ?? '1000',
            'sro_cap' => $request->get('sro_cap') ?? '110',
            'sro_exp' => $request->get('sro_exp') ?? '1',
            'sro_exp_gold' => $request->get('sro_exp_gold') ?? '1',
            'sro_exp_drop' => $request->get('sro_exp_drop') ?? '1',
            'sro_exp_job' => $request->get('sro_exp_job') ?? '1',
            'sro_exp_party' => $request->get('sro_exp_party') ?? '1',
            'sro_ip_limit' => $request->get('sro_ip_limit') ?? '1',
            'sro_hwid_limit' => $request->get('sro_hwid_limit') ?? '1',
        ];

        $siteSettings = SiteSettings::first();
        if (empty($siteSettings)) {
            SiteSettings::create([
                'settings' => $requestToJsonArray
            ]);
        } else {
            $siteSettings->settings = $requestToJsonArray;
            $siteSettings->save();
        }

        \Cache::forget('siegeFortress');

        return back()->with('success', trans('backend/notification.form-submit.success'));
    }
}
