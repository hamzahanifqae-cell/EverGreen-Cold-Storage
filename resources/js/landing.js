import { applyLanguage, resolveLanguage, setLanguage, translations } from './landing-i18n';

const THEME_KEY = 'evergreen-landing-theme';
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const getStoredTheme = () => {
    try {
        return localStorage.getItem(THEME_KEY);
    } catch {
        return null;
    }
};

const resolveTheme = () => {
    const stored = getStoredTheme();

    if (stored === 'light' || stored === 'dark') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const applyTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);

    const toggle = document.querySelector('[data-theme-toggle]');
    if (toggle) {
        toggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    }

    applyLanguage(resolveLanguage());
};

const setTheme = (theme) => {
    applyTheme(theme);

    try {
        localStorage.setItem(THEME_KEY, theme);
    } catch {
        // Ignore storage failures in private browsing.
    }
};

applyTheme(resolveTheme());
applyLanguage(resolveLanguage());

const themeToggle = document.querySelector('[data-theme-toggle]');
if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        setTheme(current === 'dark' ? 'light' : 'dark');
    });
}

const langToggle = document.querySelector('[data-lang-toggle]');
if (langToggle) {
    langToggle.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-lang') === 'ur' ? 'ur' : 'en';
        setLanguage(current === 'ur' ? 'en' : 'ur');
    });
}

const navWrap = document.querySelector('.landing-nav-wrap');
if (navWrap) {
    const syncNav = () => {
        navWrap.classList.toggle('is-scrolled', window.scrollY > 8);
    };
    syncNav();
    window.addEventListener('scroll', syncNav, { passive: true });
}

const chamber = document.querySelector('[data-chamber]');
const stage = document.querySelector('[data-stage]');
const flowNodes = [...document.querySelectorAll('[data-flow-step]')];
const flowStatus = document.querySelector('[data-flow-status]');
const flowStatusKeys = ['hero.visual.1', 'hero.visual.2', 'hero.visual.3', 'hero.visual.4'];

if (chamber) {
    chamber.setAttribute('data-active-step', '0');
}

const syncFlowStatus = (step) => {
    if (!flowStatus) {
        return;
    }

    const key = flowStatusKeys[step] ?? flowStatusKeys[0];
    const lang = resolveLanguage();
    const dict = translations[lang] ?? translations.en;
    flowStatus.setAttribute('data-i18n', key);
    flowStatus.textContent = dict[key] ?? flowStatus.textContent;
};

if (chamber && flowNodes.length && !reduceMotion) {
    let step = 0;
    syncFlowStatus(step);

    window.setInterval(() => {
        step = (step + 1) % flowNodes.length;
        chamber.setAttribute('data-active-step', String(step));
        flowNodes.forEach((node, index) => {
            node.classList.toggle('is-active', index === step);
        });
        syncFlowStatus(step);
    }, 2400);
} else if (chamber && flowNodes.length) {
    syncFlowStatus(0);
}

if (chamber && stage && !reduceMotion) {
    let stageRect = null;
    let tiltRaf = 0;
    let pendingX = 0;
    let pendingY = 0;
    const canTilt = () => window.matchMedia('(min-width: 721px)').matches;

    const refreshRect = () => {
        stageRect = stage.getBoundingClientRect();
    };

    const applyTilt = () => {
        tiltRaf = 0;
        if (!stageRect || !canTilt()) {
            return;
        }

        const x = pendingX;
        const y = pendingY;
        chamber.style.transform = `rotateX(${12 - y * 10}deg) rotateY(${-18 + x * 14}deg)`;
    };

    const onMove = (event) => {
        if (!canTilt()) {
            return;
        }

        if (!stageRect) {
            refreshRect();
        }

        pendingX = (event.clientX - stageRect.left) / stageRect.width - 0.5;
        pendingY = (event.clientY - stageRect.top) / stageRect.height - 0.5;

        if (!tiltRaf) {
            tiltRaf = window.requestAnimationFrame(applyTilt);
        }
    };

    const onLeave = () => {
        if (!canTilt()) {
            chamber.style.removeProperty('transform');
            return;
        }

        chamber.style.transform = 'rotateX(12deg) rotateY(-18deg)';
    };

    stage.addEventListener('pointerenter', refreshRect);
    stage.addEventListener('pointermove', onMove);
    stage.addEventListener('pointerleave', onLeave);
    window.addEventListener(
        'resize',
        () => {
            refreshRect();
            if (!canTilt()) {
                chamber.style.removeProperty('transform');
            }
        },
        { passive: true },
    );
}

