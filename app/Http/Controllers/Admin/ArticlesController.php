<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProduct;
use App\Http\Requests;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\Session;
use Request;

class ArticlesController extends Controller
{
    /**
     * Ecommerce-CMS
     *
     * Copyright (C) 2014 - 2015  Tihomir Blazhev.
     *
     * LICENSE
     *
     * Ecommerce-cms is released with dual licensing, using the GPL v3 (license-gpl3.txt) and the MIT license (license-mit.txt).
     * You don't have to do anything special to choose one license or the other and you don't have to notify anyone which license you are using.
     * Please see the corresponding license file for details of these licenses.
     * You are free to use, modify and distribute this software, but all copyright information must remain.
     *
     * @package     ecommerce-cms
     * @copyright   Copyright (c) 2014 through 2015, Tihomir Blazhev
     * @license     http://opensource.org/licenses/MIT  MIT License
     * @version     1.0.0
     * @author      Tihomir Blazhev <raylight75@gmail.com>
     */

    /**
     *
     * Product Class
     *
     * @package ecommerce-cms
     * @category Base Class
     * @author Tihomir Blazhev <raylight75@gmail.com>
     * @link https://raylight75@bitbucket.org/raylight75/ecommerce-cms.git
     */

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $products = Product::with('brands', 'size','category')->get();
        $products = Product::paginate(10);
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $data['checkbox'] = Size::all();
        $data['products'] = Product::with('brands', 'size','category')->get();
        return view('admin.product.create', $data);
    }

    /**
     * Store items in database.
     *
     * @param CreateProduct $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateProduct $request)
    {
        $data = $this->proccesData($request);
        $product = Product::create($data);
        $product->size()->attach($data['size_id']);
        Session::flash('flash_message', 'Product successfully added!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::with('brands', 'size','category')->find($id);
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */

    public function edit($id)
    {
        $data['checkbox'] = Size::all();
        $data['product'] = Product::with('brands', 'size','category')->find($id);
        return view('admin.product.edit', $data);
    }

    /**
     * Update the specified products.
     *
     * @param $id
     * @param CreateProduct $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, CreateProduct $request)
    {
        $data = $this->proccesData($request);
        $product = Product::find($id);
        $product->update($data);
        $product->size()->sync($data['size_id']);
        Session::flash('flash_message', 'Product successfully updated!');
        return redirect()->back();
    }

    /**
     * Delete the specified products.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id)->delete();
        //Without database OnDdelete Cascade
        //$product->size()->detach($id);
        Session::flash('flash_message', 'Product successfully deleted!');
        return redirect()->back();
    }

    /**
     * Process uploaded images and request data.
     *
     * @param $request
     * @return mixed
     */
    public function proccesData($request)
    {
        $data = $request->except('a_img','size', 'image1', 'image2', 'image3','image4','image5');
        $data['size_id'] = $request->input('size');
        if ($request->hasFile('a_img')) {
            $destinationPath = '/public/images/products';
            $fileName = $request->file('a_img')->getClientOriginalName();
            $request->file('a_img')->move($destinationPath, $fileName);
            $data['a_img'] = $request->file('a_img')->getClientOriginalName();
        }
        $gallery_images = array('image1', 'image2', 'image3', 'image4', 'image5');
        foreach($gallery_images as $gallery) {
            if ($request->hasFile($gallery)) {
                $destinationPath = base_path() . '/public/images/products';
                $fileName = $request->file($gallery)->getClientOriginalName();
                $request->file($gallery)->move($destinationPath, $fileName);
                $data[$gallery] = $request->file($gallery)->getClientOriginalName();
            }
        }
        return $data;
    }

    /**
     * Search Form for tables
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $search = Request::get('search');
        $products = Product::where('name', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->paginate(5);
        return view('admin.product.index', compact('products'));
    }
}
