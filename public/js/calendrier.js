
function calendarComponent(opts) {
    return {
        year: opts.initialYear,
        month: opts.initialMonth,
        selected: new Set(opts.initialSelected || []),
        reservedDates: {},
        calendarType: opts.calendarType || 'terrain',
        min: opts.min || null,
        max: opts.max || null,
        locale: opts.locale || 'fr',
        cache: {},
        loading: true,

        async init() {
            this.buildSkeleton();
            await this.fetchReservedDates().catch(() => {});;

            // Preload mois adjacents
            setTimeout(() => {
                this.preloadMonth(this.year, this.month + 1);
                this.preloadMonth(this.year, this.month + 2);
                this.preloadMonth(this.year, this.month - 1);
            }, 300);

            document.dispatchEvent(new CustomEvent('calendar-ready', {
                detail: { calendar: this }
            }));
        },

        async preloadMonth(year, month) {
            if (month < 0) { month = 11; year--; }
            if (month > 11) { month = 0; year++; }
            const key = `${year}-${month + 1}`;
            if (this.cache[key]) return;

            try {
                const url = this.calendarType === 'evenement'
                    ? `/evenements/reserved-dates?year=${year}&month=${month+1}`
                    : `/terrains/reserved-dates?year=${year}&month=${month+1}`;
                const response = await fetch(url);
                const data = await response.json();
                this.cache[key] = data.reservedDates || {};
            } catch {}
        },

        get yearLabel() {
            const d = new Date(this.year, this.month, 1);
            const year = d.toLocaleString(this.locale, { year: 'numeric' });
            return year;
        },

        get monthLabel() {
            const d = new Date(this.year, this.month, 1);
            const month = d.toLocaleString(this.locale, { month: 'short' });
            const formattedMonth = month.charAt(0).toUpperCase() + month.slice(1);
            return formattedMonth;
        },

        get weekdays() {
            const base = new Date(1970,0,4); // Sunday
            return Array.from({length:7}).map((_,i)=>base.setDate(4+i) && new Date(base).toLocaleString(this.locale,{weekday:'short'}));
        },

        days: [],

        build() {
            this.days = [];
            const firstOfMonth = new Date(this.year, this.month, 1);
            const startDay = firstOfMonth.getDay(); // 0 (Sun) - 6 (Sat)
            // Determine previous month's tail
            const prevMonthLastDate = new Date(this.year, this.month, 0).getDate();
            const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

            // 6 lignes de 7 colonnes
            const totalCells = 42;
            const startOffset = startDay;
            const today = new Date();
            today.setHours(0,0,0,0);

            for (let i = 0; i < totalCells; i++) {
                const dayIndex = i - startOffset + 1;
                let cellDate = new Date(this.year, this.month, dayIndex);
                let isOtherMonth = (cellDate.getMonth() !== this.month);

                const iso = cellDate.toISOString().slice(0,10);
                const label = cellDate.getDate();
                const isDisabled = (this.min && iso < this.min) || (this.max && iso > this.max);
                const isPast = cellDate < today;
                const reservationsForDay = (this.reservedDates[iso] || []).slice(0, 6);

                this.days.push({
                    date: cellDate,
                    iso,
                    label,
                    isOtherMonth,
                    isDisabled,
                    isPast,
                    reservations: reservationsForDay
                });
            }
        },

        isSelected(date) {
            if (!date) return false;
            const iso = date.toISOString().slice(0, 10);
            return this.selected.has(iso);
        },

        select(day) {
            if (day.isDisabled) return;
            const iso = day.iso;

            if (this.selected.has(iso)) {
                this.selected.delete(iso);
            } else {
                this.selected.clear();
                this.selected.add(iso);
            }

            const [payload] = Array.from(this.selected);

            this.$el.dispatchEvent(new CustomEvent('date-selected', { detail: payload, bubbles: true }));
        },

        buildSkeleton() {
            this.days = Array.from({ length: 42 }).map(() => ({
                date: null, label: '', isOtherMonth: true, isDisabled: true, isPast: false, reservations: []
            }));
        },

        prevMonth() {
            if (this.month === 0) { this.month = 11; this.year--; } else { this.month--; }
            this.buildSkeleton();
            this.fetchReservedDates().catch(() => {});
        },

        nextMonth() {
            if (this.month === 11) { this.month = 0; this.year++; } else { this.month++; }
            this.buildSkeleton();
            this.fetchReservedDates().catch(() => {});
        },

        goToToday() {
            const t = new Date();
            this.year = t.getFullYear();
            this.month = t.getMonth();
            this.fetchReservedDates().catch(() => {});

            const iso = t.toISOString().slice(0, 10);
            const isDisabled = (this.min && iso < this.min) || (this.max && iso > this.max);
            if (!isDisabled) {
                const day = { date: t, iso, isDisabled: false };
                this.select(day);
            }
        },

        goToDate(iso) {
            // Format valide
            if (!iso || !/^\d{4}-\d{2}-\d{2}$/.test(iso)) return;

            const [year, month, day] = iso.split('-').map(Number);

            this.year = year;
            this.month = month - 1;

            this.fetchReservedDates()
            .then(() => {
                const t = new Date(year, month - 1, day);
                const isDisabled = (this.min && iso < this.min) || (this.max && iso > this.max);
                if (!isDisabled) {
                    const dayObj = { date: t, iso, isDisabled: false };
                    this.select(dayObj);
                }
            })
            .catch(() => {});
        },

        async fetchReservedDates() {
            const key = `${this.year}-${this.month + 1}`;
            if (this.cache[key]) {
                this.reservedDates = this.cache[key];
                this.build();
                return;
            }

            if (this._abortController) this._abortController.abort();
            this._abortController = new AbortController();
            const signal = this._abortController.signal;

            this.loading = true;
            try {
                const url = this.calendarType === 'evenement'
                    ? `/evenements/reserved-dates?year=${this.year}&month=${this.month+1}`
                    : `/terrains/reserved-dates?year=${this.year}&month=${this.month+1}`;

                const response = await fetch(url, { signal });
                const data = await response.json();

                this.reservedDates = data.reservedDates || {};
                this.cache[key] = this.reservedDates;
                this.build();
            } catch (e) {
                if (e.name !== 'AbortError') console.error('Failed to fetch reserved dates', e);
            } finally {
                this.loading = false;
            }
        },

    };

}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        document.querySelectorAll('[style*="scrollDots"]').forEach(el => {
            el.style.animationPlayState = 'paused';
        });
    } else {
        document.querySelectorAll('[style*="scrollDots"]').forEach(el => {
            el.style.animationPlayState = 'running';
        });
    }
});
