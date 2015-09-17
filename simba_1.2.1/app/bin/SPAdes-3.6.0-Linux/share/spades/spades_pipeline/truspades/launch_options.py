############################################################################
# Copyright (c) 2015 Saint Petersburg State University
# Copyright (c) 2011-2014 Saint Petersburg Academic University
# All Rights Reserved
# See file LICENSE for details.
############################################################################

__author__ = 'anton'
import getopt
import os
import sys

class Options:
    def set_default_options(self):
        self.threads = 8
        self.dataset_file = None
        self.input_dirs = None
        self.print_dataset = False
        self.print_commands = False
        self.output_dir = None
        self.command_list = None
        self.spades_options = ""
        self.continue_launch = False
        self.index = ""
        self.reference = ""
        self.mode = "run_truspades"
        self.possible_modes = ["run_truspades", "generate_dataset", "construct_subreferences"]

    def __init__(self, argv):
        if len(argv) == 1:
            print_usage_and_exit(1)
        long_params = "help-hidden construct-dataset reference= reference-index= do= continue threads= help dataset= input-dir= additional-options".split(" ")
        short_params = "o:t:h"
        self.set_default_options()
        try:
            options_list, self.spades_options = getopt.gnu_getopt(argv[1:], short_params, long_params)
        except getopt.GetoptError:
            _, exc, _ = sys.exc_info()
            sys.stderr.write(str(exc) + "\n")
            print_usage_and_exit(1)
        for (key, value) in options_list:
            if key == "--help" or key == "-h":
                print_usage_and_exit(1)
            elif key == "--do":
                self.mode = value
            elif key == "--construct-dataset":
                self.mode = "generate_dataset"
            elif key == "--dataset":
                self.dataset_file = value
            elif key == "--input-dir":
                if self.input_dirs is None:
                    self.input_dirs = []
                self.input_dirs.append(value)
            elif key == "--run-truspades":
                self.mode = "run_truspades"
            elif key == "--reference-index":
                self.index = value
            elif key == "--reference":
                self.reference = value
            elif key == "--continue":
                self.continue_launch = True
            elif key == "--additional-options":
                self.spades_options = value
            elif key == "-o":
                self.output_dir = value
            elif key == "--threads" or key == "-t":
                self.threads = int(value)
            elif key == "--help-hidden":
                print_usage_and_exit(0, True)
        if not self.mode in self.possible_modes:
            sys.stderr.write("Error: --do parameter can only have one of the following values: " + ", ".join(self.possible_modes) + "\n")
            print_usage_and_exit(1)
        if None == self.output_dir or os.path.isfile(self.output_dir):
            sys.stderr.write("Error: Please provide output directory\n")
            print_usage_and_exit(1)
        if self.continue_launch:
            return
        cnt = len([option for option in [self.dataset_file, self.input_dirs, self.command_list] if option != None])
        if cnt != 1:
            sys.stderr.write("Error: exactly one of dataset-file and input-dir must be specified\n")
            print_usage_and_exit(1)
        if self.mode == "construct_subreferences":
            if self.index == "":
                sys.stderr.write("Error: Please provide reference index for BWA")
                print_usage_and_exit(1)
            if self.reference == "":
                sys.stderr.write("Error: Please provide reference for subreference construction")
                print_usage_and_exit(1)


def print_usage_and_exit(code, show_hidden = False):
    sys.stderr.write("Usage: " + str(sys.argv[0]) + " [options] -o <output_dir>" + "\n")
    sys.stderr.write("" + "\n")
    sys.stderr.write("Basic options:" + "\n")
    sys.stderr.write("-h/--help\t\tprints this usage message" + "\n")
    sys.stderr.write("-o\t<output_dir>\tdirectory to store all the resulting files (required)" + "\n")
    sys.stderr.write("-t/--threads\t<int>\t\tnumber of threads" + "\n")
    sys.stderr.write("--continue\t\tcontinue interrupted launch" + "\n")
    sys.stderr.write("--construct-dataset\t\tparse dataset from input folder")
    sys.stderr.write("" + "\n")
    sys.stderr.write("Input options:" + "\n")
    sys.stderr.write("--input-dir\t<directory>\tdirectory with input data. Note that the directory should contain only files with reads. This option can be used several times to provide several input directories." + "\n")
    sys.stderr.write("--dataset\t<file>\tfile with dataset description" + "\n")
    if show_hidden:
        pass
        #ToDo
    # sys.stderr.write("" + "\n")
    # sys.stderr.write("Output options:" + "\n")
    # sys.stderr.write("--print-dataset\tprints file with dataset generated after analysis of input directory contents" + "\n")
    # sys.stderr.write("--print-commands\tprints file with truspades commands that would assemble barcodes from dataset" + "\n")
    # sys.stderr.write("--run-truspades\truns truSPAdes on all barcodes" + "\n")
    sys.stderr.flush()
    sys.exit(code)
