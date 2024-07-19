<?php

namespace App\Http\Controllers;

use Exception;
use PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\File;


class BackUpController extends Controller
{
    public function show()
    {
        return inertia('Dashboard/Backup/Download');
    }

    public function download()
    {
        $this->cleanUp();
        $mysqldumpPath = '/usr/bin/mysqldump'; //C:/xampp/mysql/bin/mysqldump << this is on local host
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $filename = $database.'_backup_file_'. date('Y-m-d_H-i-s') . ".sql";
        $backupFile = "public/backup/{$filename}";
        $backupFilePath = storage_path("app/{$backupFile}");

        try {
            // Construct the command
            $command = "\"$mysqldumpPath\" --user=$username --password=$password --host=$host $database > \"$backupFilePath\"";

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new Exception("mysqldump command failed with return code $returnVar");
            }

            // // Generate a URL for the backup file
            $url = url("storage/backup/{$filename}");
            
            return redirect()->back()->with('success', $url);
            
        } catch (Exception $e) {
            Log::error('Error running mysqldump command: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error creating backup']);
        }
    }
    

    public function showRestore()
    {
        return inertia('Dashboard/Backup/Restore');
    }

    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'database' => 'required|file'
        ]);

        $databaseFile = $request->file('database');

        try 
        {
            // Process the database restoration
            $backupPath = storage_path('app/backup'); // Adjust the path as necessary
            $fileName = $databaseFile->getClientOriginalName();
            $filePath = $databaseFile->storeAs('backup', $fileName);

            
            $mysqldumpPath = 'C:/xampp/mysql/bin/mysql'; // Adjust the path to your MySQL client
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');

           
            // Construct the command to restore the database
            $command = "\"$mysqldumpPath\" --user=$username --password=$password --host=$host $database < \"$backupPath/$fileName\"";
            
            exec($command, $output, $returnVar);

            
            if ($returnVar !== 0) {
                throw new Exception("Database restoration failed with return code $returnVar");
            }

            
            // Clean up the uploaded backup file
            unlink(storage_path("app/$filePath"));

            return redirect()->back()->with('success','Database successfully restored!');
        } catch (Exception $e) {
            Log::error('Error restoring database: ' . $e->getMessage());

            // If an error occurs, delete the uploaded backup file if it exists
            if (isset($filePath) && file_exists(storage_path("app/$filePath"))) {
                unlink(storage_path("app/$filePath"));
            }
            return redirect()->back()->with('error','Failed to restore database!');
        }
    }

     public function testDatabaseConnection()
    {
        try {
            // Attempt to connect to the database using Laravel's DB facade
            DB::connection()->getPdo();

            // Dump the output if connection is successful
            dd("Connected successfully to MySQL server");
        } catch (PDOException $e) {
            // Dump the error message if connection fails
            dd("Connection failed: " . $e->getMessage());
        }
    }

    public function cleanUp()
    {
        
        $directory = public_path('storage/backup');

        // Check if the directory exists
        if (File::exists($directory)) {
            // Delete the directory and its contents
            File::deleteDirectory($directory);

            // Recreate the empty directory if needed
            File::makeDirectory($directory, 0755, true, true);
        }

    }

}