<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/signiflow/login', 'SigniFlowController@login')->name('signiflow.login');
Route::get('/signiflow/getsigniflowdocument/{client_id}/{user_id}/{process_id}', 'SigniFlowController@getSigniflowDocument')->name('signiflow.getsigniflowdocument');
Route::get('/signiflow/getclientconsent/{client_id}', 'SigniFlowController@getClientConsent')->name('signiflow.getclientconsent');
Route::get('/signiflow/getstatutorynotice/{client_id}', 'SigniFlowController@getStatutoryNotice')->name('signiflow.getstatutorynotice');
// Route::get('/signiflow/getsigneddocument', 'SigniFlowController@getSignedDocument')->name('signiflow.getsigneddocument');

Route::post('/address/kyc/individual', 'BureauController@confirmIndividualKYCAddress')->name('address.kyc.individual');
Route::post('/cpb/idv/confirm', 'BureauController@confirmIDV')->name('cpb.idv.confirm');
Route::post('/cpb/getproofofaddress', 'BureauController@getProofOfAddress')->name('cpb.getproofofaddress');
Route::get('/cpb/getidvlist', 'BureauController@getIDVList')->name('cpb.idvlist');
Route::get('/cpb/getaddresslist', 'BureauController@getAddressList')->name('cpb.getaddresslist');
Route::get('/cpb/getemploymentlist', 'BureauController@getEmploymentList')->name('cpb.getemploymentlist');
Route::get('/cpb/gettelephonelist', 'BureauController@getTelephoneList')->name('cpb.gettelephonelist');
Route::post('/cpb/getavs', 'BureauController@getAVS')->name('cpb.getavs');
// Route::get('/testlogin', 'BureauController@testlogin')->name('testlogin');

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::middleware('auth:api')->post('/logout', 'AuthController@logout');

Route::get('/search','SearchController@getResults');
Route::get('getusers','UserController@getUsers');
Route::post('getnotifications','UserController@getNotifications');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');
    });
});

Route::post('/whatsapp-replies', 'WhatsappController@listenToReplies');

Route::post('/webhooks/inbound', 'WhatsappController@incoming');