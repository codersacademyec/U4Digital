<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\CompanyUser;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.users.index', ['users' => User::with('roles')->sortable(['email' => 'asc'])->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create', ['roles' => Role::get(), 'companies' => Company::orderby('name','asc')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|confirmed|max:255',
            'password_confirmation' => 'required|max:255',
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        if($request->has('belong_company') && $request->get('belong_company') == 'on') {
            $validator = Validator::make($request->all(), [
                'companies.*' => [
                    'required',
                    Rule::notIn(['0']),
                ],
                'roles' => ['required']
            ]);
            if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        }

        $user = new User();
        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->active = $request->get('active', 0);
        $user->confirmed = $request->get('confirmed', 0);
        $user->save();

        if($request->has('belong_company') && $request->get('belong_company') == 'on') {
            $companyId = $request->get('companies')[0];

            $companyUsers = new CompanyUser();
            $companyUsers->company_id = $companyId;
            $companyUsers->user_id = $user->id;
            $companyUsers->active = true;
            $companyUsers->save();
        }

        //roles
        if ($request->has('roles')) {
            $user->roles()->detach();

            if ($request->get('roles')) {
                $user->roles()->attach($request->get('roles'));
            }
        }

        return redirect()->intended(route('admin.users'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $companyUser = CompanyUser::where('user_id','=',$user->id)->where('active','=','1')->first();
        if($companyUser != null) {
            return view('admin.users.show', ['user' => $user, 'company' => $companyUser->company]);
        }
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $companyUser = CompanyUser::where('user_id','=',$user->id)->where('active','=','1')->first();
        if($companyUser != null) {
            return view('admin.users.edit', ['user' => $user, 'companyUser' => $companyUser , 'roles' => Role::get(), 'companies' => Company::orderby('name','asc')->get()]);
        }
        return view('admin.users.edit', ['user' => $user, 'roles' => Role::get(), 'companies' => Company::orderby('name','asc')->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return mixed
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'active' => 'sometimes|boolean',
            'confirmed' => 'sometimes|boolean',
        ]);

        $validator->sometimes('email', 'unique:users', function ($input) use ($user) {
            return strtolower($input->email) != strtolower($user->email);
        });

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        if($request->has('belong_company') && $request->get('belong_company') == 'on') {
            $validator = Validator::make($request->all(), [
                'companies.*' => [
                    'required',
                    Rule::notIn(['0']),
                ],
                'roles' => ['required']
            ]);
            if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $user->active = $request->get('active', 0);
        $user->confirmed = $request->get('confirmed', 0);

        $user->save();

        if($request->has('belong_company') && $request->get('belong_company') == 'on') {
            $companyId = $request->get('companies')[0];

            $companyUsers = CompanyUser::where('company_id','=',$companyId)->where('user_id','=',$user->id)->first();

            if($companyUsers == null) {
                $companyUsers = new CompanyUser();
                $companyUsers->company_id = $companyId;
            }
            $companyUsers->user_id = $user->id;
            $companyUsers->active = true;
            $companyUsers->save();
        }
        else {
            $companyUsers = CompanyUser::where('user_id','=',$user->id)->first();
            $companyUsers->delete();
        }

        //roles
        if ($request->has('roles')) {
            $user->roles()->detach();

            if ($request->get('roles')) {
                $user->roles()->attach($request->get('roles'));
            }
        }

        return redirect()->intended(route('admin.users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if($user != null) {
            $companiesRelations = CompanyUser::where('user_id','=',$id)->first();
            if($companiesRelations != null) {
                $companiesRelations->delete();
            }
            $user->delete();
        }

        return redirect()->intended(route('admin.users'));

    }
}
