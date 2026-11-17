// EverGreen Cold Storage — marketing home page behaviour.
const root = document.documentElement;
root.classList.add('cs-js');

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Sticky nav shadow once the page scrolls.
const nav = document.querySelector('[data-nav]');
if (nav) {
    const sync = () => nav.classList.toggle('is-scrolled', window.scrollY > 8);
    sync();
    window.addEventListener('scroll', sync, { passive: true });
}

// Mobile menu.
const menu = document.querySelector('[data-menu]');
const toggle = document.querySelector('[data-menu-toggle]');
if (menu && toggle) {
    const setOpen = (open) => {
        menu.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    };
    toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setOpen(false);
    });
}

// Reveal sections as they scroll into view.
const reveals = document.querySelectorAll('[data-reveal]');
if (reduceMotion || !('IntersectionObserver' in window)) {
    reveals.forEach((node) => node.classList.add('is-visible'));
} else {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach((node) => observer.observe(node));
}

// ---------------------------------------------------------------
// Snow loader → hero entrance
// ---------------------------------------------------------------
const loader = document.querySelector('[data-loader]');
const loadStart = performance.now();
let loaded = false;
const finishLoading = () => {
    if (loaded) return;
    loaded = true;
    // Keep the loader up briefly so it doesn't just flash.
    const wait = reduceMotion ? 0 : Math.max(0, 900 - (performance.now() - loadStart));
    window.setTimeout(() => {
        loader?.classList.add('is-done');
        root.classList.add('is-loaded');
        startCounters();
        window.setTimeout(() => loader?.remove(), 700);
    }, wait);
};
if (document.readyState === 'complete') {
    finishLoading();
} else {
    window.addEventListener('load', finishLoading, { once: true });
    window.setTimeout(finishLoading, 3500); // never block on a slow image or font
}

// ---------------------------------------------------------------
// Count-up numbers in the hero mock-up
// ---------------------------------------------------------------
function startCounters() {
    const nodes = document.querySelectorAll('[data-count]');
    if (reduceMotion) return;
    nodes.forEach((node) => {
        const target = Number(node.dataset.count);
        const duration = 1600;
        const begin = performance.now() + 700;
        const step = (now) => {
            const t = Math.min(1, Math.max(0, (now - begin) / duration));
            const eased = 1 - Math.pow(1 - t, 3);
            node.textContent = Math.round(target * eased).toLocaleString('en-US');
            if (t < 1) requestAnimationFrame(step);
        };
        node.textContent = '0';
        requestAnimationFrame(step);
    });
}

