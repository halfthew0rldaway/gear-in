import './bootstrap';
import './animations';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

class AuroraField {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.pixelRatio = window.devicePixelRatio || 1;
        this.cursor = { x: 0, y: 0, active: false };
        this.nodes = [];
        this.frame = null;
        this.touchIdentifier = null;
        this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (this.prefersReducedMotion.matches) {
            canvas.style.display = 'none';
            return;
        }

        this.boundResize = this.resize.bind(this);
        this.boundPointerMove = this.handlePointerMove.bind(this);
        this.boundPointerLeave = this.handlePointerLeave.bind(this);
        this.boundVisibilityChange = this.handleVisibility.bind(this);

        this.updateConfig();
        this.resize();
        this.seedNodes();
        this.bindEvents();
        this.loop();
    }

    updateConfig() {
        const mobileQuery = window.matchMedia('(max-width: 768px)');
        const compact = mobileQuery.matches;
        this.config = {
            nodeCount: compact ? 60 : 90,
            radius: compact ? [0.7, 1.4] : [1.0, 2.0],
            velocity: compact ? 0.12 : 0.18,
            linkDistance: compact ? 120 : 180,
            cursorInfluence: compact ? 150 : 220,
            hueBase: compact ? 210 : 215,
        };
    }

    resize() {
        this.width = this.canvas.clientWidth || window.innerWidth;
        this.height = this.canvas.clientHeight || window.innerHeight;
        this.canvas.width = this.width * this.pixelRatio;
        this.canvas.height = this.height * this.pixelRatio;
        this.ctx.setTransform(this.pixelRatio, 0, 0, this.pixelRatio, 0, 0);
        this.updateConfig();
        if (this.nodes.length === 0) {
            this.seedNodes();
        }
    }

    seedNodes() {
        const rand = (min, max) => Math.random() * (max - min) + min;
        this.nodes = Array.from({ length: this.config.nodeCount }, () => ({
            x: rand(0, this.width),
            y: rand(0, this.height),
            vx: rand(-this.config.velocity, this.config.velocity),
            vy: rand(-this.config.velocity, this.config.velocity),
            radius: rand(this.config.radius[0], this.config.radius[1]),
            pulse: rand(0.8, 1.4),
        }));
    }

    bindEvents() {
        window.addEventListener('resize', this.boundResize, { passive: true });
        window.addEventListener('mousemove', this.boundPointerMove, { passive: true });
        window.addEventListener('touchmove', this.boundPointerMove, { passive: true });
        window.addEventListener('touchend', this.boundPointerLeave, { passive: true });
        window.addEventListener('mouseleave', this.boundPointerLeave, { passive: true });
        document.addEventListener('visibilitychange', this.boundVisibilityChange);
    }

    handlePointerMove(event) {
        const point = event.touches ? event.touches[0] : event;
        if (!point) return;
        const rect = this.canvas.getBoundingClientRect();
        this.cursor.x = point.clientX - rect.left;
        this.cursor.y = point.clientY - rect.top;
        this.cursor.active = true;
    }

    handlePointerLeave() {
        this.cursor.active = false;
    }

    handleVisibility() {
        if (document.hidden) {
            cancelAnimationFrame(this.frame);
            this.frame = null;
        } else if (!this.frame) {
            this.loop();
        }
    }

    updateNodes() {
        for (const node of this.nodes) {
            node.x += node.vx;
            node.y += node.vy;

            if (node.x < -50) node.x = this.width + 50;
            if (node.x > this.width + 50) node.x = -50;
            if (node.y < -50) node.y = this.height + 50;
            if (node.y > this.height + 50) node.y = -50;

            if (this.cursor.active) {
                const dx = this.cursor.x - node.x;
                const dy = this.cursor.y - node.y;
                const dist = Math.hypot(dx, dy);
                if (dist < this.config.cursorInfluence) {
                    const strength = (1 - dist / this.config.cursorInfluence) * 0.02;
                    node.vx -= dx * strength * 0.01;
                    node.vy -= dy * strength * 0.01;
                }
            }
        }
    }

    drawNodes() {
        const { linkDistance, cursorInfluence, hueBase } = this.config;
        this.ctx.clearRect(0, 0, this.width, this.height);

        // Bloomed halo background
        const gradient = this.ctx.createRadialGradient(
            this.width * 0.5,
            this.height * 0.45,
            0,
            this.width * 0.5,
            this.height * 0.5,
            Math.max(this.width, this.height) * 0.8
        );
        gradient.addColorStop(0, 'rgba(229, 231, 235, 0.06)');
        gradient.addColorStop(1, 'rgba(2, 6, 23, 0.0)');
        this.ctx.fillStyle = gradient;
        this.ctx.fillRect(0, 0, this.width, this.height);

        for (let i = 0; i < this.nodes.length; i++) {
            const a = this.nodes[i];

            for (let j = i + 1; j < this.nodes.length; j++) {
                const b = this.nodes[j];
                const dx = a.x - b.x;
                const dy = a.y - b.y;
                const dist = Math.hypot(dx, dy);

                if (dist < linkDistance) {
                    const alpha = (1 - dist / linkDistance) * 0.5;
                    this.ctx.strokeStyle = `hsla(${hueBase + dist * 0.06}, 12%, 74%, ${alpha})`;
                    this.ctx.lineWidth = 0.8;
                    this.ctx.beginPath();
                    this.ctx.moveTo(a.x, a.y);
                    this.ctx.lineTo(b.x, b.y);
                    this.ctx.stroke();
                }
            }

            if (this.cursor.active) {
                const dx = a.x - this.cursor.x;
                const dy = a.y - this.cursor.y;
                const dist = Math.hypot(dx, dy);
                if (dist < cursorInfluence) {
                    const alpha = (1 - dist / cursorInfluence) * 0.7;
                    this.ctx.strokeStyle = `rgba(209, 213, 219, ${alpha})`;
                    this.ctx.lineWidth = 1.2;
                    this.ctx.beginPath();
                    this.ctx.moveTo(a.x, a.y);
                    this.ctx.lineTo(this.cursor.x, this.cursor.y);
                    this.ctx.stroke();
                }
            }
        }

        for (const node of this.nodes) {
            const hue = hueBase + node.pulse * 3;
            const pulse = Math.sin(performance.now() / 1200 + node.pulse) * 0.35 + 0.65;
            this.ctx.beginPath();
            this.ctx.fillStyle = `hsla(${hue}, 8%, ${68 + pulse * 16}%, 0.95)`;
            this.ctx.shadowColor = `hsla(${hue}, 10%, 82%, 0.55)`;
            this.ctx.shadowBlur = 9;
            this.ctx.arc(node.x, node.y, node.radius * (0.8 + pulse * 0.6), 0, Math.PI * 2);
            this.ctx.fill();
        }

        this.ctx.shadowBlur = 0;
    }

    loop() {
        this.updateNodes();
        this.drawNodes();
        this.frame = requestAnimationFrame(() => this.loop());
    }

    destroy() {
        cancelAnimationFrame(this.frame);
        window.removeEventListener('resize', this.boundResize);
        window.removeEventListener('mousemove', this.boundPointerMove);
        window.removeEventListener('touchmove', this.boundPointerMove);
        window.removeEventListener('touchend', this.boundPointerLeave);
        window.removeEventListener('mouseleave', this.boundPointerLeave);
        document.removeEventListener('visibilitychange', this.boundVisibilityChange);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('node-background');
    if (!canvas) return;
    const field = new AuroraField(canvas);
    window.addEventListener('beforeunload', () => field.destroy());
});
