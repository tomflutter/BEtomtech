<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * TaskController menangani semua operasi CRUD untuk resource Task.
 * Semua respons dikembalikan dalam format JSON standar.
 */
class TaskController extends Controller
{
    /**
     * GET /api/tasks
     * Ambil semua tugas, dengan opsi filter & pencarian.
     *
     * Query params:
     *   - status: 'all' | 'active' | 'completed' (default: 'all')
     *   - search: string (pencarian berdasarkan title)
     */
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

        // ← hitung meta dari seluruh data, bukan dari $tasks yang sudah difilter
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

    /**
     * POST /api/tasks
     * Buat tugas baru dengan validasi dari StoreTaskRequest.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        // Data sudah tervalidasi oleh Form Request
        $task = Task::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data'    => $task,
        ], 201); // 201 Created
    }

    /**
     * GET /api/tasks/{id}
     * Ambil satu tugas berdasarkan ID.
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $task,
        ]);
    }

    /**
     * PUT/PATCH /api/tasks/{id}
     * Perbarui tugas — bisa update title, description, atau is_completed.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data'    => $task->fresh(), // Reload dari database
        ]);
    }

    /**
     * DELETE /api/tasks/{id}
     * Hapus tugas secara permanen.
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
        ]);
    }

    /**
     * PATCH /api/tasks/{id}/toggle
     * Toggle status is_completed dengan cepat (shortcut).
     */
    public function toggle(Task $task): JsonResponse
    {
        $newStatus = ! $task->is_completed; // ← simpan nilai baru DULU
        $task->update(['is_completed' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus // ← pakai $newStatus, bukan $task->is_completed
                ? 'Tugas ditandai selesai.'
                : 'Tugas ditandai belum selesai.',
            'data'    => $task->fresh(), // ← konsisten dengan update()
        ]);
    }
}
