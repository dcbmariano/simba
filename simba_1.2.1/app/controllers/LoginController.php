<?php

class LoginController extends BaseController {

	public function getLogin(){
		return View::make('login');
	}

	public function postLogin(){
		$regras = array("email"=>"required","senha"=>"required");
		$validacao = Validator::make(Input::all(), $regras);
		if($validacao->fails()){
			return Redirect::to('login')->withErrors($validacao);
		}
		// Tenta logar o usuario
		if(Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('senha')))){
			return Redirect::to('/');
		}
		else{
			return Redirect::to('login')->withErrors('User or password invalid.');
		}
	}

	public function logout(){
		Auth::logout();
		return View::make('login');
	}
}