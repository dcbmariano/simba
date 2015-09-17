#! /usr/bin/python
#     Program: mapRepeat.py
#    Function: Fechamento de gaps de regioes repetitivas
# Description: Utiliza 5 scripts diferentes. Recebe dados de sequenciamento, arquivo de contigs e genoma referencia  // cut modificado para 3000
#      Author: Diego Mariano
#     Version: 3

# WARNING: MODIFICADO PARA SER EXECUTADO NO SIMBA

from Bio import SeqIO
import sys
import os
 
# Helper
try:
	if(sys.argv[1] == '--help' or sys.argv[1] == '-h'):
		print "Syntax 'python mapRepeat.py [contigs aligned file] [reference file] [fastq xml folder] [contig left name] [contig right name]'"
		sys.exit()
except:
	print "Syntax error. \nSyntax 'python mapRepeat.py [contigs aligned file] [reference file] [fastq xml folder] [contig left name] [contig right name]'"
	sys.exit()

# Recebe as variaveis da chamada
contigs = sys.argv[1]
reference = sys.argv[2]
fastq = sys.argv[3]
left = sys.argv[4]
right = sys.argv[5]

print "\n------------------------- Running mapRepeat -------------------------"

# -------------------------------------------------------------------------------------------------------------------------------
# 
# selecionaExtremidades.py
# Descricao: seleciona 5000pb ao redor do gap
# Original Syntax: 'python selecionaExtremidades.py [file_name] [name_contig_left] [name_contig_right]'
#
# -------------------------------------------------------------------------------------------------------------------------------

# Extrai as sequencias proximas ao gap
for i in SeqIO.parse(contigs,"fasta"):
	print i.id
	left = left.replace("\r","")
	right = right.replace("\r","")
	if(i.id == left):
		seq_left = str(i.seq)
	if(i.id == right):
		seq_right = str(i.seq)

# Reduz tamanho da sequencia - max. 5000
tam_seq_left = len(seq_left)
tam_seq_right = len(seq_right)

if(tam_seq_left > 3000):
	seq_left = seq_left[-3000:]

if(tam_seq_right > 3000):
	seq_right = seq_right[:3000]

# Grava sequencia esquerda
l = open('tmp_seq_left.txt','w')
l.write(seq_left)
l.close()
l.closed

# Grava sequencia direita
r = open('tmp_seq_right.txt','w')
r.write(seq_right)
r.close()
r.closed

# IMPORTANTE: 5000pb antes do gap armazenados na variavel "seq_left" e no arquivo "tmp_seq_left.txt"; 5000pb depois em "seq_right" - "tmp_seq_right.txt"
print "\nStep 1/5: select edges of the gap. \nSuccess."


# -------------------------------------------------------------------------------------------------------------------------------
# 
# blastExtremidades.py
# Descricao: efetua blast das regioes ao redor do gap a procura de uma regiao proxima na referencia
# Original Syntax 'python blastExtremidades.py [reference_file_name] [OPTIONAL name_file_left] [OPTIONAL name_file_right]'
#
# -------------------------------------------------------------------------------------------------------------------------------

# Efetua consultas blast e retorna os as posicoes
query_left = "blastn -subject %s -query tmp_seq_left.txt -outfmt '6 sstart' > tmp_left_position_reference_start.txt" %(reference)
query_right = "blastn -subject %s -query tmp_seq_right.txt -outfmt '6 send' > tmp_right_position_reference_end.txt" %(reference)
cut_left = os.system(query_left)
cut_right = os.system(query_right)

# Le o arquivo e pega apenas o melhor resultado 
s = open('tmp_left_position_reference_start.txt','r')
e = open('tmp_right_position_reference_end.txt','r')
begin_cut_reference = int(s.readline())
end_cut_reference = int(e.readline())
s.closed
e.closed

# Pequena correcao na posicao de inicio
begin_cut_reference = begin_cut_reference-1

# IMPORTANTE: pontos de corte armazenados em begin_cut_reference e end_cut_reference
print "\nStep 2/5: returns the position of the beginning of 5000pb before of gap and the position of the end of 3000pb after of gap in a reference genome. \nSuccess."


# -------------------------------------------------------------------------------------------------------------------------------
# 
# cortaSeq.py
# Descricao: corta regiao delimitada na referencia
# Original Syntax 'python cortaSeq.py reference.fasta [OPTIONAL int begin] [OPTIONAL int end]'
#
# -------------------------------------------------------------------------------------------------------------------------------

# Le genoma refecencia usando biopython
for i in SeqIO.parse(reference,"fasta"):
	seq_reference = str(i.seq)

# Faz os cortes no genoma e grava a sequencia extraida num arquivo fasta
tmp_cut_seq = "tmp_cut_reference_seq.fasta"
w = open (tmp_cut_seq,'w')
new_seq = seq_reference.rstrip()[begin_cut_reference:end_cut_reference]
len_ref = len(new_seq)
w.write(">seq_cut_genome_reference\n")
w.write(new_seq)
w.close()
w.closed

