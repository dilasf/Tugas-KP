<?php

use App\Http\Controllers\AccountStudentController;
use App\Http\Controllers\AccountTeacherController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttitudeScoreController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HeightWeightController;
use App\Http\Controllers\KnowledgeScoreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaporController;
use App\Http\Controllers\SemesterYearController;
use App\Http\Controllers\skillScoreController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ValidationsController;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Data Guru
Route::middleware(['auth', 'verified', 'role:admin|kepala_sekolah'])->group(function () {
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
Route::middleware(['auth', 'verified', 'role:admin|guru_mapel|guru_kelas|kepala_sekolah'])->group(function () {
    Route::get('/student', [StudentController::class, 'index'])->name('student_data.index');
    Route::get('/student/create', [StudentController::class, 'create'])->name('student_data.create');
    Route::post('/student/store', [StudentController::class, 'store'])->name('student_data.store');
    Route::get('/student/cancel', [StudentController::class, 'cancel'])->name('student_data.cancel');

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
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/subject', [SubjectController::class, 'index'])->name('subject.index');
    Route::get('/subject/create', [SubjectController::class, 'create'])->name('subject.create');
    Route::post('/subject/store', [SubjectController::class, 'store'])->name('subject.store');
    Route::get('/subject/{id}/edit', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::match(['put', 'patch'],'/subject/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/subject/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');
});

//Data Semester
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/subject/semester_year', [SemesterYearController::class, 'index'])->name('subject.semester_year.index');
    Route::get('/subject/semester_year/create', [SemesterYearController::class, 'create'])->name('subject.semester_year.create');
    Route::post('/subject/semester_year/store', [SemesterYearController::class, 'store'])->name('subject.semester_year.store');
    Route::get('/subject/semester_year/{id}/edit', [SemesterYearController::class, 'edit'])->name('subject.semester_year.edit');
    Route::match(['put', 'patch'],'/subject/semester_year/{id}', [SemesterYearController::class, 'update'])->name('subject.semester_year.update');
    Route::delete('/subject/semester_year/{id}', [SemesterYearController::class, 'destroy'])->name('subject.semester_year.destroy');
});

//Data Kelas
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/class', [StudentClassController::class, 'index'])->name('class.index');
    Route::get('/class/create', [StudentClassController::class, 'create'])->name('class.create');
    Route::post('/class/store', [StudentClassController::class, 'store'])->name('class.store');
    Route::get('/class/{id}/edit', [StudentClassController::class, 'edit'])->name('class.edit');
    Route::match(['put', 'patch'],'/class/{id}', [StudentClassController::class, 'update'])->name('class.update');
    Route::delete('/class/{id}', [StudentClassController::class, 'destroy'])->name('class.destroy');
    // Route::get('guardians/create', [StudentController::class, 'createGuardian'])->name('guardians.create');

});


//Kelas dan mapel
Route::middleware(['auth', 'verified', 'role:guru_mapel|guru_kelas'])->group(function () {
    Route::get('/class-subjects', [ClassSubjectController::class, 'index'])->name('class-subjects.index');
    Route::get('/class-subjects/{id}', [ClassSubjectController::class, 'show'])->name('class-subjects.show');
});



Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/account/teacher', [AccountTeacherController::class, 'index'])->name('account.teacher.index');
    Route::get('/account/teacher/{id}/edit', [AccountTeacherController::class, 'edit'])->name('account.teacher.edit');
    Route::patch('/account/teacher/{id}', [AccountTeacherController::class, 'update'])->name('account.teacher.update');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/account/student', [AccountStudentController::class, 'index'])->name('account.student.index');
    Route::get('/account/student/{id}/edit', [AccountStudentController::class, 'edit'])->name('account.student.edit');
    Route::patch('/account/student/{id}', [AccountStudentController::class, 'update'])->name('account.student.update');
});


//Jenis Nilai Pengetahuan
Route::middleware(['auth', 'verified', 'role:guru_mapel|guru_kelas'])->group(function () {
    Route::get('/knowledge-scores', [KnowledgeScoreController::class, 'index'])->name('grade.knowledge_scores.index');
    Route::get('/knowledge-scores/create', [KnowledgeScoreController::class, 'create'])->name('grade.knowledge_scores.create');
    Route::post('/knowledge-scores', [KnowledgeScoreController::class, 'store'])->name('grade.knowledge_scores.store');
    Route::get('/knowledge-scores/{assessment_type}/edit', [KnowledgeScoreController::class, 'edit'])->name('grade.knowledge_scores.edit');
    Route::match(['put', 'patch'],'/knowledge-scores/{assessment_type}', [KnowledgeScoreController::class, 'update'])->name('grade.knowledge_scores.update');
    Route::delete('/knowledge-scores/{assessment_type}', [KnowledgeScoreController::class, 'destroy'])->name('grade.knowledge_scores.destroy');
});

