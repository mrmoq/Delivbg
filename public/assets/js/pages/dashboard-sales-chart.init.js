/**
 * Dashboard home: dual-axis area chart (revenue KD vs paid order count).
 * Expects window.dashboardSalesChart from the index view; optional
 * window.dashboardSalesChartFetchUrl for daily/monthly/yearly switching.
 */
(function () {
    var el = document.querySelector("#dashboard-sales-chart");
    if (!el || typeof ApexCharts === "undefined" || !window.dashboardSalesChart) {
        return;
    }

    var cfg = window.dashboardSalesChart;
    var revenueLabel = cfg.revenueLabel || "Revenue";
    var ordersLabel = cfg.ordersLabel || "Orders";

    var primaryBlue = "#3577f1";
    var successGreen = "#0ab39c";

    function labelRotateForCount(n) {
        if (n > 20) {
            return -55;
        }
        if (n > 12) {
            return -40;
        }
        if (n > 8) {
            return -30;
        }
        return 0;
    }

    function buildOptions(labels, revenue, orders) {
        var n = (labels && labels.length) || 0;
        return {
            series: [
                {
                    name: revenueLabel,
                    type: "area",
                    data: revenue || [],
                },
                {
                    name: ordersLabel,
                    type: "area",
                    data: orders || [],
                },
            ],
            chart: {
                height: 360,
                type: "area",
                stacked: false,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: "inherit",
                foreColor: "#878a99",
            },
            colors: [primaryBlue, successGreen],
            stroke: {
                curve: "smooth",
                width: 2,
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.42,
                    opacityTo: 0.06,
                    stops: [0, 92, 100],
                },
            },
            dataLabels: { enabled: false },
            markers: {
                size: 0,
                strokeWidth: 0,
                hover: { size: 4 },
            },
            xaxis: {
                categories: labels || [],
                tickAmount: undefined,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    rotate: labelRotateForCount(n),
                    rotateAlways: false,
                    maxHeight: 88,
                    style: { fontSize: "11px" },
                },
                crosshairs: {
                    show: true,
                    stroke: { dashArray: 4 },
                },
            },
            yaxis: [
                {
                    seriesName: revenueLabel,
                    title: {
                        text: revenueLabel,
                        style: { fontWeight: 500, fontSize: "12px" },
                    },
                    labels: {
                        formatter: function (val) {
                            if (val === undefined || val === null) return "";
                            return parseFloat(val).toLocaleString(undefined, {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 2,
                            });
                        },
                    },
                    tickAmount: 4,
                    forceNiceScale: true,
                },
                {
                    opposite: true,
                    seriesName: ordersLabel,
                    title: {
                        text: ordersLabel,
                        style: { fontWeight: 500, fontSize: "12px" },
                    },
                    labels: {
                        formatter: function (val) {
                            if (val === undefined || val === null) return "";
                            return Math.round(val).toString();
                        },
                    },
                    tickAmount: 4,
                    forceNiceScale: true,
                },
            ],
            grid: {
                borderColor: "#eef0f4",
                strokeDashArray: 4,
                padding: { left: 8, right: 8 },
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: true } },
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "end",
                fontWeight: 500,
                markers: { width: 10, height: 10, radius: 2 },
            },
            tooltip: {
                shared: true,
                intersect: false,
                x: { show: true },
                y: {
                    formatter: function (val, opts) {
                        if (val === undefined || val === null) {
                            return "";
                        }
                        if (opts.seriesIndex === 0) {
                            return parseFloat(val).toFixed(3) + " KD";
                        }
                        return Math.round(val).toString();
                    },
                },
            },
        };
    }

    var labels = cfg.labels || [];
    var revenue = cfg.revenue || [];
    var orders = cfg.orders || [];
    var chart = new ApexCharts(el, buildOptions(labels, revenue, orders));
    chart.render();
    window.dashboardSalesChartInstance = chart;

    var wrap = document.getElementById("dashboard-sales-chart-granularity-wrap");
    var btn = document.getElementById("dashboard-sales-chart-granularity-btn");
    var fetchUrl = window.dashboardSalesChartFetchUrl;
    if (!wrap || !btn || !fetchUrl) {
        return;
    }

    var items = wrap.querySelectorAll("[data-sales-granularity]");
    var currentGranularity = "monthly";

    function setActiveItem(granularity) {
        items.forEach(function (node) {
            var g = node.getAttribute("data-sales-granularity");
            var isActive = g === granularity;
            node.classList.toggle("active", isActive);
            if (isActive) {
                node.setAttribute("aria-current", "true");
            } else {
                node.removeAttribute("aria-current");
            }
        });
    }

    function loadGranularity(granularity, labelText) {
        if (granularity === currentGranularity) {
            return;
        }
        var url =
            fetchUrl +
            (fetchUrl.indexOf("?") >= 0 ? "&" : "?") +
            "granularity=" +
            encodeURIComponent(granularity);
        btn.disabled = true;
        items.forEach(function (n) {
            n.disabled = true;
        });
        fetch(url, {
            method: "GET",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error("HTTP " + res.status);
                }
                return res.json();
            })
            .then(function (data) {
                currentGranularity = granularity;
                btn.textContent = labelText;
                setActiveItem(granularity);
                var lb = data.labels || [];
                var rev = data.revenue || [];
                var ord = data.orders || [];
                chart.updateOptions(buildOptions(lb, rev, ord));
            })
            .catch(function () {
                /* keep current chart on failure */
            })
            .finally(function () {
                btn.disabled = false;
                items.forEach(function (n) {
                    n.disabled = false;
                });
            });
    }

    items.forEach(function (item) {
        item.addEventListener("click", function () {
            var g = item.getAttribute("data-sales-granularity");
            if (!g) {
                return;
            }
            var label = (item.textContent || "").trim();
            loadGranularity(g, label);
        });
    });
})();
