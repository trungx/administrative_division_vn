<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Unit;

class UnitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Unit::create([
            'name' => $this->data['name'],
            'name_with_type' => $this->data['name_with_type'],
            'code' => $this->data['code'],
            'parent_code' => $this->data['parent_code'] ?? null,
            'slug' => $this->data['slug'],
            'level' => $this->data['level'],
            'type' => $this->data['type'],
            'path' => $this->data['path'] ?? null,
            'path_with_type' => $this->data['path_with_type'] ?? null,
        ]);

    }
}
