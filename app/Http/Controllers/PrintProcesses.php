<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;

class PrintProcesses extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index()
	{
		return view('bibles.ocr.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create()
	{
		return view('bibles.ocr.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return View
	 */
	public function store(Request $request)
	{
		$abbr     = $request->abbr;
		$folder   = public_path("uploads/" . $abbr);
		$filePath = public_path("uploads/" . $abbr . "/" . $abbr . ".pdf");
		$tiffPath = public_path("uploads/" . $abbr . "/" . $abbr . ".tiff");

		if (!is_dir($folder)) {
			mkdir($folder);
		}
		$request->file('pdfBible')->move($folder, basename($filePath));
		exec('convert -density 300 ' . $filePath . ' -depth 8  -fill white -background white -alpha off ' . $tiffPath);
		exec('tesseract ' . $tiffPath . ' output');

		$pdf = new pdf($tiffPath);

		$page_number = $pdf->getNumberOfPages();
		dd($page_number);
		//Pdf::
		dd($request);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return view('bibles.ocr.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view('bibles.ocr.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
