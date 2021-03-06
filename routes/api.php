<?php

use App\User;
use App\Product;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Support\Facades\Validator;

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
// $this->post('/users', function (Request $request) {

//     $data = $request->all();

//     return User::create($data);

// });

// $this->get('/users', function (Request $request) {

//     $data = $request->all();

//     return User::all();

// });


// $this->post('/products', function (Request $request) {

//     $data = $request->all();

//     return Product::create($data);

// });

$this->post('/users', function (Request $request) {

    $data = $request->all();

    $validator = Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'roles' => 'required|array'
    ]);

    if( $validator->fails()) {
        return $validator->errors();
    }

    $data['password'] = bcrypt($data['password']);
    $data['uuid'] = Uuid::generate()->string;

    $user = User::create($data);

    // grab credentials from the request
    $credentials = $request->only('email', 'password');
    $user['token'] = JWTAuth::attempt($credentials);

    return $user;

});


$this->post('/login', function (Request $request) {

    // grab credentials from the request
    $credentials = $request->only('email', 'password');


    try {
        // attempt to verify the credentials and create a token for the user
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (JWTException $e) {
        // something went wrong whilst attempting to encode the token
        return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // all good so return the token
    return response()->json(compact('token'));

});


$this->get('/userss', function (Request $request) {

    $user = User::find($request->get('id'));
    return $user->delete();

});

$this->get('/all', function (Request $request) {

    return User::all();

});

$this->group(['middleware' => 'jwt.auth'], function () {

    $this->get('/users', function () {
        return User::all();
    });

});





$this->middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
