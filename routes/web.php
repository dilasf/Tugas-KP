<?php

use App\Http\Controllers\AccountStudentController;
use App\Http\Controllers\AccountTeacherController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\KnowledgeScoreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SemesterYearController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
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
    Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher_data.index');;
    Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher_data.create');
    Route::post('/teacher/store', [TeacherController::class, 'store'])->name('teacher_data.store');
    Route::get('/teacher/{id}/edit', [TeacherController::class, 'edit'])->name('teacher_data.edit');
    Route::match(['put', 'patch'],'/teacher/{id}', [TeacherController::class, 'update'])->name('teacher_data.update');
    Route::delete('/teacher/{id}', [TeacherController::class, 'destroy'])->name('teacher_data.destroy');
    Route::post('/teacher/import', [TeacherController::class, 'import'])->name('teacher_data.import');

    // Route::get('/books/print', [BookController::class, 'print'])->name('book.print');
    // Route::get('/books/export', [BookController::class, 'export'])->name('book.export');
});

Route::middleware('auth')->group(function () {
    Route::get('/student', [StudentController::class, 'index'])->name('student_data.index');
    Route::get('/student/create', [StudentController::class, 'create'])->name('student_data.create');
    Route::post('/student/store', [StudentController::class, 'store'])->name('student_data.store');
    Route::get('/student/{id}/edit', [StudentController::class, 'edit'])->name('student_data.edit');
    Route::match(['put', 'patch'],'/student/{id}', [StudentController::class, 'update'])->name('student_data.update');
    Route::delete('/student/{id}', [StudentController::class, 'destroy'])->name('student_data.destroy');
    Route::get('/student/create-parent', [StudentController::class, 'createParent'])->name('student_data.create-parent');
    Route::post('/student/store-parent', [StudentController::class, 'storeParent'])->name('student_data.store-parent');
    Route::get('/student/{id}/edit-parent', [StudentController::class, 'editParent'])->name('student_data.edit-parent');
    Route::patch('/student/{id}/update-parent', [StudentController::class, 'updateParent'])->name('student_data.updateParent');
    Route::get('/student/create-guardian', [StudentController::class, 'createGuardian'])->name('student_data.create-guardian');
    Route::post('/student/store-guardian', [StudentController::class, 'storeGuardian'])->name('student_data.store-guardian');
    Route::get('student/{id}/edit-guardian', [StudentController::class, 'editGuardian'])->name('student_data.edit-guardian');
    Route::patch('student/{id}/update-guardian', [StudentController::class, 'updateGuardian'])->name('student_data.updateGuardian');
    Route::post('height_weights/store', [StudentController::class, 'storeheights'])->name('heightstore.store');
    Route::post('/student/import', [StudentController::class, 'import'])->name('student_data.import');
    Route::get('/student/{id}', [StudentController::class, 'show'])->name('student_data.show-detail');
    // Route::get('/students/{id}/grade/{$classSubjectId}', [StudentController::class, 'showGrades'])->name('grade.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/subject', [SubjectController::class, 'index'])->name('subject.index');
    Route::get('/subject/create', [SubjectController::class, 'create'])->name('subject.create');
    Route::post('/subject/store', [SubjectController::class, 'store'])->name('subject.store');
    Route::get('/subject/{id}/edit', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::match(['put', 'patch'],'/subject/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/subject/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/subject/semester_year', [SemesterYearController::class, 'index'])->name('subject.semester_year.index');
    Route::get('/subject/semester_year/create', [SemesterYearController::class, 'create'])->name('subject.semester_year.create');
    Route::post('/subject/semester_year/store', [SemesterYearController::class, 'store'])->name('subject.semester_year.store');
    Route::get('/subject/semester_year/{id}/edit', [SemesterYearController::class, 'edit'])->name('subject.semester_year.edit');
    Route::match(['put', 'patch'],'/subject/semester_year/{id}', [SemesterYearController::class, 'update'])->name('subject.semester_year.update');
    Route::delete('/subject/semester_year/{id}', [SemesterYearController::class, 'destroy'])->name('subject.semester_year.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/class', [StudentClassController::class, 'index'])->name('class.index');
    Route::get('/class/create', [StudentClassController::class, 'create'])->name('class.create');
    Route::post('/class/store', [StudentClassController::class, 'store'])->name('class.store');
    Route::get('/class/{id}/edit', [StudentClassController::class, 'edit'])->name('class.edit');
    Route::match(['put', 'patch'],'/class/{id}', [StudentClassController::class, 'update'])->name('class.update');
    Route::delete('/class/{id}', [StudentClassController::class, 'destroy'])->name('class.destroy');
    // Route::get('guardians/create', [StudentController::class, 'createGuardian'])->name('guardians.create');

});

Route::middleware('auth')->group(function () {
    Route::get('/class-subjects', [ClassSubjectController::class, 'index'])->name('class-subjects.index');
    Route::get('/class-subjects/{id}', [ClassSubjectController::class, 'show'])->name('class-subjects.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/account/teacher', [AccountTeacherController::class, 'index'])->name('account.teacher.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/account/student', [AccountStudentController::class, 'index'])->name('account.student.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/grade/{studentId}/{classSubjectId}', [GradeController::class, 'index'])->name('grade.index');
    Route::get('/grade/detail/{studentId}/{classSubjectId}/{semesterYearId}', [GradeController::class, 'showDetail'])->name('grade.detail');
    Route::get('/grade/edit/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'edit'])->name('grade.edit');
    Route::match(['put', 'patch'],'/grade/update/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'update'])->name('grade.update');

    // Route::get('grades/create/{studentId}/{classSubjectId}', [GradeController::class, 'create'])->name('grade.create');
    // Route::post('grades', [GradeController::class, 'store'])->name('grade.store');

    // Route::post('grade/store-knowledge-score/{studentId}', [GradeController::class, 'storeKnowledgeScore'])->name('grade.storeKnowledgeScore');
    // Route::get('/grade/createAttitudeScore', [GradeController::class, 'createAttitudeScore'])->name('grade.createAttitudeScore');
    // Route::post('/grade/storeAttitudeScore', [GradeController::class, 'storeAttitudeScore'])->name('grade.storeAttitudeScore');
    // Route::get('/grade/createSkillScore', [GradeController::class, 'createSkillScore'])->name('grade.createSkillScore');
    // Route::post('/grade/storeSkillScore', [GradeController::class, 'storeSkillScore'])->name('grade.storeSkillScore');
    // Route::get('/grades/{studentId}/{classSubjectId}/{semesterId}', [GradeController::class,'show'])->name('grade.show');
    // Route::get('/cancel', [GradeController::class, 'cancelAction'])->name('grade.cancel');

});

Route::middleware('auth')->group(function () {
    Route::get('/knowledge-scores', [KnowledgeScoreController::class, 'index'])->name('grade.knowledge_scores.index');
    Route::get('/knowledge-scores/create', [KnowledgeScoreController::class, 'create'])->name('grade.knowledge_scores.create');
    Route::post('/knowledge-scores', [KnowledgeScoreController::class, 'store'])->name('grade.knowledge_scores.store');
    Route::get('/knowledge-scores/{id}/edit', [KnowledgeScoreController::class, 'edit'])->name('grade.knowledge_scores.edit');
    Route::match(['put', 'patch'],'/knowledge-scores/{id}', [KnowledgeScoreController::class, 'update'])->name('grade.knowledge_scores.update');
    Route::delete('/knowledge-scores/{id}', [KnowledgeScoreController::class, 'destroy'])->name('grade.knowledge_scores.destroy');
});


require __DIR__.'/auth.php';
