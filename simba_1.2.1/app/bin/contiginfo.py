#!/usr/bin/python
#     Program: contigINFO.py
#    Function: Analisa funcoes de contigs - Le um arquivo multifasta e retorna informacoes sobre ele
# Description: le funcoes basicas do biopython, como ler sequencias
#      Author: Diego Mariano
#     Version: 3

from Bio import SeqIO
import sys

try:
	arquivo = sys.argv[1]
	if arquivo == "-h" or arquivo == '--help':
		print "HELP: Use 'python contiginfo.py [name_file].fasta [-f]' \n[-h] Help \n[-f]: Save results in file'"
		sys.exit()
except:
	print "HELP: Use 'python contiginfo.py [name_file].fasta [-f]' \n[-h] Help \n[-f]: Save results in file'"
	sys.exit()

quant = 0
sumcontig = 0
maxcontig = 0
mincontig = None
nome_organismo = None
todos_tamanhos = list()
sum_tamanhos = 0

for i in SeqIO.parse(arquivo,"fasta"):

	# Tamanho do contig atual
	tam_contig = len(i.seq)
	todos_tamanhos.append(tam_contig)

	# Pegando o menor contig na primeira rodada
	if mincontig is None:
		mincontig = len(i.seq)

	# Descobrindo o maior contig
	if tam_contig > maxcontig:
		maxcontig = tam_contig

	# Descobrindo o menor contig
	if tam_contig < mincontig:
		mincontig = tam_contig

	# Somando todos os elementos
	sumcontig = sumcontig + tam_contig

	# Descobrindo o total de contigs
	quant = quant + 1

	# Nome do organismo
	if nome_organismo is None:
		nome_organismo = i.name

# Calculo de N50
todos_tamanhos.sort()
v50 = sumcontig/2

for tam in todos_tamanhos:
	sum_tamanhos = sum_tamanhos + tam
	if sum_tamanhos > v50:
		n50 = tam
		break

# Agora vamos exibir os resultados
print "\n   File: ",arquivo
print "    Min: ",mincontig
print "    Max: ",maxcontig
print "    N50: ",n50
print "  Bases: ",sumcontig
print "Contigs: ",quant

# Se necessario, salvar resultados em disco
info = "File:\n%s\nMin:\n%d\nMax:\n%d\nN50:\n%d\nBases:\n%d\nContigs:\n%d\n" %(arquivo,mincontig,maxcontig,n50,sumcontig,quant)
try:
	argumento = sys.argv[2]
	print ''
	if argumento == '-f':
		w = open('contiginfo_result.txt','w')
		w.write(info)
		w.close()
		w.closed
except:
	print ''
