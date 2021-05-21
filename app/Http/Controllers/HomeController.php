<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Sacamos las imagenes r medio de una consulta con el metodo image:: ordenadas como yo quiero
        //Paginated para la paginación de las imagenes
		$images = Image::orderBy('id', 'desc')->paginate(5);
        
        //Las pasamos a la vista mediante una array 
        return view('home',[
			'images' => $images
		]);
    }
}
