<?php

namespace App\Http\Controllers;

use Log;
use PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Exception;
use Symfony\Component\Process\Exception\ProcessFailedException;



class BackUpController extends Controller
{
    public function show()
    {
        return inertia('Dashboard/Backup/Download');
    }

    public function download()
    {
        $mysqldumpPath = 'C:/xampp/mysql/bin/mysqldump';
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $filename = "backup-{$database}-" . date('Y-m-d_H-i-s') . ".sql";
        $backupFile = "C:/xampp/htdocs/Test_Bank-v1-main/public/backup/{$filename}";
    
        try {
            // Construct the command
            $command = "\"$mysqldumpPath\" --user=$username --password=$password --host=$host $database > \"$backupFile\"";
            exec($command, $output, $returnVar);
            //dd('i');
            if ($returnVar !== 0) {
                throw new Exception("mysqldump command failed with return code $returnVar");
            }
            
            
            // Read the backup file content
            $backupContent = file_get_contents($backupFile);
            
            return response($backupContent)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (Exception $e) {
            Log::error('Error running mysqldump command: ' . $e->getMessage());
            throw $e;
        } finally {
            // Clean up the backup file
            if (file_exists($backupFile)) {
                unlink($backupFile);
            }
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

}
