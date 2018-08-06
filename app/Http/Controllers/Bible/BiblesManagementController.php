<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Profile;
use App\Models\User;

use App\Traits\CaptureIpTrait;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;

class BiblesManagementController extends APIController
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagintaionEnabled = config('biblesmanagement.enablePagination');
        if ($pagintaionEnabled) {
            $bibles = Bible::paginate(config('biblesmanagement.paginateListSize'));
        } else {
            $bibles = Bible::all();
        }
        $roles = Role::all();

        return view('bibles.management.show-bibles', compact('bibles', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        return view('bibles.management.create-user',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name'                  => 'required|max:255|unique:bibles',
                'first_name'            => '',
                'last_name'             => '',
                'email'                 => 'required|email|max:255|unique:bibles',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'role'                  => 'required',
            ],
            [
                'name.unique'         => trans('auth.userNameTaken'),
                'name.required'       => trans('auth.userNameRequired'),
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required'  => trans('auth.lNameRequired'),
                'email.required'      => trans('auth.emailRequired'),
                'email.email'         => trans('auth.emailInvalid'),
                'password.required'   => trans('auth.passwordRequired'),
                'password.min'        => trans('auth.PasswordMin'),
                'password.max'        => trans('auth.PasswordMax'),
                'role.required'       => trans('auth.roleRequired'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();

        $bible = Bible::create([
            'name'             => $request->input('name'),
            'first_name'       => $request->input('first_name'),
            'last_name'        => $request->input('last_name'),
            'email'            => $request->input('email'),
            'password'         => bcrypt($request->input('password')),
            'token'            => str_random(64),
            'admin_ip_address' => $ipAddress->getClientIp(),
            'activated'        => 1,
        ]);

        $bible->profile()->save($profile);
        $bible->attachRole($request->input('role'));
        $bible->save();

        return redirect('bibles')->with('success', trans('biblesmanagement.createSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bible = Bible::find($id);
        return view('bibles.management.show-bible', compact('bible'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bible = Bible::with('links','translations')->findOrFail($id);
        $alphabets = Alphabet::select(['script','name'])->get();
        $languages = Language::select(['id','name'])->get();
        $books = Book::orderBy('protestant_order','asc')->get()->groupBy('book_group');
		$organizations  = Organization::all();
        return view('bibles.management.edit-bible', compact('bible','alphabets','languages','books','organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $bible = User::find($id);
        $emailCheck = ($request->input('email') != '') && ($request->input('email') != $bible->email);
        $ipAddress = new CaptureIpTrait();

        if ($emailCheck) {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|max:255|unique:bibles',
                'email'    => 'email|max:255|unique:bibles',
                'password' => 'present|confirmed|min:6',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|max:255|unique:bibles',
                'password' => 'nullable|confirmed|min:6',
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $bible->name = $request->input('name');
        $bible->first_name = $request->input('first_name');
        $bible->last_name = $request->input('last_name');

        if ($emailCheck) {
            $bible->email = $request->input('email');
        }

        if ($request->input('password') != null) {
            $bible->password = bcrypt($request->input('password'));
        }

        $bibleRole = $request->input('role');
        if ($bibleRole != null) {
            $bible->detachAllRoles();
            $bible->attachRole($bibleRole);
        }

        $bible->updated_ip_address = $ipAddress->getClientIp();

        switch ($bibleRole) {
            case 3:
                $bible->activated = 0;
                break;

            default:
                $bible->activated = 1;
                break;
        }

        $bible->save();

        return back()->with('success', trans('biblesmanagement.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $bible = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        if ($bible->id != $currentUser->id) {
            $bible->deleted_ip_address = $ipAddress->getClientIp();
            $bible->save();
            $bible->delete();

            return redirect('bibles')->with('success', trans('biblesmanagement.deleteSuccess'));
        }

        return back()->with('error', trans('biblesmanagement.deleteSelfError'));
    }

    /**
     * Method to search the bibles.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('user_search_box');
        $searchRules = [
            'user_search_box' => 'required|string|max:255',
        ];
        $searchMessages = [
            'user_search_box.required' => 'Search term is required',
            'user_search_box.string'   => 'Search term has invalid characters',
            'user_search_box.max'      => 'Search term has too many characters - 255 allowed',
        ];

        $validator = Validator::make($request->all(), $searchRules, $searchMessages);

        if ($validator->fails()) {
            return response()->json([
                json_encode($validator),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $results = User::where('id', 'like', $searchTerm.'%')
                            ->orWhere('name', 'like', $searchTerm.'%')
                            ->orWhere('email', 'like', $searchTerm.'%')->get();

        // Attach roles to results
        foreach ($results as $result) {
            $roles = [
                'roles' => $result->roles,
            ];
            $result->push($roles);
        }

        return response()->json([
            json_encode($results),
        ], Response::HTTP_OK);
    }
}
