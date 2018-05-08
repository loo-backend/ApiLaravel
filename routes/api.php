<?php

use App\User;
use App\Book;
use App\Author;
use App\Product;
use App\Pedido;

use MongoDB\BSON\ObjectID;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Support\Facades\Validator;


//Full Text

//https://github.com/jenssegers/laravel-mongodb/wiki/Creating-Full-Text-Index-With-Laravel-Migration-Using-Moloquent


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
$this->post('/userss', function (Request $request) {

    $data = $request->all();

    return User::create($data);

});

// $this->get('/users', function (Request $request) {

//     $data = $request->all();

//     return User::all();

// });


// $this->post('/products', function (Request $request) {

//     $data = $request->all();

//     return Product::create($data);

// });

//https://laracasts.com/discuss/channels/general-discussion/how-to-validate-array-fields?page=3

$this->post('/users', function (Request $request) {

    $data = $request->all();

    $validator = Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'roles' => 'required|array',
        'roles.*.role' => 'required|string',
        'roles.*.permissions' => 'required|array',
        'roles.*.permissions.*.permission' => 'required|string',
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


    //return $user->delete();

});

$this->get('/all', function (Request $request) {

    return User::paginate();

});

$this->post('/books', function (Request $request) {


    $book=    Book::create(['title' => 'A Game of Thrones'. mt_rand(1,99)]);

    // $book = new Book(['title' => 'A Game of Thrones']);

    // $user = User::first();

    // $book = $user->books()->save($book);

    // return $book;

    $author = new Author(['name' => 'John Doe'. mt_rand(1,99)]);



    $book->author()->create(['name' => 'John Doe'. mt_rand(1,99)]);

    $author = Book::find('5af1ca1e7e8a2d48cc7658aa')->author;

    return $author;

});

//https://stackoverflow.com/questions/46971021/how-to-find-record-matching-the-nested-key-laravel-mongodb-jenssegers

$this->get('/all2', function (Request $request) {


    // $user = User::where(
    //     'roles',
    //     'elemMatch',
    //     [ 'role' => 'ADMIN' ]
    // )->get();

    // $user = User::where(
    //     'roles',
    //     'elemMatch',
    //     [ 'permissions.permission' => 'create' ]
    // )->get();

     $user = User::whereFullText('Dr. Hayley Rath')
     ->orderBy('score',['$meta'=>'textScore'])->get();




    return $user;

});

$this->group(['middleware' => 'jwt.auth'], function () {

    $this->get('/users', function () {
        return User::all();
    });

});


$this->get('/pedidos', function (Request $request) {

    $usuario = 2;

    return Pedido::raw(function($collection) use ($usuario)
    {
        return $collection->aggregate(
            [

                [
                    '$match' => ['usuario' => $usuario]
                ],

                [
                    '$lookup' => [
                        "from" => "produtos",
                        "localField" => "produto_id",
                        "foreignField" => "_id",
                        "as" => "pedidos_realizados"
                    ]
                ]

            ]
        );
    });





});



$this->middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
