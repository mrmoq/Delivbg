<style>
    .invoice-page-wrap { width: fit-content; max-width: 100%; margin-left: auto; margin-right: auto; }
    .invoice-page-wrap .card { width: fit-content; max-width: 100%; }
    .basma-inv { max-width: 920px; margin: 0 auto; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; color: #182e65; }
    .basma-inv__header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 1.5rem; padding-bottom: 1.25rem; border-bottom: 2px solid #182e65; margin-bottom: 1.5rem; }
    .basma-inv__brand { display: flex; align-items: center; gap: 0.75rem; }
    .basma-inv__brand img { height: 36px; width: auto; }
    .basma-inv__meta { display: flex; align-items: stretch; gap: 1rem; font-size: 0.95rem; }
    .basma-inv__meta-col { display: flex; flex-direction: column; gap: 0.25rem; }
    .basma-inv__meta-col span:first-child { color: #8a9aaf; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em; }
    .basma-inv__meta-col strong { color: #182e65; font-weight: 700; }
    .basma-inv__meta-vr { width: 1px; background: #c5d0e0; align-self: stretch; min-height: 2.5rem; }

    .basma-inv__info { background: linear-gradient(135deg, #eef3f9 0%, #e4ebf4 100%); border-radius: 6px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: minmax(0, 1.85fr) minmax(0, 1fr); gap: 1.5rem; align-items: start; }
    @media (max-width: 576px) { .basma-inv__info { grid-template-columns: 1fr; } }
    .basma-inv__info h6 { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; color: #182e65; font-weight: 700; margin-bottom: 0.75rem; }
    .basma-inv__info dl { margin: 0; font-size: 0.9rem; }
    .basma-inv__info dt { color: #5a6b82; font-weight: 600; float: right; clear: right; width: 5.5rem; text-align: right; }
    .basma-inv__info dd { margin: 0 5.5rem 0.35rem 0; color: #182e65; }
    .basma-inv__delivery-dd { white-space: normal; line-height: 1.45; max-width: 100%; }

    .basma-inv__table-wrap { margin-bottom: 1.5rem; overflow-x: auto; }
    .basma-inv__table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .basma-inv__table thead th { text-align: right; font-weight: 700; color: #182e65; padding: 0.65rem 0.5rem; border-bottom: 3px solid #182e65; }
    .basma-inv__table thead th.text-end { text-align: left; }
    .basma-inv__table tbody td { padding: 0.85rem 0.5rem; border-bottom: 1px solid #dce4ef; vertical-align: top; color: #182e65; }
    .basma-inv__table tbody td.text-end { text-align: left; font-weight: 600; }
    .basma-inv__table tbody tr:last-child td { border-bottom: none; }

    .basma-inv__bottom { display: grid; grid-template-columns: 1fr minmax(280px, 320px); gap: 2rem; align-items: start; margin-top: 0.5rem; }
    @media (max-width: 768px) { .basma-inv__bottom { grid-template-columns: 1fr; } }

    .basma-inv__pay h4 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: #182e65; font-weight: 800; margin-bottom: 1rem; }
    .basma-inv__pay p { margin: 0 0 0.5rem; font-size: 0.9rem; color: #5a6b82; }
    .basma-inv__pay p strong { color: #182e65; font-weight: 600; margin-left: 0.35rem; }

    .basma-inv__sum { font-size: 0.88rem; min-width: 280px; }
    .basma-inv__sum-line { display: flex; justify-content: space-between; align-items: baseline; gap: 1rem; padding: 0.3rem 0; color: #5a6b82; }
    .basma-inv__sum-line span:last-child { color: #182e65; font-weight: 600; white-space: nowrap; }
    .basma-inv__sum-line--head { font-weight: 700; color: #182e65; padding-top: 0; }
    .basma-inv__total-box { background: #182e65; color: #fff; padding: 0.85rem 1.1rem; border-radius: 4px; margin-top: 0.75rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .basma-inv__total-box span:first-child { font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.8rem; }
    .basma-inv__total-box span:last-child { font-size: 1.15rem; font-weight: 800; }

    @media print {
        @page { margin: 12mm 6mm 14mm 6mm; size: auto; }
        html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; height: auto !important; min-height: 0 !important; }
        #layout-wrapper { min-height: 0 !important; height: auto !important; }
        #layout-wrapper .main-content, #layout-wrapper .page-content { min-height: 0 !important; height: auto !important; }
        #page-topbar, .navbar-menu, .app-menu, #back-to-top, .footer, .breadcrumb, .invoice-print-actions, .btn { display: none !important; }
        #layout-wrapper .main-content { margin-left: 0 !important; margin-right: 0 !important; }
        #layout-wrapper .page-content { padding: 0 !important; margin: 0 !important; }
        #layout-wrapper .container-fluid { max-width: 100% !important; display: block !important; min-height: 0 !important; height: auto !important; padding-left: 4mm !important; padding-right: 4mm !important; padding-top: 0 !important; padding-bottom: 0 !important; box-sizing: border-box !important; }
        #printPage.row { --bs-gutter-x: 0 !important; --bs-gutter-y: 0 !important; display: flex !important; flex-wrap: wrap !important; align-items: flex-start !important; width: 100% !important; max-width: 100% !important; margin-left: 0 !important; margin-right: 0 !important; justify-content: center !important; min-height: 0 !important; height: auto !important; }
        #printPage > .col-auto { flex: 0 1 auto !important; align-self: flex-start !important; width: 100% !important; max-width: 920px !important; padding-left: 0 !important; padding-right: 0 !important; height: auto !important; min-height: 0 !important; }
        .invoice-page-wrap { width: 100% !important; max-width: 920px !important; margin: 0 auto !important; padding: 0 !important; height: auto !important; }
        .invoice-page-wrap .card { width: 100% !important; max-width: 920px !important; box-shadow: none !important; border: 0 !important; height: auto !important; }
        .invoice-page-wrap .card-body { padding: 5mm 3mm !important; height: auto !important; }
        .basma-inv { max-width: 100%; margin-left: auto; margin-right: auto; height: auto !important; }
        .basma-inv__info { grid-template-columns: minmax(0, 1.85fr) minmax(0, 1fr) !important; }
        .basma-inv__bottom { display: grid !important; grid-template-columns: minmax(0, 1fr) minmax(260px, 320px) !important; gap: 1.25rem !important; align-items: start !important; break-inside: auto; page-break-inside: auto; }
        .basma-inv__info, .basma-inv__total-box { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
