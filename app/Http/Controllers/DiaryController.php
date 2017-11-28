<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyUser;

class DiaryController extends Controller
{
    //
    public function index()
    {
        //$products = Product::all();
        $companies = $this->getCompanies();
        return view('diary.index', ['companies'=>$companies]);
    }

    private function getCompanies() {
        $id = auth()->user()->id;
        $companies = [];
        if(auth()->user()->hasRole('community_manager')) {
            $communityCompanies = CompanyUser::where('user_id','=',$id)->get();
            foreach ($communityCompanies as $communityCompany) {
                $company = Company::where('id','=',$communityCompany->company_id)->first();
                array_push($companies,$company);
            }
        }
        else if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('company_user')) {
            $companyAdmin = CompanyUser::where('user_id','=',$id)->first();
            $company = Company::where('id','=',$companyAdmin->company_id)->first();
            array_push($companies,$company);
        }
        else {
            $companies = Company::all();
        }
        return $companies;
    }
}

