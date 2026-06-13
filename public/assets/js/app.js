/* ===================================================================
   OrgPMS Enterprise – Main JS
=================================================================== */

const OrgPMS = {
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.content,

    init() {
        this.initSidebar();
        this.initTheme();
        this.initNotifications();
        this.initMessageCount();
        this.initDataTables();
        this.initTooltips();
        this.initProgressBars();
        this.initAutoLogout();
        this.initGlobalSearch();
    },

    // ── Sidebar ──────────────────────────────────────────────────
    initSidebar() {
        const sidebar    = document.getElementById('sidebar');
        const toggle     = document.getElementById('sidebarToggle');
        const closeBtn   = document.getElementById('sidebarClose');
        const mainWrapper = document.getElementById('mainWrapper');

        if (!sidebar || !toggle) return;

        // Desktop: collapse/expand
        toggle.addEventListener('click', () => {
            const isMobile = window.innerWidth < 992;
            if (isMobile) {
                sidebar.classList.toggle('mobile-open');
                this.toggleOverlay();
            } else {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        });

        // Restore state
        if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth >= 992) {
            sidebar.classList.add('collapsed');
        }

        closeBtn?.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            this.toggleOverlay(false);
        });
    },

    toggleOverlay(show) {
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
            overlay.addEventListener('click', () => {
                document.getElementById('sidebar')?.classList.remove('mobile-open');
                this.toggleOverlay(false);
            });
        }
        if (show === false) {
            overlay.classList.remove('show');
        } else {
            overlay.classList.toggle('show');
        }
    },

    // ── Dark / Light Theme ────────────────────────────────────────
    initTheme() {
        const btn  = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');
        const html = document.documentElement;
        const saved = localStorage.getItem('theme') || 'light';

        const apply = (theme) => {
            html.setAttribute('data-theme', theme);
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        };

        apply(saved);

        btn?.addEventListener('click', () => {
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            apply(next);
            localStorage.setItem('theme', next);
            // Persist to server
            fetch('/api/theme', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                body: JSON.stringify({ theme: next }),
            }).catch(() => {});
        });
    },

    // ── Notifications ─────────────────────────────────────────────
    initNotifications() {
        this.fetchNotifications();
        setInterval(() => this.fetchNotifications(), 30000);

        document.getElementById('markAllRead')?.addEventListener('click', async () => {
            await fetch('/api/notifications/read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': this.csrfToken },
            });
            this.fetchNotifications();
        });
    },

    async fetchNotifications() {
        try {
            const res   = await fetch('/api/notifications');
            const notifs = await res.json();
            const dot   = document.getElementById('notifDot');
            const list  = document.getElementById('notifList');

            if (!list) return;

            if (notifs.length === 0) {
                if (dot) dot.style.display = 'none';
                list.innerHTML = `<div class="text-center text-muted p-4"><i class="bi bi-bell-slash fs-3 d-block mb-2"></i>No new notifications</div>`;
                return;
            }

            if (dot) dot.style.display = 'block';

            list.innerHTML = notifs.slice(0, 10).map(n => {
                const data = typeof n.data === 'string' ? JSON.parse(n.data) : n.data;
                return `<div class="notif-item ${!n.read_at ? 'unread' : ''}">
                    <div class="notif-icon" style="background:${data.color||'#e0e7ff'};color:${data.iconColor||'#4f46e5'}">
                        <i class="${data.icon || 'bi bi-bell'}"></i>
                    </div>
                    <div>
                        <div class="notif-text">${data.message || 'New notification'}</div>
                        <div class="notif-time">${this.timeAgo(n.created_at)}</div>
                    </div>
                </div>`;
            }).join('');
        } catch (e) {
            console.warn('Notification fetch failed:', e);
        }
    },

    // ── Message count ─────────────────────────────────────────────
    initMessageCount() {
        this.fetchMessageCount();
        setInterval(() => this.fetchMessageCount(), 60000);
    },

    async fetchMessageCount() {
        try {
            const res   = await fetch('/messages/unread/count');
            const data  = await res.json();
            const badge = document.querySelector('.msg-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline-flex' : 'none';
            }
        } catch (e) {}
    },

    // ── DataTables ────────────────────────────────────────────────
    initDataTables() {
        document.querySelectorAll('.data-table').forEach(el => {
            if ($.fn.DataTable) {
                $(el).DataTable({
                    pageLength: 15,
                    responsive: true,
                    language: { search: '', searchPlaceholder: 'Search...' },
                });
            }
        });
    },

    // ── Tooltips ──────────────────────────────────────────────────
    initTooltips() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    },

    // ── Animated Progress Bars ────────────────────────────────────
    initProgressBars() {
        const bars = document.querySelectorAll('.progress-bar[data-value]');
        const obs  = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const val = e.target.dataset.value;
                    e.target.style.width = val + '%';
                    obs.unobserve(e.target);
                }
            });
        });
        bars.forEach(b => { b.style.width = '0%'; obs.observe(b); });
    },

    // ── Auto Logout ───────────────────────────────────────────────
    initAutoLogout() {
        const timeout = (window.AUTO_LOGOUT_MINUTES || 30) * 60 * 1000;
        let timer;

        const reset = () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                alert('Session expired due to inactivity. You will be logged out.');
                document.querySelector('form[action*="logout"]')?.submit();
            }, timeout);
        };

        ['mousemove','keydown','click','touchstart'].forEach(e => document.addEventListener(e, reset, { passive: true }));
        reset();
    },

    // ── Global Search ─────────────────────────────────────────────
    initGlobalSearch() {
        const input = document.getElementById('globalSearch');
        if (!input) return;

        let debounce;
        input.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(async () => {
                const q = input.value.trim();
                if (q.length < 2) return;
                // Could open a search results dropdown
                console.log('Search:', q);
            }, 300);
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                const q = input.value.trim();
                if (q) window.location.href = `/plans?search=${encodeURIComponent(q)}`;
            }
        });
    },

    // ── Helpers ───────────────────────────────────────────────────
    timeAgo(dateStr) {
        const now  = new Date();
        const date = new Date(dateStr);
        const diff = Math.floor((now - date) / 1000);
        if (diff < 60)   return 'just now';
        if (diff < 3600) return Math.floor(diff/60) + 'm ago';
        if (diff < 86400) return Math.floor(diff/3600) + 'h ago';
        return Math.floor(diff/86400) + 'd ago';
    },

    ajax(url, method = 'GET', data = null) {
        return fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json',
            },
            body: data ? JSON.stringify(data) : null,
        }).then(r => r.json());
    },
};

