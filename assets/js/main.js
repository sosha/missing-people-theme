/**
 * Missing People Theme - JS Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-navigation');

    if (menuToggle && nav) {
        menuToggle.addEventListener('click', () => {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            nav.style.display = isExpanded ? 'none' : 'block';
        });
    }

    // Smooth Scroll for anchored links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Reveal animations on scroll
    const scrollReveal = () => {
        const reveals = document.querySelectorAll('.mpr-card, .hero-title, .section-header');
        reveals.forEach(el => {
            const windowHeight = window.innerHeight;
            const elementTop = el.getBoundingClientRect().top;
            const elementVisible = 150;
            if (elementTop < windowHeight - elementVisible) {
                el.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', scrollReveal);

    // Lead Submission Form Handler
    const leadForm = document.getElementById('mpr-lead-form');
    if (leadForm) {
        leadForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'mpr_submit_lead');

            const statusBox = this.querySelector('.form-status');
            const submitBtn = this.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            statusBox.textContent = '';

            const ajaxUrl = (typeof mp_theme_vars !== 'undefined' && mp_theme_vars.ajax_url)
                ? mp_theme_vars.ajax_url
                : (typeof mpr_push_vars !== 'undefined' ? mpr_push_vars.ajax_url : '');

            if (!ajaxUrl) {
                statusBox.textContent = 'Unable to submit right now. Please try again later.';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Secure Lead';
                return;
            }

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        statusBox.innerHTML = `<span style="color: #155724; background: #d4edda; padding: 10px; display: block; border-radius: 5px;">${data.data.message}</span>`;
                        leadForm.reset();
                    } else {
                        statusBox.innerHTML = `<span style="color: #721c24; background: #f8d7da; padding: 10px; display: block; border-radius: 5px;">${data.data.message}</span>`;
                    }
                })
                .catch(err => {
                    statusBox.textContent = 'An error occurred. Please try again.';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Secure Lead';
                });
        });
    }

    // Volunteer Signup Form Handler
    const volunteerForm = document.getElementById('mp-volunteer-form');
    if (volunteerForm) {
        volunteerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const statusBox = this.querySelector('.form-status');
            const submitBtn = this.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            statusBox.textContent = '';

            const ajaxUrl = (typeof mp_theme_vars !== 'undefined' && mp_theme_vars.ajax_url) ? mp_theme_vars.ajax_url : '';
            if (!ajaxUrl) {
                statusBox.textContent = 'Unable to submit right now. Please try again later.';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Volunteer Request';
                return;
            }

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        statusBox.innerHTML = `<span style="color: #155724; background: #d4edda; padding: 10px; display: block; border-radius: 5px;">${data.data.message}</span>`;
                        volunteerForm.reset();
                    } else {
                        statusBox.innerHTML = `<span style="color: #721c24; background: #f8d7da; padding: 10px; display: block; border-radius: 5px;">${data.data.message}</span>`;
                    }
                })
                .catch(() => {
                    statusBox.textContent = 'An error occurred. Please try again.';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Volunteer Request';
                });
        });
    }

    // Simple translate helper (Google Translate)
    const translateSelect = document.getElementById('mp-translate-select');
    if (translateSelect) {
        translateSelect.addEventListener('change', function () {
            const lang = this.value;
            const url = this.dataset.url || window.location.href;
            if (!lang) return;
            const target = `https://translate.google.com/translate?sl=auto&tl=${encodeURIComponent(lang)}&u=${encodeURIComponent(url)}`;
            window.open(target, '_blank');
        });
    }

    // Archive Map Rendering with clustering
    const archiveMapEl = document.getElementById('mpr-archive-map');
    if (archiveMapEl && typeof L !== 'undefined' && typeof mp_theme_map_data !== 'undefined') {
        const cases = mp_theme_map_data.cases || [];
        const filters = mp_theme_map_data.filters || {};

        const filteredCases = cases.filter(item => {
            if (filters.status && item.status !== filters.status) return false;
            if (filters.risk && item.risk !== filters.risk) return false;
            if (filters.loc && item.location && !item.location.toLowerCase().includes(filters.loc.toLowerCase())) return false;
            if (filters.search && !item.title.toLowerCase().includes(filters.search.toLowerCase())) return false;
            return true;
        });

        const defaultLat = filteredCases.length ? filteredCases[0].lat : -1.2921;
        const defaultLng = filteredCases.length ? filteredCases[0].lng : 36.8219;

        const map = L.map('mpr-archive-map').setView([defaultLat, defaultLng], filteredCases.length ? 9 : 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let layer = null;
        if (typeof L.markerClusterGroup === 'function') {
            layer = L.markerClusterGroup();
        } else {
            layer = L.layerGroup();
        }

        filteredCases.forEach(item => {
            const marker = L.marker([item.lat, item.lng]);
            const popup = `
                <div class="map-popup">
                    <img src="${item.image}" alt="${item.title}" />
                    <div class="popup-text">
                        <strong>${item.title}</strong>
                        <div>${item.status} • ${item.risk} Risk</div>
                        <a href="${item.url}">View case</a>
                    </div>
                </div>
            `;
            marker.bindPopup(popup);
            layer.addLayer(marker);
        });

        layer.addTo(map);
    }
});
