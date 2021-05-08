<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Validator;
use URL;

class SettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('cp.settings.index')->with('title', 'Настройки');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('cp.settings.create_edit')->with('title', 'Добавление параметра');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:settings',
            'value' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Settings::flushCache();

        Settings::create($request->all());

        return redirect(URL::route('cp.settings.index'))->with('success', trans('message.information_successfully_added'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $row = Settings::where('id', $id)->first();

        if (!$row) abort(404);

        return view('cp.settings.create_edit', compact('row'))->with('title', 'Редактирование параметра');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:settings,name,' . $request->id,
            'value' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $row = Settings::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->name;
        $row->description = $request->description;
        $row->value = $request->value;
        $row->save();

        Settings::flushCache();

        return redirect(URL::route('cp.settings.index'))->with('success', trans('message.data_updated'));
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        Settings::flushCache();

        Settings::where('id', $id)->delete();
    }
}
