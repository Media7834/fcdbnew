<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\Yourmodel;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage; // Storage????????

class PythonController3 extends Controller
{
    public function executePythonAndSearch(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('images');
        $imageFullPath = storage_path('app/' . $imagePath);

            Log::info("start..................................................................");
        //Log::info("Image saved at: {$imageFullPath}");

        try {
            $process = new Process(['/usr/local/bin/python3.7', '/home/users/2/deca.jp-broad-hyuga-0115/web/fcdb3/app/Python/modelsoftmax.py', $imageFullPath]);
            $process->mustRun();

            $output = trim($process->getOutput());
            $results = json_decode($output, true);

            // Python?????Laravel??????
            //Log::info("Python execution results: " . $output);

            // ?????????????????????
            Storage::put('python_results.txt', $output);

            if (isset($results['error'])) {
                Log::error("Recognition error: {$results['error']}");
            } else {
                // ???????????????????
                $highestResult = $results[array_key_first($results)];
                foreach ($results as $result) {
                    Log::info(" Class {$result['class']}, Softmax Value {$result['softmax_value']}");
                    if ($result['softmax_value'] > $highestResult['softmax_value']) {
                        $highestResult = $result;
                    }
                }
              



                // ??????????????
                $highestClass = $highestResult['class'];

                // ?????...
                $today = now()->toDateString();

                $student = Yourmodel::where('number', $highestClass)->first();
                 Log::info("---------------------------------------Result ---> ----- ", ['class' => $highestClass]);

                if ($student) {
                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'date' => $today],
                        ['status' => 'present']
                    );
                }

                // ???????????????...

                $allStudents = Yourmodel::all();
                $absentStudents = [];

                foreach ($allStudents as $student) {
                    $attendance = Attendance::where('student_id', $student->id)
                                            ->where('date', $today)
                                            ->first();

                    if (!$attendance || $attendance->status == 'absent') {
                        $absentStudents[] = $student->name;
                    }
                }

                $messages = [];
                $absentChunks = array_chunk($absentStudents, 3);
                foreach ($absentChunks as $chunk) {
                    $messages[] = "Absent students: " . implode(', ', $chunk);
                }

                return response()->json($messages);
            }
        } catch (ProcessFailedException $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
