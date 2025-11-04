<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ZoomSignatureController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Tutor\TutorQuizController;
use App\Http\Controllers\Admin\AdminTutorController;
use App\Http\Controllers\Tutor\TutorClassController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminParentController;
use App\Http\Controllers\Admin\AdminReplayController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\AdminMaterialController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Student\StudentQuizController;
use App\Http\Controllers\Tutor\TutorMaterialController;
use App\Http\Controllers\Tutor\TutorOverviewController;
use App\Http\Controllers\Tutor\TutorScheduleController;
use App\Http\Controllers\Webhook\ZoomWebhookController;
use App\Http\Controllers\Admin\AdminClassroomController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Parent\ParentProfileController;
use App\Http\Controllers\Tutor\TutorDashboardController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Student\StudentReplayController;
use App\Http\Controllers\Admin\AdminPerformanceController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Student\StudentPaymentController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Tutor\TutorReplayVideoController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Student\StudentScheduleController;
use App\Http\Controllers\Student\StudentClassroomController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentEnrollmentController;

Route::get('/', [FrontendController::class, 'index']);



Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::get('/replay/{replay}/video', [StudentReplayController::class, 'show'])->name('replay.show');
    Route::post('/replay/track-progress/{replay_video}', [StudentReplayController::class, 'trackProgress'])->name('replay.track-progress');

    Route::get('/classes/{classroom}/join', [StudentClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classes/{classroom}/detail', [StudentClassroomController::class, 'show'])->name('classrooms.show');
    
    Route::get('/join/{schedule}/zoom', [StudentScheduleController::class, 'class'])->name('schedule.class');
    Route::get('/join/{schedule}/zoom/embed', [StudentScheduleController::class, 'embed'])->name('schedule.zoom');
    Route::post('/attendance/join/{scheduleId}', [StudentScheduleController::class, 'recordJoin'])->name('attendance.join');
    Route::post('/attendance/out/{scheduleId}', [StudentScheduleController::class, 'recordOut'])->name('attendance.out');

    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        Route::get('/{quiz}/preview', [StudentQuizController::class, 'preview'])->name('preview');
        Route::post('/{quiz}/submit', [StudentQuizController::class, 'storeAnswer'])->name('submit');
        Route::get('/{quiz}/attempt/{attempt}/view-answer', [StudentQuizController::class, 'viewAnswer'])->name('viewAnswer');
        Route::get('/{quiz}/detail', [StudentQuizController::class, 'show'])->name('show');
        Route::get('/{quiz}/start', [StudentQuizController::class, 'start'])->name('start');
    });

    Route::prefix('enrollment')->name('enrollment.')->group(function () {
        Route::get('/class-type', [StudentEnrollmentController::class, 'classType'])->name('class-type');
        Route::get('/checkout', [StudentEnrollmentController::class, 'checkout'])->name('checkout');
        Route::get('/{subscription}/timetable', [StudentEnrollmentController::class, 'timetable'])->name('timetable');
        Route::post('/store/enrollment', [StudentEnrollmentController::class, 'store'])->name('store');
        Route::get('/{subscription}/summary', [StudentEnrollmentController::class, 'summary'])->name('summary');
        Route::post('/subscription/{id}/voucher', [StudentEnrollmentController::class, 'applyVoucher'])->name('applyVoucher');
        Route::post('/subscription/{id}/remove-voucher', [StudentEnrollmentController::class,'removeVoucher'])->name('removeVoucher');
        Route::post('/subscription/{id}/plusian/voucher', [StudentEnrollmentController::class, 'applyPlusian'])->name('applyPlusian');
        Route::post('/subscription/{id}/plusian/remove-voucher', [StudentEnrollmentController::class,'removePlusian'])->name('removePlusian');
        Route::post('/subscription/{id}/update-plan', [StudentEnrollmentController::class,'updatePlan'])->name('updatePlan');

        Route::get('/{subscription}/payment', [StudentPaymentController::class, 'index'])->name('payment');
        Route::post('/{subscription}/process-payment', [StudentPaymentController::class, 'processPayment'])->name('processPayment');

        Route::post('/payment/billplz/webhook', [StudentPaymentController::class, 'billplzWebhook'])->name('payment.billplzWebhook');
        Route::get('/payment/{subscription}/callback', [StudentPaymentController::class, 'paymentCallback'])->name('payment.paymentCallback');
        Route::get('/payment-success/{subscription}', [StudentPaymentController::class, 'paymentSuccess'])->name('payment.paymentSuccess');

    });
    
});


