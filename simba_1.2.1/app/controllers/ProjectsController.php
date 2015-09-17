<?php

class ProjectsController extends BaseController {
	
	# Declare o endereco do diretorio raiz para as montagens
	private $raiz = "../app/assembly/";
	
	/* Principal funcao */
	public function index(){
		$projects = Project::orderBy('id_project', 'desc')->get();
		$curations = Curation::select(DB::raw('fk_id_project,MIN(num_scaffolds) as num_scaffolds,MAX(version_curation) as version_curation'))->groupBy('fk_id_project')->orderBy('fk_id_project', 'desc')->get();
		return View::make('projects')->with('projects',$projects)->with('curations',$curations);
	}
	
	/* Cria um novo projeto */
	public function create_project(){
		return View::make('project_create');
	}
	
	/* Armazena o projeto criado na funcao anterior */
	public function store_project(){
		
		$validacao = Validator::make(Input::all(),Project::$regras);
		
		if($validacao->fails()){
			$projects = Project::all();
			return Redirect::to('projects')->withErrors($validacao)->with('projects',$projects);
		}
		else {			
			/* Recebendo arquivo RAW */
			if (Input::hasFile('raw_data')){
				/* Criando diretorio */
				$name_project = Input::get('name_project');
				$name_project = str_replace(" ", "_", $name_project);
				system("cd ../app/assembly && mkdir $name_project");
				$destinationPath = public_path()."/uploads";
				$filename = Input::file('raw_data')->getClientOriginalName();
				$extension = Input::file('raw_data')->getClientOriginalExtension(); 
				if($extension == 'bam' or $extension == 'sff' or $extension == 'fastq' or $extension == 'zip'){
					$upload_success = Input::file('raw_data')->move($destinationPath, $filename);
				}
				else {
					return Redirect::to('projects')->withErrors(array("Invalid extension. Simba requires BAM, SFF, FASTQ or ZIP (mate-pair/paired-end) file."))->with('projects',$projects);
				}
			}
			
			/* Movendo arquivo RAW para diretorio especifico */
			system("cd uploads && mv $filename ../../app/assembly/$name_project/.");
			
			/* Extraindo arquivo ZIP */
			system("cd ../../app/assembly/$name_project/ && unzip *.zip");
			
			/* Gravando no banco de dados */
			$projects = new Project;
			$projects->name_project = Input::get('name_project');
			$projects->organism_project = Input::get('organism_project');
			$projects->ngs_project = Input::get('ngs_project');
			$projects->library_project = Input::get('library_project');
			$projects->bam = Input::get('bam');
			$projects->sff = Input::get('sff');
			$projects->fastq = Input::get('fastq');
			$projects->assembly = Input::get('assembly');
			$projects->save();
			$projects = Project::all();
			
			return Redirect::to('projects')->with('sucesso', TRUE)->with('projects',$projects)->withErrors(array('<b>Warning: </b>Your project was <b style="color:#009900">created successfully</b>. Please click <b>"update"</b> to list available files. You can do file conversions and starting assemblies using "action" button. <b>Important:</b> You must be a "fastq" file to start the assembly.')); 
		}
	}
	
	/* Exibe a pagina de edicao de projetos | Via GET */
	public function edit_project($id){
		$project = Project::getProject($id);
		if(is_null($project)){
			return Redirect::to('projects')->withErrors(array('Project not found.'));
		}
		return View::make('project_update')->with('project',$project);
	}
	
	/* Confirma a edicao do projeto | Via POST */
	public function update_project($id){
		$input = Input::all();
		$validacao = Validator::make($input,Project::$regras_update);
		if($validacao->passes()){
			$project = Project::updateProject($input);
			$success = 'success';
			return Redirect::to('projects')->withErrors(array('Project edited <b style="color:#009900">successfully</b>'));
		}
		else {
			return Redirect::to('projects')->withErrors($validacao);
		}
	}
	
	/* Deleta um projeto | Pagina de confirmacao via GET */
	public function delete_project($id){
		$project = Project::getProject($id);
		return View::make('project_delete')->with('project',$project);
	}
	
	/* Deleta um projeto | Confirmado via POST */
	public function delete_confirm_project($id){
		$project = Project::deleteProject($id);
		return Redirect::to('projects')->withErrors(array("Project $id deleted <b style='color:#009900'>successfully</b>"));
	}
	
	/* Deleta uma montagem | Pagina de confirmacao via GET */
	public function delete_assembly($id_project,$id_assembly){
		$project = Project::getProject($id_project);
		$assembly = Assembly::findOrFail($id_assembly);
		return View::make('assembly_delete')->with('project',$project)->with('assembly',$assembly);
	}
	
	/* Deleta um projeto | Confirmado via POST */
	public function delete_confirm_assembly($id_project){
		$id_assembly_2 = Input::get('id_assembly');
		$assembly = Assembly::deleteAssembly($id_assembly_2);
		
		return Redirect::to("projects/$id_project")->withErrors(array("Assembly trial number <b>$id_assembly_2</b> was <b style='color:#009900'>successfully</b> deleted."));
	}

	/* Tentativas de montagem para cada projeto */
	public function list_assemblies($id = null){
		
		/* Procura assemblies relacionadas ao projeto */
		$project = Project::findOrFail($id);
		$assemblies = $project->assemblies;
				
		/* Se nenhum id for transferido, redireciona para projects */			
		if ($id == null){
			return Redirect::to('projects')->withErrors(array('Project not found.'));
		}
		
		return View::make('assembly')->with('assemblies',$assemblies)->with('project',$project);
	}

	/* Roda QUAST */
	public function new_quast($id,$version){
		$project = Project::getProject($id);
		$folder = $this->raiz.$project->name_project;
		return View::make('quast_create')->with('project',$project)->with('version',$version)->with('folder', $folder);
	}

	public function run_new_quast($id){
		$project = Project::findOrFail($id);
		$folder = $this->raiz.$project->name_project;
		// var_dump($_POST, $_FILES);die();

		if (isset($_FILES["quast_fasta"]) && isset($_FILES["quast_gff"])){
			$errors = array();
			if ($_FILES["quast_fasta"]["name"] == ""){
				if (!is_file($this->raiz.$project->name_project."/quast_fasta.fasta")){
					$errors[] = "<b>Warning: </b>Please, inform reference fasta file.";
				}
			} else {
				move_uploaded_file($_FILES["quast_fasta"]["tmp_name"], $folder."/quast_fasta.fasta");
			}
			if ($_FILES["quast_gff"]["name"] == ""){
				if (!is_file($this->raiz.$project->name_project."/quast_gff.gff")){
					$errors[] = "<b>Warning: </b>Please, inform reference gff file.";
				}
			} else {
				move_uploaded_file($_FILES["quast_gff"]["tmp_name"], $folder."/quast_gff.gff");
			}
			if (count($errors)>0){
				return Redirect::to("projects/$id/new_quast/1")->withErrors($errors);
			}
			
			$quast_processors = (int)Input::get('quast_processors');
			if($quast_processors == '' or $quast_processors == 0) $quast_processors = 1;
			$list_assemblies = shell_exec("cd $folder && find . |grep \"_out.unpadded.fasta\" |grep _assembly");
			$list_assemblies = explode("\n", $list_assemblies);
			$results = implode(" ", $list_assemblies);

			
			$query = "cd $folder && ../../bin/quast-3.1/quast.py -R quast_fasta.fasta -G quast_gff.gff -t $quast_processors $results &";
			popen($query, "r");
			return Redirect::to("projects/$id")->withErrors(array("<b>Warning: </b>Your quast analysis is <b style=\"color:#009900\">running successfully</b>. Wait a couple of minutes and enter in Run Quast again."));
		} else {
			return Redirect::to("projects/$id/new_quast/1")->withErrors(array("<b>Warning: </b>Please, inform reference fasta and gff file(s)."));
		}
	}
	
