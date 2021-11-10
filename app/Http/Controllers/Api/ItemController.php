<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = DB::select("SELECT item.`id`, item.`nama`, 
                            GROUP_CONCAT(JSON_OBJECT('id', pajak.`id`, 'nama', pajak.`nama`, 'rate', pajak.`rate`))AS pajak 
                            FROM item 
                            JOIN pajak ON item.`id` = pajak.`item_id`
                            GROUP BY item.`id`, item.`nama`");
        return res(200, 'success', '', $item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return res(400, 'error', $validator->messages());
        }

        $data = [
            'nama'  => $request->nama,
        ];
        return res(200, 'success', 'berhasil di simpan', Item::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = DB::select("SELECT item.`id`, item.`nama`, 
                            GROUP_CONCAT(JSON_OBJECT('id', pajak.`id`, 'nama', pajak.`nama`, 'rate', pajak.`rate`))AS pajak 
                            FROM item 
                            JOIN pajak ON item.`id` = pajak.`item_id`
                            WHERE item.`id` = ".$id."
                            GROUP BY item.`id`, item.`nama`");

        if($item){
            return res(200, 'success', '', $item);
        }else{
            return res(405, 'error', ['Item' => ['Item tidak di temukan']]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return res(400, 'error', $validator->messages());
        }

        $item = Item::find($id);

        if($item){
            $data = [
                'nama'  => $request->nama,
            ];
            
            return res(200, 'success', 'berhasil di simpan', $item->update($data));
        }else{
            return res(405, 'error', ['Item' => ['Item tidak di temukan']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);

        if($item){
            return res(200, 'success', 'berhasil di hapus', $item->delete());
        }else{
            return res(405, 'error', ['Item' => ['Item tidak di temukan']]);
        }
    }
}
