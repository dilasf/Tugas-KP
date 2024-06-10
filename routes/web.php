<?php

use App\Http\Controllers\AccountStudentController;
use App\Http\Controllers\AccountTeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttitudeScoreController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\KnowledgeScoreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaporController;
use App\Http\Controllers\SemesterYearController;
use App\Http\Controllers\skillScoreController;
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
    return view('dashboard-admin');
})->middleware(['auth', 'verified'])->name('dashboard-admin');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard-admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Data Guru
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


//Data Siswa
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


//Data Mapel
Route::middleware('auth')->group(function () {
    Route::get('/subject', [SubjectController::class, 'index'])->name('subject.index');
    Route::get('/subject/create', [SubjectController::class, 'create'])->name('subject.create');
    Route::post('/subject/store', [SubjectController::class, 'store'])->name('subject.store');
    Route::get('/subject/{id}/edit', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::match(['put', 'patch'],'/subject/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/subject/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');
});

//Data Semester
Route::middleware('auth')->group(function () {
    Route::get('/subject/semester_year', [SemesterYearController::class, 'index'])->name('subject.semester_year.index');
    Route::get('/subject/semester_year/create', [SemesterYearController::class, 'create'])->name('subject.semester_year.create');
    Route::post('/subject/semester_year/store', [SemesterYearController::class, 'store'])->name('subject.semester_year.store');
    Route::get('/subject/semester_year/{id}/edit', [SemesterYearController::class, 'edit'])->name('subject.semester_year.edit');
    Route::match(['put', 'patch'],'/subject/semester_year/{id}', [SemesterYearController::class, 'update'])->name('subject.semester_year.update');
    Route::delete('/subject/semester_year/{id}', [SemesterYearController::class, 'destroy'])->name('subject.semester_year.destroy');
});

//Data Kelas
Route::middleware('auth')->group(function () {
    Route::get('/class', [StudentClassController::class, 'index'])->name('class.index');
    Route::get('/class/create', [StudentClassController::class, 'create'])->name('class.create');
    Route::post('/class/store', [StudentClassController::class, 'store'])->name('class.store');
    Route::get('/class/{id}/edit', [StudentClassController::class, 'edit'])->name('class.edit');
    Route::match(['put', 'patch'],'/class/{id}', [StudentClassController::class, 'update'])->name('class.update');
    Route::delete('/class/{id}', [StudentClassController::class, 'destroy'])->name('class.destroy');
    // Route::get('guardians/create', [StudentController::class, 'createGuardian'])->name('guardians.create');

});


//Kelas dan mapel
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

// //Nilai
// Route::middleware('auth')->group(function () {
//     Route::get('/grades/{studentId}/subjects/{classSubjectId}', [GradeController::class, 'index'])->name('grade.index');

// Route::get('/grades/detail-knowledge-score/{studentId}/{classSubjectId}', [GradeController::class, 'showDetailKnowledgeScore'])->name('grade.detailKnowledgeScore');


// Route::get('/grades/{studentId}/subjects/{classSubjectId}/edit/{semesterYearId}/{assessmentType}', [GradeController::class, 'editKnowledgeScore'])->name('grade.editKnowledgeScore');
// Route::match(['put', 'patch'],'/grades/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'updateKnowledgeScore'])->name('grade.updateKnowledgeScore');



//     //nilai sikap
//     Route::get('/grades/detail-attitude-score/{studentId}/{classSubjectId}', [GradeController::class, 'showDetailAttitudeScore'])->name('grade.detailAttitudeScore');
// Route::get('/grades/edit-attitude-score/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}',[GradeController::class,'editAttitudeScore'])->name('grade.editAttitudeScore');
// Route::match(['put', 'patch'],'/grades/update-attitude-score/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}',  [GradeController::class,'updateAttitudeScore'])->name('grade.updateAttitudeScore');

//     //nilai keterampilan
//     Route::get('/grades/{studentId}/{classSubjectId}', [GradeController::class, 'showDetailSkillScore'])->name('grade.detailSkillScore');
//     Route::get('/grades/edit-skill-score/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'editSkillScore'])->name('grade.editSkillScore');
//     Route::match(['put', 'patch'],'/grades/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'updateSkillScore'])->name('grade.updateSkillScore');

//     // kehadiran
//     Route::patch('/attendance/{studentId}/{classSubjectId}/{semesterYearId}/{type}/{action}', [AttendanceController::class, 'update'])->name('attendance.update');


// });

Route::middleware('auth')->group(function () {
    Route::get('/grades/{studentId}/subjects/{classSubjectId}', [GradeController::class, 'index'])->name('grade.index');
    Route::get('/grades/detail-knowledge-score/{studentId}/{classSubjectId}', [GradeController::class, 'showDetailKnowledgeScore'])->name('grade.detailKnowledgeScore');
    Route::get('/grades/{studentId}/subjects/{classSubjectId}/edit/{semesterYearId}/{assessmentType}', [GradeController::class, 'editKnowledgeScore'])->name('grade.editKnowledgeScore');
    Route::match(['put', 'patch'],'/grades/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'updateKnowledgeScore'])->name('grade.updateKnowledgeScore');

    //nilai sikap
    Route::get('/grades/detail-attitude-score/{studentId}/{classSubjectId}', [GradeController::class, 'showDetailAttitudeScore'])->name('grade.detailAttitudeScore');
    Route::get('/grades/edit-attitude-score/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}',[GradeController::class,'editAttitudeScore'])->name('grade.editAttitudeScore');
    Route::match(['put', 'patch'],'/grades/update-attitude-score/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}',  [GradeController::class,'updateAttitudeScore'])->name('grade.updateAttitudeScore');

    //nilai keterampilan
    Route::get('/grades/{studentId}/{classSubjectId}', [GradeController::class, 'showDetailSkillScore'])->name('grade.detailSkillScore');
    Route::get('/grades/edit-skill-score/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'editSkillScore'])->name('grade.editSkillScore');
    Route::match(['put', 'patch'],'/grades/{studentId}/{classSubjectId}/{semesterYearId}/{assessmentType}', [GradeController::class, 'updateSkillScore'])->name('grade.updateSkillScore');

    // kehadiran
    Route::patch('/attendance/{studentId}/{classSubjectId}/{semesterYearId}/{type}/{action}', [AttendanceController::class, 'update'])->name('attendance.update');
});


//Jenis Nilai Pengetahuan
Route::middleware('auth')->group(function () {
    Route::get('/knowledge-scores', [KnowledgeScoreController::class, 'index'])->name('grade.knowledge_scores.index');
    Route::get('/knowledge-scores/create', [KnowledgeScoreController::class, 'create'])->name('grade.knowledge_scores.create');
    Route::post('/knowledge-scores', [KnowledgeScoreController::class, 'store'])->name('grade.knowledge_scores.store');
    Route::get('/knowledge-scores/{id}/edit', [KnowledgeScoreController::class, 'edit'])->name('grade.knowledge_scores.edit');
    Route::match(['put', 'patch'],'/knowledge-scores/{id}', [KnowledgeScoreController::class, 'update'])->name('grade.knowledge_scores.update');
    Route::delete('/knowledge-scores/{id}', [KnowledgeScoreController::class, 'destroy'])->name('grade.knowledge_scores.destroy');
});

//Jenis Nilai Pengetahuan
Route::middleware('auth')->group(function () {
    Route::get('/attitude-scores', [AttitudeScoreController::class, 'index'])->name('grade.attitude_scores.index');
    Route::get('/attitude-scores/create', [AttitudeScoreController::class, 'create'])->name('grade.attitude_scores.create');
    Route::post('/attitude-scores', [AttitudeScoreController::class, 'store'])->name('grade.attitude_scores.store');
    Route::get('/attitude-scores/{id}/edit', [AttitudeScoreController::class, 'edit'])->name('grade.attitude_scores.edit');
    Route::match(['put', 'patch'],'/attitude-scores/{id}', [AttitudeScoreController::class, 'update'])->name('grade.attitude_scores.update');
    Route::delete('/attitude-scores/{id}', [AttitudeScoreController::class, 'destroy'])->name('grade.attitude_scores.destroy');
});


//Jenis Nilai Pengetahuan
Route::middleware('auth')->group(function () {
    Route::get('/skill-scores', [skillScoreController::class, 'index'])->name('grade.skill_scores.index');
    Route::get('/skill-scores/create', [skillScoreController::class, 'create'])->name('grade.skill_scores.create');
    Route::post('/skill-scores', [skillScoreController::class, 'store'])->name('grade.skill_scores.store');
    Route::get('/skill-scores/{id}/edit', [skillScoreController::class, 'edit'])->name('grade.skill_scores.edit');
    Route::match(['put', 'patch'],'/skill-scores/{id}', [skillScoreController::class, 'update'])->name('grade.skill_scores.update');
    Route::delete('/skill-scores/{id}', [skillScoreController::class, 'destroy'])->name('grade.skill_scores.destroy');
});

Route::middleware('auth')->group(function () {
    // Route::get('/rapors', [RaporController::class, 'index'])->name('rapors.index');
    // Route::post('/report/create', [RaporController::class, 'create'])->name('report.create');
    Route::get('/rapor/{id}', [RaporController::class, 'index'])->name('rapors.index');
    Route::get('/generate-rapor/{id}/{classSubjectId}', [RaporController::class, 'generateRapor']);
});

require __DIR__.'/auth.php';
