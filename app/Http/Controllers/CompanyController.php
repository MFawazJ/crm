<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::paginate(10);

        return view('companies.index', [
            'companies' => $companies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        //validated input data from the request
        $vData = $request->validated();

        //Save Data
        try {
            $company = new Company;

            $company->name = $vData['name'];
            $company->email = $vData['email'];
            $company->website = $vData['website'];

            if($request->hasFile('logo')){

                $logoFile = $request->file('logo');

                // Save the file to the storage/app/public directory
                $path = $logoFile->store('public');

                // URL of the saved file
                $company->logo = Storage::url($path);
            }else{
                $company->logo = null;
            }

            $company->save();

        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', $e->getMessage());
        }


        return back()->with('success', 'Company Details Added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {


        return view('companies.edit',compact('company')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, string $id)
    {
        //validated input data from the request
        $vData = $request->validated();

        //Save Data
        try {
            $company = Company::findorFail($id);

            $company->name = $vData['name'];
            $company->email = $vData['email'];
            $company->website = $vData['website'];

            if($request->hasFile('logo')){

                $logoFile = $request->file('logo');

                // Save the file to the storage/app/public directory
                $path = $logoFile->store('public');

                // URL of the saved file
                $company->logo = Storage::url($path);
            }

            $company->update();

        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Company Details Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);

        if(!is_null($company->logo)){
            // delete the company's logo
            Storage::delete($company->logo);
        }

        // delete the company
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company Deleted Successfully');
    }
}
