<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExecutePythonSubmissions extends Command
{
    protected $signature = 'execute:python-submissions';
    protected $description = 'Executes pending Python challenge submissions and updates their status';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch pending submissions with their test cases and expected outputs
        $submissions = DB::table('challenge_submissions')
            ->join('coding_challenges', 'challenge_submissions.challenge_id', '=', 'coding_challenges.id')
            ->where('challenge_submissions.status', 'pending') // Only process unverified submissions
            ->select(
                'challenge_submissions.id as submission_id',
                'challenge_submissions.code',
                'coding_challenges.testcase',
                'coding_challenges.tc_answer'
            )
            ->get();

        if ($submissions->isEmpty()) {
            $this->info("No pending submissions found.");
            return;
        }

        foreach ($submissions as $submission) {
            $this->info("Processing Submission ID: {$submission->submission_id}");

            // Execute the Python code
            $actualOutput = $this->runPythonCode($submission->code, $submission->testcase);

            // Determine the status
            $status = (trim($actualOutput) === trim($submission->tc_answer)) ? 'correct':'incorrect';

            // Update the database
            DB::table('challenge_submissions')
                ->where('id', $submission->submission_id)
                ->update([
                    'status' => $status
                ]);

            $this->info("Submission ID: {$submission->submission_id} - Status: $status");
        }
    }

    private function runPythonCode($code, $testCase)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'code') . '.py';
        file_put_contents($tempFile, $code);

        $process = proc_open(
            "python3 $tempFile 2>&1",
            [
                0 => ["pipe", "r"],  // STDIN
                1 => ["pipe", "w"],  // STDOUT
                2 => ["pipe", "w"]   // STDERR
            ],
            $pipes
        );

        $output = "";
        if (is_resource($process)) {
            fwrite($pipes[0], $testCase);
            fclose($pipes[0]);

            $output = trim(stream_get_contents($pipes[1]));
            $errorOutput = trim(stream_get_contents($pipes[2]));

            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }

        unlink($tempFile);

        return !empty($errorOutput) ? $errorOutput : $output;
    }
}
