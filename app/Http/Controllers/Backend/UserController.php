<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables, Auth;

class UserController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend.users.users');
    }

    public function getUserList(Request $request)
    {

        $data = User::get();

        return Datatables::of($data)
            ->addColumn('name', function ($data) {
                $name = $data->name;
                if ($data->hasRole('SuperAdmin')) {
                    $image = asset('img/super_admin.jpg');
                } else {
                    $image = $data->getFirstMediaUrl('avatar') != null ? $data->getFirstMediaUrl('avatar', 'thumb') : config('app.placeholder') . '160';
                }

                $badge = '';
                if ($name) {
                    $badge = $name . '   <img width="40" class="rounded-circle"
                                                             src="' . $image . '" alt="User Avatar">';
                }

                return $badge;
            })
            ->addColumn('roles', function ($data) {
                $roles = $data->getRoleNames()->toArray();
                $badge = '';
                if ($roles) {
                    $badge = implode(' , ', $roles);
                }

                return $badge;
            })
            ->addColumn('permissions', function ($data) {
                $roles = $data->getAllPermissions();
                $badges = '';
                foreach ($roles as $key => $role) {
                    $badges .= '<span class="badge badge-dark m-1">' . $role->name . '</span>';
                }
                if ($data->name == 'SuperAdmin') {
                    return '<span class="badge badge-success m-1">All permissions</span>';
                }
                return $badges;
            })
            ->addColumn('action', function ($data) {
                if ($data->role() == 'SuperAdmin') {
                    return '';
                }
                if (Auth::user()->can('manage_user')) {
                    return '<div class="table-actions">
                                <a href="' . url('user/' . $data->id) . '" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="' . url('user/delete/' . $data->id) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['name', 'roles', 'permissions', 'action'])
            ->make(true);
    }

    public function create()
    {
        try {
            $roles = Role::pluck('name', 'id');
            return view('backend.users.create-user', compact('roles'));

        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    public function store(Request $request)
    {

        // create user
        $validator = Validator::make($request->all(), [
            'name' => 'required | string ',
            'email' => 'required | email | unique:users',
            'password' => 'required | confirmed',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try {
            // store user information
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // assign new role to the user
            $user->syncRoles($request->role);

            // upload images
            if ($request->hasFile('avatar')) {
                $user->addMedia($request->avatar)->toMediaCollection('avatar');
            }

            if ($user) {
                return redirect('users')->with('success', 'New user created!');
            } else {
                return redirect('users')->with('error', 'Failed to create new user! Try again.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::with('roles', 'permissions')->find($id);

            if ($user) {
                $user_role = $user->roles->first();
                $roles = Role::pluck('name', 'id');

                return view('backend.users.user-edit', compact('user', 'user_role', 'roles'));
            } else {
                return redirect('404');
            }

        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request)
    {


        // update user info
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required | string ',
            'email' => 'required | email',
            'role' => 'required'
        ]);

        // check validation for password match
        if (isset($request->password)) {
            $validator = Validator::make($request->all(), [
                'password' => 'required | confirmed'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try {

            $user = User::find($request->id);

            $update = $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // update password if user input a new password
            if (isset($request->password)) {
                $update = $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            // sync user role
            $user->syncRoles($request->role);

            // upload images
            if ($request->hasFile('avatar')) {
                $user->addMedia($request->avatar)->toMediaCollection('avatar');
            }

            return redirect()->back()->with('success', 'User information updated succesfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }


    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect('users')->with('success', 'User removed!');
        } else {
            return redirect('users')->with('error', 'User not found');
        }
    }
}
