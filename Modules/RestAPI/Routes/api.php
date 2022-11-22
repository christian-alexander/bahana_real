<?php

ApiRoute::group(['namespace' => 'Modules\RestAPI\Http\Controllers'], function () {

    ApiRoute::get('app', ['as' => 'api.app', 'uses' => 'AppController@app']);

    // Forgot Password
    ApiRoute::post('auth/forgot-password', ['as' => 'api.auth.forgotPassword', 'uses' => 'AuthController@forgotPassword']);

    // Auth routes
    ApiRoute::post('auth/login', ['as' => 'api.auth.login', 'uses' => 'AuthController@login']);
    // Auth routes
    ApiRoute::post('auth/testOneSignal', ['as' => 'api.auth.testOneSignal', 'uses' => 'AuthController@testOneSignal']);
    ApiRoute::post('auth/logout', ['as' => 'api.auth.logout', 'uses' => 'AuthController@logout']);
    ApiRoute::post('auth/reset-password', ['as' => 'api.auth.resetPassword', 'uses' => 'AuthController@resetPassword']);
    ApiRoute::get('auth/refresh', ['as' => 'api.auth.refresh', 'uses' => 'AuthController@refresh']);
    ApiRoute::get('attendance/fixAttendancesTime', ['as' => 'attendance.fixAttendancesTime', 'uses' => 'AttendanceController@fixAttendancesTime']);
    ApiRoute::get('getDatabase', ['as' => 'getDatabase', 'uses' => 'AuthController@getDatabase']);
  	ApiRoute::post('attendance/getServerTime', ['as' => 'attendance.getServerTime', 'uses' => 'AttendanceController@getServerTime']);
    ApiRoute::post('employee/storeGpsWithUser', ['as' => 'employee.storeGpsWithUser', 'uses' => 'EmployeeController@storeGpsWithUser']);
});

