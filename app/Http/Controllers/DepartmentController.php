<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function showDepartment()
    {
        
        return inertia('Dashboard/Department/DepartmentAll', [
                'departments' => Department::with([
                    'departmentHeads' => function ($query) {
                    $query->where('role', 'department head');
                },
                'faculty' => function ($query) {
                    $query->where('role', 'faculty');
                },
                'divisions',
            ])->latest()->get(),
        ]);
    }

    public function destroy($id)
    {
        
        try {
            $depTodelete = Department::findOrFail($id);
            $depTodelete->delete();
    
            return redirect()->back()->with('success', 'Successfully deleted!');
        } catch (\Exception $e) {
            // Log the exception message if needed
            Log::error('Department Deletion Error: ' . $e->getMessage());
    
            // Return with an error message
            return redirect()->back()->with('error', 'Error occurred while trying to delete the department. Please try again.');
        }
    }

    public function addDepartment()
    {
        return inertia('Dashboard/Department/DepartmentAdd');
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'departmentName' => 'required|unique:departments,name',
        ]);

        try{
            DB::beginTransaction();
            $newDepartment = new Department();

            $newDepartment->name = $request->departmentName;
            $newDepartment->save();

            // Simulate an error
            //throw new \Exception('Simulated error');

            DB::commit();
            return redirect()->route('departments.show')->with('success', 'Successfully created new department.');
        }catch(\Exception $e)
        {
            DB::rollback();

            // Log the exception message if needed
            Log::error('Failed to create new department error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create new department');
        }
        
    }

    public function updateDepartmentShow($id)
    {
        $department = Department::findOrFail($id);
        
        return inertia('Dashboard/Department/DepartmentUpdate',[
            'department' => $department, 
        ]);
    }
    public function updateDepartment(Request $request)
    {
        //dd($request->departmentId);
        $request->validate([
            'departmentName' => [
                'required',
                Rule::unique('departments', 'name')->ignore($request->departmentId),
            ],
        ]);

        try{
            DB::beginTransaction();

            $depToUpdate = Department::findOrFail($request->departmentId);

            $depToUpdate->name = $request->departmentName;
            $depToUpdate->save();

            //throw new \Exception('simulated error'); 
            DB::commit();

            return redirect()->route('departments.show')->with('success', 'Updated Successfully.');
        }catch(\Exception $e)
        {
            DB::rollback();
            Log::error('Failed to update department:  '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to update department. ');
        }
        
    }
}
