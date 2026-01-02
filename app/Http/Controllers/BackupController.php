<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('local');
        $files = $disk->files('backups');
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backups[] = [
                    'filename' => pathinfo($file, PATHINFO_BASENAME),
                    'size' => $this->formatSize($disk->size($file)),
                    'date' => Carbon::createFromTimestamp($disk->lastModified($file))->format('Y-m-d H:i:s'),
                    'path' => $file
                ];
            }
        }

        // Sort by date desc
        usort($backups, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            // Use Storage facade to ensure path consistency and directory creation
            Storage::disk('local')->makeDirectory('backups');
            $path = Storage::disk('local')->path('backups/' . $filename);

            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');
            $dbHost = env('DB_HOST');
            
            // Build credentials string
            $credentials = "--user=\"{$dbUser}\" --host=\"{$dbHost}\"";
            if (!empty($dbPass)) {
                $credentials .= " --password=\"{$dbPass}\"";
            }

            // find mysqldump
            $dumpBinaryPath = 'mysqldump';
            
            // Heuristic for Laragon
            if (file_exists('C:/laragon/bin/mysql')) {
                $dirs = scandir('C:/laragon/bin/mysql');
                foreach($dirs as $dir) {
                    if (strpos($dir, 'mysql-') !== false) {
                        $candidate = "C:/laragon/bin/mysql/$dir/bin/mysqldump.exe";
                        if (file_exists($candidate)) {
                            $dumpBinaryPath = "\"$candidate\"";
                            break;
                        }
                    }
                }
            }

            // Add --column-statistics=0 for compatibility with some mysql 8 versions if needed, 
            // but let's stick to basic first. capture stderr 2>&1
            $command = "{$dumpBinaryPath} {$credentials} {$dbName} > \"{$path}\" 2>&1";
            
            // Execute command
            exec($command, $output, $returnVar);

            if ($returnVar === 0 && file_exists($path) && filesize($path) > 0) {
                return redirect()->route('backups.index')->with('success', 'Backup created successfully.');
            } else {
                // Remove empty file if exists
                if (file_exists($path)) {
                    unlink($path);
                }
                
                $errorMsg = 'Failed to create backup.';
                if (!empty($output)) {
                    $errorMsg .= ' Output: ' . implode("\n", $output);
                } else {
                    $errorMsg .= ' Ensure mysqldump is available and accessible.';
                }
                
                return redirect()->route('backups.index')->with('error', $errorMsg);
            }

        } catch (\Exception $e) {
            return redirect()->route('backups.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        if (Storage::disk('local')->exists('backups/' . $filename)) {
            return Storage::disk('local')->download('backups/' . $filename);
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    public function delete($filename)
    {
        if (Storage::disk('local')->exists('backups/' . $filename)) {
            Storage::disk('local')->delete('backups/' . $filename);
            return redirect()->route('backups.index')->with('success', 'Backup deleted successfully.');
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
