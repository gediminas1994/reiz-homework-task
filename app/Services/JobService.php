<?php

namespace App\Services;

use App\Jobs\ScrapeUrls;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class JobService
{
    public function createJob(array $data): array
    {
        $jobID = Str::uuid();
        $job = [
            'id' => $jobID,
            'urls' => $data['urls'],
            'selectors' => $data['selectors'],
            'status' => 'pending',
            'data' => null
        ];

        Redis::set('job:' . $jobID, json_encode($job));

        dispatch(new ScrapeUrls($jobID));

        return ['job_id' => $jobID, 'status' => 'Job created successfully'];
    }

    public function getJobById(string $id): ?array
    {
        $jobData = Redis::get('job:' . $id);

        return $jobData ? json_decode($jobData, true) : null;
    }

    public function deleteJob(string $id): bool
    {
        $deleted = Redis::del('job:' . $id);

        return $deleted === 1;
    }

    public function scrape(string $jobID): void
    {
        $jobData = Redis::get('job:' . $jobID);

        if (!$jobData) {
            return;
        }

        $job = json_decode($jobData, true);

        if ($job['status'] === 'completed') {
            return;
        }

        // @todo Add scraping logic when this is a live app
        // This is where scraping logic should be handled,
        // but since the task does not ask for it, I'm just leaving a comment

        $scrapedData = [
            'data' => 'Placeholder scraped data'
        ];

        // Update job data with scraped results and status
        $job['data'] = $scrapedData;
        $job['status'] = 'completed';

        // Save updated job back to Redis
        Redis::set('job:' . $jobID, json_encode($job));
    }
}
