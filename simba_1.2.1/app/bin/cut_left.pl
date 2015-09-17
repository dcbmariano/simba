#!/bin/perl

# Script para cortar sequencias left: editado para gerar .fq
# Diego Mariano
# 2014

if(($ARGV[0] eq "-h") or ($ARGV[0] eq "--help")){
        print "Syntax 'perl cut_left.pl len_cut file sample\n'";
}

$len_corte = int($ARGV[0]);
$seq = $ARGV[1];
$perc_sample = int($ARGV[2]);
$printing = TRUE;
$app_sample = ($perc_sample > 0 && $perc_sample < 100);

if ($app_sample) {
	$test = int(rand(101));
	$printing = ($test < $perc_sample);
}


open(FILE,$seq);
open(OUT,'>out_trim.fq');

$i = 0;
while($line = <FILE>){
	$i = $i + 1;
	if($i%2 == 0){
		$line = substr $line,$len_corte;
	}
	if ($printing) {
		print OUT $line;
	}
	if ($i % 4 == 0) {
		if ($app_sample) {
			$test =  int(rand(101));
			$printing = ($test < $perc_sample);
		}
	}
}

close(FILE);
close(OUT);
