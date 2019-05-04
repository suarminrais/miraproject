<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Paramedis;

class ParamedisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Paramedis::latest()->paginate(7);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'spesialis' => 'required',
            'telp' => 'required',
            'status' => 'required'
        ]);

        return Paramedis::create([
            'name' => $request->name,
            'spesialis' => $request->spesialis,
            'telp' => $request->telp,
            'status' => $request->status,
        ]);
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
        $this->validate($request, [
            'name' => 'required',
            'spesialis' => 'required',
            'telp' => 'required',
            'status' => 'required'
        ]);

        $data = Paramedis::findOrFail($id);
        $data->update($request->all());
        
        return [
            "message" => "sukses"
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = findOrFail($id);
        $data->delete();

        return [
            'message' => 'sukses'
        ];
    }
}
