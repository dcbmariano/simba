function fillOneRow(metric, mainMetrics, group_n, order, glossary, is_primary, rowName,
                    report_n, assembliesNames, notAlignedContigs, notExtendedMetrics, isEmptyRows) {
    (function(group_n) {
        var id_group = '#group_' + group_n;
        $(function() {
            $(id_group).removeClass('group_empty');
        });
    })(group_n);

    var table = '';
    var metricName = metric.metricName;
    var quality = metric.quality;
    var values = metric.values;

    var trClass = 'content-row';
    if (metric.isMain || $.inArray(metricName, mainMetrics) > -1) {
        var numPlot = $.inArray(metricName, mainMetrics);
        var iconPlots = '<img id="' + numPlot + '" style="vertical-align: bottom" src="report_html_aux/img/icon_plot.png" onclick="setPlot($(this))"/>';
        (function(group_n) {
            var id_group = '#group_' + group_n;
            $(function() {
                $(id_group).removeClass('row_hidden');
            });
        })(group_n);
    } else {
        trClass = 'content-row row_hidden row_to_hide';
    }
    var tdClass = '';
    if (!is_primary) {
        trClass += ' secondary_hidden';
        tdClass = 'secondary_td';
    }
    else {
        trClass += ' primary';
    }

    var not_extend = false;
    if ($.inArray(metricName, notExtendedMetrics) > -1 || isEmptyRows == true){
        not_extend = true;
        trClass += ' not_extend';
    }

    table +=
        '<tr class="' + trClass + '" quality="' + quality + '" onclick="toggleSecondary($(this))">' +
        '<td class="left_column_td ' + tdClass + '">' +
        '<span class="metric-name' +
          (is_primary ? ' primary' : ' secondary') + (not_extend || !is_primary ? '' : ' expandable collapsed') + '">' +
           initial_spaces_to_nbsp(addTooltipIfDefinitionExists(glossary, rowName.trunc(55)), metricName) +
        (metric.isMain && is_primary ? ("&nbsp" + iconPlots) : '') +
        '</span></td>';

    if (report_n > -1) {
        for (var not_aligned_n = 0; not_aligned_n < notAlignedContigs[report_n].length; not_aligned_n++) {
            values.splice(assembliesNames.indexOf(notAlignedContigs[report_n][not_aligned_n]), 0, '');
        }
    }
    var icon_misassemblies = '';
    for (var val_n = 0; val_n < values.length; val_n++) {
        var value = values[order[val_n]];
        var plotSrc = assembliesNames[order[val_n]] + "_misassemblies.jpg";
        icon_misassemblies = '<img id="' + plotSrc + '" src="report_html_aux/img/icon_plot.png" onclick="setPlot($(this))"/>';

        if (value === null || value === '') {
            table += '<td><span>-</span></td>';
        } else {
            if (typeof value === 'number') {
                table +=
                    '<td number="' + value + '"><span>'
                        + toPrettyString(value) + '</span></td>';
            } else {
                var result = /([0-9\.]+)(.*)/.exec(value);
                var num = parseFloat(result[1]);
                var rest = result[2];

                if (num !== null) {
                    table += '<td number="' + num + '"><span>' + toPrettyString(num) + rest + '</span></td>';
                } else {
                    table += '<td><span>' + value + '</span></td>';
                }
            }
        }
    }

    return table;
}


function buildGenomeTable(reports, group_n, numColumns) {
    var tableGenome = '';
    tableGenome += '<div class="report" id="ref_report">';
    tableGenome += '<table cellspacing="0" id="refgenome">';
    tableGenome += '<tr class="top_row_tr"><td class="left_column_td"><span>' + 'Reference' + '</span></td>';
    var colNames = ['Size, bp', 'GC, %', 'Genes', 'Operons'];
    for (var col_n = 0; col_n < numColumns; col_n++) {
        var columnName = colNames[col_n];
        tableGenome += '<td class="second_through_last_col_headers_td">' +
            '<span class="assembly_name">' + columnName + '</span>' +
        '</td>';
    }
    for (var report_n = 0; report_n < reports.length; report_n++ ) {
        var trClass = 'content-row';
        var refName = reports[report_n].name;
        if (refName == 'not_aligned') continue;
        tableGenome +=
            '<tr class="' + trClass + '">' +
            '<td class="left_column_td">' +
                '<span class="metric-name">' +
                    '<a href="../' + refName + '_quast_output/report.html">' + refName + '</a>' +
                '</span>' +
            '</td>';
        var metrics = reports[report_n].report[group_n][1];
        for (var metric_n = 0; metric_n < metrics.length; metric_n++) {
            var metric = metrics[metric_n];
            if (metric.metricName == 'Reference name') continue;

            var value = metric.values[0];

            if (value === null || value === '') {
                tableGenome += '<td><span>-</span></td>';
            } else {
                if (typeof value === 'number') {
                    tableGenome +=
                        '<td number="' + value + '"><span>'
                        + toPrettyString(value) + '</span></td>';
                } else {
                    var result = /([0-9\.]+)(.*)/.exec(value);
                    var num = parseFloat(result[1]);
                    var rest = result[2];
//                        alert('value = ' + value + ' result = ' + result);

//                        var num = parseFloat(value);

                    if (num !== null) {
                        tableGenome += '<td number="' + num + '"><span>' + toPrettyString(num) + rest + '</span></td>';
                    } else {
                        tableGenome += '<td><span>' + value + '</span></td>';
                    }
                }
            }
        }
    }

    tableGenome += '</table>';

    tableGenome += '</div>';
    return tableGenome;
}


