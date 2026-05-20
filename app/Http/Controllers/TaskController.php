<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $query = Task::query()->latest();

        $status = $request->query('status', 'all');
        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'completed') {
            $query->completed();
        }

        $search = $request->query('search');
        if ($search) {
            $query->search($search);
        }

        $tasks = $query->get();

        $totalAll       = Task::count();
        $totalCompleted = Task::where('is_completed', true)->count();

        return response()->json([
            'success' => true,
            'data'    => $tasks,
            'meta'    => [
                'total'     => $totalAll,
                'completed' => $totalCompleted,
                'active'    => $totalAll - $totalCompleted,
            ],
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data'    => $task,
        ], 201); 
    }

    
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $task,
        ]);
    }

    
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data'    => $task->fresh(),
        ]);
    }

    
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
        ]);
    }

   
    public function toggle(Task $task): JsonResponse
    {
        $newStatus = ! $task->is_completed; 
        $task->update(['is_completed' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus 
                ? 'Tugas ditandai selesai.'
                : 'Tugas ditandai belum selesai.',
            'data'    => $task->fresh(),
        ]);
    }
}