# Grava um arquivo .qual (exigencia do mira) - vamos definir 50 como valor phred 
saida_qual = "tmp_cut_reference_seq.fasta.qual"
q = open (saida_qual,'w')
q.write(">qual_seq_cut_genome_reference\n")
qual = ""
for i in range(0,len_ref):
	qual = "%s50 " %qual
q.write(qual)
q.close()
q.closed

# IMPORTANTE: sequencia extraida esta no arquivo "tmp_cut_reference_seq.fasta" - variavel: tmp_cut_seq. Existe um arquivo .qual com mesmo nome do .fasta na pasta
print "\nStep 3/5: cuts the marked region in the reference genome. \nSuccess."


# -------------------------------------------------------------------------------------------------------------------------------
# 
# mapMira.py
# Descricao: usa o Mira para mapear os dados brutos no trecho cortado
# Original Syntax 'python mapMira.py [seq] [OPTIONAL name] [OPTIONAL raw_fastq] [OPTIONAL seq_qual] [OPTIONAL raw_xml]'
#
# -------------------------------------------------------------------------------------------------------------------------------

# Modelo manifest OPTIONAL \nparameters = -AS:urd=yes -OUT:ora=on => formato ace
manifest = "project = tmp_map \njob = genome,mapping,accurate \nparameters = -OUT:rtd=on:ort=on:orh=on \n\nreadgroup \nis_reference \ndata = %s\n\nreadgroup = fragment \ntechnology = iontor \ndata = %s*.fastq %s*.xml" %(tmp_cut_seq,fastq,fastq)

print "\nManifest file\n"
print manifest

m = open('tmp_map.manifest','w')
m.write(manifest)
m.close()
m.closed

# Modificado para ser executado da pasta F4
#command = "mira tmp_map.manifest"
command = "../../../../../bin/mira tmp_map.manifest" 

os.system(command)

# IMPORTANTE: Eh importante analisar qual versao do Mira esta sendo utilizada
print "\nStep 4/5: mapping with Mira. \nSuccess."


# -------------------------------------------------------------------------------------------------------------------------------
# 
# fechaGap.py
# Descricao: transfere a consenso do mapeamento para fechar o gap
# Original Syntax 'python fechaGap.py [seq] [contig left name] [contig right name] [OPTIONAL result mira]'
#
# -------------------------------------------------------------------------------------------------------------------------------

# Recebe resultado mira
result_mira = "tmp_map_assembly/tmp_map_d_results/tmp_map_out_ReferenceStrain.unpadded.fasta"

# remove as sequencias repetidas do resultado do mira
for i in SeqIO.parse(result_mira,"fasta"):
	seq_mira = str(i.seq)
query_left = "blastn -subject %s -query tmp_seq_left.txt  -outfmt '6 send' > tmp_left_position_map_result_end.txt" %(result_mira)
query_right = "blastn -subject %s -query tmp_seq_right.txt  -outfmt '6 sstart' > tmp_right_position_map_result_start.txt" %(result_mira)
os.system(query_left)
os.system(query_right)
tmpA = open('tmp_left_position_map_result_end.txt','r')
tmpB = open('tmp_right_position_map_result_start.txt','r')

'''
# Tentando corrigir uma falha do blast (apresenta 2 resultados quando deveria apresentar so 1) 
# Right
cr = tmpB.readlines()
# Forcando cut_right a ter o maior valor possivel
cut_right = 0
for i in cr:
	cut_right = cut_right+int(i)
# Agora definindo que cutright seja o elemento de menor valor do vetor
for i in cr:
	if(int(i)<cut_right):
		cut_right = int(i)
# Left
cl = tmpA.readlines()
cut_left = 0
for i in cl:
	if(int(i) > cut_left):
		cut_left = int(i) 
cut_left = cut_left+1
'''
cut_left = int(tmpA.readline())
cut_right = int(tmpB.readline())

tmpA.close()
tmpA.closed
tmpB.close()
tmpB.closed

seq_mira = seq_mira[cut_left:cut_right]
print "cut_left: %d | Cut right: %d " %(cut_left,cut_right)
print "Sequence inserted: "+seq_mira

# REMOVE O GAP
sf = open(contigs,'r')
genome = sf.read()
remove_contig_id = "\n>%s\n" %(right)
gap_closed = genome.replace(remove_contig_id,seq_mira)

sf.close()
sf.closed

g = open('m4_PART.fasta','w')
g.write(gap_closed)
g.close()
g.closed

# IMPORTANTE: essa etapa sera corretamente ajustada quando for feito outro alinhamento contra uma referencia
print "\nStep 5/5: transfers the consensus of mapping to close the gap. \nSuccess."
print "\nResult: m4_PART.fasta\n"
