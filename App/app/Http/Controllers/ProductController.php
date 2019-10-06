<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts() {

        $PresentationModel = new Presentation();
        $products = $PresentationModel->listData();

        return response()->json( [ 'products' => $products ] );
    }
}
