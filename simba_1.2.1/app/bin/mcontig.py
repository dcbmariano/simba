#! /usr/bin/python
#     Program: mContig.py
#    Function: Corta scaffold em um arquivo mulfifasta
# Description: 
#      Author: Diego Mariano
#     Version: 1

from Bio import SeqIO
import sys

# Helper
try:
	p1 = sys.argv[1]
except:
	print "HELP: Use 'python mcontig.py [name_file].fasta'"
	sys.exit()

if p1 == "-h" or p1 == '--help':
	print "HELP: Use 'python mcontig.py [name_file].fasta'"
	sys.exit()


# Lendo sequencia com Biopython
for i in SeqIO.parse(p1,"fasta"):
	seq_final = str(i.seq)
	tam_seq = len(i.seq)

# Declaracoes iniciais
seq_final += "N"
tam_seq += 1
tamGap = 0
tmpContig = []
cont = 1
w = open('m.fasta','w')
i = 0

# Le nucleotideo por nucleotideo
while i < tam_seq:
	if seq_final[i] == 'n' or seq_final[i] == 'N':
		if tamGap == 0:
			gapStart = i
			tmpContigStr = ''.join(tmpContig)

			# Grava no arquivo
			if cont == 1:
				contig_atual = ">contig_%d\n" %(cont)
			if cont > 1:				
				contig_atual = "\n>contig_%d\n" %(cont)
			cont = cont + 1
			w.write(contig_atual)
			w.write(tmpContigStr)

			tmpContig = []
			tmpContigStr = ''

		tamGap = tamGap + 1
	else:
		tmpContig.append(seq_final[i])

		if tamGap > 0:
			gapEnd = i-1
			tamGap = 0

	i = i + 1
print cont-1
w.close()
w.closed
