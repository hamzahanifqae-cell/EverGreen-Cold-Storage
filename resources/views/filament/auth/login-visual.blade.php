<canvas class="eg-login-frost" data-login-frost aria-hidden="true"></canvas>

<div class="eg-login-stage" data-login-stage>
    <div class="eg-login-stage-glow"></div>
    <div class="eg-login-orbit eg-login-orbit-1"></div>
    <div class="eg-login-orbit eg-login-orbit-2"></div>
    <div class="eg-login-orbit eg-login-orbit-3"></div>
    <div class="eg-login-chamber" data-login-chamber>
        <div class="eg-login-chamber-face">
            <div class="eg-login-chamber-temp">-18°C</div>
            <div class="eg-login-chamber-door"></div>
            <div class="eg-login-shelf eg-login-shelf-1"></div>
            <div class="eg-login-shelf eg-login-shelf-2"></div>
            <div class="eg-login-crate eg-login-crate-1"></div>
            <div class="eg-login-crate eg-login-crate-2"></div>
            <div class="eg-login-crate eg-login-crate-3"></div>
            <div class="eg-login-vapor"></div>
        </div>
    </div>
</div>

<script>
    (() => {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const chamber = document.querySelector('[data-login-chamber]');
        const stage = document.querySelector('[data-login-stage]');
        const frostCanvas = document.querySelector('[data-login-frost]');
        const visual =
            frostCanvas?.closest('.flowdesk-auth-visual') ||
            frostCanvas?.closest('.flowdesk-auth-layout') ||
            frostCanvas?.parentElement;

        if (chamber && stage && !reduceMotion) {
            let stageRect = null;
            let tiltRaf = 0;
            let pendingX = 0;
            let pendingY = 0;

            const refreshRect = () => {
                stageRect = stage.getBoundingClientRect();
            };

            const applyTilt = () => {
                tiltRaf = 0;
                if (!stageRect) {
                    return;
                }

                chamber.style.transform = `rotateX(${18 - pendingY * 12}deg) rotateY(${-28 + pendingX * 18}deg)`;
            };

            stage.addEventListener('pointerenter', refreshRect);
            stage.addEventListener('pointermove', (event) => {
                if (!stageRect) {
                    refreshRect();
                }

                pendingX = (event.clientX - stageRect.left) / stageRect.width - 0.5;
                pendingY = (event.clientY - stageRect.top) / stageRect.height - 0.5;

                if (!tiltRaf) {
                    tiltRaf = window.requestAnimationFrame(applyTilt);
                }
            });
            stage.addEventListener('pointerleave', () => {
                chamber.style.transform = 'rotateX(18deg) rotateY(-28deg)';
            });
            window.addEventListener('resize', refreshRect, { passive: true });
        }

        if (!frostCanvas || !visual || reduceMotion) {
            return;
        }

        const ctx = frostCanvas.getContext('2d', { alpha: true, desynchronized: true });
        const particles = [];
        let width = 0;
        let height = 0;
        let rafId = 0;
        let lastTs = 0;

        const stopLoop = () => {
            if (rafId) {
                window.cancelAnimationFrame(rafId);
                rafId = 0;
            }
        };

        const resize = () => {
            const rect = visual.getBoundingClientRect();
            width = Math.max(1, Math.floor(rect.width));
            height = Math.max(1, Math.floor(rect.height));
            frostCanvas.width = width;
            frostCanvas.height = height;
            frostCanvas.style.width = `${width}px`;
            frostCanvas.style.height = `${height}px`;
            ctx.setTransform(1, 0, 0, 1, 0, 0);

            const count = Math.round(Math.min(70, Math.max(36, width / 18)));
            particles.length = 0;

            for (let i = 0; i < count; i += 1) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    r: 1 + Math.random() * 2,
                    vx: -0.18 + Math.random() * 0.36,
                    vy: 0.2 + Math.random() * 0.55,
                    twinkle: Math.random() * Math.PI * 2,
                    speed: 0.65 + Math.random() * 1.1,
                });
            }
        };

        const draw = (ts) => {
            rafId = 0;
            const delta = Math.min(32, ts - (lastTs || ts)) / 16.67;
            lastTs = ts;
            ctx.clearRect(0, 0, width, height);

            for (const p of particles) {
                p.x += p.vx * p.speed * delta;
                p.y += p.vy * p.speed * delta;
                p.twinkle += 0.025 * delta;

                if (p.y > height + 8) {
                    p.y = -6;
                    p.x = Math.random() * width;
                }
                if (p.x < -8) {
                    p.x = width + 6;
                } else if (p.x > width + 8) {
                    p.x = -6;
                }

                const alpha = 0.2 + Math.abs(Math.sin(p.twinkle)) * 0.45;
                ctx.beginPath();
                ctx.fillStyle = `rgba(94, 234, 212, ${alpha})`;
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fill();
            }

            rafId = window.requestAnimationFrame(draw);
        };

        resize();
        rafId = window.requestAnimationFrame(draw);

        window.addEventListener(
            'resize',
            () => {
                stopLoop();
                resize();
                rafId = window.requestAnimationFrame(draw);
            },
            { passive: true },
        );
    })();
</script>
