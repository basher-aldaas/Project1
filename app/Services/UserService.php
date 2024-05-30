<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserService
{
    //register process for student or teacher
    public function register($request) : array
    {
        if($request['type'] == 'student') {

            $user=User::query()->create([
                'full_name' => $request['full_name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' => bcrypt($request['password']),
                'birthday' => $request['birthday'],
                'address' => $request['address'],
                'type' => $request['type'],
                'image'=> $request['image'],
            ]);
            $studentRole = Role::query()
                ->where('name', 'student')
                ->first();
            $user->assignRole($studentRole);
            $permissions = $studentRole->permissions()->pluck('name')->toArray();
            $user->givePermissionTo($permissions);
            $user->load('roles', 'permissions');

            $user = User::query()->find($user['id']);
            $user = $this->appendRolesAndPermissions($user);
            $user['token'] = $user->createToken("token")->plainTextToken;
            $message = 'user created successfully';
            return [
                'user' => $user,
                'message' => $message,
            ];
        }else{
            $user=User::query()->create([
                'full_name' => $request['full_name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' => bcrypt($request['password']),
                'birthday' => $request['birthday'],
                'address' => $request['address'],
                'type' =>'teacher',
                'image'=> $request['image'],
            ]);

            $teacherRole = Role::query()->where('name', 'teacher')->first();
            $user->assignRole($teacherRole);
            $permissions = $teacherRole->permissions()->pluck('name')->toArray();
            $user->givePermissionTo($permissions);
            $user->load('roles', 'permissions');

            $user = User::query()->find($user['id']);
            $user = $this->appendRolesAndPermissions($user);
            $user['token'] = $user->createToken("token")->plainTextToken;
            $message = 'user created successfully';
            return [
                'user' => $user,
                'message' => $message,
            ];
        }
    }

    //login process for student or teacher
    public function login($request) : array
    {
        $user=User::query()->where('email',$request['email'])
         //   ->orWhere('phone',$request['phone'])
            ->first();
        if(!is_null($user)){
            if (!Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
                $message='User email dose not match with password';
                $code=401;
            }else{
                $user = $this->appendRolesAndPermissions($user);
                $user['token']=$user->createToken("token")->plainTextToken;
                $message='Your logged successfully';
                $code=200;
            }

        }else{
            $message='User not found in data you need to register first';
            $code=404;
        }
        return [
            'user' => $user,
            'message' => $message,
            'code' => $code,
        ];

    }

    //logout process for student or teacher
    public function logout() : array
    {
        $user=Auth::user();
        if(!is_null($user)){
            Auth::user()->currentAccessToken()->delete();
            $message='Logged Out successfully';
            $code=200;

        }else{
            $message='Invalid Token';
            $code=404;
                }
        return [
            'user' => $user,
            'message' => $message,
            'code' => $code,
        ];

    }

    //function to add roles and permissions to user array
    public function appendRolesAndPermissions($user)
    {
        $roles=[];
        foreach ($user->roles as $role){
            $roles = $role->name;
        }
        unset($user['roles']);
        $user['roles']=$roles;

        $permissions=[];
        foreach ($user->permissions as $permission) {
            $permissions=$permission->name;
        }

        unset($user['permissions']);
        $user['permissions']=$permissions;

        return $user;

    }

}
