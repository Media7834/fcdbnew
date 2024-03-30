<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\Yourmodel;
use Log;

class PythonController extends Controller
{
    public function executePythonAndSearch(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('images');
        $imageFullPath = storage_path('app/' . $imagePath);

        $process = new Process(['/usr/local/bin/python3.7', '/home/users/2/deca.jp-broad-hyuga-0115/web/fcdb3/app/Python/modelsoftmax.py', $imageFullPath]);

        try {
            $process->mustRun();

            $output = trim($process->getOutput());
            $results = json_decode($output, true);

            if (!is_array($results)) {
                throw new \Exception("Invalid JSON output from Python script");
            }

            // Log the start of result output
            Log::info("---------------------------------------Start ---> -----");

            foreach ($results as $result) {
                if (!is_array($result) || !isset($result['class'], $result['softmax_value'])) {
                    continue; // Skip invalid entries
                }
                Log::info("Class {$result['class']}, Softmax Value {$result['softmax_value']}");
            }
       
            // Proceed with existing database logic
            $highestConfidenceClass = $results[0]['class'];
            $dbResult = Yourmodel::where('number', $highestConfidenceClass)->get();

            Log::info("---------------------------------------Result ---> ----- ", ['class' => $highestConfidenceClass]);

            $searchResults = $dbResult->map(function ($result) {
                return [
                    'number' => $result->number,
                    'name' => mb_convert_encoding($result->name, 'UTF-8', 'auto'),
                    'age' => $result->age,
                    'member' => $result->member,
                    'gpa' => $result->gpa,
                ];
            });

            return response()->json($searchResults);
        } catch (ProcessFailedException $exception) {
            Log::error('Process failed: ' . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Error processing Python script output: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
