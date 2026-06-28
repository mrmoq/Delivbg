/**
 * Admin dashboard: multi-line order trend, compact payment donut.
 */
(function () {
    if (typeof ApexCharts === "undefined" || !window.dashboardStats) {
        return;
    }

    var cfg = window.dashboardStats;
    var emptyText = cfg.emptyText || "لا توجد بيانات";

    var statusColors = {
        pending: "#f7b84b",
        paid: "#ea580c",
        preparing: "#2563eb",
        delivered: "#16a34a",
        failed: "#f06548",
        cancelled: "#878a99",
    };

    function renderEmpty(elId, minHeight) {
        var el = document.querySelector("#" + elId);
        if (!el) {
            return;
        }
        el.innerHTML =
            '<div class="d-flex align-items-center justify-content-center text-muted" style="min-height:' +
            (minHeight || 260) +
            'px">' +
            '<span class="fs-13">' +
            emptyText +
            "</span>" +
            "</div>";
    }

    function renderOrderStatusChart() {
        var el = document.querySelector("#order-status-chart");
        if (!el) {
            return;
        }

        var trend = cfg.orderStatusTrend || {};
        var trendSeries = trend.series || [];
        var labels = trend.labels || [];

        if (!trendSeries.length || !labels.length) {
            renderEmpty("order-status-chart", 380);
            return;
        }

        var hasData = trendSeries.some(function (item) {
            return item.data.some(function (val) {
                return val > 0;
            });
        });

        if (!hasData) {
            renderEmpty("order-status-chart", 380);
            return;
        }

        var series = trendSeries.map(function (item) {
            return {
                name: item.name,
                data: item.data,
            };
        });

        var colors = trendSeries.map(function (item) {
            return statusColors[item.status] || "#405189";
        });

        var options = {
            series: series,
            colors: colors,
            chart: {
                type: "line",
                height: 400,
                fontFamily: "inherit",
                toolbar: { show: false },
                zoom: { enabled: false },
            },
            stroke: {
                curve: "smooth",
                width: 2.5,
            },
            markers: {
                size: 0,
                hover: { size: 5 },
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: labels,
                labels: {
                    style: { fontSize: "11px" },
                    rotate: -45,
                    hideOverlappingLabels: true,
                },
                tickAmount: 10,
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return Math.round(val);
                    },
                },
                min: 0,
                forceNiceScale: true,
            },
            grid: {
                borderColor: "#f1f1f1",
                strokeDashArray: 4,
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "center",
                fontSize: "12px",
                fontWeight: 500,
                markers: { width: 10, height: 10, radius: 12 },
                itemMargin: { horizontal: 8, vertical: 4 },
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val) {
                        return val + " طلب";
                    },
                },
            },
        };

        new ApexCharts(el, options).render();
    }

    function renderPaymentStatusChart() {
        var el = document.querySelector("#payment-status-chart");
        if (!el) {
            return;
        }

        var success = parseInt(cfg.paymentsSuccess, 10) || 0;
        var failed = parseInt(cfg.paymentsFailed, 10) || 0;
        var total = success + failed;

        if (total === 0) {
            renderEmpty("payment-status-chart", 200);
            return;
        }

        var options = {
            series: [success, failed],
            labels: ["دفع ناجح", "دفع فاشل"],
            colors: ["#0ab39c", "#f06548"],
            chart: {
                type: "donut",
                height: 220,
                fontFamily: "inherit",
                toolbar: { show: false },
            },
            stroke: { width: 2, colors: ["#fff"] },
            plotOptions: {
                pie: {
                    expandOnClick: false,
                    donut: {
                        size: "72%",
                        labels: {
                            show: true,
                            name: { show: false },
                            value: { show: false },
                            total: {
                                show: true,
                                label: "الإجمالي",
                                fontSize: "12px",
                                color: "#878a99",
                                formatter: function () {
                                    return total;
                                },
                            },
                        },
                    },
                },
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " عملية";
                    },
                },
            },
        };

        new ApexCharts(el, options).render();
    }

    renderOrderStatusChart();
    renderPaymentStatusChart();
})();
