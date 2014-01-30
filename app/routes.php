<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//test routes
Route::get('mock',function(){
	return View::make('dashboard.dashboardMock');
});

Route::post('user/test',array(
	'as' => 'upload_with_encryption_test',
	'uses' => 'EncryptionController@postTestGet'
));

// open front page 
Route::get('/', array( 'as' => 'landing', function()
{
	return View::make('landing.landing',array('title' => 'Kumo.'));
}));

//user login
Route::post('user/login',array(
	'as' => 'sign_in' , 
	'uses' => 'UsersController@postSignin'
	));

//user signup
Route::post('user/signup',array(
	'as' => 'sign_up',
	'uses' => 'UsersController@postCreate'
	));


Route::get('user/register',array(
	'as' => 'register', 
	'uses' => 'UsersController@getRegister'
	));


//user signin 
Route::get('signin', array( 
	'as' => 'sign_in_page',function() {
    return View::make('user.signin',array('title' => 'Sign in.'));
}));

//user signup
Route::get('signup', array(
	'as' => 'sign_up_page',function() {
    return View::make('user.signup',array('title' => 'Sign up.'));
}));

//logout
Route::get('user/logout', array(
	'as' => 'logout' , 
	'uses' => 'UsersController@getLogout'
	));

Route::get('user/dashboard', array(
	'as' => 'dashboard', 
	'uses' => 'UsersController@getDashboard'
	));


// UNCOMMENT these two routes when you need authentication of dropbox 
// I have disabled this functionality after getting accessToken for my dropbox account 
// and hardcoding it in the seeds 


//Route::get('user/{cloud}',array('as'=>'authenticate', 'uses'=>'UsersController@getRegistrationPage'));

// Route to user authentication
Route::get('user/authenticate/{cloudName}/{userCloudName}',array(
	'as'=>'authenticate_route',
	'uses'=>'UsersController@getRegistrationPage'
	));

//authorization from dropbox
Route::get('auth/{cloudName}',array(
	'as'=>'completion_route',
	'uses'=>'UsersController@getCompletion'
	));

// home page...This is a development route..to be deleted later 
Route::get('user/home',array(
	'as'=>'home_route',
	'uses'=>'UsersController@getHome'
));

// download
Route::get('user/download',array(
	'as'=>'download_route',
	'uses'=>'FilesController@getFile'
));

//download encrypted file after decryption
Route::post('user/downloadEncryptedFile',array(
	'as' => 'download_encrypted_file',
	'uses' => 'EncryptionController@postDownloadEncryptedFile'
));

//upload any no of files 
Route::post('user/upload/{cloudName}',array(
	'as'=>'upload_route',
	'uses'=>'FilesController@postFile'
));

//upload any no of files and encrypt them
Route::post('user/uploadWithEncryption/{cloudName}',array(
	'as' => 'upload_with_encryption',
	'uses' => 'EncryptionController@postEncryptFiles'
	));

// get folder content
Route::get('user/folder_content',array(
	'as'=>'folder_content_route',
	'uses'=>'FilesController@getFolderContents'
));

// create folder 
Route::get('user/new_folder',array(
	'as'=>'create_folder_route',
	'uses'=>'FilesController@getCreateFolder'
));

// delete a file or folder 
Route::delete('user/delete',array(
	'as'=>'delete_route',
	'uses'=>'FilesController@delete'
));

//add a new cloud 
Route::get('user/add_cloud/{cloudName}',array(
	'as'=>'add_cloud_route',
	'uses'=>'FilesController@getAddCloud'
));

//refresh cloud
Route::get('user/refresh/{cloudName}',array(
	'as'=>'refresh_route',
	'uses'=>'FilesController@getRefresh'
));
//download folder route 
Route::get('user/download_folder',array(
	'as'=>'download_folder_route',
	'uses'=>'FilesController@getDownloadFolder'
));

//share file route
Route::get('user/share',array(
	'as'=>'share_file_route',
	'uses'=>'SharedFilesController@getShareFile'
));

// get files shared by user
Route::get('user/get_files_shared_by_user',array(
	'as'=>'files_shared_by_user_route',
	'uses'=>'SharedFilesController@getFilesSharedByUser'
));

// get files shared with user
Route::get('user/get_files_shared_with_user',array(
	'as'=>'files_shared_with_user_route',
	'uses'=>'SharedFilesController@getFilesSharedWithUser'
));

//download shared file route 
Route::get('user/download_shared_file',array(
	'as'=>'download_shared_file_route',
	'uses'=>'SharedFilesController@getSharedFile'
));
// Change access RIghts
Route::get('user/change_access_rights',array(
	'as'=>'change_access_rights_route',
	'uses'=>'SharedFilesController@getChangeAccessRights'
));