function buildTotalReport(assembliesNames, report, order, date, minContig, glossary,
                          qualities, mainMetrics, reports) {
    $('#report_date').html('<p>' + date + '</p>');
    $('#mincontig').html('<p>All statistics are based on contigs of size &ge; ' + minContig +
        '<span class="rhs">&nbsp;</span>bp, unless otherwise noted (e.g., "# contigs (>= 0 bp)" and "Total length (>= 0 bp)" include all contigs.)</p>');
    $('#per_ref_msg').html('<p>Rows show values for the whole assembly (column name) vs. combined reference (concatenation of input references).<br>' +
        'Clicking on a row with <span style="color: #CCC">+</span> sign will expand values for contigs aligned to each of input references separately.<br>' +
        'Note that some metrics (e.g. # contigs) may not sum up, because one contig may be aligned to several references and thus, counted several times.</p>');
    $('#quast_name').html('MetaQUAST');
    $('#report_name').html('summary report');
    if (kronaPaths = readJson('krona')) {
        if (kronaPaths.paths != undefined) {
            $('#krona').html('Krona charts: ');
            for (var assembly_n = 0; assembly_n < assembliesNames.length; assembly_n++ ) {
                var assemblyName = assembliesNames[assembly_n];
                $('#krona').append(
                    '&nbsp&nbsp<span class="metric-name">' +
                    '<a href="' + kronaPaths.paths[assembly_n] + '">' + assemblyName + '</a>' +
                    '</span>&nbsp&nbsp');
            }
            if (assembliesNames.length > 1)  $('#krona').append(
                    '&nbsp&nbsp&nbsp&nbsp<span class="metric-name">' +
                    '<a href="Krona/summary_taxonomy_chart.html">Summary</a>' +
                    '</span>&nbsp');
        }
    }

    var table = '';
    table += '<table cellspacing="0" class="report_table draggable" id="main_report_table">';
    var refNames = [];
    for (var report_n = 0; report_n < reports.length; report_n++) {
        var refName = reports[report_n].referenceName;
        refNames.push(refName);
    }
    reports = refNames.map(function (name, report_n) {
    return {
        name: name,
        report: this[report_n].report,
        asmNames: this[report_n].assembliesNames
        };
    }, reports);
    notAlignedContigs = {};
    for(report_n = 0; report_n < reports.length; report_n++ ) {
        notAlignedContigs[report_n] = [];
        for (var assembly_n = 0; assembly_n < assembliesNames.length; assembly_n++) {
            var assemblyName = assembliesNames[assembly_n];
            if (reports[report_n].asmNames.indexOf(assemblyName) == -1) {
                notAlignedContigs[report_n].push(assemblyName);
            }
        }
    }
    var notExtendedMetrics = ['    # interspecies translocations'];
    if (minContig > 0)
        notExtendedMetrics = ['    # interspecies translocations', '# contigs (&gt;= 0 bp)', 'Total length (&gt;= 0 bp)',
            'Fully unaligned length', '# fully unaligned contigs'];
    for (var group_n = 0; group_n < report.length; group_n++) {
        var group = report[group_n];
        var groupName = group[0];
        var metrics = group[1];

        var width = assembliesNames.length + 1;

        if (groupName == 'Reference statistics') {
            var referenceValues = {};
            for (var metric_n = 0; metric_n < metrics.length; metric_n++) {
                var metric = metrics[metric_n];
                var metricName = metric.metricName;
                var value = metric.values[0];
                referenceValues[metricName] = value;
            }
            var refName = referenceValues['Reference name'];
            var refLen = referenceValues['Reference length'];
            var refGC = referenceValues['Reference GC (%)'];
            var refGenes = referenceValues['Reference genes'];
            var refOperons = referenceValues['Reference operons'];

            var numColumns = 0;

            if (refName) {
                $('#reference_name').find('.val').html(refName);
            }
            $('#reference_name').show();

            if (refLen) {
                $('#reference_length').show().find('.val').html(toPrettyString(refLen));
                numColumns++;
            }
            if (refGC) {
                $('#reference_gc').show().find('.val').html(toPrettyString(refGC));
                numColumns++;
            }
            if (refGenes) {
                $('#reference_genes').show().find('.val').html(toPrettyString(refGenes));
                numColumns++;
            }
            if (refOperons) {
                $('#reference_operons').show().find('.val').html(toPrettyString(refOperons));
                numColumns++;
            }

            $('#main_ref_genome').html(buildGenomeTable(reports, group_n, numColumns))
            continue;
        }

        if (group_n == 0) {
            table += '<tr class="top_row_tr"><td id="top_left_td" class="left_column_td"><span>' + groupName + '</span></td>';

            for (var assembly_n = 0; assembly_n < assembliesNames.length; assembly_n++) {
                var assemblyName = assembliesNames[order[assembly_n]];
                if (assemblyName.length > 30) {
                    assemblyName =
                        '<span class="tooltip-link" rel="tooltip" title="' + assemblyName + '">' +
                            assemblyName.trunc(30) +
                            '</span>'
                }

                table += '<td class="second_through_last_col_headers_td" position="' + order[assembly_n] + '">' +
                    '<span class="drag_handle"><span class="drag_image"></span></span>' +
                    '<span class="assembly_name">' + assemblyName + '</span>' +
                    '</td>';
            }

        } else {
            table +=
                '<tr class="group_header row_hidden group_empty" id="group_' + group_n + '">' +
                    '<td class="left_column_td"><span>' + groupName + '</span></td>'; //colspan="' + width + '"
            for (var i = 1; i < width; i++) {
                table += '<td></td>';
            }
            table += '</tr>';
        }
        for (metric_n = 0; metric_n < metrics.length; metric_n++) {
            var metric = metrics[metric_n];
            var isEmptyRows = true;
            for(report_n = 0; report_n < reports.length; report_n++ ) {  //  add information for each reference
                var metrics_ref = reports[report_n].report[group_n][1];
                for (var metric_ext_n = 0; metric_ext_n < metrics_ref.length; metric_ext_n++){
                    if (metrics_ref[metric_ext_n].metricName == metrics[metric_n].metricName) {
                        isEmptyRows = false;
                        break;
                    }
                }
            }
            table += fillOneRow(metric, mainMetrics, group_n, order, glossary, true, metric.metricName, -1, assembliesNames, notAlignedContigs, notExtendedMetrics, isEmptyRows);
            for(report_n = 0; report_n < reports.length; report_n++ ) {  //  add information for each reference
                var metrics_ref = reports[report_n].report[group_n][1];
                for (var metric_ext_n = 0; metric_ext_n < metrics_ref.length; metric_ext_n++){
                    if (metrics_ref[metric_ext_n].metricName == metrics[metric_n].metricName) {
                        table += fillOneRow(metrics_ref[metric_ext_n], mainMetrics, group_n, order, glossary, false, reports[report_n].name, report_n, assembliesNames, notAlignedContigs, notExtendedMetrics);
                        break;
                    }
                }
            }
        }
        table += '</tr>';
    }
    table += '</table>';

    //table += '<p id="extended_link"><a class="dotted-link" id="extended_report_link" onclick="extendedLinkClick($(this))">Extended report</a></p>';
    table += buildExtendedLinkClick();

    setUpHeatMap(table);
}


function toggleSecondary(caller) {
    var event = window.event;
    if(event.target.nodeName == "IMG") return;
    if (!caller.hasClass('primary') || caller.hasClass('not_extend')) {
        return;
    }
    var nextRow = caller.next('.content-row');
    $(caller).find('.metric-name').toggleClass('collapsed').toggleClass('expanded');

    while (!nextRow.hasClass('primary') && (nextRow.length > 0)) {
        nextRow.toggleClass('secondary_hidden');
        nextRow.find('.left_column_td').css('background-color', '#E8E8E8');
        nextRow = nextRow.next('.content-row');
    }
}

function setPlot(icon) {
    num = icon.attr('id');
    names = ['contigs', 'largest', 'totallen', 'n50', 'misassemblies', 'misassembled', 'mismatches', 'indels',
            'ns', 'genome', 'duplication', 'nga50'];
    switchSpan = names[num] + '-switch';
    document.getElementById(switchSpan).click();
}