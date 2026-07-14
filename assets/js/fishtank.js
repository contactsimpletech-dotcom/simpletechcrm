/**
 * WebPSA Sidebar Fish Tank
 * Drop this into the bottom of header.php before </body>
 * Activated when bh_setting('sidebar_theme') === 'fishtank'
 */
(function() {
    if (typeof SIDEBAR_THEME === 'undefined' || SIDEBAR_THEME !== 'fishtank') return;

    var sidebar = document.getElementById('perfex-sidebar');
    if (!sidebar) return;

    // Create canvas
    var canvas = document.createElement('canvas');
    canvas.id = 'fishtank-canvas';
    canvas.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:0;opacity:0.35;';
    sidebar.style.position = 'relative';
    sidebar.insertBefore(canvas, sidebar.firstChild);

    // Make nav sit above canvas
    var navUl = document.getElementById('side-menu');
    if (navUl) navUl.style.position = 'relative';
    var logo = sidebar.querySelector('.sidebar-logo-area');
    if (logo) logo.style.position = 'relative';
    var profile = sidebar.querySelector('.sidebar-user-profile');
    if (profile) profile.style.position = 'relative';

    var ctx = canvas.getContext('2d');
    var W, H;

    function resize() {
        W = canvas.width  = sidebar.offsetWidth;
        H = canvas.height = sidebar.Height;
    }
    resize();
    window.addEventListener('resize', resize);

    // Fish emojis and colors
    var FISH_TYPES = ['🐠','🐟','🐡','🦈','🐙','🦑','🐬'];
    var BUBBLE_COLOR = 'rgba(255,255,255,0.6)';

    // Fish
    var fish = [];
    for (var i = 0; i < 8; i++) {
        fish.push({
            x: Math.random() * 230,
            y: Math.random() * 600,
            speed: 0.3 + Math.random() * 0.7,
            size: 14 + Math.floor(Math.random() * 16),
            type: FISH_TYPES[Math.floor(Math.random() * FISH_TYPES.length)],
            dir: Math.random() > 0.5 ? 1 : -1,
            wobble: Math.random() * Math.PI * 2,
            wobbleSpeed: 0.02 + Math.random() * 0.03,
            depth: 0.4 + Math.random() * 0.6,
        });
    }

    // Bubbles
    var bubbles = [];
    for (var j = 0; j < 15; j++) {
        bubbles.push({
            x: Math.random() * 230,
            y: Math.random() * 800,
            r: 1.5 + Math.random() * 3.5,
            speed: 0.2 + Math.random() * 0.4,
            drift: (Math.random() - 0.5) * 0.3,
        });
    }

    // Seaweed
    var weeds = [];
    for (var k = 0; k < 5; k++) {
        weeds.push({
            x: 20 + Math.floor(Math.random() * 190),
            segments: 4 + Math.floor(Math.random() * 4),
            phase: Math.random() * Math.PI * 2,
            color: k % 2 === 0 ? '#2d6a4f' : '#1b4332',
        });
    }

    var t = 0;

    function drawWeed(w) {
        var segH = 18;
        ctx.lineWidth = 4;
        ctx.strokeStyle = w.color;
        ctx.beginPath();
        var px = w.x, py = H;
        ctx.moveTo(px, py);
        for (var s = 0; s < w.segments; s++) {
            var sway = Math.sin(t * 0.8 + w.phase + s * 0.5) * 8;
            px += sway;
            py -= segH;
            ctx.lineTo(px, py);
        }
        ctx.stroke();
    }

    function animate() {
        resize();
        ctx.clearRect(0, 0, W, H);

        // Water gradient
        var grad = ctx.createLinearGradient(0, 0, 0, H);
        grad.addColorStop(0, 'rgba(0,40,80,0.7)');
        grad.addColorStop(1, 'rgba(0,20,50,0.9)');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, W, H);

        // Seaweed
        weeds.forEach(drawWeed);

        // Bubbles
        bubbles.forEach(function(b) {
            b.y -= b.speed;
            b.x += b.drift;
            if (b.y < -10) { b.y = H + 10; b.x = Math.random() * W; }
            ctx.beginPath();
            ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
            ctx.strokeStyle = BUBBLE_COLOR;
            ctx.lineWidth = 0.8;
            ctx.stroke();
        });

        // Fish
        fish.forEach(function(f) {
            f.wobble += f.wobbleSpeed;
            f.x += f.speed * f.dir * f.depth;
            f.y += Math.sin(f.wobble) * 0.5;

            if (f.x > W + 40)  { f.x = -40; f.y = 30 + Math.random() * (H - 60); }
            if (f.x < -40)     { f.x = W + 40; f.y = 30 + Math.random() * (H - 60); }
            if (f.y < 20)      f.y = 20;
            if (f.y > H - 20)  f.y = H - 20;

            ctx.save();
            ctx.translate(f.x, f.y);
            if (f.dir < 0) ctx.scale(-1, 1);
            ctx.font = f.size + 'px serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.globalAlpha = f.depth;
            ctx.fillText(f.type, 0, 0);
            ctx.restore();
        });

        // Light rays
        ctx.save();
        for (var r = 0; r < 4; r++) {
            var rx = 30 + r * 55 + Math.sin(t * 0.3 + r) * 10;
            var rayGrad = ctx.createLinearGradient(rx, 0, rx + 20, H * 0.7);
            rayGrad.addColorStop(0, 'rgba(255,255,200,0.07)');
            rayGrad.addColorStop(1, 'rgba(255,255,200,0)');
            ctx.fillStyle = rayGrad;
            ctx.beginPath();
            ctx.moveTo(rx, 0);
            ctx.lineTo(rx + 20, 0);
            ctx.lineTo(rx + 40 + Math.sin(t * 0.2) * 10, H * 0.7);
            ctx.lineTo(rx - 10, H * 0.7);
            ctx.closePath();
            ctx.fill();
        }
        ctx.restore();

        t += 0.016;
        requestAnimationFrame(animate);
    }

    animate();
})();