//Jenis Nilai Sikap
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/attitude-scores', [AttitudeScoreController::class, 'index'])->name('grade.attitude_scores.index');
    Route::get('/attitude-scores/create', [AttitudeScoreController::class, 'create'])->name('grade.attitude_scores.create');
    Route::post('/attitude-scores', [AttitudeScoreController::class, 'store'])->name('grade.attitude_scores.store');
    Route::get('/attitude-scores/{assessment_type}/edit', [AttitudeScoreController::class, 'edit'])->name('grade.attitude_scores.edit');
    Route::match(['put', 'patch'],'/attitude-scores/{assessment_type}', [AttitudeScoreController::class, 'update'])->name('grade.attitude_scores.update');
    Route::delete('/attitude-scores/{assessment_type}', [AttitudeScoreController::class, 'destroy'])->name('grade.attitude_scores.destroy');
});


//Jenis Nilai Keterampilan
Route::middleware(['auth', 'verified', 'role:guru_mapel|guru_kelas'])->group(function () {
    Route::get('/skill-scores', [skillScoreController::class, 'index'])->name('grade.skill_scores.index');
    Route::get('/skill-scores/create', [skillScoreController::class, 'create'])->name('grade.skill_scores.create');
    Route::post('/skill-scores', [skillScoreController::class, 'store'])->name('grade.skill_scores.store');
    Route::get('/skill-scores/{assessment_type}/edit', [skillScoreController::class, 'edit'])->name('grade.skill_scores.edit');
    Route::patch('/skill-scores/{assessment_type}', [SkillScoreController::class, 'update'])->name('grade.skill_scores.update');
    Route::delete('/skill-scores/{assessment_type}', [skillScoreController::class, 'destroy'])->name('grade.skill_scores.destroy');

});

//Nilai
Route::middleware(['auth', 'verified', 'role:guru_mapel|guru_kelas'])->group(function () {
    Route::get('/{studentId}/{classSubjectId}', [GradeController::class, 'index'])->name('grade.index');
    Route::get('/grade/{studentId}/{classSubjectId}/detail-knowledge-score', [GradeController::class, 'detailKnowledgeScore'])->name('grade.detailKnowledgeScore');
    Route::get('/grade/{studentId}/{classSubjectId}/edit-knowledge-score/{assessmentType}', [GradeController::class, 'editKnowledgeScore'])->name('grade.editKnowledgeScore');
    Route::match(['put', 'patch'],'/grade/{studentId}/{classSubjectId}/update-knowledge-score/{assessmentType}', [GradeController::class, 'updateKnowledgeScore'])->name('grade.updateKnowledgeScore');

    //nilai sikap
    Route::get('/grade/{studentId}/{classSubjectId}/detail-attitude-score', [GradeController::class, 'detailAttitudeScore'])->name('grade.detailAttitudeScore');
    Route::get('/grade/{studentId}/{classSubjectId}/edit-attitude-score/{assessmentType}', [GradeController::class, 'editAttitudeScore'])->name('grade.editAttitudeScore');
    Route::match(['put', 'patch'],'/grade/{studentId}/{classSubjectId}/update-attitude-score/{assessmentType}', [GradeController::class, 'updateAttitudeScore'])->name('grade.updateAttitudeScore');

    //nilai keterampilan
    Route::get('/grade/{studentId}/{classSubjectId}/detail-skill-score', [GradeController::class, 'detailSkillScore'])->name('grade.detailSkillScore');
    Route::get('/grade/{studentId}/{classSubjectId}/edit-skill-score/{assessmentType}', [GradeController::class, 'editSkillScore'])->name('grade.editSkillScore');
    Route::match(['put', 'patch'],'/grade/{studentId}/{classSubjectId}/update-skill-score/{assessmentType}', [GradeController::class, 'updateSkillScore'])->name('grade.updateSkillScore');

    // Route untuk penambahan absensi
    Route::post('/attendance/increment/sick', [AttendanceController::class, 'incrementSick'])->name('attendance.increment.sick');
    Route::post('/attendance/increment/permission', [AttendanceController::class, 'incrementPermission'])->name('attendance.increment.permission');
    Route::post('/attendance/increment/unexcused', [AttendanceController::class, 'incrementUnexcused'])->name('attendance.increment.unexcused');

    // Route untuk pengurangan absensi
    Route::post('/attendance/decrement/sick', [AttendanceController::class, 'decrementSick'])->name('attendance.decrement.sick');
    Route::post('/attendance/decrement/permission', [AttendanceController::class, 'decrementPermission'])->name('attendance.decrement.permission');
    Route::post('/attendance/decrement/unexcused', [AttendanceController::class, 'decrementUnexcused'])->name('attendance.decrement.unexcused');
    Route::post('/attendance/update', [AttendanceController::class, 'updateAttendance'])->name('attendance.update');

});

