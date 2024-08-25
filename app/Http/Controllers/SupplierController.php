<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\SupplierCategories;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;
use App\Exports\InspectedSuppliersExport;
use App\Exports\UninspectedSuppliersExport;
use App\Exports\CompliantSuppliersExport;







//use Faker\Provider\File;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
                $suppliers = Suppliers::where('company_id', \Auth::user()->current_company)->with('category')->get();
      


            return view('suppliers.index', compact('suppliers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function categories()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
                $categories = SupplierCategories::where('company_id', \Auth::user()->current_company)->get();
      


            return view('suppliers.categories', compact('categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }
    public function inspected()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
                $suppliers = Suppliers::where('company_id', \Auth::user()->current_company)->where('inspection_status', 'Inspected')->get();
      


            return view('suppliers.inspected', compact('suppliers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function uninspected()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
            $suppliers = Suppliers::where('company_id', \Auth::user()->current_company)->where('inspection_status','Not Inspected')->get();
      


            return view('suppliers.uninspected', compact('suppliers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function compliant()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
            $suppliers = Suppliers::where('company_id', \Auth::user()->current_company)->where('compliance_status','Compliant')->get();
      


            return view('suppliers.compliant', compact('suppliers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function noncompliant()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
            $suppliers = Suppliers::where('company_id', \Auth::user()->current_company)->where('compliance_status','Non Compliant')->get();
      


            return view('suppliers.non_compliant', compact('suppliers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function sanctioned()
    {

        if (\Auth::user()->can('Manage Suppliers')) {
          
            $suppliers = Suppliers::where('company_id',\Auth::user()->current_company)->where('sanctioned_status',1)->with([ 'user'])->get();
      


            return view('suppliers.sanctioned', compact('suppliers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function create()
    {
        if (\Auth::user()->can('Create Suppliers')) {
            $categories = SupplierCategories::where('company_id',\Auth::user()->current_company)->get()->pluck('name', 'id');
            
            return view('suppliers.create', compact('categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function create_category()
    {
        if (\Auth::user()->can('Create Suppliers')) {
          
            return view('suppliers.create_category');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function store_category(Request $request)
    {
        if (\Auth::user()->can('Create Suppliers')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
            
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }
            $supplier = SupplierCategories::create(
                [
                    'name' => $request['name'],
                    'company_id' => \Auth::user()->current_company,
                ]

            );
    
           
            return redirect()->route('suppliers.categories')->with('success', __('Category successfully created.'));
            // return redirect()->route('employee.index')->with('success', __('Employee successfully created.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function edit_supplier_category($id)
    {
        $id = Crypt::decrypt($id);
        if (\Auth::user()->can('Edit Suppliers')) {

            $category     = SupplierCategories::find($id);

            return view('suppliers.edit_category', compact('category'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update_supplier_category(Request $request, $id)
    {

        if (\Auth::user()->can('Edit Suppliers')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category = SupplierCategories::findOrFail($id);

           

            $category = SupplierCategories::findOrFail($id);
            $input    = $request->all();
            $category->fill($input)->save();
            

      
        return redirect()->route('suppliers.categories', \Illuminate\Support\Facades\Crypt::encrypt($category->id))->with('success', __('Category successfully updated.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
            
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy_supplier_category($id)
    {
        if (Auth::user()->can('Delete Suppliers')) {
            $category      = SupplierCategories::findOrFail($id);
        
            $category->delete();

            return redirect()->route('suppliers.categories')->with('success', 'Category successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Suppliers')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'location' => 'required',
                    'category_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            

            $suppliers = Suppliers::create(
                [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'category' => $request['category_id'],
                    'location' => $request['location'],
                    'phone' => $request['phone'],
                    'inspection_status' => 'Not Inspected',
                    'complaince_status' => 'Compliant',
                    'sanction_status' => 'Sanctioned',
                    'company_id' => \Auth::user()->current_company,
                ]

            );

      
           
            return redirect()->route('suppliers.index')->with('success', __('Supplier successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        if (\Auth::user()->can('Edit Suppliers')) {
            $categories     = SupplierCategories::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $supplier     = Suppliers::find($id);

            return view('suppliers.edit', compact('supplier','categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('Edit Suppliers')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',

                    'dob' => 'required',
                    'gender' => 'required',
                    'phone' => 'required|numeric',
                    'address' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


           

            $supplier = Suppliers::findOrFail($id);
            $input    = $request->all();
            $supplier->fill($input)->save();
            

      
        return redirect()->route('suppliers.show', Crypt::encrypt($supplier->id))->with('success', __('Supplier successfully updated.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
            
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        if (Auth::user()->can('Delete Suppliers')) {
            $supplier      = Suppliers::findOrFail($id);
        
            $supplier->delete();

            return redirect()->route('suppliers.index')->with('success', 'Supplier successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function export()
    {
        $name = 'suppliers_' . date('Y-m-d i:h:s');
        $data = Excel::download(new SuppliersExport(), $name . '.xlsx');


        return $data;
    }
    public function export_inspected()
    {
        $name = 'inspected-suppliers_' . date('Y-m-d i:h:s');
        $data = Excel::download(new InspectedSuppliersExport(), $name . '.xlsx');


        return $data;
    }

    public function export_uninspected()
    {
        $name = 'un-inspected-suppliers_' . date('Y-m-d i:h:s');
        $data = Excel::download(new UninspectedSuppliersExport(), $name . '.xlsx');


        return $data;
    }
    public function export_compliant()
    {
        $name = 'compliant-suppliers_' . date('Y-m-d i:h:s');
        $data = Excel::download(new CompliantSuppliersExport(), $name . '.xlsx');


        return $data;
    }



}
