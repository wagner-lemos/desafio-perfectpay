<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        return view('products', ['products' => $this->productService->getAllProducts()->toArray($request)]);
    }
}