	/* Cria uma nova montagem */
	public function new_assembly($id,$version){
		$project = Project::getProject($id);
		return View::make('assembly_create')->with('project',$project)->with('version',$version);
	}
	
	/* Grava e inicia a nova montagem */
	public function run_new_assembly($id){
		
		/* Construir o manifest para MIRA 4 */
		$name_project = Input::get('name_project');
		$name = 't'.Input::get('version');
		$job = Input::get('job_1').','.Input::get('job_2').','.Input::get('job_3');
		$NGS = Input::get('ngs_assembly');
		if($NGS == 'SOLEXA'){ $illumina = 1; } else { $illumina = 0; }
		$ngs = strtolower($NGS);
		$NGS = $NGS.'_SETTINGS';
		$readgroup = Input::get('readgroup');
		$autopairing = Input::get('autopairing');
		$general_parameters = Input::get('general_parameters');
		$ngs_parameters = Input::get('ngs_parameters');
		$assembler = Input::get('assembler');

		if($ngs = 'iontor')	$xml = "*.xml";
		else $xml = '';
		
		if($readgroup != 'fragment') $parameters = 'templatesize = '.Input::get('ts_1').' '.Input::get('ts_2')."\nsegmentplacement = ".Input::get('segment');
		else $parameters = '';
		
		if($autopairing == 1) $parameters .= 'autopairing';
		
		if($illumina == 1){ $data_ngs = "data = *R1.fastq *R2.fastq"; }
		else { $data_ngs = "*fastq"; }
		/* Definindo arquivo manifest - necessario apenas para o mira */
		$manifest = "project = $name\njob = $job\nparameters = $general_parameters $NGS $ngs_parameters\nreadgroup = $readgroup\ntechnology = $ngs\ndata = $data_ngs $xml\n$parameters";

		/* Manifest manual assembly */
		if($assembler == 'text') $manifest = "Manual assembly\nNo info available.";
		
		/* Manifest Minia */
		$minia_kmer = Input::get('minia_kmer');
		$minia_len_genome = Input::get('minia_len_genome');
		if($minia_kmer == '' or $minia_kmer == 0) $minia_kmer = 31;
		if($minia_len_genome == '' or $minia_len_genome == 0) $minia_len_genome = '3000000';
		if($assembler == 'minia') $manifest = "project = $name\nassembler = minia\nk_mer = $minia_kmer\nlength genome = $minia_len_genome";
		
		/* Manifest Newbler */
		$newbler_cut = Input::get('newbler_cut');
		if($newbler_cut == '' or $newbler_cut < 0) $newbler_cut = 18;
		$newbler_processors = Input::get('newbler_processors');
		$newbler_cluster = Input::get('newbler_cluster');
		if($newbler_cluster == '' or $newbler_cluster > 100 or $newbler_cluster < 1) $newbler_cluster = 100;
		if($newbler_processors == '' or $newbler_processors == 0) $newbler_processors = 16;
		if($assembler == 'newbler') $manifest = "project = $name\nassembler = newbler\nprocessors = $newbler_processors \n%cluster = $newbler_cluster\%";

		/* Manifest SPAdes */
		$spades_cut = Input::get('spades_cut');
		if($spades_cut == '' or $spades_cut < 0) $spades_cut = 18;
		$spades_processors = Input::get('spades_processors');
		$spades_cluster = Input::get('spades_cluster');
		if($spades_cluster == '' or $spades_cluster > 100 or $spades_cluster < 1) $spades_cluster = 100;
		if($spades_processors == '' or $spades_processors == 0) $spades_processors = 16;
		if($assembler == 'spades') $manifest = "project = $name\nassembler = spades\nprocessors = $spades_processors \n%cluster = $spades_cluster\%";
		
		/* Grava no banco de dados */
		$assembly = new Assembly;
		$assembly->fk_id_project = $id;
		$assembly->version_assembly = Input::get('version');
		$assembly->status_assembly = 'R'; //running
		$assembly->parameters_assembly = $manifest;		
		$assembly->save();
		
		/* Cria o manifest e executor */
		$folder = $this->raiz.$name_project;
		$arquivo = $this->raiz.$name_project.'/'.$name.'.manifest';
		
		/* Grava manifest */
		$pt = fopen($arquivo,'w');
		fwrite($pt,$manifest);
		fclose($pt);
		
		/* Correcao do bug - execucao duas vezes seguidas */
		shell_exec("cd $folder && chmod 775 *");

        /* DEFINE O MONTADOR QUE IRA EXECUTAR - Tambem declara o comando para montagem - vai rodar por fila ou nao */
        switch($assembler){
        	case 'mira':
				/* Execucao com nohup */ 
            	$query = "cd $folder && nohup ../../bin/mira $name.manifest > $name.log.txt &";

               	/* Grava exec_mira - uso de gerenciadores de fila 
              	$exec = $this->raiz.$name_project.'/mira.sh';
               	$exec_content = "#PBS -o mira.out\n#PBS -e mira.err\n\ncd \$PBS_O_WORKDIR\n../../bin/mira $name.manifest";
               	$pt = fopen($exec,'w');
               	fwrite($pt,$exec_content);
               	fclose($pt);

               	$query = "cd $folder && qsub -q assembly ./mira.sh";*/
               	break;
        	case 'mira39':
				/* Execucao com nohup */
            	$query = "cd $folder && nohup ../../bin/mira39 $name.manifest > $name.log.txt &";
				
                /* Grava exec_mira - uso de gerenciadores de fila 
                $exec = $this->raiz.$name_project.'/mira.sh';
                $exec_content = "#PBS -o mira.out\n#PBS -e mira.err\n\ncd \$PBS_O_WORKDIR\n../../bin/mira39 $name.manifest";
                $pt = fopen($exec,'w');
                fwrite($pt,$exec_content);
                fclose($pt);

                $query = "cd $folder && qsub -q assembly ./mira.sh";*/
                break;
            case 'newbler':				
				/* Execucao com nohup */
                $query = "cd $folder && nohup perl ../../bin/cut_left.pl $newbler_cut *.fastq $newbler_cluster && ../../bin/454/apps/mapper/bin/runAssembly -rip -m -cpu $newbler_processors -scaffold -o newbler_$name\_assembly *fq > $name.log.txt &";
				
                /* Grava exec_newbler - uso de gerenciadores de fila 
                $exec = $this->raiz.$name_project.'/newbler.sh';
                $exec_content = "#PBS -o newbler.out\n#PBS -e newbler.err\n\ncd \$PBS_O_WORKDIR\n../../bin/aquacen_sample_fastq -x -n $newbler_cluster -c $newbler_cut *.fastq \nmv *sample.fastq raw_data.fq \n../../bin/454/apps/mapper/bin/runAssembly -rip -m -cpu $newbler_processors -scaffold -o newbler_$name\_assembly *fq";
                $pt = fopen($exec,'w');
                fwrite($pt,$exec_content);
                fclose($pt);

                $query = "cd $folder && qsub -q assembly ./newbler.sh";
                	*/
                break;

			case 'spades':
				$mais = "";
				if($ngs = 'iontor')	$mais = " --iontorrent ";
				/* Execucao com nohup */
				
                $query = "cd $folder && nohup perl ../../bin/cut_left.pl $spades_cut *.fastq $spades_cluster && ../../bin/SPAdes-3.6.0-Linux/bin/spades.py -o spades_$name\_assembly -s out_trim.fq -k 21,33,55,77,99,127 -t $spades_processors $mais > $name.log.txt 2>&1 && cd spades_$name\_assembly && cp spades.log spades2.log &";
                /* Grava exec_newbler - uso de gerenciadores de fila 
                $exec = $this->raiz.$name_project.'/spades.sh';
                $exec_content = "#PBS -o spades.out\n#PBS -e spades.err\n\ncd \$PBS_O_WORKDIR\n../../bin/aquacen_sample_fastq -x -n $spades_cluster -c $spades_cut *.fastq \nmv *sample.fastq out_trim.fq \n../../bin/SPAdes-3.6.0-Linux/bin/spades.py -o spades_$name\_assembly -s out_trim.fq -k 21,33,55,77,99,127 $mais > $name.log.txt ";
                $pt = fopen($exec,'w');
                fwrite($pt,$exec_content);
                fclose($pt);

                $query = "cd $folder && qsub -q assembly ./spades.sh";
                	*/
                break;

			case 'minia':
				/* Execucao com nohup */
				$query = "cd $folder && nohup ../../bin/minia *.fastq $minia_kmer 3 $minia_len_genome minia_$name\_assembly > $name.log.txt &";
				
				/* Grava exec_minia - uso de gerenciadores de fila 
                $exec = $this->raiz.$name_project.'/minia.sh';
                $exec_content = "#PBS -o minia.out\n#PBS -e minia.err\n\ncd \$PBS_O_WORKDIR\n../../bin/minia *.fastq $minia_kmer 3 $minia_len_genome minia_$name\_assembly";
                $pt = fopen($exec,'w');
                fwrite($pt,$exec_content);
                fclose($pt);

                $query = "cd $folder && qsub -q assembly ./minia.sh";*/
				break;
            default:
                 $query = "";
                 break;
		}
		
		
		/* --------------------- IMPORTANTE: INICIA A MONTAGEM ------------------------------- */
		popen($query,"r");
		
		return Redirect::to("projects/$id")->withErrors(array('<b>Warning: </b>Your assembly is <b style="color:#009900">running successfully</b>. Wait couple of hours and click <b>"Update"</b>.'));
	}