//prestasi
Route::middleware(['auth', 'verified', 'role:guru_kelas'])->group(function () {
    Route::get('/students/{studentId}/achievements/create/{semester_year_id}', [AchievementController::class, 'create'])->name('achievements.create');
    Route::post('/students/{studentId}/achievements/store/{semester_year_id}', [AchievementController::class, 'store'])->name('achievements.store');
    Route::get('/students/{studentId}/achievements/{achievementId}/edit', [AchievementController::class, 'edit'])->name('achievements.edit');
    Route::match(['put', 'patch'], '/achievements/{studentId}/{achievementId}', [AchievementController::class, 'update'])->name('achievements.update');
    Route::delete('/achievements/{achievementId}', [AchievementController::class, 'destroy'])->name('achievements.destroy');
});

//ekstrakulikuler
Route::middleware(['auth', 'verified', 'role:guru_kelas'])->group(function () {
    Route::get('/students/{studentId}/extracurriculars/create/{semester_year_id}', [ExtracurricularController::class, 'create'])->name('extracurriculars.create');
    Route::post('/students/{studentId}/extracurriculars/store/{semester_year_id}', [ExtracurricularController::class, 'store']) ->name('extracurriculars.store');
    Route::get('/students/{studentId}/extracurriculars/{extracurricularId}/edit', [ExtracurricularController::class, 'edit'])->name('extracurriculars.edit');
    Route::match(['put', 'patch'], '/extracurricular/{studentId}/{extracurricularId}', [ExtracurricularController::class, 'update'])->name('extracurriculars.update');
    Route::delete('/extracurricular/{extracurricularId}', [ExtracurricularController::class, 'destroy'])->name('extracurriculars.destroy');
});

//kesehatan
Route::middleware(['auth', 'verified', 'role:guru_kelas'])->group(function () {
    Route::get('students/{studentId}/healths/create/{semester_year_id}/{aspectName}', [HealthController::class, 'create'])->name('healths.create');
    Route::get('students/{studentId}/healths/{healthId}/edit/{aspectName}', [HealthController::class, 'edit'])->name('healths.edit');
    Route::post('/healths/storeOrUpdate/{studentId}/{semester_year_id}/{aspectName}', [HealthController::class, 'storeOrUpdate'])->name('healths.storeOrUpdate');
});

//Tinggi Badan
Route::middleware(['auth', 'verified', 'role:guru_kelas'])->group(function () {
    Route::get('/height_weights/{studentId}/{heightWeightId}/{aspectName}/{semester_year_id}/edit', [HeightWeightController::class, 'edit'])
    ->name('height_weights.edit');
    Route::patch('/height_weights/{studentId}/{heightWeightId}/{aspectName}/{semester_year_id}', [HeightWeightController::class, 'update'])
        ->name('height_weights.update');

});

Route::middleware(['auth', 'verified', 'role:kepala_sekolah'])->group(function () {
    Route::get('/validation', [ValidationsController::class, 'index'])->name('rapors.validation.index');
    Route::get('/validation/{id}', [ValidationsController::class, 'show'])->name('rapors.validation.detail');
    Route::post('/validation/{id}/approve', [ValidationsController::class, 'approve'])->name('rapors.validation.approve');
    Route::post('/validation/{id}/reject', [ValidationsController::class, 'reject'])->name('rapors.validation.reject');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('rapors/lihat/{studentId}', [RaporController::class, 'index'])->name('rapors.index');
    Route::get('/rapors/download/{studentId}', [RaporController::class, 'downloadPDF'])->name('rapors.download');

});

Route::middleware(['auth', 'verified', 'role:guru_kelas|kepala_sekolah|siswa'])->group(function () {
    //  Route::get('rapors/lihat/{studentId}', [RaporController::class, 'index'])->name('rapors.index');
     Route::get('rapors/edit/{studentId}/{semesterYearId}/{classSubjectId}', [RaporController::class, 'edit'])->name('rapors.edit');
    Route::match(['put', 'patch'], '/rapors/{rapor}', [RaporController::class, 'update'])->name('rapors.update');

    Route::get('/rapors/{studentId}/edit-suggestion/{semesterYearId}', [RaporController::class, 'editSuggestion'])->name('rapors.editSuggestion');
    Route::match(['put', 'patch'],'/rapors/{studentId}/update-suggestion/{semesterYearId}', [RaporController::class, 'updateSuggestion'])->name('rapors.updateSuggestion');

    Route::get('/rapors/{studentId}/create/{aspectName}', [RaporController::class, 'createAspect'])->name('rapors.createAspect');
    Route::post('/rapors/{studentId}/store/{aspectName}', [RaporController::class, 'storeAspect'])->name('rapors.storeAspect');
    Route::get('/rapors/{studentId}/edit/{raporId}/{aspectName}', [RaporController::class, 'editAspect'])->name('rapors.editAspect');
    Route::patch('/rapors/{studentId}/update/{raporId}/{aspectName}', [RaporController::class, 'updateAspect'])->name('rapors.updateAspect');

    Route::post('/send-report/{raporId}', [RaporController::class, 'sendReport'])->name('send.report');
});




require __DIR__.'/auth.php';
