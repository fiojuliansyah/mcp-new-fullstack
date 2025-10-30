<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ZoomSignatureController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Tutor\TutorQuizController;
use App\Http\Controllers\Tutor\TutorClassController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminReplayController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminMaterialController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Student\StudentQuizController;
use App\Http\Controllers\Tutor\TutorMaterialController;
use App\Http\Controllers\Tutor\TutorOverviewController;
use App\Http\Controllers\Tutor\TutorScheduleController;
use App\Http\Controllers\Webhook\ZoomWebhookController;
use App\Http\Controllers\Admin\AdminClassroomController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Tutor\TutorDashboardController;
use App\Http\Controllers\Tutor\TutorReplayVideoController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Student\StudentClassroomController;
use App\Http\Controllers\Student\StudentDashboardController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classroom}/join', [StudentClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classes/{classroom}/detail', [StudentClassroomController::class, 'show'])->name('classrooms.show');

    Route::get('/quizzes/{quiz}/preview', [StudentQuizController::class, 'preview'])->name('quizzes.preview');
    Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'storeAnswer'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/attempt/{attempt}/view-answer', [StudentQuizController::class, 'viewAnswer'])->name('quizzes.viewAnswer');
    Route::get('/quizzes/{quiz}/detail', [StudentQuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/start', [StudentQuizController::class, 'start'])->name('quizzes.start');
});


Route::middleware('auth')->prefix('parent.')->group(function () {
    

});

Route::middleware('auth')->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{slug}', [TutorDashboardController::class, 'show'])->name('dashboard.subject');
    Route::get('/all-class/{slug}', [TutorClassController::class, 'index'])->name('class.index');
    Route::get('/class/show/{classroom}', [TutorClassController::class, 'show'])->name('class.show');

    Route::get('/student/subscription/{slug}/overview', [TutorOverviewController::class, 'subscriptionIndex'])->name('overview.subscription.index');
    Route::get('/student/subscription/{user}/detail/overview', [TutorOverviewController::class, 'subscriptionShow'])->name('overview.subscription.show');

    Route::get('/student/performance/{slug}/overview', [TutorOverviewController::class, 'performanceIndex'])->name('overview.performance.index');
    Route::get('/student/performances/{slug}/{user}', [TutorOverviewController::class, 'peformanceShow'])->name('overview.performance.show');

    
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/{slug}/create', [TutorScheduleController::class, 'create'])->name('create');
        Route::post('/store', [TutorScheduleController::class, 'store'])->name('store');
        Route::put('/{id}/update', [TutorScheduleController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [TutorScheduleController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}/{date}', [TutorDashboardController::class, 'getSchedules'])->name('byDate');
    });
    
    Route::prefix('replay')->name('replay.')->group(function () {
        Route::get('/{slug}/create', [TutorReplayVideoController::class, 'create'])->name('create');
        Route::get('/get-classrooms/{formId}', [TutorReplayVideoController::class, 'getClassrooms'])->name('getClassrooms');
        Route::get('/get-topics/{classroomId}', [TutorReplayVideoController::class, 'getTopics'])->name('getTopics');
        Route::post('/store', [TutorReplayVideoController::class, 'store'])->name('store');
        Route::post('/upload-chunk', [TutorReplayVideoController::class, 'uploadChunk'])->name('upload-chunk');
    });

    Route::prefix('material')->name('material.')->group(function () {
        Route::get('/{slug}/create', [TutorMaterialController::class, 'create'])->name('create');
        Route::get('/get-classrooms/{formId}', [TutorMaterialController::class, 'getClassrooms'])->name('getClassrooms');
        Route::get('/get-topics/{classroomId}', [TutorMaterialController::class, 'getTopics'])->name('getTopics');
        Route::post('/upload-chunk', [TutorMaterialController::class, 'uploadChunk'])->name('upload-chunk');
        Route::post('/store', [TutorMaterialController::class, 'store'])->name('store');
    });
    
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/create/{slug}', [TutorQuizController::class, 'create'])->name('create');
        Route::get('/get-classrooms/{formId}', [TutorQuizController::class, 'getClassrooms'])->name('getClassrooms');
        Route::get('/get-topics/{classroomId}', [TutorQuizController::class, 'getTopics'])->name('getTopics');
        Route::post('/store', [TutorQuizController::class, 'store'])->name('store');
        Route::put('/{quiz}/update', [TutorQuizController::class, 'update'])->name('update');

        Route::get('/{quiz}/question/create', [TutorQuizController::class, 'createQuestion'])->name('question.create');
        Route::post('/{quiz}/question/store', [TutorQuizController::class, 'storeQuestion'])->name('question.store');
        Route::get('{quiz}/question/preview', [TutorQuizController::class, 'preview'])->name('question.preview');
    });

});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {

            Route::get('/', [AdminUserController::class, 'index'])->name('index');

            Route::get('/students', [AdminStudentController::class, 'index'])->name('student');
            Route::get('/students/create', [AdminStudentController::class, 'create'])->name('student.create');
            Route::post('/students/store', [AdminStudentController::class, 'store'])->name('student.store');
            Route::get('/students/{id}/edit', [AdminStudentController::class, 'edit'])->name('student.edit');
            Route::put('/students/{id}/update', [AdminStudentController::class, 'update'])->name('student.update');
            Route::get('/students/{id}/detail', [AdminStudentController::class, 'show'])->name('student.show');

            Route::get('/parents', [AdminUserController::class, 'parent'])->name('parent');
            Route::get('/tutors', [AdminUserController::class, 'tutor'])->name('tutor');
        }); 
        
        Route::resource('plans', AdminPlanController::class);
        Route::get('/plans/{id}/price', [AdminPlanController::class, 'getPrice'])->name('plans.price');

        Route::resource('coupons', AdminCouponController::class);
        Route::resource('subscriptions', AdminSubscriptionController::class);
        Route::resource('classrooms', AdminClassroomController::class);

        Route::resource('replays', AdminReplayController::class);
        Route::post('/replays/upload-chunk', [AdminReplayController::class, 'uploadChunk'])->name('replays.upload-chunk');
        Route::delete('/replays/video/{video}', [AdminReplayController::class, 'deleteVideo'])->name('replays.delete-video');
        
        Route::resource('materials', AdminMaterialController::class);
        Route::post('/materials/upload-chunk', [AdminMaterialController::class, 'uploadChunk'])->name('materials.upload-chunk');
        Route::delete('/materials/file/{file}', [AdminMaterialController::class, 'deleteFile'])->name('materials.delete-file');

        Route::resource('schedules', AdminScheduleController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/webhooks/zoom', [ZoomWebhookController::class, 'handle']);
Route::post('/zoom/signature', [ZoomSignatureController::class, 'generateSignature'])->name('zoom.signature');

require __DIR__.'/auth.php';
