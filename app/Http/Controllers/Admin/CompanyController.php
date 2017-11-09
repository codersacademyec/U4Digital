<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = ['companies'=>Company::sortable(['ruc','asc'])->paginate()];

        return view('admin.companies.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'ruc' => 'required|unique:companies|max:255',
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|unique:companies|email|max:255',
            'business_name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $company = new Company();
        $company->ruc = $request->get('ruc');
        $company->name = $request->get('name');
        $company->phone = $request->get('phone');
        $company->email = $request->get('email');
        $company->business_name = $request->get('business_name');
        $company->address = $request->get('address');

        $company->save();
        return redirect()->intended(route('admin.companies'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('admin.companies.show', ['company' => $company]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', ['company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'ruc' => 'required|max:255',
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
            'business_name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $validator->sometimes('ruc', 'unique:companies', function ($input) use ($company) {
            return strtolower($input->ruc) != strtolower($company->ruc);
        });

        $validator->sometimes('email', 'unique:companies', function ($input) use ($company) {
            return strtolower($input->email) != strtolower($company->email);
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $company->ruc = $request->get('ruc');
        $company->name = $request->get('name');
        $company->phone = $request->get('phone');
        $company->email = $request->get('email');
        $company->business_name = $request->get('business_name');
        $company->address = $request->get('address');

        $company->save();

        return redirect()->intended(route('admin.companies'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        return redirect()->intended(route('admin.companies'));
    }
}
