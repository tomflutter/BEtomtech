<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Task Management
|--------------------------------------------------------------------------
|
| Semua route di sini otomatis diberi prefix /api oleh Laravel.
| Route menggunakan resourceful pattern + route tambahan untuk toggle.
|
*/

// Resource routes standar: index, store, show, update, destroy
Route::apiResource('tasks', TaskController::class);

// Route tambahan: toggle status selesai dengan cepat
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])
    ->name('tasks.toggle');

/*
|--------------------------------------------------------------------------
| Ringkasan Endpoint:
|--------------------------------------------------------------------------
| GET    /api/tasks              → Daftar semua tugas (+ filter & search)
| POST   /api/tasks              → Buat tugas baru
| GET    /api/tasks/{id}         → Detail satu tugas
| PUT    /api/tasks/{id}         → Update tugas (full)
| PATCH  /api/tasks/{id}         → Update tugas (partial)
| DELETE /api/tasks/{id}         → Hapus tugas
| PATCH  /api/tasks/{id}/toggle  → Toggle status selesai
|--------------------------------------------------------------------------
*/