<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $applications = $request->user()
            ->jobApplications()
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, fn ($q, $search) => $q->where('company_name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10);

        return ApplicationResource::collection($applications);
    }

    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $application = $request->user()
            ->jobApplications()
            ->create($request->validated());

        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    public function show(JobApplication $application): ApplicationResource
    {
        $this->authorize('view', $application);

        return new ApplicationResource($application);
    }

    public function update(UpdateApplicationRequest $request, JobApplication $application): ApplicationResource
    {
        $this->authorize('update', $application);

        $application->update($request->validated());

        return new ApplicationResource($application);
    }

    public function destroy(JobApplication $application): Response
    {
        $this->authorize('delete', $application);

        $application->delete();

        return response()->noContent();
    }
}