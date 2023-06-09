<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerRole;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//WooCommerce Customer
use Customer as WooCommerceCustomer;

class CustomersController extends Controller
{
    public function populate()
    {
        $customers = WooCommerceCustomer::all(['role'=>'all']);
		
		$include_role_array = ['customer', 'r1', 'r2', 'r3', 'r4'];
		foreach($customers as $customer){
			if(in_array($customer->role, $include_role_array)){								
				
				$cust = Customer::firstOrNew(array('email' => $customer->email));
				$cust->first_name = $customer->first_name;
				$cust->last_name = $customer->last_name;
				$cust->email = $customer->email;
				$cust->role_id = CustomerRole::where('name',$customer->role)->first()->id;
				$cust->save();				
			}
		}
		echo "Customers populated successfully";
		
    }
	
	public function index()
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::with(['role'])->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = CustomerRole::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.customers.create', compact('roles'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        return redirect()->route('admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = CustomerRole::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customer->load('role');

        return view('admin.customers.edit', compact('customer', 'roles'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());

        return redirect()->route('admin.customers.index');
    }

    public function show(Customer $customer)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->load('role');

        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->delete();

        return back();
    }

    public function massDestroy(MassDestroyCustomerRequest $request)
    {
        $customers = Customer::find(request('ids'));

        foreach ($customers as $customer) {
            $customer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