Route::middleware('auth')->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{slug}', [ParentDashboardController::class, 'show'])->name('dashboard.child');

    Route::get('/profile', [ParentProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ParentProfileController::class, 'update'])->name('profile.update');

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
            Route::get('/students/{id}/show', [AdminStudentController::class, 'show'])->name('student.show');
            Route::get('/students/{id}/detail', [AdminStudentController::class, 'detail'])->name('student.detail');
            
            
            Route::get('/parents', [AdminParentController::class, 'index'])->name('parents.index');
            Route::get('/parents/create', [AdminParentController::class, 'create'])->name('parents.create');
            Route::post('/parents/store', [AdminParentController::class, 'store'])->name('parents.store');
            Route::get('/parents/{id}/edit', [AdminParentController::class, 'edit'])->name('parents.edit');
            Route::put('/parents/{id}/update', [AdminParentController::class, 'update'])->name('parents.update');
            Route::get('/parents/{id}/show', [AdminParentController::class, 'show'])->name('parents.show');
            Route::get('/parents/{id}/detail', [AdminParentController::class, 'detail'])->name('parents.detail');
            
            Route::get('/tutors', [AdminTutorController::class, 'index'])->name('tutors.index');
            Route::get('/tutors/create', [AdminTutorController::class, 'create'])->name('tutors.create');
            Route::post('/tutors/store', [AdminTutorController::class, 'store'])->name('tutors.store');
            Route::get('/tutors/{id}/edit', [AdminTutorController::class, 'edit'])->name('tutors.edit');
            Route::put('/tutors/{id}/update', [AdminTutorController::class, 'update'])->name('tutors.update');
            Route::get('/tutors/{id}/show', [AdminTutorController::class, 'show'])->name('tutors.show');
            Route::get('/tutors/{id}/detail', [AdminTutorController::class, 'detail'])->name('tutors.detail');
        }); 
        
        Route::resource('plans', AdminPlanController::class);
        Route::get('/plans/{id}/price', [AdminPlanController::class, 'getPrice'])->name('plans.price');
        
        Route::resource('coupons', AdminCouponController::class);
        Route::get('/subscriptions/{id}/renewal', [AdminSubscriptionController::class, 'renewal'])->name('subscriptions.renewal');
        Route::get('/subscriptions/{id}/refund', [AdminSubscriptionController::class, 'refund'])->name('subscriptions.refund');
        Route::get('/subscriptions/{id}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::resource('subscriptions', AdminSubscriptionController::class);
        Route::resource('classrooms', AdminClassroomController::class);

        Route::resource('replays', AdminReplayController::class);
        Route::post('/replays/upload-chunk', [AdminReplayController::class, 'uploadChunk'])->name('replays.upload-chunk');
        Route::delete('/replays/video/{video}', [AdminReplayController::class, 'deleteVideo'])->name('replays.delete-video');
        
        Route::resource('materials', AdminMaterialController::class);
        Route::post('/materials/upload-chunk', [AdminMaterialController::class, 'uploadChunk'])->name('materials.upload-chunk');
        Route::delete('/materials/file/{file}', [AdminMaterialController::class, 'deleteFile'])->name('materials.delete-file');

        Route::resource('schedules', AdminScheduleController::class);
        
        Route::get('/performances', [AdminPerformanceController::class, 'index'])->name('performances.index');
        Route::get('/performances/{id}/show', [AdminPerformanceController::class, 'show'])->name('performances.show');
        
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/report/subscription', [AdminReportController::class, 'subscription'])->name('reports.subscription');
        Route::get('/report/performance', [AdminReportController::class, 'performance'])->name('reports.performance');
        Route::get('/report/replay', [AdminReportController::class, 'replay'])->name('reports.replay');
        Route::resource('attendances', AdminAttendanceController::class);
        Route::resource('supports', AdminSupportController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/webhooks/zoom', [ZoomWebhookController::class, 'handle']);
Route::post('/zoom/signature', [ZoomSignatureController::class, 'generateSignature'])->name('zoom.signature');

require __DIR__.'/auth.php';
