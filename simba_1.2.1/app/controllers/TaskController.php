<?php 

class TaskController extends BaseController {

	public function getAdd(){
		return View::make('add_task');
	}

	public function postAdd(){
		// Regra de validação
		$regras = array('titulo' => 'required');
		// Validando
		$validacao = Validator::make(Input::all(), $regras);
		// Se a validacao falhar
		if($validacao->fails()){
			$tasks = Task::all();
			return Redirect::to('list_tasks')->withErrors($validacao)->with('tasks',$tasks);
		}
		else {
			$task = new Task;
			$task->titulo = Input::get('titulo');
			$task->save();
			$tasks = Task::all();
			return View::make('list_tasks')->with('sucesso', TRUE)->with('tasks',$tasks);
		}
	}

	public function listar(){
		$tasks = Task::all();
		return View::make('list_tasks')->with('tasks',$tasks);
	}

	public function check() {
        //verifica se a request é ajax
        if (Request::ajax()) {
            //criando regras de validação
            $regras = array('task_id' => 'required|integer');

            $validacao = Validator::make(Input::all(), $regras);

            if ($validacao->fails()) {
                return Response::json( array("status" => FALSE) );
            }
            else {
                //tenta encontrar e atualizar a task
                try {
                    $task = Task::findOrFail(Input::get('task_id'));
                    $task->status = TRUE;
                    $task->save();

                    return Response::json( array("status" => TRUE, "titulo" => $task->titulo) );
                }
                //caso não tenha conseguido encontrar a task
                catch(Exception $e) {
                    return Response::json( array("status" => FALSE, "mensagem" => $e->getMessage()) );
                }
            }
        }
    }
}