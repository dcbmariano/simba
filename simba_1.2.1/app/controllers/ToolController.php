<?php

class ToolController extends BaseController {
	
	# Lista todos os tools
	public function list_tools(){
		return View::make('tools');
	}
	
	# Web CONTIGuator
	public function webcontiguator(){
		return View::make('webcontiguator');
	}
	
	# Run Web CONTIGuator
	public function run_webcontiguator(){
		/* Definindo limites de upload e tempo de execucao */ 
		ini_set('upload_max_filesize','38M');
		ini_set('post_max_size','38M'); 
		ini_set('max_input_time',600);
		ini_set('max_execution_time',600);
		system("cd tmp/webcontiguator && rm -rf *");
		/* Recebendo arquivos fasta*/
		if (Input::hasFile('contigs') and Input::hasFile('reference')){
			
			/* Criando diretorio */
			system("mkdir tmp/webcontiguator");
			# AVISO: Comando crítico
			system("cd tmp/webcontiguator && rm -rf *");
			$destinationPath = public_path()."/tmp/webcontiguator";
			$contigs = 'contigs.fa';
			$reference = 'reference.fa';
			$extension_contigs = Input::file('contigs')->getClientOriginalExtension(); 
			$extension_reference = Input::file('reference')->getClientOriginalExtension(); 
			if($extension_contigs == 'fa' or $extension_contigs == 'fasta' or $extension_contigs == 'fna' or $extension_contigs == 'fas'){
				$upload_success_contigs = Input::file('contigs')->move($destinationPath, $contigs);
			}
			if($extension_reference == 'fa' or $extension_reference == 'fasta' or $extension_reference == 'fna' or $extension_reference == 'fas'){
				$upload_success_reference = Input::file('reference')->move($destinationPath, $reference);
			}
			else {
				return Redirect::to('projects')->withErrors(array("Invalid extension. Simba requires BAM, SFF or FASTQ file."))->with('projects',$projects);
			}
		}
		
		/* Run CONTIGuator */
		system("export PATH=\$PATH:/opt/ncbi/bin/ && cd tmp/webcontiguator && python ../../../app/bin/CONTIGuator.py -r reference.fa -c contigs.fa");
		
		/* Convertendo PDF para PNG */
		$folder = 'tmp/webcontiguator';
		$pdf_name = system("cd tmp/webcontiguator/Map* && ls *.pdf");
		$folder_map = system("cd tmp/webcontiguator/Map* && pwd");
		$myurl = $folder_map.'/'.$pdf_name;
		$image = new Imagick($myurl); 
		$image->setResolution( 450, 450 );
		$image->setImageFormat( "png" );
		if($image->writeImage("tmp/webcontiguator/webcontiguator.png")) echo 'OK<br/><br/>';
		else echo "Fail";
		
		system("mv $folder_map\/$pdf_name tmp/webcontiguator/webcontiguator.pdf");
		system("mv $folder_map\/PseudoContig.fsa tmp/webcontiguator/webcontiguator.fsa");
		
		return Redirect::to('tools/webcontiguator_view_result');
	}

	public function webcontiguator_view_result(){
		return View::make('webcontiguator_result');
	}
	
	# Supercontigs constructor
	public function supercontigs(){
		return View::make('supercontigs');
	}
	
	# LegoScaffold
	public function legoscaffold(){
		return View::make('legoscaffold');
	}
	
	# scaffoldHibrido
	public function scaffoldhibrido(){
		return View::make('scaffoldhibrido');
	}
	
}