	/* 
	 * IMPORTANTE:
	 * Para cada tentativa de montagem:
	 * 1 project tem N assemblies 
	 * 1 assembly tem N trial
	 * 1 trial tem 5 curation 
	*/
	public function list_trial($id_project,$id_assembly){
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$trial = $assembly->curations;
		/* Buscando uma curadoria por ves */
		$f1 = Curation::getCuration($id_assembly,$id_project,1);
		$f2 = Curation::getCuration($id_assembly,$id_project,2);
		$f3 = Curation::getCuration($id_assembly,$id_project,3);
		$f4 = Curation::getCuration($id_assembly,$id_project,4);
		$f5 = Curation::getCuration($id_assembly,$id_project,5);
		
		return View::make('trial')->with('trial',$trial)->with('project',$project)->with('assembly',$assembly)->with('f1',$f1)->with('f2',$f2)->with('f3',$f3)->with('f4',$f4)->with('f5',$f5);
	}
	
	/* Etapa 1 de finalizacao de montagens */
	public function f1($id_project,$id_assembly){
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$trial = $assembly->curations;
		return View::make('f1')->with('trial',$trial)->with('project',$project)->with('assembly',$assembly);
	}
	
	/* Etapa 1 - Executa F1 */
	public function run_f1($id_project,$id_assembly){
		
		/* Recebe os dados via formulario */
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$step = Input::get('step');
		$fna = Input::get('fna');
		$gbk = Input::get('gbk');
		$optical_mapping = Input::get('optical_mapping');
		$report = Input::get('optical_mapping_value');
		
		/* Corrige protocolo ftp dos arquivos FNA e GBK - erro no Firefox / Linux */
		$fna = str_replace('http://ftp', 'ftp://ftp', $fna);
		$gbk = str_replace('http://ftp', 'ftp://ftp', $gbk);
		
		if(($fna == '' or $gbk == '')and($optical_mapping == 'FALSE'))
			return Redirect::to("projects/$id_project/assemblies/$id_assembly/F1")->withErrors(array('The fields "Fasta file" and "Genbank file" are required.'));
		
		$step_folder = $step.'_assembly';
		$trial = Input::get('trial');
		$trial_file = 't'.$trial.'_out.unpadded.fasta';
		$trial_folder = 't'.$trial.'_assembly';
		$trial_results_folder = 't'.$trial.'_d_results';
		  
		/* Criando pastas */
		$folder = system("cd ../app/assembly/$project->name_project/$trial_folder/curation && pwd");
		
		if(empty($folder)){
			echo "Creating folders and downloading data: Wait.<br/>OK<br/><br/>";
			
			/* POR REFERENCIA */
			if($optical_mapping == 'FALSE'){
				system("cd ../app/assembly/$project->name_project/$trial_folder && mkdir curation && cd curation && mkdir f1 f2 f3 f4 f5 && wget $fna && wget $gbk"); 
			}
			/* End */

			/* POR MAPA OPTICO */
			if($optical_mapping == 'TRUE'){
				$var = explode("\n",$report);
				$orientation = array();
				$contig = array();
				
				foreach($var as $v){
					if(($v != '')and($v != "\n")){
						$element = explode("\t",$v);
						if($element[3] != 'Contig'){
							$insert = explode(" ",$element[3]);
							array_push($contig,$insert[0]);
							array_push($orientation,$element[6]);
						}
					}					
				}

				$arquivo = file_get_contents("../app/assembly/$project->name_project/$trial_folder/$trial_results_folder/$trial_file");
				$seqs = array();
				$seqs = explode(">",$arquivo);
				
				/* Ordenar contigs e construir scaffolds */
				$scaffold = "";
				$num_contigs = count($contig);
				
				/* $num_contigs - 1 remove o ultimo contig repetitivo */
				if($contig[0] == $contig[$num_contigs-1]){
					$num_contigs--;
				}
				
				for($i = 0; $i < $num_contigs; $i++){
					foreach($seqs as $seq){
						$s1 = explode("\n",$seq);
                        $s = explode(" ",$s1[0]);
						$c = explode("\n",$contig[$i]);
						if($c[0]."\n" == $s[0]."\n"){
							echo "$c[0] = $s[0]<br/>";
							$scaf = str_replace($c[0]."\n", '', $seq);
							if($orientation[$i] == -1 or $orientation[$i] == '-1'){
								$scaf = strrev($scaf);
								
								/* Gerando complementar da reversa */
								$scaf = str_ireplace('A', 'B', $scaf);
								$scaf = str_ireplace('T', 'U', $scaf);
								$scaf = str_ireplace('C', 'D', $scaf);
								$scaf = str_ireplace('G', 'H', $scaf);
				
								$scaf = str_ireplace('B', 'T', $scaf);
								$scaf = str_ireplace('U', 'A', $scaf);
								$scaf = str_ireplace('D', 'G', $scaf);
								$scaf = str_ireplace('H', 'C', $scaf);
							}

							$scaffold .= $scaf.'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
						}
					}
				}
				
				/* Criando pastas */
				system("cd ../app/assembly/$project->name_project/$trial_folder && mkdir curation && cd curation && mkdir f1 f2 f3 f4 f5");
				
				/* Gravando em arquivo FNA */
				$pt_mo = fopen("../app/assembly/$project->name_project/$trial_folder/curation/mapa_optico.fna",'w');
				fwrite($pt_mo,">scaffold.\n");
				fwrite($pt_mo,$scaffold);
				fclose($pt_mo);
				
				/* Gravando arquivo GBK fake */
				$pt_mo_gbk = fopen("../app/assembly/$project->name_project/$trial_folder/curation/mapa_optico.gbk",'w');
				$gbk_fake = "LOCUS       Mapping_optical            1 bp    DNA     circular CON \nDEFINITION  Map_optical \nORIGIN\n \n//";
				fwrite($pt_mo_gbk,$gbk_fake);
				fclose($pt_mo_gbk);
				
				echo "Scaffold construido com sucesso.";
								 
			}
			/* End - mapa optico */
			
			$folder = system("cd ../app/assembly/$project->name_project/$trial_folder/curation && pwd");
		}
		else{
			return Redirect::to("projects/$id_project/assemblies/$id_assembly/F1")->withErrors(array('Reference file already exists.'));
		}
		
		/* Executando CONTIGuatorD */
		echo "Run align with CONTIGuatorD: Wait.<br/>";
		system("export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/$trial_folder/curation && cd f1 && ../../../../../bin/CONTIGuatorD.py -c ../../$trial_results_folder/$trial_file -g ../*gbk -r ../*fna");
		echo "OK<br/><br/>";
		
		/* Convertendo PDF para PNG */
		echo "Creating PNG image: Wait.<br/>";
		$pdf_name = system("cd ../app/assembly/$project->name_project/$trial_folder/curation/f1/Map* && ls *.pdf");
		$folder_map = substr($pdf_name,0,-5);
		$folder_map = str_replace('_', '.', $folder_map);
		$folder_map = 'Map_'.$folder_map.'.';
		$myurl = $folder.'/f1/'.$folder_map.'/'.$pdf_name;
		$image = new Imagick($myurl);
		$image->setResolution( 450, 450 );
		$image->setImageFormat( "png" );
		if($image->writeImage("$folder/f1/align_image.png")) echo 'OK<br/><br/>';
		else echo "Fail";
		
		/* Gerando arquivos temporarios */
		echo "Creating tmp files: Wait.<br/>";
		system("cp ../app/assembly/$project->name_project/$trial_folder/curation/f1/align_image.png tmp/f1_a$id_assembly\_$project->name_project\.png && cp ../app/assembly/$project->name_project/$trial_folder/curation/f1/Map*/*.pdf tmp/f1_a$id_assembly\_$project->name_project\.pdf");
		echo "OK<br/>";
		
		/* Mover para o TMP */
		echo "Movendo fasta para TMP: Wait. <br/>";
		system("cp ../app/assembly/$project->name_project/$trial_folder/curation/f1/$folder_map/PseudoContig.fsa tmp/f1_a$id_assembly\_$project->name_project\.fasta");
		echo "OK<br/>";
		
		/* Gerar relatorio de gaps */
		echo "Calculando a quantidade de Contigs: Wait. Num_contigs: ";
		$quant_contigs = system("cd tmp && ../../app/bin/mcontig.py f1_a$id_assembly\_$project->name_project\.fasta");
		system("cd tmp && mv m.fasta m1_a$id_assembly\_$project->name_project\.fasta");
		echo "<br/>OK<br/>";
		$quant_gaps = $quant_contigs - 1;
		
		/* Atualizar banco de dados */
		$curation = new Curation;
		$curation->fk_id_assembly = $id_assembly;
		$curation->version_curation = 1;
		$curation->report_curation = '';
		$curation->num_scaffolds = $quant_contigs;
		$curation->len_genome_curation = '';
		$curation->min_contig_curation = '';
		$curation->max_contig_curation = '';
		$curation->n50_curation = '';
		$curation->fk_id_project = $id_project;
		$curation->save();
		
		return Redirect::to("projects/$id_project/assemblies/$id_assembly")->with('project',$project)->with('assembly',$assembly);
		
	}
	
