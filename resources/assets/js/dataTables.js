import DataTable from "dataTables.net";
$(document).ready(function () {
    var tables = $(".table");

    $.each(tables, function (index, element) {
        var table = $(this);

        if (table.data("route")) {
            if (table.data("route") == "bibles") {
                var order = 4;
            } else {
                var order = 0;
            }

            table.DataTable({
                ajax: "https://api." + window.location.hostname + "/" + table.data("route") + "?key=1234&v=jQueryDataTable&params=" + table.data("params"),
                dom: '<<"dataTables_header"lf><t>ip>',
                fixedHeader: true,
                order: [order, "asc"],
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

        // specific to the bibles route
        if (table.data("route") == "bibles") {

            $(".dataTables_header").append('<div class="dataTables_views small-hide"><i class="table"><img src="http://images.bible.cloud/nav_list_color.svg" /></i><i class="grid"><img src="http://images.bible.cloud/nav_grid_color.svg" /></i></div>');
            $('section[role="banner"]').append('<div class="banner-logo"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/icons-bibleBanner.svg#bible"></use></svg></div>');
            $(".continents label").click(function () {
                var currentValue = $(this).prev().val();
                console.log(currentValue);
                table.DataTable().columns(0).search(currentValue).draw();
            });

            $(".bible-type label").click(function () {
                var currentValue = $(this).prev().val();
                table.DataTable().columns(2).search(currentValue).draw();

                //$(".banner-image img").attr('src', "/img/bible-banner-" + currentValue + ".png");
                if(currentValue == "") currentValue = "Bible";
                console.log(currentValue);
                $(".icon.logo use").attr('xlink:href', "/img/icons-bibleBanner.svg#" + currentValue);
            });

            $(".dataTables_views i").click(function () {
                table.toggleClass("grid");
            });
        }

        if (table.data("route") == "libraries/resources") {
            $("select[name='country-select']").change(function () {
                table.DataTable().columns(0).search($(this).val()).draw();
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

});