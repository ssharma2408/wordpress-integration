<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCustomerRoleRequest;
use App\Http\Requests\StoreCustomerRoleRequest;
use App\Http\Requests\UpdateCustomerRoleRequest;
use App\Models\CustomerRole;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerRolesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('customer_role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customerRoles = CustomerRole::all();

        return view('admin.customerRoles.index', compact('customerRoles'));
    }

    public function create()
    {
        abort_if(Gate::denies('customer_role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customerRoles.create');
    }

    public function store(StoreCustomerRoleRequest $request)
    {
        $customerRole = CustomerRole::create($request->all());

        return redirect()->route('admin.customer-roles.index');
    }

    public function edit(CustomerRole $customerRole)
    {
        abort_if(Gate::denies('customer_role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customerRoles.edit', compact('customerRole'));
    }

    public function update(UpdateCustomerRoleRequest $request, CustomerRole $customerRole)
    {
        $customerRole->update($request->all());

        return redirect()->route('admin.customer-roles.index');
    }

    public function show(CustomerRole $customerRole)
    {
        abort_if(Gate::denies('customer_role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customerRoles.show', compact('customerRole'));
    }

    public function destroy(CustomerRole $customerRole)
    {
        abort_if(Gate::denies('customer_role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customerRole->delete();

        return back();
    }

    public function massDestroy(MassDestroyCustomerRoleRequest $request)
    {
        $customerRoles = CustomerRole::find(request('ids'));

        foreach ($customerRoles as $customerRole) {
            $customerRole->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
