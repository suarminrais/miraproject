<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rtarif as tarif;

class rtarif extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return tarif::latest()->paginate(7);
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
        $this->validate($request,[
            'tarif' => 'required',
            'nama' => 'required',
            'nokamar' => 'required',
            'nott' => 'required',
            'kelas' => 'required',
            'kategori' => 'required'
        ]);

        return tarif::create([
            'tarif' => $request['tarif'],
            'nama' => $request['nama'],
            'nokamar' => $request['nokamar'],
            'nott' => $request['nott'],
            'kelas' => $request['kelas'],
            'kategori' => $request['kategori'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return tarif::findOrFail($id);
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
        $this->validate($request,[
            'tarif' => 'required',
            'nama' => 'required',
            'nokamar' => 'required',
            'nott' => 'required',
            'kelas' => 'required',
            'kategori' => 'required'
        ]);

        $data = tarif::findOrFail($id);
        $data->update($request->all());

        return [
            'message' => 'sukses'
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
        $data = tarif::findOrFail($id);
        $data->delete();

        return [
            'message' => 'sukses'
        ];
    }
}
