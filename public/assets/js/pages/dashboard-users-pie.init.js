/**
 * Dashboard: simple pie chart for active vs inactive users.
 * Expects window.dashboardUsersPie from the index view.
 */
(function () {
    var el = document.querySelector("#dashboard-users-pie-chart");
    if (!el || typeof ApexCharts === "undefined" || !window.dashboardUsersPie) {
        return;
    }

    var cfg = window.dashboardUsersPie;
    var active = parseInt(cfg.active, 10) || 0;
    var inactive = parseInt(cfg.inactive, 10) || 0;
    var labelActive = cfg.labelActive || "Active";
    var labelInactive = cfg.labelInactive || "Inactive";
    var emptyText = cfg.emptyText || "";

    var total = active + inactive;
    if (total === 0) {
        el.innerHTML =
            '<div class="d-flex align-items-center justify-content-center text-muted" style="min-height:260px">' +
            '<span class="fs-13">' +
            (emptyText || "") +
            "</span>" +
            "</div>";
        return;
    }

    var options = {
        series: [active, inactive],
        chart: {
            type: "pie",
            height: 300,
            fontFamily: "inherit",
            toolbar: { show: false },
        },
        labels: [labelActive, labelInactive],
        colors: ["#0ab39c", "#f06548"],
        legend: {
            position: "bottom",
            fontWeight: 500,
            fontSize: "13px",
            markers: { width: 10, height: 10, radius: 2 },
            formatter: function (seriesName, opts) {
                var n = opts.w.globals.series[opts.seriesIndex];
                return seriesName + " (" + n + ")";
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Math.round(val) + "%";
            },
            dropShadow: { enabled: false },
        },
        stroke: {
            show: false,
        },
        plotOptions: {
            pie: {
                expandOnClick: false,
                dataLabels: {
                    offset: -4,
                },
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
        responsive: [
            {
                breakpoint: 480,
                options: {
                    chart: { height: 280 },
                    legend: { position: "bottom" },
                },
            },
        ],
    };

    var chart = new ApexCharts(el, options);
    chart.render();
})();
