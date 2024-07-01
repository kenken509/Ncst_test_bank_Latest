<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    function showAnnouncement()
    {
        // Create the subquery to get the minimum id for each marking group
        $subQuery = Announcement::select('marking', DB::raw('MIN(id) as min_id'))
        ->groupBy('marking');
        
        // Join the subquery with the main table to get the full rows
        $announcements = Announcement::joinSub($subQuery, 'sub', function ($join) {
            $join->on('announcements.id', '=', 'sub.min_id');
        })
        ->select('announcements.*') // Select all columns from the announcements table
        ->orderBy('id','desc')
        ->get();

        
        return inertia('Dashboard/Announcement/AnnouncementShow',[
            'announcements' => $announcements,
        ]);
    }

    public function showAddAnnouncement()
    {
        return inertia('Dashboard/Announcement/AnnouncementAdd');
    }
    public function storeAnnouncement(Request $request)
    {
        $userToNotify = User::where('role','!=','admin')->Where('role','!=','co-admin')->get();

        
        
        $marking = strval(now());
        
        try
        {
            DB::beginTransaction();
            foreach($userToNotify as $user)
            {
                

                $announcement = new Announcement();
                
                $announcement->content      = $request->content;
                $announcement->start_date   = $request->startDate;
                $announcement->end_date     = $request->endDate;
                $announcement->user_id      = $user->id;
                $announcement->author_id    = $request->author;
                $announcement->marking      = $marking;
                
                $announcement->save();

            }

            DB::commit();
            $randomNumber = rand(1,99999999);
            return redirect()->back()->with('success', 'Successfully Added new announcement.'.$randomNumber);
        }
        catch(\Exception $e)
        {
            DB::rollback();

            Log::error('error creating new announcement: '.$e->getMessage());
            
            $randomNumber = rand(1,99999999);
            return redirect()->back()->with('error', 'Failed to create new announcement.'.$randomNumber);
        }
        

        
    }

    public function updateAnnouncement(Request $request)
    {
        $announcementsToUpdate = Announcement::where('marking',$request->marking)->get();

        $randomNumber = rand(1,999999);
        
        try
        {
            DB::beginTransaction();
            foreach($announcementsToUpdate as $announcement)
            {
                $announcement->content      = $request->content;
                $announcement->start_date   = $request->startDate;
                $announcement->end_date     = $request->endDate;
                $announcement->editor_id    = $request->editor;
                $announcement->read_at      = null;
                $announcement->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Updated Successfully.'.$randomNumber);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::error('Error updating announcement: '.$e->getMessage());

            return redirect()->back()->with('error','Failed to update.'.$randomNumber);
        }
    }
    public function delete($marking)
    {
        $announcementsToDelete = Announcement::where('marking',$marking)->get();

        try
        {
            DB::beginTransaction();
            foreach($announcementsToDelete as $announcement)
            {
                $announcement->delete();
            }
            //throw new \Exception('simulated error');
            DB::commit();
            $randomNumber = rand(1,99999999);
            return redirect()->back()->with('success','Successfully deleted announcement.'.$randomNumber);
        }
        catch(Exception $e)
        {
            DB::rollback();

            Log::error('Error deleting announcement: '.$e->getMessage());

            $randomNumber = rand(1,99999999);
            return redirect()->back()->with('error','Failed to delete announcement.'.$randomNumber);
        }
        

       
    }
}
