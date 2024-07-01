<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function showUsers()
    {
       
        $departments = Department::with('divisions')->latest()->get();
        $users = User::with('department', 'division')->latest()->get();

        return inertia('Dashboard/Users/UsersAll',[
            'users'         => $users,
            'departments'   => $departments,
        ]);
    }

    public function showAddUser()
    {
        $departments = Department::with('divisions')->latest()->get();
       
        return inertia('Dashboard/Users/UsersAdd',[
            'departments' => $departments,
        ]);
    }

    public function storeUser(Request $request)
    {   
        
        $defaultPassword = 'Ncst1234#';
        $newUser = new User();
        
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        try
        {
            DB::beginTransaction();

            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($defaultPassword);
            $newUser->role = $request->role;
            $newUser->department_id = $request->department;
            $newUser->division_id = $request->division_id; 
            $newUser->save();

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            Log::error('error creating new user: '.$e->getMessag());

            return redirect()->back()->with('error', 'Failed to create new user!');
        }
        

        return redirect()->route('users.show')->with('success', 'Successfully created new user');
    }

    public function updateUserShow($id)
    {
        $departments = Department::with('divisions')->latest()->get();
        $userToUpdate = User::findOrFail($id);
        return inertia('Dashboard/Users/UserUpdate',[
            'userToUpdate' => $userToUpdate,
            'departments' => $departments,
        ]);
    }

    public function updateUserStore(Request $request)
    {
        //dd($request);
        $userToUpdate = User::findOrFail($request->id);

        // Validate the request, ignoring the current user's email
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userToUpdate->id),
            ],
            // Add other validation rules as needed
        ]);
 
        try
        {
            DB::beginTransaction();

            $userToUpdate->name = $request->name;
            $userToUpdate->email = $request->email;
            $userToUpdate->role = $request->role;
            $userToUpdate->department_id = $request->department;
            $userToUpdate->division_id = $request->division_id; 
            $userToUpdate->save();

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            Log::error('error creating new user: '.$e->getMessag());

            return redirect()->back()->with('error', 'Failed to update user!');
        }
        

        return redirect()->route('users.show')->with('success', 'Updated Successfully.');
    }

    public function deleteUser($id)
    {
        $loggedUserId = Auth::user()->id;

        if($id == $loggedUserId)
        {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }
        else
        {
            try
            {
                DB::beginTransaction();

                $user = User::findOrFail($id);
                $user->delete();

                DB::commit();
                return redirect()->back()->with('success', 'Successfully Deleted a User.');
            }
            catch(\Exception $e)
            {
                DB::rollback();
                Log::error('error deleting user: '.$e->getMessage());

                return redirect()->back()->with('error', 'Error Deleting a User');
            }
        }

        

    }

    
}
