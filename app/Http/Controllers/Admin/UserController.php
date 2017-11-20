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
        $users = null;

        if(auth()->user()->hasRole('system_admin')) {
            $users = User::with('roles')->sortable(['email' => 'asc'])->paginate();
        }
        else if(auth()->user()->hasRole('company_admin')) {
            $companyUser = CompanyUser::where('user_id','=',auth()->user()->id)->first();
            $usersCompany = CompanyUser::where('company_id','=',$companyUser->company_id)->get();
            $usersId = array();
            foreach ($usersCompany as $userCompany) {
                $user = User::where('id', $userCompany->user_id)->with('roles')->first();
                if($user->roles->pluck('name')[0] == 'company_user') {
                    array_push($usersId,$userCompany->user_id);
                }
            }
            $users = User::whereIn('id', $usersId)->with('roles')->sortable(['email' => 'asc'])->paginate();
        }
        else {
            $companyUser = CompanyUser::where('user_id','=',auth()->user()->id)->first();
            $usersCompany = CompanyUser::where('company_id','=',$companyUser->company_id)->get();
            $usersId = array();
            foreach ($usersCompany as $userCompany) {
                array_push($usersId,$userCompany->user_id);
            }
            $users = User::whereIn('id', $usersId)->with('roles')->sortable(['email' => 'asc'])->paginate();
        }
        //$users = User::with('roles')->sortable(['email' => 'asc'])->paginate();
        return view('admin.users.index', ['users' => $users]);
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
            'roles.*' => [
                'required',
                Rule::notIn(['0']),
            ]
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $user = new User();
        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->active = $request->get('active', 0);
        $user->confirmed = $request->get('confirmed', 0);


        $rol = Role::find($request->get('roles')[0]);

        $validatorCompanies = Validator::make($request->all(), [
            'companies.*' => [
                'required',
                Rule::notIn(['0']),
            ]
        ]);

        switch ($rol->name) {
            case 'system_admin' :
                $user->save();
                break;
            case 'community_manager' :
                if ($validatorCompanies->fails()) return redirect()->back()->withErrors($validatorCompanies->errors());
                $user->save();
                $this->setCompaniesToUser($request->get('companies'), $user->id);
                break;
            case 'company_admin' :
                if ($validatorCompanies->fails()) return redirect()->back()->withErrors($validatorCompanies->errors());
                $user->save();
                $this->setCompaniesToUser($request->get('companies'), $user->id);
                break;
            case 'company_user' :
                if ($validatorCompanies->fails()) return redirect()->back()->withErrors($validatorCompanies->errors());
                $user->save();
                $this->setCurrentCompanyToUser($user->id);
                break;
            default:
                break;
        }


        //$user->save();
        $user->roles()->detach();
        $user->roles()->attach($request->get('roles'));

        return redirect()->intended(route('admin.users'));
    }

    private function setCurrentCompanyToUser($userId) {
        $userCompany = CompanyUser::where('user_id','=',auth()->user()->id)->first();
        $companyUsers = new CompanyUser();
        $companyUsers->company_id = $userCompany->company_id;
        $companyUsers->user_id = $userId;
        $companyUsers->active = true;
        $companyUsers->save();

    }

    private function setCompaniesToUser($companies, $userId) {

        foreach ($companies as $companyId) {
            $companyUsers = new CompanyUser();
            $companyUsers->company_id = $companyId;
            $companyUsers->user_id = $userId;
            $companyUsers->active = true;
            $companyUsers->save();
        }
    }

    private function updateCompaniesToUser($companies, $userId) {
        $companiesOld = CompanyUser::where('user_id','=',$userId)->get();

        // Companies to delete
        foreach ($companiesOld as $companyOld) {
            $notFound = true;
            foreach ($companies as $companyNew) {
                if($companyOld->company_id == $companyNew) {
                    $notFound = false;
                    break;
                }
            }

            if($notFound) {
                $companyOld->delete();
            }
        }

        foreach ($companies as $companyNew) {
            $notFound = true;
            foreach ($companiesOld as $companyOld) {
                if($companyOld->company_id == $companyNew) {
                    $notFound = false;
                    break;
                }
            }

            if($notFound) {
                $company = new CompanyUser();
                $company->company_id = $companyNew;
                $company->user_id = $userId;
                $company->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $companiesUser = CompanyUser::where('user_id','=',$user->id)->where('active','=','1')->get();
        if($companiesUser != null) {
            return view('admin.users.show', ['user' => $user, 'companiesUser' => $companiesUser]);
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
        $companyUser = CompanyUser::where('user_id','=',$user->id)->where('active','=','1')->get();
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
            'roles.*' => [
                'required',
                Rule::notIn(['0']),
            ]
        ]);

        $validator->sometimes('email', 'unique:users', function ($input) use ($user) {
            return strtolower($input->email) != strtolower($user->email);
        });

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $user->active = $request->get('active', 0);
        $user->confirmed = $request->get('confirmed', 0);

        $user->save();

        $user->roles()->detach();
        $user->roles()->attach($request->get('roles'));

        $rol = Role::find($request->get('roles')[0]);

        $validatorCompanies = Validator::make($request->all(), [
            'companies.*' => [
                'required',
                Rule::notIn(['0']),
            ]
        ]);

        switch ($rol->name) {
            case 'system_admin' :
                //$user->save();
                break;
            case 'community_manager' :
                if ($validatorCompanies->fails()) return redirect()->back()->withErrors($validatorCompanies->errors());
                //$user->save();
                $this->updateCompaniesToUser($request->get('companies'), $user->id);
                break;
            case 'company_admin' :
                if ($validatorCompanies->fails()) return redirect()->back()->withErrors($validatorCompanies->errors());
                //$user->save();
                $this->updateCompaniesToUser($request->get('companies'), $user->id);
                break;
            case 'company_user' :
                if ($validatorCompanies->fails()) return redirect()->back()->withErrors($validatorCompanies->errors());
                //$user->save();
                $this->updateCompaniesToUser($request->get('companies'), $user->id);
                break;
            default:
                break;
        }



        /*if($request->has('belong_company') && $request->get('belong_company') == 'on') {
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
            if($companyUsers != null) {
                $companyUsers->delete();
            }
        }

        //roles
        if ($request->has('roles')) {
            $user->roles()->detach();

            if ($request->get('roles')) {
                $user->roles()->attach($request->get('roles'));
            }
        }*/

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
            $companiesRelations = CompanyUser::where('user_id','=',$id)->get();
            if($companiesRelations != null) {
                foreach ($companiesRelations as $companyRelation)
                    $companyRelation->delete();
            }
            $user->delete();
        }

        return redirect()->intended(route('admin.users'));

    }
}
