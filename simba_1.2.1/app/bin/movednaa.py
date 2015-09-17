#! /usr/bin/python
#     Program: moveDNAA.py
#    Function: Recebe um arquivo fasta (scaffold) e uma referencia. Busca o gene dnaA e o move para o comeco
# Description: 
#      Author: Diego Mariano
#     Version: 2

from Bio import SeqIO
import sys
import os

if(sys.argv[1] == '--help' or sys.argv[1] == '-h'):
	print "Syntax 'python movednaa.py [seq] [reference fasta file]'"
	sys.exit()

print "---------------------------- moveDNAA ----------------------------"
# Recebe sequencia e referencia
sequence = sys.argv[1]
reference = sys.argv[2]

# extrai a sequencia
for i in SeqIO.parse(reference,"fasta"):
	seq_ref = str(i.seq)

# Cortamos os 1000 primeiros nucleotideos
query = seq_ref.rstrip()[0:1000]

# Gravamos a query em um arquivo para poder usar no blast
q = open('tmp_query.txt','w')
q.write(query)
q.close()
q.closed

# BLAST para determinar posicao de corte
print "Searching genus dnaA in reference... OK"
query_blast = "blastn -subject %s -query tmp_query.txt -outfmt '6 sstart' > tmp_cut_dnaa.txt" %(sequence)
os.system(query_blast)
tmp_cut_dnaa = open('tmp_cut_dnaa.txt','r')
cut = int(tmp_cut_dnaa.readline())
tmp_cut_dnaa.close()
tmp_cut_dnaa.closed

for i in SeqIO.parse(sequence,"fasta"):
	seq = str(i.seq)

print "Searching dnaA in your data... OK"
dnaa_before = seq.rstrip()[:cut]
cut = cut - 1 #Correcao do erro: faltava 1 nucleotideo
dnaa_after = seq.rstrip()[cut:]
last_gap = "NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN"

print "Moving the dnaA to the beginning of the genome... OK"
genome = dnaa_after + last_gap + dnaa_before

print "Saving data... OK"
s = open("f2.fasta","w")
s.write(">f2.fasta\n")
s.write(genome)
s.close()
s.closed

print "Resulting file: f2.fasta"

print "Success."
