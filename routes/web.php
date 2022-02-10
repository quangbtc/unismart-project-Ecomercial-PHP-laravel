<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/trangchu', function () {
    return view('clienthome');
});
Route::get('/', 'HomeController@index')->name('home');
Route::post('timkiem','HomeController@search')->name('search');
Auth::routes(['verify' => true]);
Route::middleware('auth', 'verified','CheckAdmin')->group(function(){
    //Modul dashboard
    Route::get('dashboard', 'DashboardController@show')->name('dashboard.show');
    Route::get('admin', 'DashboardController@show')->name('dashboard.show');
    //users 
    Route::get('admin/user/list', 'AdminUserController@list')->name('user.list');
    Route::get('admin/user/add', 'AdminUserController@add')->name('user.add')->middleware('CheckPermission:user-add');
    Route::post('admin/user/store', 'AdminUserController@store')->name('user.store');
    Route::get('admin/user/delete/{id}','AdminUserController@delete')->name('user.delete')->middleware('CheckPermission:user-delete');
    Route::post('admin/user/action','AdminUserController@action')->name('user.action')->middleware('CheckPermission:user-delete');;
    Route::get('admin/user/edit/{id}','AdminUserController@edit')->name('user.edit')->middleware('CheckPermission:user-update');
    Route::post('admin/user/update/{id}','AdminUserController@update')->name('user.update');
    //Customers
    Route::get('admin/customer/list','CustomerController@list')->name('customer.list');
    Route::get('admin/customer/edit/{id}','CustomerController@edit')->name('customer.edit');
    Route::post('admin/customer/update/{id}', 'CustomerController@update')->name('customer.update');
    Route::get('admin/customer/delete/{id}', 'CustomerController@delete')->name('customer.delete');
    Route::post('admin/customer/action', 'CustomerController@action')->name('customer.action');
    

    //Order Đơn hàng
    Route::get('admin/order/list','orderController@list')->name('order.list');
    Route::get('admin/order/detail/{id}','orderController@detail')->name('order.detail');
    Route::get('admin/order/update/{id}','orderController@update')->name('order.update');
    Route::get('admin/order/delete/{id}','orderController@delete')->name('order.delete');
    Route::post('admin/order/action','orderController@action')->name('order.action');
    //Roles
    Route::get('admin/role/list','AdminRoleController@list')->name('role.list');
    Route::get('admin/role/add', 'AdminRoleController@add')->name('role.add');
    Route::post('admin/role/store', 'AdminRoleController@store')->name('role.store');
    Route::get('admin/role/delete/{id}', 'AdminRoleController@delete')->name('role.delete');
    Route::post('admin/role/action','AdminRoleController@action')->name('role.action');
    Route::get('admin/role/edit/{id}', 'AdminRoleController@edit')->name('role.edit');
    Route::post('admin/role/update/{id}', 'AdminRoleController@update')->name('role.update');
    //Permission
    Route::get('admin/permission/list','AdminPermissionController@list')->name('permission.list');
    Route::get('admin/permission/add','AdminPermissionController@add')->name('permission.add');
    Route::post('admin/permission/store','AdminPermissionController@store')->name('permission.store');
    Route::get('admin/permission/delete/{id}','AdminPermissionController@delete')->name('permission.delete');
    Route::post('admin/permission/action','AdminPermissionController@action')->name('permission.action');
    Route::get('admin/permission/edit/{id}','AdminPermissionController@edit')->name('permission.edit');
    Route::post('admin/permission/update/{id}','AdminPermissionController@update')->name('permission.update');
    //Module post
    Route::get('admin/post/list','AdminPostController@list')->name('post.list');
    Route::get('admin/post/add', 'AdminPostController@add')->name('post.add');
    Route::post('admin/post/store','AdminPostController@store')->name('post.store');
    Route::get('admin/post/edit/{id}','AdminPostController@edit')->name('post.edit');
    Route::post('admin/post/update/{id}', 'AdminPostController@update')->name('post.update');
    Route::get('admin/post/delete/{id}', 'AdminPostController@delete')->name('post.delete');
    Route::post('admin/post/action', 'AdminPostController@action')->name('post.action');

    //Page Module
    Route::get('admin/page/list', 'AdminPageController@list')->name('page.list');
    Route::get('admin/page/add', 'AdminPageController@add')->name('page.add');
    Route::post('admin/page/store', 'AdminPageController@store')->name('page.store');
    Route::get('admin/page/edit/{id}', 'AdminPageController@edit')->name('page.edit');
    Route::post('admin/page/update/{id}', 'AdminPageController@update')->name('page.update');
    Route::get('admin/page/delete/{id}', 'AdminPageController@delete')->name('page.delete');
    Route::post('admin/page/action', 'AdminPageController@action')->name('page.action');
    //Post_cat
    Route::get('admin/post_cat/add','AdminPostCatController@add')->name('post_cat.add');
    Route::get('admin/post_cat/list','AdminPostCatController@list')->name('post_cat.list');
    Route::post('admin/post_cat/store','AdminPostCatController@store')->name('post_cat.store');
    Route::get('admin/post_cat/edit/{id}','AdminPostCatController@edit')->name('post_cat.edit');
    Route::post('admin/post_cat/update/{id}','AdminPostCatController@update')->name('post_cat.update');
    Route::get('admin/post_cat/delete/{id}','AdminPostCatController@delete')->name('post_cat.delete');
    Route::post('admin/post_cat/action', 'AdminPostCatController@action')->name('post_cat.action');
    //Modul Proudcts
    Route::get('admin/product/list', 'AdminProductController@list')->name('product.list');
    Route::get('admin/product/add', 'AdminProductController@add')->name('product.add');
    Route::post('admin/product/store', 'AdminProductController@store')->name('product.store');
    Route::get('admin/product/edit/{id}', 'AdminProductController@edit')->name('product.edit');
    Route::post('admin/product/update/{id}', 'AdminProductController@update')->name('product.update');
    Route::get('admin/product/delete/{id}', 'AdminProductController@delete')->name('product.delete');
    Route::post('admin/product/action', 'AdminProductController@action')->name('product.action');
    //Product_cat
    //Post_cat
    Route::get('admin/product_cat/add', 'AdminProductCatController@add')->name('product_cat.add');
    Route::get('admin/product_cat/list', 'AdminProductCatController@list')->name('product_cat.list');
    Route::post('admin/product_cat/store', 'AdminProductCatController@store')->name('product_cat.store');
    Route::get('admin/product_cat/edit/{id}', 'AdminProductCatController@edit')->name('product_cat.edit');
    Route::post('admin/product_cat/update/{id}', 'AdminProductCatController@update')->name('product_cat.update');
    Route::get('admin/product_cat/delete/{id}', 'AdminProductCatController@delete')->name('product_cat.delete');
    Route::post('admin/product_cat/action', 'AdminProductCatController@action')->name('product_cat.action');
    //Slider
    Route::get('admin/slider/list','sliderController@list')->name('slider.list');
    Route::get('admin/slider/add', 'sliderController@add')->name('slider.add');
    Route::post('admin/slider/store','sliderController@store')->name('slider.store');
    Route::get('admin/slider/delete/{id}', 'sliderController@delete')->name('slider.delete');
    Route::post('admin/slider/action','sliderController@action')->name('slider.action');
    //Setting
    Route::get('admin/setting/list', 'settingController@list')->name('setting.list');
    Route::get('admin/setting/add', 'settingController@add')->name('setting.add');
    Route::post('admin/setting/store', 'settingController@store')->name('setting.store');
    Route::get('admin/setting/delete/{id}', 'settingController@delete')->name('setting.delete');
    Route::post('admin/setting/action', 'settingController@action')->name('setting.action');
    Route::get('admin/setting/edit/{id}','settingController@edit')->name('setting.edit');
    Route::post('admin/setting/update/{id}','settingController@update')->name('setting.update');
});
//Trang lỗi
Route::get('error',function(){
    return view('error.401');
});
//Đinh tuyến quản lý file
Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
//Post Modul bài viết
Route::get('tintuc','PostController@index')->name('client.post.index');
Route::get('post/detail/{id}', 'PostController@detail')->name('client.post.detail');
//Category
Route::get('tintuc/{slug}/{id}','PostCatController@list')->name('client.post.category.list');
//Product
Route::get('sanpham', 'ProductController@index')->name('client.product.index');
Route::get('sanpham/{slug}/{id}', 'ProductController@detail')->name('client.product.detail');

//Product Category
Route::get('danhmuc/{slug}/{id}','ProductCatController@list')->name('client.product.category.list');
//Page module
Route::get('gioi-thieu/{id}','PageController@about_us')->name('page.about_us');
Route::get('lien-he/{id}','PageController@contact')->name('page.contact');
//Cart giỏ hàng
Route::get('cart/add/{id}','CartController@add')->name('cart.add');
Route::get('cart/show', 'CartController@show')->name('cart.show');
Route::get('cart/remove/{rowId}', 'CartController@remove')->name('cart.remove');
Route::get('cart/destroy', 'CartController@destroy')->name('cart.destroy');
Route::post('cart/update','CartController@update')->name('cart.update');
Route::get('cart/checkout','CartController@checkout')->name('cart.checkout');
Route::post('cart/restore','CartController@restore')->name('cart.restore');
Route::get('cart/order','CartController@list')->name('cart.list');
Route::get('cart/detail/{id}','CartController@detail')->name('cart.detail');
Route::get('cart/order_history/{customer}','CartController@order_history')->name('order.history');
//Quản lý khách hàng Customer
Route::get('logout','CustomerController@logout')->name('customer.logout');

