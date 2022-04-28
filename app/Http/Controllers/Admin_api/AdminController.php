<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Jobs\DeleteMultipleAdmins;
use App\Model\Admin\Admin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Get admins
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Admin::class, Auth::user());

        try {
            $admins = Admin::lookForOriginal($request->filter)
                            ->tableSort($request)->paginate($request->per_page ?? 50);
            return AdminResource::collection($admins);
        } catch (Exception $e) {
            Log::error("Failed to get admins. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add admin
     *
     * @return AdminResource
     */
    public function store(CreateAdminRequest $request)
    {
        $this->authorize('create', Admin::class, Auth::user());

        try {
            $admin = new Admin();
            $admin->firstname = $request->firstname;
            $admin->lastname = $request->lastname;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->save();

            $admin->statusAccount()->create(['completed' => true]);

            return new AdminResource($admin);
        } catch (Exception $e) {
            Log::error("Failed to add new admin. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get admin
     *
     * @return AdminResource
     */
    public function show(int $id)
    {
        try {
            $admin = Admin::find($id);

            $this->authorize('view', $admin, Auth::user());

            return new AdminResource($admin);
        } catch (Exception $e) {
            Log::error("Failed to get admin. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit admin
     *
     * @return AdminResource
     */
    public function update(UpdateAdminRequest $request, int $id)
    {
        try {
            $admin = Admin::find($id);

            $this->authorize('update', $admin, Auth::user());

            $admin->firstname = $request->firstname;
            $admin->lastname = $request->lastname;
            $admin->email = $request->email;
            if ($request->password) {
                $admin->password = Hash::make($request->password);
            }
            $admin->save();

            return new AdminResource($admin);
        } catch (Exception $e) {
            Log::error("Failed to edit admin. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete admin
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $admin = Admin::find($id);

            $this->authorize('delete', $admin, Auth::user());

            $admin->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete admin. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->selected;
        
            // authorize action
            $categories = Admin::findOrFail($ids);
            foreach ($categories as $category) {
                $this->authorize('delete', $category, Auth::user());
            }

            // start job
            DeleteMultipleAdmins::dispatch($ids, Auth::user());

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to bulk delete admins. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
