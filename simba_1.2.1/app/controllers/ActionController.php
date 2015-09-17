<?php

class ActionController extends BaseController {
	
	# Declare o endereco do diretorio raiz para as montagens
	private $raiz = "../app/assembly/";
	
	# Atualiza lista de projetos 
	public function update_projects_list(){
		
		print "Updating projects list. Wait...";
		$dir = dir($this->raiz);
		$lista = array();
		$erro = 0;
		
		# Lista os arquivos do diretorio
		while ($d = $dir->read())
			if($d != '.' and $d != '..' and is_dir($this->raiz.$d))
				array_push($lista,$d);
		
		# Entra em cada diretorio e detecta os arquivos
		foreach($lista as $l){
			try{
				$info[$l]['BAM'] = 0;
				$info[$l]['SFF'] = 0;
				$info[$l]['FASTQ'] = 0;
				$info[$l]['Assembly'] = 0;
				$diretorio_atual = $this->raiz.$l;
						
				$dir_at = dir($diretorio_atual);
				#leia todos os arquivos do diretorio atual e grava em um array
				while($a = $dir_at->read()){
					if(stristr($a, ".bam") !== FALSE)
						$info[$l]['BAM'] = 1;
					if(stristr($a, ".sff") !== FALSE)
						$info[$l]['SFF'] = 1;
					if(stristr($a, ".fastq") !== FALSE)
						$info[$l]['FASTQ'] = 1;
					if(stristr($a, "t1_") !== FALSE)
						$info[$l]['Assembly'] = 1;
				}
					
				# Valida se o projeto existe no banco de dados
				$project = Project::getProjectName($l);
			
				# Se não existir, faca uma insercao
				if(is_null($project)){
					$projects = new Project;
					$projects->name_project = $l;
					$projects->organism_project = '';
					$projects->ngs_project = '';
					$projects->library_project = '';				
					$projects->bam = $info[$l]['BAM'];
					$projects->sff = $info[$l]['SFF'];
					$projects->fastq = $info[$l]['FASTQ'];
					$projects->assembly = $info[$l]['Assembly'];
					$projects->save();
					$projects = Project::all();
				}
				# Se ja existir, apenas atualize
				else{
					$update = Project::updateProjectArray($project,$info[$l]);
				}
			}
			catch(Exception $e){
				$erro = 1; /* Nao precisa fazer nada, apenas proteja o usuario de erros se nao houver nada */
			}
		}
		
		
		return Redirect::to('projects');
	}

	# Converte arquivo BAM em SFF
	public function bam2sff($name_folder){
		
		print "Converting BAM to SFF in background...";
		
		$folder = $this->raiz.$name_folder;
				
		/* Correcao do bug - execucao duas vezes seguidas */
		shell_exec("cd $folder && chmod 775 *");
		
		/* Executa o sff_extract embutido */
		shell_exec("cd $folder && nohup ../../bin/bam2sff *.bam > log.txt &");
	
		return Redirect::to('projects')->withErrors(array('<b>Warning: </b>Could not update the table at the moment, but <b style="color:#009900">BAM2SFF is running successfully</b>. Calm down, this is a normal operation. Wait ~10 minutes and click <b>"Update"</b>.'));;
	
	}
	
	# Extrai FASTQ do SFF
	public function sff_extract($name_folder){
		
		$folder = $this->raiz.$name_folder;
		
		print 'Running SFF extract in background...';
		
		/* Correcao do bug - execucao duas vezes seguidas */
		shell_exec("cd $folder && chmod 775 *");
		
		/* Executa o sff_extract embutido */
		shell_exec("cd $folder && nohup python ../../bin/sff_extract.py *.sff > log.txt &");
	
		return Redirect::to('projects')->withErrors(array('<b>Warning: </b>Could not update the table at the moment, but <b style="color:#009900">SFF_extract is running successfully</b>. Calm down, this is a normal operation. Wait ~10 minutes and click <b>"Update"</b>.'));;
	
	}
	
	# Roda analise FastQC
	public function fastqc($name_folder){
		
		$project = Project::getProjectName($name_folder);
		
		$folder = $this->raiz.$name_folder;
		
		# Analisa se existe diretorio fastqc
		system('cd tmp && mkdir fastqc');
		$dir_tmp = 'tmp/fastqc';
		$dir = dir($dir_tmp);
		$tmp = 0;
		
		while (($d = $dir->read()) and ($tmp == 0)){
			if($d == $name_folder){
				$tmp = 1;
			}
		}
			
		if($tmp == 0){
			
			print "FastQC running. Wait 3 minutes.";
			
			/* Correcao do bug - execucao duas vezes seguidas */
			shell_exec("cd tmp/fastqc && chmod -R 775 *");
			
			/* Executa o fastqc embutido */
			system("cd ../app/assembly/$name_folder && mv *fastq $name_folder\.fastq");
			shell_exec("cd tmp/fastqc && mkdir $name_folder && nohup ../../../app/bin/FastQC/fastqc ../../../app/assembly/$name_folder/*.fastq -o $name_folder > log.txt &");
			
			return Redirect::to('projects')->withErrors(array('<b>Warning: </b><b style="color:#009900">FastQC is running successfully</b>. Calm down, this is a normal operation. Wait ~5 minutes and click "FastQC Report" again.'));;
		
		}
		
		elseif($tmp == 1){
			return View::make('fastqc')->with('project',$project);	
		}
	}

