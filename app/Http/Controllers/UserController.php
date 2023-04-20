<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {

        //$users = User::where('name', 'LIKE', "%{$request->search}%")->get();
        $search = $request->search;
        $users = User::where( function ($query) use($search) {
                if ($search) {
                    $query->where('email', $search);
                    $query->orWhere('name', 'LIKE', "%{$search}%");
                } 
            } 
        )->get();
        
        return view('users.index', compact('users'));
    }

    public function show($id) {

        //$user = User::where('id', $id)->first();
        if(!$user = User::find($id))
            return redirect()->route('users.index');

        return view('users.show', compact('user'));
    }

    public function create() {
        return view('users.create');
    }

    public function raiz() {
        return view('welcome'); 
    }

    public function store(StoreUpdateUserFormRequest $request){

        // $user = new User;
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = $request->password;
        // $user->save();

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        User::create($data);
        return redirect()->route('users.index');
    }

    public function edit($id) {
        if(!$user = User::find($id))
            return redirect()->route('users.index');

        return view('users.edit', compact('user'));
    }

    public function update(StoreUpdateUserFormRequest $request, $id) {



        if(!$user = User::find($id))
            return redirect()->route('users.index');

        $dataForm = $request->only('name', 'email');
        if ($request->password) 
            $dataForm['password'] = bcrypt($request->password);
        
        
        $user->update($dataForm);
        
        return redirect()->route('users.index');
    }

    public function destroy($id) {

        //$user = User::where('id', $id)->first();
        if(!$user = User::find($id))
            return redirect()->route('users.index');

        $user->delete();

        return redirect()->route('users.index');
    }

}
