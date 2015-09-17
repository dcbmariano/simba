############################################################################
# Copyright (c) 2015 Saint Petersburg State University
# Copyright (c) 2011-2015 Saint Petersburg Academic University
# All Rights Reserved
# See file LICENSE for details.
############################################################################

import os
import itertools
import fastaparser
from libs import reporting, qconfig, qutils

from libs.log import get_logger
logger = get_logger(qconfig.LOGGER_DEFAULT_NAME)


######## MAIN ############
def do(ref_fpath, aligned_contigs_fpaths, output_dirpath, json_output_dirpath,
       aligned_lengths_lists, aligned_stats_dirpath):

    if not os.path.isdir(aligned_stats_dirpath):
        os.mkdir(aligned_stats_dirpath)

    ########################################################################
    report_dict = {'header': []}
    for contigs_fpath in aligned_contigs_fpaths:
        report_dict[qutils.name_from_fpath(contigs_fpath)] = []

    ########################################################################
    logger.print_timestamp()
    logger.info('Running NA-NGA calculation...')

    reference_length = sum(fastaparser.get_lengths_from_fastafile(ref_fpath))
    assembly_lengths = []
    for contigs_fpath in aligned_contigs_fpaths:
        assembly_lengths.append(sum(fastaparser.get_lengths_from_fastafile(contigs_fpath)))

    import N50
    for i, (contigs_fpath, lens, assembly_len) in enumerate(
            itertools.izip(aligned_contigs_fpaths, aligned_lengths_lists, assembly_lengths)):
        na50 = N50.NG50(lens, assembly_len)
        nga50 = N50.NG50(lens, reference_length)
        na75 = N50.NG50(lens, assembly_len, 75)
        nga75 = N50.NG50(lens, reference_length, 75)
        la50 = N50.LG50(lens, assembly_len)
        lga50 = N50.LG50(lens, reference_length)
        la75 = N50.LG50(lens, assembly_len, 75)
        lga75 = N50.LG50(lens, reference_length, 75)
        logger.info('  ' +
                    qutils.index_to_str(i) +
                    qutils.label_from_fpath(contigs_fpath) +
                 ', Largest alignment = ' + str(max(lens)) +
                 ', NA50 = ' + str(na50) +
                 ', NGA50 = ' + str(nga50) +
                 ', LA50 = ' + str(la50) +
                 ', LGA50 = ' + str(lga50))
        report = reporting.get(contigs_fpath)
        report.add_field(reporting.Fields.LARGALIGN, max(lens))
        report.add_field(reporting.Fields.NA50, na50)
        report.add_field(reporting.Fields.NGA50, nga50)
        report.add_field(reporting.Fields.NA75, na75)
        report.add_field(reporting.Fields.NGA75, nga75)
        report.add_field(reporting.Fields.LA50, la50)
        report.add_field(reporting.Fields.LGA50, lga50)
        report.add_field(reporting.Fields.LA75, la75)
        report.add_field(reporting.Fields.LGA75, lga75)

    ########################################################################
    num_contigs = max([len(aligned_lengths_lists[i]) for i in range(len(aligned_lengths_lists))])

    if json_output_dirpath:
        from libs.html_saver import json_saver
        json_saver.save_assembly_lengths(json_output_dirpath, aligned_contigs_fpaths, assembly_lengths)

    # saving to html
    if qconfig.html_report:
        from libs.html_saver import html_saver
        html_saver.save_assembly_lengths(output_dirpath, aligned_contigs_fpaths, assembly_lengths)

    if qconfig.draw_plots:
        # Drawing cumulative plot (aligned contigs)...
        import plotter
        plotter.cumulative_plot(ref_fpath, aligned_contigs_fpaths, aligned_lengths_lists,
                                os.path.join(aligned_stats_dirpath, 'cumulative_plot'),
                                'Cumulative length (aligned contigs)')

        # Drawing NAx and NGAx plots...
        plotter.Nx_plot(output_dirpath, num_contigs > qconfig.max_points, aligned_contigs_fpaths, aligned_lengths_lists, aligned_stats_dirpath + '/NAx_plot', 'NAx', assembly_lengths)
        plotter.Nx_plot(output_dirpath, num_contigs > qconfig.max_points, aligned_contigs_fpaths, aligned_lengths_lists, aligned_stats_dirpath + '/NGAx_plot', 'NGAx', [reference_length for i in range(len(aligned_contigs_fpaths))])

    logger.info('Done.')
    return report_dict
