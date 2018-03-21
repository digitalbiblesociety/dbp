window.onload = function() {
    $("nav[role='tablist'] a").not('.external').click(function(event) {
        event.preventDefault();
        $(this).attr("aria-selected", "true");
        $(this).siblings().attr("aria-selected", "false");
        var tab = $(this).attr("href");
        $(tab).attr("aria-hidden", "false");
        $(tab).siblings().attr("aria-hidden", "true");
    });

    $(window).on('hashchange', function () {
        $("nav[role='tablist'] a").each(function (index, a) {
            if ($(this).attr("href") == location.hash) {
                var tab = $(this).attr("href");
                $(tab).attr("aria-hidden", "false");
                $(tab).siblings().attr("aria-hidden", "true");
            }
        });
    });
}