<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;
use JWTAuth;

class QuoteController extends Controller
{
	public function __construct() {
		$this->middleware('auth.jwt', ['except' => ['index']]);
	}

	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		$quotes = Quote::all();
		
		$response = [
			'quotes' => $quotes
		];
		
		return response()->json($response, 200);
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
		$user = JWTAuth::parseToken()->toUser();
		
		$quote = new Quote();
		$quote->content = $request->input('content');
		$quote->save();
		
		return response()->json(['quote' => $quote, 'user' => $user], 201);
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
		$quote = Quote::find($id);
		
		if (empty($quote)) {
			return response()->json(['message' => 'Docment not found'], 404);
		}
		
		$quote->content = $request->input('content');
		$quote->save();
		
		return response()->json($quote, 200);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id)
	{
		$quote = Quote::find($id);
		
		if (!empty($quote)) {
			$quote->delete();
			return response()->json(['message'=>'Quote deleted'], 200);
		} 
		else {
			return response()->json(['message'=>'Error deleted'], 404);
		}
	}
}
