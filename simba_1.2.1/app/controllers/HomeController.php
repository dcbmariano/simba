<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	
	public function showWelcome()
	{
		return View::make('hello');
	}

	public function ola($usuario = null){
		$usuario = ucwords($usuario);
		return View::make('ola',array('usuario' => $usuario));
	}
	
	*/

	public function redirect(){
		return Redirect::to('projects');
	}
	
	public function control_panel(){
		$users = User::orderBy('id')->get();
		return View::make('control_panel')->with('users',$users);		
	} 
	
	public function control_panel_add_user(){
		return View::make('control_panel_add_user');
	}
	
	public function control_panel_store_user(){
		if(strtoupper(Auth::user()->email) == 'ADMIN'){
			$users = User::get();	
			
			/* Verifica se o usuario ja existe */
			foreach($users as $user){
				if($user->email == Input::get('email')){
					return Redirect::to('control_panel/add_user')->withErrors("User exists in the database. Try another name.")->with('users',$users);
				}
			}
			
			/* Gravando no banco de dados */
			$user = new User;
			$user->email = Input::get('email');
			$password = Hash::make(Input::get('password'));			
			$user->password = $password;
			$user->save();
			$users = User::all();
			
			return Redirect::to('control_panel')->with('users',$users);
			
		}
	}
	
	public function control_panel_delete_user($id){
		return View::make('control_panel_delete_user')->with('id',$id);
	}
	
	public function control_panel_confirm_delete_user(){
		$id = Input::get('id_user');
		if($id != 1){
			$delete = User::deleteUser($id);
			return Redirect::to('control_panel')->withErrors('User deleted with successful.');
		}
		else {
			return Redirect::to('control_panel')->withErrors("You can't delete the admin.");
		}
	}
	
	public function control_panel_edit_user($id){
		$user = User::findOrFail($id);
		return View::make('control_panel_edit_user')->with('user',$user);		
	}
	
	public function control_panel_confirm_edit_user(){
		$id = Input::get('id_user');
		$password = (string)Input::get('user_password');	
		$password = Hash::make($password);
		
		/* Erro senha em branco */
		if(empty($password)){
			return Redirect::to('control_panel')->withErrors("Password empty.");
		}
		
		$user = User::updateUser($id,$password);
		
		return Redirect::to('control_panel')->withErrors("User edited with successful");
		
	}

}