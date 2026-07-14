    </div><!-- #perfex-content -->

    <div id="perfex-footer">
        &copy; <?php echo date('Y'); ?> <?php echo View::e(bh_setting('company_name', BH_APP_NAME)); ?> &mdash; v<?php echo BH_VERSION; ?>
    </div>
</div><!-- #perfex-main -->

</div><!-- #perfex-wrapper -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo BH_ASSETS_URL; ?>/js/app.js?v=<?php echo BH_VERSION; ?>"></script>
<script>
(function($){
    // ── Sidebar toggle ──────────────────────────────────────
    $('#sidebarToggle').on('click', function(){
        $('body').toggleClass('sidebar-mobile-open');
    });
    $('#mobileSidebarOverlay').on('click', function(){
        $('body').removeClass('sidebar-mobile-open');
    });

    // ── CSRF on all AJAX ────────────────────────────────────
    $.ajaxSetup({ headers: { 'X-CSRF-Token': '<?php echo Csrf::token(); ?>' } });

    // ── Confirm delete ──────────────────────────────────────
    $(document).on('click','[data-confirm]',function(e){
        if(!confirm($(this).data('confirm')||'Are you sure?')){ e.preventDefault(); return false; }
    });

    // ── Auto-dismiss alerts ─────────────────────────────────
    setTimeout(function(){
        $('[data-auto-dismiss]').fadeOut(400, function(){ $(this).remove(); });
    }, 5000);

    // ── Tab switching ───────────────────────────────────────
    $(document).on('click','.tab-link',function(e){
        e.preventDefault();
        var target = $(this).data('tab');
        $(this).closest('.bh-tabs-wrap').find('.tab-link').removeClass('active');
        $(this).closest('.bh-tabs-wrap').find('.tab-content').removeClass('active');
        $(this).addClass('active');
        $('#'+target).addClass('active');
        try { sessionStorage.setItem('bh_tab_'+window.location.pathname, target); } catch(ex){}
    });
    // Restore tab
    try {
        var st = sessionStorage.getItem('bh_tab_'+window.location.pathname);
        if (st && $('#'+st).length) $('[data-tab="'+st+'"]').trigger('click');
    } catch(ex){}

    // ── Module toggle AJAX ──────────────────────────────────
    $(document).on('change','.bh-module-toggle',function(){
        var $t = $(this), mid = $t.data('module'), active = $t.is(':checked') ? 1 : 0;
        $.post('<?= BH_APP_URL ?>/settings/modules/toggle', {module_id:mid, active:active, _csrf: $t.data('csrf')});
    });

    // ── Dropdown helper ─────────────────────────────────────
    function closeAll() {
        $('.header-dropdown-panel').removeClass('open');
    }
    $(document).on('click', function(e){
        if (!$(e.target).closest('.header-dropdown').length) closeAll();
    });
    $('#notifBtn').on('click', function(e){
        e.stopPropagation();
        var $p = $('#notifPanel');
        var was = $p.hasClass('open');
        closeAll();
        if (!was) $p.addClass('open');
    });
    $('#userChipBtn').on('click', function(e){
        e.preventDefault(); e.stopPropagation();
        var $p = $('#userMenuPanel');
        var was = $p.hasClass('open');
        closeAll();
        if (!was) $p.addClass('open');
    });
    $(document).on('keydown', function(e){ if(e.key==='Escape') closeAll(); });

    // ── Search ⌘K ───────────────────────────────────────────
    $(document).on('keydown', function(e){
        if ((e.ctrlKey||e.metaKey) && e.key==='k') {
            e.preventDefault();
            $('#perfexSearchInput').focus().select();
        }
    });

})(jQuery);
</script>
<?php if (isset($extra_js)) echo $extra_js; ?>

