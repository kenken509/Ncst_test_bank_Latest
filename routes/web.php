<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackUpController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TestPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProblemSetController;
use App\Http\Controllers\SubjectCodeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TestGeneratorController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    return inertia('Index/Index');
})->name('index');

Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'create')->name('login.show');
    Route::post('/login', 'store')->name('login.store');
    Route::post('/logout', 'destroy')->name('logout');
});

Route::controller(UserManagementController::class)->group(function(){
    Route::get('/test_bank/users', 'showUsers')->name('users.show')->middleware('isCoAdmin');
    Route::get('/test_bank/user/add', 'showAddUser' )->name('user.add');
    Route::post('/test_bank/new-user/store', 'storeUser')->name('user.store');
    Route::delete('test_bank/user/delete/{id}', 'deleteUser')->name('user.delete');
    Route::get('test_bank/user/update/{id}', 'updateUserShow')->name('user.update');
    Route::put('test_bank/user/update/store', 'updateUserStore')->name('user.update.store');
});

Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard', 'showDashboard')->name('dashboard.show')->middleware('isCoAdmin');
});



Route::controller(DepartmentController::class)->group(function(){
    Route::get('/departments', 'showDepartment')->name('departments.show')->middleware('isCoAdmin');
    Route::get('/department/add', 'addDepartment')->name('department.add')->middleware('isAdmin');
    Route::delete('/test_bank/departments/delete/{id}','destroy')->name('department.delete')->middleware('isAdmin');
    Route::post('/test_bank/departments/store', 'storeDepartment')->name('department.store')->middleware('isAdmin');
    Route::get('/test_bank/departments/update/{id}','updateDepartmentShow')->name('department.update.show')->middleware('isAdmin');
    Route::put('/test_bank/departments/update/store', 'updateDepartment')->name('department.update')->middleware('isAdmin');
});

Route::controller(DivisionController::class)->group(function(){
    Route::get('/test_bank/divisions', 'showDivision')->name('division.show')->middleware('isCoAdmin');
    Route::delete('/test_bank/divisions/delete/{id}', 'destroy')->name('division.delete')->middleware('isAdmin');
    Route::get('/test_bank/divisions/add', 'addDivision')->name('division.add')->middleware('isAdmin');
    Route::post('/test_bank/divisions/store', 'storeDivision')->name('division.store')->middleware('isAdmin');
    Route::get('/test_bank/divisions/update/{id}', 'updateShow')->name('division.update.show')->middleware('isAdmin');
    Route::put('/test_bank/divisions/update/store', 'storeUpdate')->name('division.update.store')->middleware('isAdmin');
});

Route::controller(SubjectCodeController::class)->group(function(){
    Route::get('/test_bank/subject_codes', 'showSubjectCodes')->name('subject.codes.show')->middleware('isCoAdmin');
    Route::delete('/test_bank/subject_code/delete/{id}', 'destroy')->name('subject.codes.delete')->middleware('isAdmin');
    Route::get('/test_bank/subject_codes/add', 'showAddSubjectCode')->name('subject.codes.add.show')->middleware('isAdmin');
    Route::post('/test_bank/subject_codes/store', 'store')->name('subject.codes.store')->middleware('isAdmin');
    Route::get('/test_bank/subject_codes/update/{id}', 'updateShow')->name('subject.codes.update.show')->middleware('isAdmin');
    Route::put('/test_bank/subject_codes/update/store', 'update')->name('subject.codes.update')->middleware('isAdmin');
});

Route::controller(QuestionController::class)->group(function(){
    Route::get('/test_bank/questions', 'showQuestions')->name('questions.show')->middleware('isFaculty');
    Route::delete('/test_bank/questions/delete/{id}', 'destroy')->name('questions.delete')->middleware('isAdmin');
    Route::get('/test_bank/questions/add', 'showAddQuestion')->name('question.add')->middleware('isFaculty');
    Route::post('/test_bank/question/store', 'storeQuestion')->name('question.store')->middleware('isFaculty');
    //Route::post('/test_bank/question/store', 'storeQuestion')->name('question.store.modal');
    Route::get('/test_bank/question/update/{id}', 'showUpdate')->name('question.update.show')->middleware('isAdmin');
    Route::post('/test_bank/question/update', 'update')->name('question.update')->middleware('isAdmin');

});

Route::controller(TestGeneratorController::class)->group(function(){
    Route::get('/test_bank/test_generator', 'showTestGenerator')->name('testGen.show');
    Route::match(['get', 'post'], '/test_bank/generated_test', 'showGeneratedExam')->name('testGen.generated');
    Route::post('/test_bank/generated_test', 'showGeneratedExam')->name('testGen.test');

    // new 

    Route::get('/test_bank/test-generator', 'showTestGeneratorNew')->name('testGen.show.new'); 
    Route::post('/test_bank/test-generator/generate', 'generateExam')->name('testGen.generate');

    Route::get('/test_bank/test-generator/generate/clean-up','cleanUp')->name('file.cleanUp');
})->middleware('isAdmin');

Route::controller(TestPageController::class)->group(function(){
    Route::get('/test', 'showTestPage')->name('testPage');
    Route::get('/test_gen_pdf','generateAndSavePDF')->name('test.genPdf');
});

Route::controller(BackUpController::class)->group(function(){
    Route::get('/backup-download-db', 'show')->name('backup.show')->middleware('isAdmin');
    Route::get('/download-database-backup', 'download')->name('backup.download')->middleware('isAdmin');
    Route::get('/backup-restore', 'showRestore')->name('backup.restore.show')->middleware('isAdmin');
    Route::post('/backup-restore-db', 'restoreDatabase')->name('backup.restore.restore')->middleware('isAdmin');
    Route::get('/test_con', 'testDatabaseConnection')->name('test.con')->middleware('isAdmin');
});

Route::controller(AnnouncementController::class)->group(function(){
    Route::get('test_bank/announcement', 'showAnnouncement')->name('announcement.show')->middleware('isCoAdmin');
    Route::get('test_bank/announcement/new', 'showAddAnnouncement')->name('announcement.add')->middleware('isCoAdmin');
    Route::post('test_bank/announcement/store', 'storeAnnouncement')->name('announcement.store')->middleware('isCoAdmin');
    Route::delete('test_bank/announcement/delete/{marking}','delete')->name('announcement.delete')->middleware('isAdmin');
    Route::put('test_bank/announcement/delete', 'updateAnnouncement')->name('announcement.update')->middleware('isAdmin');
});

Route::controller(ProblemSetController::class)->group(function(){
    Route::post('test_bank/problem_set/store','store')->name('problem.set.store');
});

Route::controller(ResetPasswordController::class)->group(function(){
    Route::post('/reset_password/send-email', 'sendPasswordReset')->name('reset.password.verify');
    Route::get('/pasword-reset/verify/{token}','verifyResetEmail')->name('reset.password.verify.email');
    Route::post('/password-reset/store', 'resetPassword')->name('reset.password.store');
});