<?php

namespace App\Http\Controllers\Api;

use App\Models\Pajak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pajak = Pajak::all();
        return res(200, 'success', '', $pajak);
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
            'item_id' => 'required|numeric:|exists:item,id',
            'nama' => 'required|max:255',
            'rate' => 'required|numeric|between:0,99.99',
        ]);

        if ($validator->fails()) {
            return res(400, 'error', $validator->messages());
        }

        $data = [
            'item_id'  => $request->item_id,
            'nama'  => $request->nama,
            'rate'  => $request->rate,
        ];
        return res(200, 'success', 'berhasil di simpan', Pajak::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pajak = Pajak::find($id)->with('pajak');

        if($pajak){
            return res(200, 'success', '', $pajak);
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
            'item_id' => 'required|numeric:|exists:item,id',
            'nama' => 'required|max:255',
            'rate' => 'required|numeric|between:0,99.99',
        ]);

        if ($validator->fails()) {
            return res(400, 'error', $validator->messages());
        }

        $pajak = Pajak::find($id);

        if($pajak){
            $data = [
                'item_id'  => $request->item_id,
                'nama'  => $request->nama,
                'rate'  => $request->rate,
            ];
            
            return res(200, 'success', 'berhasil di simpan', $pajak->update($data));
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
        $pajak = Pajak::find($id);

        if($pajak){
            return res(200, 'success', 'berhasil di hapus', $pajak->delete());
        }else{
            return res(405, 'error', ['Item' => ['Item tidak di temukan']]);
        }
    }
}
