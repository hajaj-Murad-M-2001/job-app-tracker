<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'position' => $this->position,
            'status' => $this->status,
            'job_url' => $this->job_url,
            'salary' => $this->salary,
            'notes' => $this->notes,
            'applied_at' => $this->applied_at?->toDateString(),
            'created_at' => $this->created_at,
        ];
    }
}