<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\Message;
use Illuminate\Http\Request;
use Validator;

class UserContactController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	if($this->api) {
		    $messages = Message::where('resolved',0)->get();
		    return $this->reply($messages);
	    }

        return view('dashboard.messages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    return view('about.contact');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $this->validateMessage($request);
	    Message::create($request->except(['_token']));
        return view('about.contact_successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $message = Message::find($id);
	    return view('dashboard.messages.show',compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $message = Message::find($id);
	    return view('dashboard.messages.edit',compact('message'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



	/**
	 * Ensure the current alphabet change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateMessage(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email'                => 'required|email',
			'subject'              => 'required|string|max:191',
			'purpose'              => 'required|string|max:191',
			'g-recaptcha-response' => 'required|captcha',
			'message'              => 'required'
		]);

		if ($validator->fails()) {
			if (!$this->api) return redirect('about/contact')->withErrors($validator)->withInput();
			return $this->setStatusCode(422)->replyWithError($validator->errors());
		}

	}

}