ApiRoute::group(['namespace' => 'Modules\RestAPI\Http\Controllers', 'middleware' => 'api.auth'], function () {

    // send notif one signal manual
    ApiRoute::post('send/one-signal', ['as' => 'api.send-one-signal', 'uses' => 'GeneralControllerController@sendOneSignal']);
    ApiRoute::get('company', ['as' => 'api.app', 'uses' => 'CompanyController@company']);
    ApiRoute::post('/project/{project_id}/members', ['as' => 'project.member', 'uses' => 'ProjectController@members']);
    ApiRoute::delete('/project/{project_id}/member/{id}', ['as' => 'project.member.delete', 'uses' => 'ProjectController@memberRemove']);
    ApiRoute::post('/project/get-list', ['as' => 'project.get-list', 'uses' => 'ProjectController@getList']);
    ApiRoute::resource('project', 'ProjectController');
    ApiRoute::resource('project-category', 'ProjectCategoryController');
    ApiRoute::resource('currency', 'CurrencyController');

    ApiRoute::post('ticket/deleteTicketReply', ['as' => 'ticket.deleteTicketReply', 'uses' => 'TicketController@deleteTicketReply']);
    ApiRoute::post('ticket/postTicketReply', ['as' => 'ticket.postTicketReply', 'uses' => 'TicketController@postTicketReply']);
    ApiRoute::get('ticket/getTicketType', ['as' => 'ticket.getTicketType', 'uses' => 'TicketController@getTicketType']);
    ApiRoute::get('ticket/getTicketAgent', ['as' => 'ticket.getTicketAgent', 'uses' => 'TicketController@getTicketAgent']);
    ApiRoute::resource('ticket', 'TicketController');

    ApiRoute::get('/task/remind/{id}', ['as' => 'task.remind', 'uses' => 'TaskController@remind']);

    ApiRoute::resource('/task/{task_id}/subtask', 'SubTaskController');

    ApiRoute::get('/getAssignee', ['as' => 'task.getAssignee', 'uses' => 'TaskController@getAssignee']);
    ApiRoute::get('/getAtasan', ['as' => 'task.getAssignee', 'uses' => 'TaskController@getAtasan']);
    ApiRoute::post('/task/storeTask', ['as' => 'task.storeTask', 'uses' => 'TaskController@storeTask']);
    ApiRoute::post('/task/editTask', ['as' => 'task.editTask', 'uses' => 'TaskController@editTask']);
    ApiRoute::post('/task/deleteTask', ['as' => 'task.deleteTask', 'uses' => 'TaskController@deleteTask']);
    ApiRoute::post('/task/getDetail', ['as' => 'task.getDetail', 'uses' => 'TaskController@getDetail']);
    ApiRoute::post('/task/getListTugas', ['as' => 'task.getListTugas', 'uses' => 'TaskController@getListTugas']);
    ApiRoute::post('/task/startTask', ['as' => 'task.startTask', 'uses' => 'TaskController@startTask']);
    ApiRoute::post('/task/stopTask', ['as' => 'task.stopTask', 'uses' => 'TaskController@stopTask']);
    ApiRoute::post('/task/getListPelaporanTugas', ['as' => 'task.getListPelaporanTugas', 'uses' => 'TaskController@getListPelaporanTugas']);
    ApiRoute::post('/task/terimaTask', ['as' => 'task.terimaTask', 'uses' => 'TaskController@terimaTask']);
    ApiRoute::post('/task/tolakTask', ['as' => 'task.tolakTask', 'uses' => 'TaskController@tolakTask']);
    ApiRoute::resource('task', 'TaskController');
    
    ApiRoute::resource('task-category', 'TaskCategoryController');
    ApiRoute::resource('taskboard-columns', 'TaskboardColumnController');
    ApiRoute::resource('timelog', 'ProjectTimeLogController');
    
    ApiRoute::resource('lead', 'LeadController');
    ApiRoute::resource('client', 'ClientController');

    // get list sub_company
    ApiRoute::post('/sub-company/list', ['as' => 'sub-company.list', 'uses' => 'SubCompanyController@list']);

    ApiRoute::post('/department/list', ['as' => 'department.list', 'uses' => 'DepartmentController@list']);
    ApiRoute::post('/department/getMember', ['as' => 'department.getMember', 'uses' => 'DepartmentController@getMember']);
    
    ApiRoute::resource('designation', 'DesignationController');
    
    ApiRoute::resource('holiday', 'HolidayController');
    
    ApiRoute::resource('contract-type', 'ContractTypeController');
    ApiRoute::resource('contract', 'ContractController');
    
    ApiRoute::get('/getTeams', ['as' => 'notice.getTeams', 'uses' => 'NoticeController@getTeams']);
    ApiRoute::get('/notice/getList', ['as' => 'notice.getList', 'uses' => 'NoticeController@getList']);
    ApiRoute::resource('notice', 'NoticeController');

    ApiRoute::post('/store-notice', ['as' => 'store-notice.list', 'uses' => 'NoticeController@storeNotice']);
    ApiRoute::post('/notice/mark-read', ['as' => 'notice.markRead', 'uses' => 'NoticeController@markRead']);

    // notes
    ApiRoute::post('/notes/list-data', ['as' => 'notes.list', 'uses' => 'NotesController@listData']);
    ApiRoute::post('/notes/store-data', ['as' => 'notes.store', 'uses' => 'NotesController@storeData']);
    ApiRoute::post('/notes/{id}/update-data', ['as' => 'notes.update', 'uses' => 'NotesController@updateData']);
    ApiRoute::post('/notes/delete-data', ['as' => 'notes.delete', 'uses' => 'NotesController@deleteData']);

    // chat orang kepercayaan
    ApiRoute::post('/comment-tugas/list-data', ['as' => 'comment-tugas.list', 'uses' => 'TaskChatCommentController@listData']);
    ApiRoute::post('/comment-tugas/store-data', ['as' => 'comment-tugas.store', 'uses' => 'TaskChatCommentController@storeData']);
    ApiRoute::post('/comment-tugas/{id}/update-data', ['as' => 'comment-tugas.update', 'uses' => 'TaskChatCommentController@updateData']);
    ApiRoute::post('/comment-tugas/delete-data', ['as' => 'comment-tugas.delete', 'uses' => 'TaskChatCommentController@deleteData']);
    ApiRoute::post('/comment-tugas/mark-read', ['as' => 'comment-tugas.markRead', 'uses' => 'TaskChatCommentController@markRead']);

    ApiRoute::resource('event', 'EventController');

    ApiRoute::resource('estimate', 'EstimateController');
    ApiRoute::resource('invoice', 'InvoiceController');

    ApiRoute::resource('product', 'ProductController');
    ApiRoute::resource('employee', 'EmployeeController');

    ApiRoute::post('/device/register', ['as' => 'device.register', 'uses' => 'DeviceController@register']);
    ApiRoute::post('/device/unregister', ['as' => 'device.unregister', 'uses' => 'DeviceController@unregister']);

    ApiRoute::post('employee/notifyAtasanKeluarRadius', ['as' => 'employee.notifyAtasanKeluarRadius', 'uses' => 'EmployeeController@notifyAtasanKeluarRadius']);
    ApiRoute::post('employee/notifyAtasanCustomMessage', ['as' => 'employee.notifyAtasanCustomMessage', 'uses' => 'EmployeeController@notifyAtasanCustomMessage']);
    ApiRoute::post('employee/storeGps', ['as' => 'employee.storeGps', 'uses' => 'EmployeeController@storeGps']);
    ApiRoute::post('employee/storeAttendance', ['as' => 'employee.storeAttendance', 'uses' => 'EmployeeController@storeAttendance']);
  
    ApiRoute::post('employee/editNotificationSetting', ['as' => 'employee.editNotificationSetting', 'uses' => 'EmployeeController@editNotificationSetting']);
    ApiRoute::post('auth/updateProfile', ['as' => 'api.auth.updateProfile', 'uses' => 'AuthController@updateProfile']);
    ApiRoute::post('auth/changePassword', ['as' => 'api.auth.changePassword', 'uses' => 'AuthController@changePassword']);
    ApiRoute::get('auth/getEmployeePermission', ['as' => 'api.auth.getEmployeePermission', 'uses' => 'AuthController@getEmployeePermission']);
    ApiRoute::post('auth/checkWifiExist', ['as' => 'api.auth.checkWifiExist', 'uses' => 'AuthController@checkWifiExist']);

    ApiRoute::get('auth/setNotificationAsRead', ['as' => 'api.auth.setNotificationAsRead', 'uses' => 'AuthController@setNotificationAsRead']);
    ApiRoute::get('auth/getStatusReadNotif', ['as' => 'api.auth.getStatusReadNotif', 'uses' => 'AuthController@getStatusReadNotif']);
    ApiRoute::get('auth/getUnreadNotifications', ['as' => 'api.auth.getUnreadNotifications', 'uses' => 'AuthController@getUnreadNotifications']);
    ApiRoute::get('auth/getNotification', ['as' => 'api.auth.getNotification', 'uses' => 'AuthController@getNotification']);
    ApiRoute::get('auth/readNotifications', ['as' => 'api.auth.readNotifications', 'uses' => 'AuthController@readNotifications']);
    ApiRoute::get('auth/getCustomNotifications', ['as' => 'api.auth.getCustomNotifications', 'uses' => 'AuthController@getCustomNotifications']);


    ApiRoute::get('auth/getProfile', ['as' => 'api.auth.getProfile', 'uses' => 'AuthController@getProfile']);

    ApiRoute::resource('attendance', 'AttendanceController');
    ApiRoute::post('attendance/getHistoryAttendance', ['as' => 'attendance.getHistoryAttendance', 'uses' => 'AttendanceController@getHistoryAttendance']);
    ApiRoute::post('attendance/storeAttendance', ['as' => 'attendance.storeAttendance', 'uses' => 'AttendanceController@storeAttendance']);
    ApiRoute::post('attendance/getOffice', ['as' => 'attendance.getOffice', 'uses' => 'AttendanceController@getOffice']);
    ApiRoute::post('attendance/getAllOffice', ['as' => 'attendance.getAllOffice', 'uses' => 'AttendanceController@getAllOffice']);
    ApiRoute::post('attendance/checkAttendance', ['as' => 'attendance.checkAttendance', 'uses' => 'AttendanceController@checkAttendance']);
    ApiRoute::post('attendance/check-position', ['as' => 'attendance.checkPosition', 'uses' => 'AttendanceController@checkPosition']);

    ApiRoute::resource('attendance_settings', 'AttendanceSettingsController');

    // leave type
    ApiRoute::resource('leave-type', 'LeaveTypeController');

    // leave
    ApiRoute::post('leave/getList', ['as' => 'leave.getList', 'uses' => 'LeaveController@getList']);
    ApiRoute::post('leave/getDetail', ['as' => 'leave.getDetail', 'uses' => 'LeaveController@getDetail']);
    ApiRoute::post('leave/storeLeave', ['as' => 'leave.storeLeave', 'uses' => 'LeaveController@storeLeave']);
    ApiRoute::post('leave/approveLeave', ['as' => 'leave.approveLeave', 'uses' => 'LeaveController@approveLeave']);
    ApiRoute::post('leave/rejectLeave', ['as' => 'leave.rejectLeave', 'uses' => 'LeaveController@rejectLeave']);
    ApiRoute::post('leave/create-pengeluaran', ['as' => 'leave.createPengeluaran', 'uses' => 'LeaveController@createPengeluaran']);
    ApiRoute::post('leave/butuh-akomodasi', ['as' => 'leave.butuhAkomodasi', 'uses' => 'LeaveController@butuhAkomodasi']);
    ApiRoute::post('leave/dinas-to-done', ['as' => 'leave.dinasToDone', 'uses' => 'LeaveController@dinasToDone']);
    ApiRoute::post('leave/sekretaris-add-accomodation', ['as' => 'leave.sekretarisAddAccomodation', 'uses' => 'LeaveController@sekretarisAddAccomodation']);
  	ApiRoute::post('leave/check-my-leave', ['as' => 'leave.check-my-leave', 'uses' => 'LeaveController@checkMyLeave']);
    ApiRoute::post('leave/employee', ['as' => 'leave.myLeave', 'uses' => 'LeaveController@myLeave']);
    ApiRoute::post('leave/activity', ['as' => 'leave.activity', 'uses' => 'LeaveController@activity']);
    ApiRoute::resource('leave', 'LeaveController');

    // spk
    // ApiRoute::post('spk/iframe/create', ['as' => 'spk.iframe.create', 'uses' => 'IframeSPKController@create']);
    // ApiRoute::post('spk/iframe/getDetail', ['as' => 'spk.iframe.getDetail', 'uses' => 'IframeSPKController@getDetail']);
    
    // spk
    ApiRoute::post('spk/getList', ['as' => 'spk.getList', 'uses' => 'SPKController@getList']);
    ApiRoute::post('spk/getHistorySPK', ['as' => 'spk.getHistorySPK', 'uses' => 'SPKController@getHistorySPK']);

    // spk
    ApiRoute::post('spk/getList', ['as' => 'spk.getList', 'uses' => 'SPKController@getList']);
    ApiRoute::post('spk/getHistorySPK', ['as' => 'spk.getHistorySPK', 'uses' => 'SPKController@getHistorySPK']);

    // surat-pengiriman-dan-tanda-terima
    ApiRoute::post('surat-pengiriman-dan-tanda-terima/getList', ['as' => 'surat-pengiriman-dan-tanda-terima.getList', 'uses' => 'SPTTController@getList']);

    // laporan-kerusakan
    ApiRoute::post('laporan-kerusakan/getList', ['as' => 'laporan-kerusakan.getList', 'uses' => 'LaporanKerusakanController@getList']);
    
    // laporan-penangguhan-pekerjaan
    ApiRoute::post('laporan-penangguhan-pekerjaan/{id}/getList', ['as' => 'laporan-penangguhan-pekerjaan.getList', 'uses' => 'LaporanPenangguhanPekerjaanController@getList']);

    // laporan-perbaikan-kerusakan
    ApiRoute::post('laporan-perbaikan-kerusakan/{id}/getList', ['as' => 'laporan-perbaikan-kerusakan.getList', 'uses' => 'LaporanPerbaikanKerusakanController@getList']);
    
    // internal memo
    ApiRoute::post('internal-memo/getList', ['as' => 'internal-memo.getList', 'uses' => 'InternalMemoController@getList']);

    // permintaan dana
    ApiRoute::post('permintaan-dana/getList', ['as' => 'permintaan-dana.getList', 'uses' => 'PermintaanDanaController@getList']);

    // sounding bunker pemakaian bbm
    ApiRoute::post('sounding-bunker-pemakaian-bbm/getList', ['as' => 'sounding-bunker-pemakaian-bbm.getList', 'uses' => 'SBPBBMController@getList']);

    // sounding pagi perwira
    ApiRoute::post('sounding-pagi-perwira/getList', ['as' => 'sounding-pagi-perwira.getList', 'uses' => 'SBPBBMController@getList']);

    // input po
    ApiRoute::post('input-po/getList', ['as' => 'input-po.getList', 'uses' => 'InputPOController@getList']);

    // ApiRoute::post('spk/getDetail', ['as' => 'spk.getDetail', 'uses' => 'SPKController@getDetail']);
    // ApiRoute::post('spk/approve', ['as' => 'spk.approve', 'uses' => 'SPKController@approve']);
    // ApiRoute::post('spk/reject', ['as' => 'spk.reject', 'uses' => 'SPKController@reject']);
    // ApiRoute::post('spk/change-product-etc', ['as' => 'spk.changeProductEtc', 'uses' => 'SPKController@changeProductEtc']);
    // ApiRoute::post('spk/rate-performance', ['as' => 'spk.ratePerformance', 'uses' => 'SPKController@ratePerformance']);
    // ApiRoute::post('spk/change-qty', ['as' => 'spk.changeQty', 'uses' => 'SPKController@changeQty']);
    // ApiRoute::post('spk/history-barang', ['as' => 'spk.historyBarang', 'uses' => 'SPKController@historyBarang']);
    // ApiRoute::post('spk/delete-barang', ['as' => 'spk.historyBarang', 'uses' => 'SPKController@historyBarang']);

    // schedule
    ApiRoute::post('schedule/index-schedule', ['as' => 'schedule.index-schedule', 'uses' => 'ScheduleController@indexSchedule']);
    ApiRoute::post('schedule/create-schedule', ['as' => 'schedule.create-schedule', 'uses' => 'ScheduleController@createSchedule']);
    ApiRoute::post('schedule/edit-schedule', ['as' => 'schedule.edit-schedule', 'uses' => 'ScheduleController@editSchedule']);
    ApiRoute::post('schedule/show-schedule', ['as' => 'schedule.show-schedule', 'uses' => 'ScheduleController@showSchedule']);
    ApiRoute::post('schedule/schedule-to-finish', ['as' => 'schedule.schedule-to-finish', 'uses' => 'ScheduleController@scheduleToFinish']);

    // pertanyaan
    ApiRoute::post('pertanyaan/list', ['as' => 'pertanyaan.list', 'uses' => 'PertanyaanController@list']);
  
  	// Tipe Cuti
    ApiRoute::post('tipe-cuti/list', ['as' => 'tipe-cuti.list', 'uses' => 'TipeCutiController@list']);
    // time log
    ApiRoute::post('time-log/list', ['as' => 'time-log.list', 'uses' => 'ProjectTimeLogController@list']);
  
  	// Asset
    ApiRoute::get('asset/getAsset', ['as' => 'asset.getAsset', 'uses' => 'AssetController@getAsset']);
    ApiRoute::get('asset/getAssetDetail', ['as' => 'asset.getAssetDetail', 'uses' => 'AssetController@getAssetDetail']);
});
