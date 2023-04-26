<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected  $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index(Request $request) {

        //$users = $this->model->where('name', 'LIKE', "%{$request->search}%")->get();
        $search = $request->search;
        $users = $this->model->getUsers(search: $request->search ?? '');
        
        return view('users.index', compact('users'));
    }

    public function show($id) {

        //$user = $this->model->where('id', $id)->first();
        if(!$user = $this->model->find($id))
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

        if($request->image) {
            $data['image'] = $request->image->store('users');
        }

        $this->model->create($data);
        return redirect()->route('users.index');
    }

    public function edit($id) {
        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');

        return view('users.edit', compact('user'));
    }

    public function update(StoreUpdateUserFormRequest $request, $id) {



        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');

        $dataForm = $request->only('name', 'email');
        if ($request->password) 
            $dataForm['password'] = bcrypt($request->password);

        if($request->image) {
            if ($user->image && Storage::exists($user->image)){
                Storage::delete($user->image);
            } 
        
            $dataForm['image'] = $request->image->store('users');
        }
        
        
        $user->update($dataForm);
        
        return redirect()->route('users.index');
    }

    public function destroy($id) {

        //$user = $this->model->where('id', $id)->first();
        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');

        $user->delete();

        return redirect()->route('users.index');
    }

}