<?php if (Auth::isStaff() && class_exists('DB')): ?>
<?php try { $last_tid = (int)(DB::val('SELECT MAX(id) FROM bh_tickets') ?? 0); } catch(\Throwable $e) { $last_tid = 0; } ?>
<script>
(function() {
    var lastId = <?= $last_tid ?>;
    var beepOn = (localStorage.getItem('bh_ticket_beep') !== 'off');

    function bhGlobalBeep() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain); gain.connect(ctx.destination);
            osc.type = 'sine'; osc.frequency.value = 880;
            gain.gain.setValueAtTime(0.4, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
            osc.start(ctx.currentTime); osc.stop(ctx.currentTime + 0.4);
        } catch(e) {}
    }

    function bhGlobalBanner(t) {
        var id = 'bh-global-ticket-banner';
        var $b = document.getElementById(id);
        if (!$b) {
            $b = document.createElement('div');
            $b.id = id;
            $b.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:99999;background:#1e293b;color:#fff;border-radius:10px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,.3);font-size:13px;display:flex;align-items:center;gap:12px;max-width:360px;cursor:pointer;';
            $b.innerHTML = '<i class="fa-solid fa-ticket" style="color:#6366f1;font-size:18px;flex-shrink:0;"></i>'
                + '<div><div style="font-weight:700;margin-bottom:2px;">New Ticket</div>'
                + '<div id="bh-global-ticket-text" style="color:#94a3b8;font-size:12px;"></div></div>'
                + '<i class="fa-solid fa-xmark" style="margin-left:auto;color:#64748b;cursor:pointer;" id="bh-global-banner-close"></i>';
            document.body.appendChild($b);
            $b.addEventListener('click', function(e) {
                if (e.target.id === 'bh-global-banner-close') { $b.remove(); return; }
                window.location.href = '<?= BH_APP_URL ?>/tickets/' + $b.dataset.tid;
            });
        }
        $b.dataset.tid = t.id;
        document.getElementById('bh-global-ticket-text').textContent = '#' + t.id + ' — ' + (t.subject || '').substring(0, 60);
        $b.style.display = 'flex';
        clearTimeout($b._timer);
        $b._timer = setTimeout(function() { if ($b.parentNode) $b.remove(); }, 15000);
    }

    function bhGlobalPoll() {
        fetch('<?= BH_APP_URL ?>/tickets/ajax/poll-new?since=' + lastId + '&_csrf=<?= Csrf::token() ?>', {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success && d.data && d.data.tickets && d.data.tickets.length) {
                d.data.tickets.forEach(function(t) {
                    if (t.id > lastId) lastId = t.id;
                    if (beepOn) bhGlobalBeep();
                    bhGlobalBanner(t);
                });
            }
        })
        .catch(function() {});
    }

    // Start polling after 10 seconds, then every 30 seconds
    setTimeout(function() {
        bhGlobalPoll();
        setInterval(bhGlobalPoll, 30000);
    }, 10000);
})();
</script>
<?php endif; ?>


</body>
</html>
<script>
(function(){
var inp=document.getElementById('global-search-input');
var box=document.getElementById('global-search-results');
if(!inp)return;
var BASE='<?= BH_APP_URL ?>';
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;');}
function icon(t){var m={'client':'fa-building','ticket':'fa-headset','invoice':'fa-file-invoice-dollar','contact':'fa-user','kb':'fa-book'};var c={'client':'#3b82f6','ticket':'#f59e0b','invoice':'#22c55e','contact':'#8b5cf6','kb':'#06b6d4'};return '<i class="fa-solid '+(m[t]||'fa-circle')+'" style="color:'+(c[t]||'#9ca3af')+';width:16px;text-align:center;flex-shrink:0;"></i>';}
function render(r){if(!r.length){box.innerHTML='<div style="padding:20px;text-align:center;color:#9ca3af;">No results found.</div>';box.style.display='block';return;}var h='',g={};r.forEach(function(x){if(!g[x.type])g[x.type]=[];g[x.type].push(x);});Object.keys(g).forEach(function(t){h+='<div style="padding:6px 12px 2px;font-size:11px;font-weight:700;text-transform:uppercase;color:#9ca3af;">'+t.charAt(0).toUpperCase()+t.slice(1)+'s</div>';g[t].forEach(function(x){h+='<a href="'+esc(x.url)+'" style="display:flex;align-items:center;gap:10px;padding:9px 14px;text-decoration:none;color:#111827;border-top:1px solid #f3f4f6;font-size:14px;">'+icon(x.type)+'<div style="flex:1;min-width:0;"><div style="font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'+esc(x.label)+'</div>'+(x.sub?'<div style="font-size:12px;color:#9ca3af;">'+esc(x.sub)+'</div>':'')+'</div></a>';});});box.innerHTML=h;box.style.display='block';}
function doSearch(){var q=inp.value.trim();if(q.length<2)return;box.innerHTML='<div style="padding:16px;text-align:center;color:#9ca3af;">Searching...</div>';box.style.display='block';fetch(BASE+'/ajax/global-search?q='+encodeURIComponent(q)).then(function(r){return r.json();}).then(function(d){if(d.success)render(d.data);}).catch(function(){box.innerHTML='<div style="padding:16px;color:#ef4444;">Error searching.</div>';});}
var btn=document.getElementById('global-search-btn');
if(btn)btn.addEventListener('click',doSearch);
inp.addEventListener('keydown',function(e){if(e.key==='Enter')doSearch();if(e.key==='Escape'){box.style.display='none';inp.value='';}});
var timer;inp.addEventListener('input',function(){clearTimeout(timer);timer=setTimeout(doSearch,300);});
document.addEventListener('click',function(e){if(e.target!==inp&&e.target!==btn&&!box.contains(e.target))box.style.display='none';});
})();
</script>
