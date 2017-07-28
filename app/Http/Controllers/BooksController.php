<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleBook;
use App\Transformers\BooksTransformer;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function show()
    {
	    $abbreviation = $_GET['dam_id'];
    	$books = BibleBook::where('abbr',$abbreviation)->get();
    	if($this->api) return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer()));
    	return view('docs.books.show');
    }
}