	# Atualiza lista de Montagens de um Projeto 
	public function update_assemblies_info($id){
		
		print "Updating assemblies list. Wait...";
		$dir = dir($this->raiz);
		$projects = array();
		$versions = array();
		$info = array();
		$erro = 0;
		
		# Lista os projetos
		while ($d = $dir->read())
			if($d != '.' and $d != '..' and is_dir($this->raiz.$d))
				array_push($projects,$d);

			# Entra em cada diretorio e detecta os arquivos
			
			$project_info = Project::getProject($id);
			$project = $project_info->name_project;	
		
			$p = dir($this->raiz.$project);
			
			# Le todos os diretorios de versoes de tentativas de montagens | $t representa trial
			while ($t = $p->read()){
				
				$name_assembler = array();
				
				if($t != '.' and $t != '..'){
					
					$name_assembler = explode('_',$t);
					
					/* Minia parser */
					if($name_assembler[0] == 'minia'){
						$tr = $name_assembler[1];
						/* Cria estrutura que permite a mesma validacao do Mira */
						system("cd $this->raiz/$project && mkdir $tr\_assembly $tr\_assembly/$tr\_d_info $tr\_assembly/$tr\_d_results");
						
						/* Move os arquivos para pasta recem-criada */
						system("cd $this->raiz/$project && mv minia_$tr\_* $tr\_assembly/$tr\_d_results/.");
						system("cd $this->raiz/$project\/$tr\_assembly/$tr\_d_results && mv minia_$tr\_assembly.contigs.fa $tr\_out.unpadded.fasta");
						
						/* Copia info */
						system("cd $this->raiz/$project && echo 'minia log' > $tr\_assembly/$tr\_d_info/$tr\_info_assembly.txt");
						system("cd $this->raiz/$project && cp $tr\.log.txt $tr\_assembly/$tr\_d_info/$tr\_info_assembly.txt");
					}
					
					/* Newbler parser */
					if($name_assembler[0] == 'newbler'){
						$tr = $name_assembler[1];
						/* Cria estrutura que permite a mesma validacao do Mira */
						system("cd $this->raiz/$project && mkdir $tr\_assembly $tr\_assembly/$tr\_d_info $tr\_assembly/$tr\_d_results");
					
						/* Move os arquivos para pasta recem-criada */
						system("cd $this->raiz/$project/newbler_$tr\_assembly && mv 454Scaffolds.fna ../$tr\_assembly/$tr\_d_results/$tr\_out.unpadded.fasta");
						
						/* Copia info */					    
						system("cd $this->raiz/$project/newbler_$tr\_assembly && mv 454NewblerMetrics.txt ../$tr\_assembly/$tr\_d_info/$tr\_info_assembly.txt");
					
					}

					/* SPAdes parser */
					if($name_assembler[0] == 'spades'){
						$tr = $name_assembler[1];
						/* Cria estrutura que permite a mesma validacao do Mira */
						system("cd $this->raiz/$project && mkdir $tr\_assembly $tr\_assembly/$tr\_d_info $tr\_assembly/$tr\_d_results");
					
						/* Move os arquivos para pasta recem-criada */
						system("cd $this->raiz/$project/spades_$tr\_assembly && mv contigs.fasta ../$tr\_assembly/$tr\_d_results/$tr\_out.unpadded.fasta");
						
						/* Copia info */					    
						system("cd $this->raiz/$project/spades_$tr\_assembly && mv spades2.log ../$tr\_assembly/$tr\_d_info/$tr\_info_assembly.txt");
					
					}
					
				}

				/* Grava todas os diretorios de versoes em um array */
				if($t != '.' and $t != '..' and is_dir($this->raiz.$project.'/'.$t))
					array_push($versions,$t);
				
			}
			
			# Para cada versao de testes
			foreach($versions as $version){
				try{
					# Definicao dos elementos da variavel info onde serao guardadas as informacoes
					$info['info'] = '';
					$info['min'] = '';
					$info['max'] = '';
					$info['n50'] = '';
					$info['len_genome'] = '';
					$info['num_contigs'] = '';
					
					/* Executando contiginfo */
					$v = explode("_", $version);
					$query = "cd ../app/assembly/$project/$version && ../../../bin/contiginfo.py ".$v[0]."_d_results/".$v[0]."_out.unpadded.fasta -f";
					shell_exec($query);
					
					# Buscar id da versao
					$vnumber = intval(substr($v[0],1));
					$id_versao = Assembly::getIdAssembly($id,$vnumber);
					
					/* 1. Ler resultado do contiginfo */
					$contig_info = fopen("../app/assembly/$project/$version/contiginfo_result.txt",'r');
					$cont = 0;
					
					# O arquivo contem 11 linhas, logo:
					while ($cont < 12) {
						# A linha atual define o que contem nela - padrao do script contiginfo 	
						switch($cont){
							case 3:$info['min'] = fgets($contig_info,1024);break;
							case 5:$info['max'] = fgets($contig_info,1024);break;
							case 7:$info['n50'] = fgets($contig_info,1024);break;
							case 9:$info['len_genome'] = fgets($contig_info,1024);break;
							case 11:$info['num_contigs'] = fgets($contig_info,1024);break;
							default: $null = fgets($contig_info,1024);break;
						}
						$cont++;
					}
					
					fclose($contig_info);
						
					# 2. Ler arquivo info da montagem
					$file_info = fopen("../app/assembly/$project/$version/".$v[0]."_d_info/".$v[0]."_info_assembly.txt",'r');
					
					while (!feof ($file_info)) {
						$info['info'] .= fgets($file_info,4096);
					}
					
					fclose($file_info);
	
					/* Grava no banco de dados */
					@$update = Assembly::updateAssemblyArray($id_versao->id_assembly,$info);
					$info = array();
				}
				catch(Exception $e){
					$erro = 1; /* Nao precisa fazer nada, apenas proteja o usuario de erros se nao houver nada */
				}
			}
			
			$versions = array();
			
			/* Falta validar montagens feitas no MINIA e no NEWBLER */
			
		return Redirect::to("projects/$id");
	}
	
}
