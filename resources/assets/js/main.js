// Fetch Jquery
import $ from "jquery";
window.$ = window.jQuery = require("jquery");

// Foundation
import 'foundation-sites'
$(document).foundation();

import selectize from "./selectize.js";
$(".selectize").selectize();

window.onload = function() {

    // Reveal any no-fouc elements
    var elems = $(".no-fouc");
    $.each(elems, function( index, foucElement ) {
        $(foucElement).removeClass("no-fouc");
    });

}

// Data Tables
import DataTable from "dataTables.net";
$(document).ready(function () {
    var tables = $(".table");

    $.each(tables, function (index, element) {
        var table = $(this);

        if (table.data("route")) {
            table.DataTable({
                ajax: "https://api." + window.location.hostname + "/" + table.data("route") + "?key=1234&v=jQueryDataTable&params=" + table.data("params"),
                dom: '<<"dataTables_header"lf><t>ip>',
                fixedHeader: true,
                lengthMenu: [[50, 250, 250, -1], [50, 100, 250, "All"]],
                stateSave: true,
                deferRender: true,
                language: {
                    search: '',
                    lengthMenu: '_MENU_',
                    searchPlaceholder: table.data("searchplaceholder"),
                    paginate: {
                        previous: '‹',
                        next: '›'
                    }
                },
                "fnInitComplete": function(oSettings, json) {
                    // Load up Fonts
                    var RequiredFonts = $(".requires-font").map(function() {
                        return $(this).data( "font" );
                    }).get();
                    RequiredFonts = jQuery.unique( RequiredFonts );
                    for (i = 0; i < RequiredFonts.length; i++) {
                        var link = document.createElement( "link" );
                        link.href = "https://fonts.googleapis.com/earlyaccess/"+ RequiredFonts[i] +".css";
                        link.type = "text/css";
                        link.rel = "stylesheet";
                        link.media = "screen,print";
                        document.getElementsByTagName( "head" )[0].appendChild( link );
                    }
                }
            });
        } else {
            table.DataTable({
                fixedHeader: true,
                stateSave: true,
                deferRender: true,
                order: [0, "desc"],
                lengthMenu: [[50, 100, 250, -1], [50, 100, 250, "All"]],
                language: {
                    search: '',
                    lengthMenu: '_MENU_',
                    searchPlaceholder: "Search",
                    paginate: {
                        previous: '‹',
                        next: '›'
                    }
                },
                "fnInitComplete": function(oSettings, json) {
                    // Load up Fonts
                    var RequiredFonts = $(".requires-font").map(function() {
                        return $(this).data( "font" );
                    }).get();
                    RequiredFonts = jQuery.unique( RequiredFonts );
                    for (i = 0; i < RequiredFonts.length; i++) {
                        var link = document.createElement( "link" );
                        link.href = "https://fonts.googleapis.com/earlyaccess/"+ RequiredFonts[i] +".css";
                        link.type = "text/css";
                        link.rel = "stylesheet";
                        link.media = "screen,print";
                        document.getElementsByTagName( "head" )[0].appendChild( link );
                    }
                }
            });
        }

        if (table.attr("data-invisiblecolumns") != undefined) {
            if(table.data("invisiblecolumns") == 0) {
                table.DataTable().columns(0).visible(false);
            } else {
                var hiddenColumnNumbers = table.data("invisiblecolumns").split(",");
                for (var i = 0; i < hiddenColumnNumbers.length; i++) {
                    table.DataTable().columns(parseInt(hiddenColumnNumbers[i])).visible(false);
                }
            }
        }
    });


    // Auto Fill DataList
    $('input[list]').on('input', function(e) {
        var $input = $(e.target),
            $options = $('#' + $input.attr('list') + ' option'),
            $hiddenInput = $('#' + $input.attr('id') + '-hidden'),
            label = $input.val();

        $hiddenInput.val(label);

        for(var i = 0; i < $options.length; i++) {
            var $option = $options.eq(i);

            if($option.text() === label) {
                $hiddenInput.val( $option.attr('data-value') );
                break;
            }
        }
    });

});


