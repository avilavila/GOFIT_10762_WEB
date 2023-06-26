<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\promo; /* Import Model */
use App\Http\Resources\PromoResource;
use Illuminate\Support\Facades\Validator;

class PromoController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $promo = promo::latest()->get();

        return new PromoResource(true, 'List Data Promo', $promo);
    }

    public function show($id)
    {
        $promo = promo::find($id);
        

        return new PromoResource(true, 'Data Promo ditemukan!', $promo);
    }
}
