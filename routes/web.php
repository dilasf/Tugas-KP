<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher_data.index');
    Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher_data.create');
    Route::post('/teacher/store', [TeacherController::class, 'store'])->name('teacher_data.store');
    Route::get('/teacher/{id}/edit', [TeacherController::class, 'edit'])->name('teacher_data.edit');
    Route::match(['put', 'patch'],'/teacher/{id}', [TeacherController::class, 'update'])->name('teacher_data.update');
    Route::delete('/teacher/{id}', [TeacherController::class, 'destroy'])->name('teacher_data.destroy');
    Route::post('/teacher/import', [TeacherController::class, 'import'])->name('teacher_data.import');

    // Route::get('/books/print', [BookController::class, 'print'])->name('book.print');
    // Route::get('/books/export', [BookController::class, 'export'])->name('book.export');

});

require __DIR__.'/auth.php';
