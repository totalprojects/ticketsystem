<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MenuMaster;

class MenuController extends Controller {
    //
    /**
     * @return view of users
     */
    public function index() {

        $menus = MenuMaster::orderBy('menu_order', 'asc')->get();
        //return $menus;
        return view('menus.index')->with(['menus' => $menus]);
    }

    public function fetchMenus() {
        $menus      = MenuMaster::orderBy('menu_order', 'asc')->where('parent_id', 0)->get();
        $totalCount = count($menus);
        return response(['data' => $menus, 'totalCount' => $totalCount]);
    }

    public function addMenu(Request $request) {
        $menu_name        = $request->menu_name;
        $menu_slug        = $request->menu_slug ?? '';
        $menu_icon        = $request->menu_icon;
        $parent_id        = $request->parent_id ?? 0;
        $latest_order     = MenuMaster::orderBy('menu_order', 'desc')->select('menu_order')->get();
        $latest_order     = $latest_order[0]->menu_order;
        $latest_order_inc = $latest_order + 1;
        $status           = $request->status;

        $checkDup = MenuMaster::where('menu_name', 'like', $menu_name . '%')->get();
        if ($checkDup->Count() == 0) {
            $data = [
                'menu_name'  => $menu_name,
                'menu_slug'  => $menu_slug,
                'icon'       => $menu_icon,
                'menu_order' => $latest_order_inc,
                'parent_id'  => $parent_id,
                'status'     => $status
            ];
            MenuMaster::create($data);
        }

        return response(['message' => 'Success', 'status' => 200], 200);
    }

    public function reorderMenu(Request $request) {

        $draggedItemId = $request->draggedItem;
        $shiftedItemId = $request->shiftedItem;

        $draggedItemOrder = $request->draggedItemOrder;
        $shiftedItemOrder = $request->shiftedItemOrder;

        $tweek_1 = MenuMaster::where('id', $draggedItemId)->update(['menu_order' => $shiftedItemOrder]);
        # update child if parent of dragged item found
        $childs_1 = MenuMaster::where('parent_id', $draggedItemId)->update([
            'menu_order' => $shiftedItemOrder
        ]);

        $tweek_1 = MenuMaster::where('id', $shiftedItemId)->update(['menu_order' => $draggedItemOrder]);

        $childs_2 = MenuMaster::where('parent_id', $shiftedItemId)->update([
            'menu_order' => $draggedItemOrder
        ]);

        return 1;

    }

    public function updateMenu(Request $request) {

        //return $request->all();
        $menu_id   = $request->emenu_id;
        $menu_name = $request->menu_name;
        $menu_slug = $request->menu_slug;
        $menu_icon = $request->menu_icon;
        $parent_id = $request->parent_id1 ?? 0;
        $status    = $request->estatus;

        $updateArray = [
            'menu_name' => $menu_name,
            'menu_slug' => $menu_slug,
            'icon'      => $menu_icon,
            'parent_id' => $parent_id,
            'status'    => $status
        ];

        try {
            $update = MenuMaster::where('id', $menu_id)->update($updateArray);

            return response(['message' => 'Success', 'data' => []], 200);

        } catch (\Exception $e) {

            return response(['message' => 'Server Error', 'data' => $e->getMessage()], 500);
        }
    }
}
