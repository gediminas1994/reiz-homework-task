<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    protected JobService $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function create(CreateJobRequest $request): JsonResponse
    {
        $result = $this->jobService->createJob($request->validated());

        return response()->json($result, 201);
    }

    public function show($id): JsonResponse
    {
        $job = $this->jobService->getJobById($id);

        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json($job);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->jobService->deleteJob($id);

        if (!$deleted) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json(['status' => 'Job deleted successfully']);
    }
}
