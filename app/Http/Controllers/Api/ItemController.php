<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Pajak;
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
        $jumlah_pajak = count($request->pajak);
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'pajak' => 'required|array|min:2',
            'pajak.*' => 'required|string|distinct|min:2',
            'rate' => 'required|array|min:2|size:'.$jumlah_pajak,
            'rate.*' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return res(400, 'error', $validator->messages());
        }

        $data = [
            'nama'  => $request->nama,
        ];
        
        foreach($request->pajak as $key => $item_pajak){
            $pajak[] = new Pajak([
                'nama'      => $item_pajak,
                'rate'      => $request->rate[$key]
            ]);
        }
        $item = Item::create($data);
        $item->pajak()->saveMany($pajak);

        return res(200, 'success', 'berhasil di simpan', true);
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
        $jumlah_pajak = count($request->pajak);
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'pajak' => 'required|array|min:2',
            'pajak.*' => 'required|string|distinct|min:2',
            'rate' => 'required|array|min:2|size:'.$jumlah_pajak,
            'rate.*' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return res(400, 'error', $validator->messages());
        }

        $item = Item::find($id);

        if($item){
            $data = [
                'nama'  => $request->nama,
            ];

            foreach($request->pajak as $key => $item_pajak){
                $pajak[] = new Pajak([
                    'nama'      => $item_pajak,
                    'rate'      => $request->rate[$key]
                ]);
            }
            
            $item->update($data);
            $item->pajak()->delete(); 
            $item->pajak()->saveMany($pajak);

            return res(200, 'success', 'berhasil di simpan', true);
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