	/* Etapa 2 de finalizacao de montagens */
	public function f2($id_project,$id_assembly){
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$trial = $assembly->curations;
		return View::make('f2')->with('trial',$trial)->with('project',$project)->with('assembly',$assembly);
	}
	
	/* Etapa 2 - executador */
	public function run_f2($id_project,$id_assembly){
		/* Recebe dados do projeto e da montagem */
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		
		$skip = Input::get('skip');		
		
		/* Copia PseudoContig.fsa, o renomeia para f1.fasta e o move para pasta f2 */
		$pdf_name = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f1/Map* && ls *.pdf");
		$folder_map = substr($pdf_name,0,-5);
		$folder_map = str_replace('_', '.', $folder_map);
		$folder_map = 'Map_'.$folder_map.'.'; 
		system ("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f1/$folder_map/PseudoContig.fsa ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2/f1.fasta");
		
		# Pular etapa ou executar moveDNAA
		if($skip == ''){	
			/* Executa movednaa.py */
			system("export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2 && ../../../../../bin/movednaa.py f1.fasta ../*fna");
			
			/* Executa mcontig.py 1.a vez */
			system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2 && ../../../../../bin/mcontig.py f2.fasta && mv m.fasta m2.fasta");
		}
		else{
			/* Executa mcontig.py 1.a vez */
			system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2 && mv f1.fasta f2.fasta && ../../../../../bin/mcontig.py f2.fasta && mv m.fasta m2.fasta");
		}
		
		/* Executa CONTIGuatorD */
		system("export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2 && ../../../../../bin/CONTIGuatorD.py -c m2.fasta -g ../*gbk -r ../*fna");
		
		/* Convertendo PDF para PNG */
		$trial_folder = "t$assembly->version_assembly\_assembly";
		echo "Creating PNG image: Wait.<br/>";
		$folder = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation && pwd");
		$pdf_name = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2/Map* && ls *.pdf");
		$folder_map = substr($pdf_name,0,-5);
		$folder_map = str_replace('_', '.', $folder_map);
		$folder_map = 'Map_'.$folder_map.'.';
		$myurl = $folder.'/f2/'.$folder_map.'/'.$pdf_name;
		$image = new Imagick($myurl);
		$image->setResolution( 450, 450 );
		$image->setImageFormat( "png" );
		if($image->writeImage("$folder/f2/align_image.png")) echo 'OK<br/><br/>';
		else echo "Fail";
		
		/* Gerando arquivos temporarios */
		echo "Creating tmp files: Wait.<br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2/align_image.png tmp/f2_a$id_assembly\_$project->name_project\.png && cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f2/Map*/*.pdf tmp/f2_a$id_assembly\_$project->name_project\.pdf");
		echo "OK<br/>";
		
