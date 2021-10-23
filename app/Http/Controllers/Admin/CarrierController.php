<?php

namespace App\Http\Controllers\Admin;

use App\Models\Carrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarrierController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = "Carrier";
        $data['carriers'] = Carrier::paginate(10);
        return view('admin.setting.courier.carrier.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create(){
        $data['title'] = "Add Carriers";
        $data['carriers'] = Carrier::all();
        return view('admin.setting.courier.carrier.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){
            $validatedData = Validator::make($request->all(),[
                'carrier_name' => ['required'],
                'km_price' => ['required','numeric'],
                'base_fare' => ['required','numeric'],
                'minimum_fare' => ['required','numeric'],
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            Carrier::_save($request);
            return redirect('/backend/carrier');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data['tittle'] = 'Carrier';
        $data['data'] = Carrier::find($id);
        return view('admin.setting.courier.carrier.edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(),[
            'carrier_name' => ['required'],
            'km_price' => ['required','numeric'],
            'base_fare' => ['required','numeric'],
            'minimum_fare' => ['required','numeric'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        Carrier::_save($request,$id);
        return redirect('/backend/carrier');
    }

    /**
     * @param $id
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $area = Carrier::find($id);
        $area->delete();
        return redirect('/backend/carrier');
    }
}
