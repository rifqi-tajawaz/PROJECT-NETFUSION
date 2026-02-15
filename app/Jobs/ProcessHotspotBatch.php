<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NetFusion\MikhmonAPI;
use App\Services\NetFusion\Modules\HotspotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BatchGenerationCompleted; // We will create this or just Log for now

class ProcessHotspotBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $qty;
    protected $connectionDetails;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param array $data Batch generation data (prefix, profile, etc)
     * @param int $qty Number of users to generate
     * @param array $connectionDetails Router connection info (ip, user, password_encrypted, port)
     * @param User|null $user User who initiated the action (for notification)
     */
    public function __construct(array $data, int $qty, array $connectionDetails, ?User $user = null)
    {
        $this->data = $data;
        $this->qty = $qty;
        $this->connectionDetails = $connectionDetails;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting Hotspot Batch Generation for {$this->qty} users.");

        try {
            $api = new MikhmonAPI();
            
            // Decrypt password
            $password = $this->connectionDetails['password'];
            try {
                $password = Crypt::decryptString($password);
            } catch (\Exception $e) {
                // Assuming it might be raw if decrypt fails, or just fail
                Log::warning("Could not decrypt router password in Job. Trying raw.");
            }

            // Connect
            if ($api->connect(
                $this->connectionDetails['ip'],
                $this->connectionDetails['user'],
                $password,
                $this->connectionDetails['port']
            )) {
                $service = new HotspotService($api);
                
                // Run generation
                $generated = $service->generateBatch($this->data, $this->qty);
                
                Log::info("Successfully generated " . count($generated) . " users.");
                
                // In a real app, we would notify the user via Websockets or Database Notification
                // For now, we just log it.
                
            } else {
                Log::error("Failed to connect to router in ProcessHotspotBatch job.");
                throw new \Exception("Could not connect to router.");
            }
            
        } catch (\Exception $e) {
            Log::error("Error in ProcessHotspotBatch: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
