<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Controllers Student
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ExamController     as StudentExam;
use App\Http\Controllers\Student\ResultController   as StudentResult;

// Controllers Teacher
use App\Http\Controllers\Teacher\DashboardController  as TeacherDashboard;
use App\Http\Controllers\Teacher\QuestionController   as TeacherQuestion;
use App\Http\Controllers\Teacher\ExamController       as TeacherExam;

// Controllers Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController      as AdminUser;

// ──────────────────────────────────────────────────────────
// TRANG CHỦ
// ──────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ──────────────────────────────────────────────────────────
// AUTH
// ──────────────────────────────────────────────────────────
Route::get ('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get ('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get ('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get ('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp.post');
Route::get ('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.store');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ──────────────────────────────────────────────────────────
// STUDENT
// ──────────────────────────────────────────────────────────
Route::middleware(['checklogin', 'checkrole:Student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboard::class, 'index'])
            ->name('dashboard');

        // Danh sách đề thi
        Route::get('/exams', [StudentExam::class, 'index'])
            ->name('exams');

        // Bắt đầu làm bài
        Route::get('/exams/{id}/start', [StudentExam::class, 'start'])
            ->name('exams.start');

        // Trang làm bài
        Route::get('/exams/{id}/do', [StudentExam::class, 'show'])
            ->name('exams.do');

        // Lưu đáp án TN (AJAX)
        Route::post('/exams/save-tn', [StudentExam::class, 'saveTN'])
            ->name('exams.saveTN');

        // Lưu đáp án Đúng/Sai (AJAX)
        Route::post('/exams/save-ds', [StudentExam::class, 'saveDS'])
            ->name('exams.saveDS');

        // Lưu đáp án số (AJAX)
        Route::post('/exams/save-so', [StudentExam::class, 'saveSo'])
            ->name('exams.saveSo');

        // Nộp bài
        Route::post('/exams/submit', [StudentExam::class, 'submit'])
            ->name('exams.submit');

        // Xem kết quả 1 bài làm
        Route::get('/results/{maBaiLam}', [StudentResult::class, 'show'])
            ->name('results.show');

        // Lịch sử làm bài
        Route::get('/results', [StudentResult::class, 'index'])
            ->name('results.index');
    });

// ──────────────────────────────────────────────────────────
// TEACHER
// ──────────────────────────────────────────────────────────
Route::middleware(['checklogin', 'checkrole:Teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])
            ->name('dashboard');

        // Ngân hàng câu hỏi
        Route::get   ('/questions',          [TeacherQuestion::class, 'index'])  ->name('questions.index');
        Route::get   ('/questions/create',   [TeacherQuestion::class, 'create']) ->name('questions.create');
        Route::post  ('/questions',          [TeacherQuestion::class, 'store'])  ->name('questions.store');
        Route::get   ('/questions/{id}/edit',[TeacherQuestion::class, 'edit'])   ->name('questions.edit');
        Route::put   ('/questions/{id}',     [TeacherQuestion::class, 'update']) ->name('questions.update');
        Route::delete('/questions/{id}',     [TeacherQuestion::class, 'destroy'])->name('questions.destroy');

        // Quản lý đề thi
        Route::get   ('/exams',              [TeacherExam::class, 'index'])      ->name('exams.index');
        Route::get   ('/exams/create',       [TeacherExam::class, 'create'])     ->name('exams.create');
        Route::post  ('/exams',              [TeacherExam::class, 'store'])      ->name('exams.store');
        Route::get   ('/exams/{id}/edit',    [TeacherExam::class, 'edit'])       ->name('exams.edit');
        Route::put   ('/exams/{id}',         [TeacherExam::class, 'update'])     ->name('exams.update');
        Route::delete('/exams/{id}',         [TeacherExam::class, 'destroy'])    ->name('exams.destroy');

        // Thêm câu hỏi vào đề
        Route::post('/exams/{id}/add-question', [TeacherExam::class, 'addQuestion'])
            ->name('exams.addQuestion');
        // Xóa câu hỏi khỏi đề

        Route::delete('/exams/{id}/remove-question', [TeacherExam::class, 'removeQuestion'])
            ->name('exams.removeQuestion');

            // Xem thống kê 1 đề
    Route::get('/exams/{id}/stats', [TeacherExam::class, 'stats'])
        ->name('exams.stats');

    // Thêm dòng này:
    Route::get('/exams/{id}/bai-lam/{maBaiLam}', [TeacherExam::class, 'xemBaiLam'])
        ->name('exams.xemBaiLam');

        // Publish đề (Draft → Published)
        Route::put('/exams/{id}/publish', [TeacherExam::class, 'publish'])
            ->name('exams.publish');
        Route::put('/exams/{id}/unpublish', [TeacherExam::class, 'unpublish'])
            ->name('exams.unpublish');

        // Xem thống kê 1 đề
        Route::get('/exams/{id}/stats', [TeacherExam::class, 'stats'])
            ->name('exams.stats');
    });

// ──────────────────────────────────────────────────────────
// ADMIN
// ──────────────────────────────────────────────────────────
Route::middleware(['checklogin', 'checkrole:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Duyệt giáo viên
        Route::get('/teachers/pending', [AdminController::class, 'pendingTeachers'])
            ->name('teachers.pending');
        Route::put('/teachers/{id}/approve', [AdminController::class, 'approveTeacher'])
            ->name('teachers.approve');
        Route::put('/teachers/{id}/reject', [AdminController::class, 'rejectTeacher'])
            ->name('teachers.reject');

        // Quản lý người dùng
        Route::get('/users', [AdminController::class, 'users'])
            ->name('users.index');
        Route::get('/users/{id}', [AdminController::class, 'userDetail'])
            ->name('users.detail');
        Route::put('/users/{id}/block', [AdminController::class, 'blockUser'])
            ->name('users.block');
        Route::put('/users/{id}/unblock', [AdminController::class, 'unblockUser'])
            ->name('users.unblock');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
            ->name('users.delete');
    });