// Charts helper
window.OrgCharts = {
    kpiGauge(el, value, color = '#4f46e5') {
        new ApexCharts(el, {
            series: [value],
            chart: { type: 'radialBar', height: 200 },
            plotOptions: { radialBar: { hollow: { size: '65%' }, dataLabels: { value: { fontSize: '1.5rem', fontWeight: 700 } } } },
            colors: [color],
            labels: ['Score'],
        }).render();
    },

    lineChart(el, series, categories) {
        new ApexCharts(el, {
            series,
            chart: { type: 'line', height: 300, toolbar: { show: false } },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: { categories },
            grid: { borderColor: '#e2e8f0' },
            colors: ['#4f46e5','#10b981','#f59e0b'],
        }).render();
    },

    barChart(el, series, categories) {
        new ApexCharts(el, {
            series,
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
            xaxis: { categories },
            colors: ['#4f46e5'],
        }).render();
    },

    donutChart(el, series, labels) {
        new ApexCharts(el, {
            series,
            chart: { type: 'donut', height: 300 },
            labels,
            colors: ['#4f46e5','#10b981','#f59e0b','#ef4444','#3b82f6'],
            legend: { position: 'bottom' },
        }).render();
    },
};

document.addEventListener('DOMContentLoaded', () => OrgPMS.init());
