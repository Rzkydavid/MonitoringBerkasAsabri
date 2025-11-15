<?php 

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ViewMenu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(ViewMenu::query())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary edit" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $parents = Menu::orderBy('order')->get();
        return view('menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'required',
            'order' => 'numeric',
        ]);

        Menu::create($request->all());

        return redirect()->route('menu.index')->with('success', 'Menu created');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::where('id', '!=', $menu->id)->get();
        return view('menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'required',
            'order' => 'numeric',
        ]);

        $menu->update($request->all());

        return redirect()->route('menu.index')->with('success', 'Menu updated');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu deleted');
    }
}
