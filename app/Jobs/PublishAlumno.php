<?php

namespace App\Jobs;

use App\Models\Alumnos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishAlumno implements ShouldQueue
{
    use Queueable;
    protected $alumno;

    /**
     * Create a new job instance.
     */
    public function __construct(Alumnos $alumno)
    {
        $this->alumno = $alumno;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->alumno->update(['is_published' => true]);
    }
}
