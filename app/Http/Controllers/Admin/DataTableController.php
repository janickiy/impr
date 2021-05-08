<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Admin, User, Settings};
use Illuminate\Support\Facades\Auth;
use DataTables;
use URL;

class DataTableController extends Controller
{

    /**
     * @return mixed
     */
    public function getUsers()
    {
        $row = User::query();

        return Datatables::of($row)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="check" value="' . $row->id . '" name="status[]">';
            })

            ->rawColumns(['checkbox'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        $row = Admin::query();

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.admin.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';

                if ($row->id != Auth::id())
                    $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';
                else
                    $deleteBtn = '';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $row = Settings::query()->dontRemember();

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.settings.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])->make(true);
    }

}