const frostCanvas = document.querySelector('[data-frost]');
const hero = frostCanvas?.closest('.landing-hero');

if (frostCanvas && hero && !reduceMotion) {
    const ctx = frostCanvas.getContext('2d', { alpha: true, desynchronized: true });
    const particles = [];
    let width = 0;
    let height = 0;
    let rafId = 0;
    let lastTs = 0;
    let heroVisible = true;
    let scrolling = false;
    let scrollTimer = 0;

    const isDark = () => document.documentElement.getAttribute('data-theme') === 'dark';

    const stopLoop = () => {
        if (rafId) {
            window.cancelAnimationFrame(rafId);
            rafId = 0;
        }
    };

    const resize = () => {
        const rect = hero.getBoundingClientRect();
        width = Math.max(1, Math.floor(rect.width));
        height = Math.max(1, Math.floor(rect.height));
        frostCanvas.width = width;
        frostCanvas.height = height;
        frostCanvas.style.width = `${width}px`;
        frostCanvas.style.height = `${height}px`;
        ctx.setTransform(1, 0, 0, 1, 0, 0);

        const count = Math.round(Math.min(48, Math.max(28, width / 28)));
        particles.length = 0;

        for (let i = 0; i < count; i += 1) {
            const lowerBias = Math.random() > 0.35;
            particles.push({
                x: Math.random() * width,
                y: lowerBias ? height * (0.45 + Math.random() * 0.55) : Math.random() * height,
                r: 1 + Math.random() * 1.8,
                vx: -0.16 + Math.random() * 0.32,
                vy: -0.06 - Math.random() * 0.28,
                twinkle: Math.random() * Math.PI * 2,
                speed: 0.55 + Math.random() * 0.9,
            });
        }
    };

    const draw = (ts) => {
        rafId = 0;

        if (!heroVisible || scrolling) {
            return;
        }

        const delta = Math.min(32, ts - (lastTs || ts)) / 16.67;
        lastTs = ts;
        ctx.clearRect(0, 0, width, height);

        const dark = isDark();
        const base = dark ? '94, 234, 212' : '15, 118, 110';

        for (const p of particles) {
            p.x += p.vx * p.speed * delta;
            p.y += p.vy * p.speed * delta;
            p.twinkle += 0.025 * delta;

            if (p.y < -8) {
                p.y = height + 6;
                p.x = Math.random() * width;
            }
            if (p.x < -8) {
                p.x = width + 6;
            } else if (p.x > width + 8) {
                p.x = -6;
            }

            const alpha = (0.16 + Math.abs(Math.sin(p.twinkle)) * 0.38) * (dark ? 0.8 : 0.65);
            ctx.beginPath();
            ctx.fillStyle = `rgba(${base}, ${alpha})`;
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
        }

        rafId = window.requestAnimationFrame(draw);
    };

    const startLoop = () => {
        if (!heroVisible || scrolling || rafId) {
            return;
        }

        lastTs = 0;
        rafId = window.requestAnimationFrame(draw);
    };

    resize();
    startLoop();

    const visibility = new IntersectionObserver(
        ([entry]) => {
            heroVisible = entry.isIntersecting;
            hero.classList.toggle('is-atmosphere-paused', !heroVisible);

            if (heroVisible) {
                startLoop();
            } else {
                stopLoop();
            }
        },
        { threshold: 0.05 },
    );
    visibility.observe(hero);

    window.addEventListener(
        'scroll',
        () => {
            scrolling = true;
            hero.classList.add('is-atmosphere-paused');
            stopLoop();
            window.clearTimeout(scrollTimer);
            scrollTimer = window.setTimeout(() => {
                scrolling = false;
                hero.classList.remove('is-atmosphere-paused');
                startLoop();
            }, 140);
        },
        { passive: true },
    );

    window.addEventListener(
        'resize',
        () => {
            stopLoop();
            resize();
            startLoop();
        },
        { passive: true },
    );
}

if (!reduceMotion) {
    const reveals = document.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -4% 0px' },
    );

    reveals.forEach((node) => observer.observe(node));
} else {
    document.querySelectorAll('[data-reveal]').forEach((node) => {
        node.classList.add('is-visible');
    });
}