// ---------------------------------------------------------------
// Falling snow in the hero
// ---------------------------------------------------------------
const snowCanvas = document.querySelector('[data-snow]');
if (snowCanvas && !reduceMotion) {
    const ctx = snowCanvas.getContext('2d');
    const hero = snowCanvas.parentElement;
    let flakes = [];
    let width = 0;
    let height = 0;
    let running = true;
    let frame = null;

    const makeFlake = (anywhere) => ({
        x: Math.random() * width,
        y: anywhere ? Math.random() * height : -10,
        r: Math.random() * 2.2 + 0.6,
        speed: Math.random() * 0.6 + 0.25,
        drift: Math.random() * 0.6 - 0.3,
        phase: Math.random() * Math.PI * 2,
        alpha: Math.random() * 0.5 + 0.3,
    });

    const resize = () => {
        const ratio = Math.min(window.devicePixelRatio || 1, 2);
        width = hero.clientWidth;
        height = hero.clientHeight;
        snowCanvas.width = width * ratio;
        snowCanvas.height = height * ratio;
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        const count = Math.round(Math.min(140, width / 9));
        flakes = Array.from({ length: count }, () => makeFlake(true));
    };

    const draw = () => {
        ctx.clearRect(0, 0, width, height);
        for (const f of flakes) {
            f.phase += 0.01;
            f.y += f.speed;
            f.x += f.drift + Math.sin(f.phase) * 0.3;
            if (f.y > height + 10 || f.x < -10 || f.x > width + 10) Object.assign(f, makeFlake(false));
            ctx.beginPath();
            ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${f.alpha})`;
            ctx.fill();
        }
        frame = running ? requestAnimationFrame(draw) : null;
    };

    resize();
    draw();
    window.addEventListener('resize', resize);

    // Pause when the hero is off screen to save battery.
    new IntersectionObserver(([entry]) => {
        running = entry.isIntersecting;
        if (running && !frame) draw();
    }).observe(hero);
}

// ---------------------------------------------------------------
// 3D hero scene: idle sway + tilt towards the cursor
// ---------------------------------------------------------------
const scene = document.querySelector('[data-scene]');
const rig = document.querySelector('[data-rig]');
if (scene && rig && !reduceMotion) {
    const canHover = window.matchMedia('(hover: hover)').matches;
    let target = { x: 4, y: -10 };
    let current = { x: 4, y: -10 };
    let pointer = null;
    let visible = true;
    let raf = null;

    if (canHover) {
        // Track the pointer over the whole hero so the scene reacts before you reach it.
        const area = scene.closest('section') || scene;
        area.addEventListener('pointermove', (event) => {
            const rect = scene.getBoundingClientRect();
            pointer = {
                x: Math.max(-1, Math.min(1, (event.clientX - (rect.left + rect.width / 2)) / (rect.width / 1.2))),
                y: Math.max(-1, Math.min(1, (event.clientY - (rect.top + rect.height / 2)) / (rect.height / 1.2))),
            };
        });
        area.addEventListener('pointerleave', () => { pointer = null; });
    }

    const tick = (time) => {
        if (pointer) {
            target = { x: 4 - pointer.y * 10, y: -10 + pointer.x * 16 };
        } else {
            // Smaller sway on phones so the floating cards stay on screen.
            const amp = window.innerWidth < 640 ? 0.45 : 1;
            target = { x: 4 + Math.cos(time / 2600) * 3 * amp, y: (-10 + Math.sin(time / 2200) * 7) * amp };
        }
        current.x += (target.x - current.x) * 0.06;
        current.y += (target.y - current.y) * 0.06;
        rig.style.transform = `rotateX(${current.x.toFixed(2)}deg) rotateY(${current.y.toFixed(2)}deg)`;
        raf = visible ? requestAnimationFrame(tick) : null;
    };
    raf = requestAnimationFrame(tick);

    new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;
        if (visible && !raf) raf = requestAnimationFrame(tick);
    }).observe(scene);
}

// ---------------------------------------------------------------
// Card spotlight follows the cursor
// ---------------------------------------------------------------
document.querySelectorAll('.cs-card').forEach((card) => {
    card.addEventListener('mousemove', (event) => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
        card.style.setProperty('--my', `${event.clientY - rect.top}px`);
    });
});

// ---------------------------------------------------------------
// Ripple on button press
// ---------------------------------------------------------------
if (!reduceMotion) {
    document.querySelectorAll('.cs-btn').forEach((button) => {
        button.addEventListener('pointerdown', (event) => {
            const rect = button.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.className = 'cs-ripple';
            ripple.style.left = `${event.clientX - rect.left}px`;
            ripple.style.top = `${event.clientY - rect.top}px`;
            button.appendChild(ripple);
            ripple.addEventListener('animationend', () => ripple.remove());
        });
    });
}

// ---------------------------------------------------------------
// 3D tilt on cards, following the cursor
// ---------------------------------------------------------------
if (!reduceMotion && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('[data-tilt3d]').forEach((card) => {
        const max = card.classList.contains('cs-about-visual') ? 6 : 10;
        card.addEventListener('pointermove', (event) => {
            if (!card.classList.contains('is-visible')) return;
            const rect = card.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;
            card.classList.add('is-tilting');
            card.style.transform = `perspective(900px) rotateX(${(-y * max).toFixed(2)}deg) rotateY(${(x * max).toFixed(2)}deg) translateY(-6px) scale(1.02)`;
        });
        card.addEventListener('pointerleave', () => {
            card.classList.remove('is-tilting');
            card.style.transform = '';
        });
    });
}

// ---------------------------------------------------------------
// How it works: timeline line fills as you scroll, dots light up
// ---------------------------------------------------------------
const timeline = document.querySelector('[data-timeline]');
if (timeline) {
    const items = [...timeline.querySelectorAll('.cs-timeline-item')];
    let queued = false;
    const update = () => {
        queued = false;
        const rect = timeline.getBoundingClientRect();
        const mark = window.innerHeight * 0.6;
        const progress = Math.min(1, Math.max(0, (mark - rect.top) / rect.height));
        timeline.style.setProperty('--progress', progress.toFixed(3));
        items.forEach((item) => {
            const dot = item.querySelector('.cs-timeline-dot');
            item.classList.toggle('is-active', dot.getBoundingClientRect().top < mark);
        });
    };
    const queue = () => {
        if (!queued) {
            queued = true;
            requestAnimationFrame(update);
        }
    };
    update();
    window.addEventListener('scroll', queue, { passive: true });
    window.addEventListener('resize', queue);
}

// ---------------------------------------------------------------
// Demo notice toast (?demo=left|expired|blocked)
// ---------------------------------------------------------------
const toast = document.querySelector('[data-toast]');
if (toast) {
    // Drop ?demo= from the address bar so a refresh doesn't show it again.
    try {
        const url = new URL(window.location.href);
        url.searchParams.delete('demo');
        window.history.replaceState(null, '', url.pathname + url.search + url.hash);
    } catch {
        // Ignore: the toast still works without cleaning the URL.
    }

    const hide = () => {
        toast.classList.add('is-hiding');
        window.setTimeout(() => toast.remove(), reduceMotion ? 0 : 300);
    };
    toast.querySelector('[data-toast-close]')?.addEventListener('click', hide);
    window.setTimeout(hide, 9000);
}

// ---------------------------------------------------------------
// Light / dark toggle (initial theme is set by an inline script in <head>)
// ---------------------------------------------------------------
document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    const sync = () => button.setAttribute('aria-pressed', String(document.documentElement.dataset.theme === 'dark'));
    sync();
    button.addEventListener('click', () => {
        const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = next;
        try { localStorage.setItem('cs-theme', next); } catch (e) { /* private mode: still switches for this page */ }
        document.querySelectorAll('[data-theme-toggle]').forEach((b) => b.setAttribute('aria-pressed', String(next === 'dark')));
    });
});