		/* Copiando fasta para o TMP */
		echo "Copiando fasta para TMP: Wait. <br/>";
		system("cp ../app/assembly/$project->name_project/$trial_folder/curation/f2/$folder_map/PseudoContig.fsa tmp/f2_a$id_assembly\_$project->name_project\.fasta");
		echo "OK<br/>";
		
		/* Gerar relatorio de gaps */
		echo "Calculando a quantidade de Contigs: Wait. Num_contigs: ";
		$quant_contigs = system("cd tmp && ../../app/bin/mcontig.py f2_a$id_assembly\_$project->name_project\.fasta");
		system("cd tmp && mv m.fasta m2_a$id_assembly\_$project->name_project\.fasta");
		echo "<br/>OK<br/>";
		$quant_gaps = $quant_contigs - 1;
		
		/* Atualizar banco de dados */
		$curation = new Curation;
		$curation->fk_id_assembly = $id_assembly;
		$curation->version_curation = 2;
		$curation->report_curation = '';
		$curation->num_scaffolds = $quant_contigs;
		$curation->len_genome_curation = '';
		$curation->min_contig_curation = '';
		$curation->max_contig_curation = '';
		$curation->n50_curation = '';
		$curation->fk_id_project = $id_project;
		$curation->save();
		
		return Redirect::to("projects/$id_project/assemblies/$id_assembly")->with('project',$project)->with('assembly',$assembly);
		
	}
	
	/* Etapa 3 de finalizacao de montagens */
	public function f3($id_project,$id_assembly){
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$trial = $assembly->curations;
		$blast = array();
		$contig_list = array();
		
		/* $pt => Nas linhas pares o cabecalho e impares as sequencias */
		$m3 = 'tmp/m3_PART_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
		/* Valida se ja existe uma edicao anterior */
		try{
			$pt = file($m3);
		}
		catch(Exception $erro){
			$m2 = 'tmp/m2_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
			$pt = file($m2);		
		}
		$num_pt = count($pt);
		
		/* Para cada sequencia, grave o contig left e o contig right e faca blast entre eles */
		for($i = 0; $i < $num_pt; $i = $i+2){
			$blast_one = '';
			if($i != $num_pt-2){
				/* Gravando os ultimos 3000pb do contig atual */
				$contig = $pt[$i];
				$pt_left = fopen('tmp/tmp_left','w');
				$contig_left = substr($pt[$i+1],-3001);
				fwrite($pt_left, $contig_left);
				fclose($pt_left);
				
				/* Gravando os primeiros 3000pb proximo contig */
				$contig_next = $pt[$i+2];
				$pt_right = fopen('tmp/tmp_right','w');
				$contig_right = substr($pt[$i+3],0,3000);
				fwrite($pt_right, $contig_right);
				fclose($pt_right);
				
				/* Efetuando blast entre eles */
				$query = "export PATH=\$PATH:/opt/ncbi/bin/ && cd tmp && blastn -subject tmp_left -query tmp_right > tmp_blast.txt";
				system($query);
				$pt_tmp = fopen('tmp/tmp_blast.txt','r');
				while(!feof($pt_tmp)){
					$blast_one .= fgets($pt_tmp);
				}				
				array_push($contig_list,$contig,$contig_next);
				array_push($blast,$blast_one);
			}
			else{
				/* Gravando o contig atual - ultimo */
				$contig = $pt[$i];
				$pt_left = fopen('tmp/tmp_left','w');
				$contig_left = substr($pt[$i+1],-3000);
				fwrite($pt_left, $contig_left);
				fclose($pt_left);
				
				/* Gravando o contig 1 */
				$contig_next = $pt[0];
				$pt_right = fopen('tmp/tmp_right','w');
				$contig_right = substr($pt[1],0,3000);
				fwrite($pt_right, $contig_right);
				fclose($pt_right);
				
				/* Efetuando blast entre eles */
				$query = "export PATH=\$PATH:/opt/ncbi/bin/ && cd tmp && blastn -subject tmp_left -query tmp_right > tmp_blast.txt";
				system($query);
				$pt_tmp = fopen('tmp/tmp_blast.txt','r');
				while(!feof($pt_tmp)){
					$blast_one .= fgets($pt_tmp);
				}				
				array_push($contig_list,$contig,$contig_next);
				array_push($blast,$blast_one);
			}			
		}
		
		/* Recebe o arquivo multifasta e analisa os gaps -> NECESSARIO PERL
		 * perl -pi -e 's/\n/#/g' teste.fasta
		 * perl -pi -e 's/#>/\n>/g' teste.fasta
		 * grep ">contig_[num]#" teste.fasta > contig_[num]
		 * perl -pi -e 's/>contig_[num]#//g' contig_[num] 
		 */
		 
		return View::make('f3')->with('trial',$trial)->with('project',$project)->with('assembly',$assembly)->with('blast',$blast)->with('contig_list',$contig_list);
		
	}

	public function run_f3_part($id_project,$id_assembly){
		
		print "<div style='background-color:#eee;color:#333;font-family:arial;width:100%-100px;height:100%-20px;margin:0px;padding:20px 50px;'>";
		print "<center><img src=\"../../../../../img/minilogo.png\" /><br/>";
		print "<h2>Simba is closing the gap. Wait...</h2><br/>PHP Parser by <a target=\"_blank\" href=\"//github.com/dcbmariano\">dcbmariano</a></center><br><br>";
		
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		
		/* Recebe valores para modificacao */
		$length_subject = Input::get('length_subject');
		if($length_subject == '')
			$length_subject = 3000;
		$contig_edit = Input::get('contig_right');
		$cut_left = Input::get('cut_left'); // Tende a ser um numero proximo a 3000
		$cut_left = $length_subject-$cut_left; // WARNING: Esse codigo pode apresentar problema se o contig for menor do que 3000
		$cut_right = Input::get('cut_right'); // Deve ser um numero menor do que $cut_left
		
		/* $pt => Nas linhas pares o cabecalho e impares as sequencias */
		$m3 = 'tmp/m3_PART_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
		/* Valida se ja existe uma edicao anterior */
		try{
			$pt = file($m3);
		}
		catch(Exception $erro){
			$m2 = 'tmp/m2_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
			$pt = file($m2);		
		}
		$num_pt = count($pt);
		
		/* Laco que percorre os cabecalhos */
		for($i = 0; $i < $num_pt; $i = $i+2){
			$name_contig = substr_count($pt[$i], $contig_edit); // cabecalho correto se maior que 0
			
			/* Verifica se eh o primeiro contig */
			if($i != 0){				
				if($name_contig > 0){
					/* Efetua o corte no elemento anterior e posterior do array */
					$count_anterior = strlen($pt[$i-1]);
					$cut = $count_anterior - $cut_left;
					print "Tamanho contig left: ".$count_anterior."<br/>Tamanho do corte no contig left: ".$cut_left."<br/>";
					print "CONTIG: ".$pt[$i]."<br/>";
					print "ANTERIOR: ".$pt[$i-1]."<br/>";
					print "POSTERIOR: ".$pt[$i+1]."<br/>";
					$pt[$i-1] = substr($pt[$i-1],0,$cut);
					$pt[$i-1] = str_replace("\n", "", $pt[$i-1]);
					$pt[$i+1] = substr($pt[$i+1],$cut_right);
					$pt[$i] = ''; // Deleta o cabecalho e junta ambos os contigs
					
					PRINT "<BR><BR>";
					print "CONTIG NOVO: ".$pt[$i]."<br/>";
					print "ANTERIOR NOVO: ".$pt[$i-1]."<br/>";
					print "POSTERIOR NOVO: ".$pt[$i+1]."<br/>";
					break;
				}
			}
			else {
				if($name_contig > 0){
				/* Verifica se existe sobreposicao entre o ultimo e o primeiro, mas corta apenas no ultimo */
					$count_anterior = strlen($pt[$num_pt-1]);
					$cut = $count_anterior - $cut_left;
					print "Tamanho contig left: ".$count_anterior."<br/>Tamanho do corte no contig left: ".$cut_left."<br>";
					print "NOVO ULTIMO CONTIG: ".$pt[$num_pt - 1]."<br/>";
					print "POSTERIOR: CONTIG_1<br/>";
					$pt[$num_pt-1] = substr($pt[$num_pt-1],0,$cut);
					break;
				}
			}
		}
		/* Gravando resultado */
		$write = fopen($m3,'w');
		foreach($pt as $p){
			fwrite($write, $p);
		}
		fclose($write);
		return Redirect::to("projects/$id_project/assemblies/$id_assembly/F3");
	}

	public function run_f3($id_project,$id_assembly){
		/* Recebe dados do projeto e da montagem */
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		
		$skip = Input::get('skip');		
		
		/* Roda etapa 3 ou apenas transfere etapa 2 para etapa 3 */
		if($skip == ''){
			/* Movendo arquivo m3_PART */
			system("cd tmp && cp m3_PART_a$assembly->id_assembly\_$project->name_project\.fasta ../../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3/m3_PART.fasta");
		}
		else{
			/* copiando m2 para m3 */
			system("cd tmp && cp m2_a$assembly->id_assembly\_$project->name_project\.fasta ../../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3/m3_PART.fasta");
			system("cd tmp && cp m2_a$assembly->id_assembly\_$project->name_project\.fasta m3_a$assembly->id_assembly\_$project->name_project\.fasta");
		}
		
		/* Executa CONTIGuatorD */
		system("export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3 && ../../../../../bin/CONTIGuatorD.py -c m3_PART.fasta -g ../*gbk -r ../*fna");
		
		/* Convertendo PDF para PNG */
		$trial_folder = "t$assembly->version_assembly\_assembly";
		echo "Creating PNG image: Wait.<br/>";
		$folder = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation && pwd");
		$pdf_name = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3/Map* && ls *.pdf");
		$folder_map = substr($pdf_name,0,-5);
		$folder_map = str_replace('_', '.', $folder_map);
		$folder_map = 'Map_'.$folder_map.'.';
		$myurl = $folder.'/f3/'.$folder_map.'/'.$pdf_name;
		$image = new Imagick($myurl);
		$image->setResolution( 450, 450 );
		$image->setImageFormat( "png" );
		if($image->writeImage("$folder/f3/align_image.png")) echo 'OK<br/><br/>';
		else echo "Fail";
		
		/* Gerando arquivos temporarios */
		echo "Creating tmp files: Wait.<br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3/align_image.png tmp/f3_a$id_assembly\_$project->name_project\.png && cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3/Map*/*.pdf tmp/f3_a$id_assembly\_$project->name_project\.pdf");
		echo "OK<br/>";
		
		/* Copiando fasta para o TMP */
		echo "Copiando fasta para TMP: Wait. <br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f3/$folder_map/PseudoContig.fsa tmp/f3_a$id_assembly\_$project->name_project\.fasta");
		echo "OK<br/>";
		
		/* Gerar relatorio de gaps */
		echo "Calculando a quantidade de Contigs: Wait. Num_contigs: ";
		$quant_contigs = system("cd tmp && ../../app/bin/mcontig.py f3_a$id_assembly\_$project->name_project\.fasta");
		system("cd tmp && cp m3_PART_a$id_assembly\_$project->name_project\.fasta m3_a$id_assembly\_$project->name_project\.fasta");
		echo "<br/>OK<br/>";
		$quant_gaps = $quant_contigs - 1;
		
		/* Atualizar banco de dados */
		$curation = new Curation;
		$curation->fk_id_assembly = $id_assembly;
		$curation->version_curation = 3;
		$curation->report_curation = '';
		$curation->num_scaffolds = $quant_contigs;
		$curation->len_genome_curation = '';
		$curation->min_contig_curation = '';
		$curation->max_contig_curation = '';
		$curation->n50_curation = '';
		$curation->fk_id_project = $id_project;
		$curation->save();
		
		return Redirect::to("projects/$id_project/assemblies/$id_assembly")->with('project',$project)->with('assembly',$assembly);
		
	}
	
	/* Etapa 4 de finalizacao de montagens */
	public function f4($id_project,$id_assembly){
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$trial = $assembly->curations;		
		$contig_list = array();
		
		/* $pt => Nas linhas pares o cabecalho e impares as sequencias */
		$m4 = 'tmp/m4_PART_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
	
		/* Valida se ja existe uma edicao anterior */
		try{
			$pt = file($m4);
		}
		catch(Exception $erro){
			$m3 = 'tmp/m3_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
			$pt = file($m3);		
		}
		$num_pt = count($pt);
		
		/* Preenche a lista de contigs */
		for($i = 0; $i < $num_pt; $i = $i+2){
			array_push($contig_list,$pt[$i]);
		}
		
		return View::make('f4')->with('trial',$trial)->with('project',$project)->with('assembly',$assembly)->with('contig_list',$contig_list);
	}
	
	public function run_f4_part($id_project,$id_assembly){
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		$trial = $assembly->curations;	
		
		$contig_left = Input::get('contig_left');
		$contig_left = str_replace('>', '', $contig_left);
		$contig_left = str_replace("\n","",$contig_left);
		$contig_right = Input::get('contig_right');
		$contig_right = str_replace('>', '', $contig_right);
		$contig_right = str_replace("\n","",$contig_right);
		
		/* Copiar m3.fasta da pasta tmp */
		system("cp tmp/m3_a$assembly->id_assembly\_$project->name_project\.fasta ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/m3.fasta");
		
		/* Execucao do mapRepeat */
		$m4 = 'tmp/m4_PART_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
	
		/* Valida se ja existe uma edicao anterior */
		try{
			$pt = file($m4);
			$q = "export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t".$assembly->version_assembly."_assembly/curation/f4 && ../../../../../bin/mapRepeat.py m4_PART.fasta ../*fna ../../../ $contig_left $contig_right > log_mapRepeat.txt";
		}
		catch(Exception $erro){
			$q = "export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t".$assembly->version_assembly."_assembly/curation/f4 && ../../../../../bin/mapRepeat.py m3.fasta ../*fna ../../../ $contig_left $contig_right > log_mapRepeat.txt";
		}
		
		print $q;
		system($q);
		
		/* Mover m4_PART.fasta para tmp */
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/m4_PART.fasta tmp/m4_PART_a$assembly->id_assembly\_$project->name_project\.fasta");
		
		return Redirect::to("projects/$id_project/assemblies/$id_assembly/F4");
	}

	public function run_f4($id_project,$id_assembly){
		
		/* Recebe dados do projeto e da montagem */
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		
		$skip = Input::get('skip');		
		
		/* Roda etapa 4 apenas transferindo etapa 3 para etapa 4 */
		if($skip != ''){
			/* copiando f3 para f4 */
			system("cd tmp && cp m3_a$assembly->id_assembly\_$project->name_project\.fasta ../../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/m4_PART.fasta");
		}
		
		/* Executa CONTIGuatorD */
		system("export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4 && ../../../../../bin/CONTIGuatorD.py -c m4_PART.fasta -g ../*gbk -r ../*fna");
		
		/* Convertendo PDF para PNG */
		$trial_folder = "t$assembly->version_assembly\_assembly";
		echo "Creating PNG image: Wait.<br/>";
		$folder = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation && pwd");
		$pdf_name = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/Map* && ls *.pdf");
		$folder_map = substr($pdf_name,0,-5);
		$folder_map = str_replace('_', '.', $folder_map);
		$folder_map = 'Map_'.$folder_map.'.';
		$myurl = $folder.'/f4/'.$folder_map.'/'.$pdf_name;
		$image = new Imagick($myurl);
		$image->setResolution(450,450);
		$image->setImageFormat( "png" );
		if($image->writeImage("$folder/f4/align_image.png")) echo 'OK<br/><br/>';
		else echo "Fail";
		
		/* Gerando arquivos temporarios */
		echo "Creating tmp files: Wait.<br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/align_image.png tmp/f4_a$id_assembly\_$project->name_project\.png && cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/Map*/*.pdf tmp/f4_a$id_assembly\_$project->name_project\.pdf");
		echo "OK<br/>";
		
		/* Copiando fasta para o TMP */
		echo "Copiando fasta para TMP: Wait. <br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f4/$folder_map/PseudoContig.fsa tmp/f4_a$id_assembly\_$project->name_project\.fasta");
		echo "OK<br/>";
		
		/* Gerar relatorio de gaps */
		echo "Calculando a quantidade de Contigs: Wait. Num_contigs: ";
		$quant_contigs = system("cd tmp && ../../app/bin/mcontig.py f4_a$id_assembly\_$project->name_project\.fasta");
		system("cd tmp && mv m.fasta m4_a$id_assembly\_$project->name_project\.fasta");
		echo "<br/>OK<br/>";
		$quant_gaps = $quant_contigs - 1;
		
		/* Atualizar banco de dados */
		$curation = new Curation;
		$curation->fk_id_assembly = $id_assembly;
		$curation->version_curation = 4;
		$curation->report_curation = '';
		$curation->num_scaffolds = $quant_contigs;
		$curation->len_genome_curation = '';
		$curation->min_contig_curation = '';
		$curation->max_contig_curation = '';
		$curation->n50_curation = '';
		$curation->fk_id_project = $id_project;
		$curation->save();
		
		return Redirect::to("projects/$id_project/assemblies/$id_assembly")->with('project',$project)->with('assembly',$assembly);
		
	}
	
	public function f5($id_project,$id_assembly){
		/* Recebe dados do projeto e da montagem */
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		
		/* $pt => Nas linhas pares o cabecalho e impares as sequencias */
		$m4 = 'tmp/m4_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
		$erro = 0;
		
		/* Valida se ja existe a versao F4 */
		try{
			$pt = file($m4);
		}
		catch(Exception $erro){
			return Redirect::to("projects/$id_project/assemblies/$id_assembly")->with('project',$project)->with('assembly',$assembly)->withErrors(array('File F4 not found.'));
		}
		
		$num_pt = count($pt);
		
		/* Validacoes: 
			R	Purine (A or G)
			Y	Pyrimidine (C, T, or U)
			M	C or A
			K	T, U, or G
			W	T, U, or A
			S	C or G
			B	C, T, U, or G (not A)
			D	A, T, U, or G (not C)
			H	A, T, U, or C (not G)
			V	A, C, or G (not T, not U)
			N	Any base (A, C, G, T, or U)
		*/
		
		$r = 0; $y = 0; $m = 0; $k = 0; $w = 0; $s = 0; $b = 0; $d = 0; $h = 0; $v = 0; $n = 0; 
		
		for($i = 1; $i < $num_pt; $i = $i+2){			
			$r += substr_count(strtoupper($pt[$i]), 'R');
			$y += substr_count(strtoupper($pt[$i]), 'Y');
			$m += substr_count(strtoupper($pt[$i]), 'M');
			$k += substr_count(strtoupper($pt[$i]), 'K');
			$w += substr_count(strtoupper($pt[$i]), 'W');
			$s += substr_count(strtoupper($pt[$i]), 'S');
			$b += substr_count(strtoupper($pt[$i]), 'B');
			$d += substr_count(strtoupper($pt[$i]), 'D');
			$h += substr_count(strtoupper($pt[$i]), 'H');
			$v += substr_count(strtoupper($pt[$i]), 'V');
			$n += substr_count(strtoupper($pt[$i]), 'N');
		}


		/* Analise do arquivo excluded */
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f1/UnMappedContigs/Excluded.fsa tmp/excluded_a$assembly->id_assembly\.fasta");
		$exc_f4_statistics =  shell_exec("../app/bin/contiginfo.py tmp/excluded_a$assembly->id_assembly\.fasta");
		$f4_statistics = shell_exec("../app/bin/contiginfo.py tmp/m4_a$id_assembly\_$project->name_project\.fasta");
		
		$nucleotideos = array($r,$y,$m,$k,$w,$s,$b,$d,$h,$v,$n); 
		
		/* Valida se ja existe a versao F5 */
		try{
			$m5 = 'tmp/m5_a'.$assembly->id_assembly.'_'.$project->name_project.'.fasta';
			$pt5 = file($m5);			
			$num_pt5 = count($pt5);
			
			$r5 = 0; $y5 = 0; $m5 = 0; $k5 = 0; $w5 = 0; $s5 = 0; $b5 = 0; $d5 = 0; $h5 = 0; $v5 = 0; $n5 = 0;
			
			for($i = 1; $i < $num_pt5; $i = $i+2){			
				$r5 += substr_count(strtoupper($pt5[$i]), 'R');
				$y5 += substr_count(strtoupper($pt5[$i]), 'Y');
				$m5 += substr_count(strtoupper($pt5[$i]), 'M');
				$k5 += substr_count(strtoupper($pt5[$i]), 'K');
				$w5 += substr_count(strtoupper($pt5[$i]), 'W');
				$s5 += substr_count(strtoupper($pt5[$i]), 'S');
				$b5 += substr_count(strtoupper($pt5[$i]), 'B');
				$d5 += substr_count(strtoupper($pt5[$i]), 'D');
				$h5 += substr_count(strtoupper($pt5[$i]), 'H');
				$v5 += substr_count(strtoupper($pt5[$i]), 'V');
				$n5 += substr_count(strtoupper($pt5[$i]), 'N');
			}
			
			$nucleotideos5 = array($r5,$y5,$m5,$k5,$w5,$s5,$b5,$d5,$h5,$v5,$n5);
			
			$f5_statistics = shell_exec("../app/bin/contiginfo.py tmp/m5_a$id_assembly\_$project->name_project\.fasta");
			
			return View::make('f5')->with('project',$project)->with('assembly',$assembly)->with('nucleotideos',$nucleotideos)->with('exc_f4_statistics',$exc_f4_statistics)->with('f4_statistics',$f4_statistics)->with('nucleotideos5',$nucleotideos5)->with('f5_statistics',$f5_statistics);
		}
		catch(Exception $erro){
			$erro = 1;
		}
				
		return View::make('f5')->with('project',$project)->with('assembly',$assembly)->with('nucleotideos',$nucleotideos)->with('exc_f4_statistics',$exc_f4_statistics)->with('f4_statistics',$f4_statistics);
	}

	public function run_f5($id_project,$id_assembly){
		
		/* Recebe dados do projeto e da montagem */
		$assembly = Assembly::findOrFail($id_assembly);
		$project = Project::findOrFail($id_project);
		
		if(Input::get('f4'))
			$transfer_f4 = Input::get('f4');
	
		/* Recebendo arquivo RAW */
		if (Input::hasFile('f5')){
			/* Criando diretorio */
			$destinationPath = public_path()."/uploads";
			$filename = "m5_a".$assembly->id_assembly."_".$project->name_project.".fasta";
			$extension = Input::file('f5')->getClientOriginalExtension(); 
			if($extension == 'fasta' or $extension == 'fa' or $extension == 'fna'){
				$upload_success = Input::file('f5')->move($destinationPath, $filename);
			}
			else {
				return Redirect::to('projects')->withErrors(array("Invalid extension. Simba requires BAM, SFF or FASTQ file."))->with('projects',$projects);
			}
		}

		/* Movendo arquivo RAW para diretorio especifico */
		system("cd uploads && mv $filename ../tmp/.");
		
		/* Copiando m5 para a pasta curation/f5 */
		system("cd tmp && cp $filename ../../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f5/m5.fasta");
		
		/* Executa CONTIGuatorD */
		system("export PATH=\$PATH:/opt/ncbi/bin/ && cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f5 && ../../../../../bin/CONTIGuatorD.py -c m5.fasta -g ../*gbk -r ../*fna");
		
		/* Convertendo PDF para PNG */
		$trial_folder = "t$assembly->version_assembly\_assembly";
		echo "Creating PNG image: Wait.<br/>";
		$folder = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation && pwd");
		$pdf_name = system("cd ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f5/Map* && ls *.pdf");
		$folder_map = substr($pdf_name,0,-5);
		$folder_map = str_replace('_', '.', $folder_map);
		$folder_map = 'Map_'.$folder_map.'.';
		$myurl = $folder.'/f5/'.$folder_map.'/'.$pdf_name;
		$image = new Imagick($myurl);
		$image->setResolution(450,450);
		$image->setImageFormat( "png" );
		if($image->writeImage("$folder/f5/align_image.png")) echo 'OK<br/><br/>';
		else echo "Fail";
		
		/* Gerando arquivos temporarios */
		echo "Creating tmp files: Wait.<br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f5/align_image.png tmp/f5_a$id_assembly\_$project->name_project\.png && cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f5/Map*/*.pdf tmp/f5_a$id_assembly\_$project->name_project\.pdf");
		echo "OK<br/>";
		
		/* Copiando fasta para o TMP */
		echo "Copiando fasta para TMP: Wait. <br/>";
		system("cp ../app/assembly/$project->name_project/t$assembly->version_assembly\_assembly/curation/f5/$folder_map/PseudoContig.fsa tmp/f5_a$id_assembly\_$project->name_project\.fasta");
		echo "OK<br/>";
		
		/* Gerar relatorio de gaps */
		echo "Calculando a quantidade de Contigs: Wait. Num_contigs: ";
		$quant_contigs = system("cd tmp && ../../app/bin/mcontig.py f5_a$id_assembly\_$project->name_project\.fasta");
		system("cd tmp && mv m.fasta m5_a$id_assembly\_$project->name_project\.fasta");
		echo "<br/>OK<br/>";
		$quant_gaps = $quant_contigs - 1;
		
		/* Atualizar banco de dados */
		$curation = new Curation;
		$curation->fk_id_assembly = $id_assembly;
		$curation->version_curation = 5;
		$curation->report_curation = '';
		$curation->num_scaffolds = $quant_contigs;
		$curation->len_genome_curation = '';
		$curation->min_contig_curation = '';
		$curation->max_contig_curation = '';
		$curation->n50_curation = '';
		$curation->fk_id_project = $id_project;
		$curation->save();
		
		return Redirect::to("projects/$id_project/assemblies/$id_assembly")->with('project',$project)->with('assembly',$assembly);
		
			
	}
}
