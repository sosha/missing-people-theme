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

            fetch(mpr_push_vars.ajax_url, {
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
});
