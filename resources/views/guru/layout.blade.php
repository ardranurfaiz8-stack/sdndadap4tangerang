@extends('layouts.app')

@push('styles')
<style>
    .card-hd{padding:1rem 1.25rem;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem}
    .tw{overflow-x:auto}
    .g2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .fg{margin-bottom:.75rem}
    .fl{display:block;font-size:.78rem;font-weight:600;color:var(--gray-700);margin-bottom:4px}
    .fc{width:100%;padding:.55rem .75rem;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:.83rem;color:var(--gray-900);outline:none;background:#fff;transition:border-color .2s}
    .fc:focus{border-color:var(--red);box-shadow:0 0 0 3px rgba(192,57,43,.1)}
    .mo{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center}
    .modal{background:#fff;border-radius:16px;width:100%;max-width:520px;margin:1rem;box-shadow:var(--shadow-lg);max-height:90vh;overflow-y:auto}
    .mh{display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100)}
    .mt{font-size:1rem;font-weight:700}
    .mx{background:var(--gray-100);border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center}
    .mb{padding:1.25rem 1.5rem}
    .mf{padding:1rem 1.5rem;border-top:1px solid var(--gray-100);display:flex;gap:.75rem;justify-content:flex-end}
    .fw7{font-weight:700}
    .tm{font-size:.82rem;color:var(--gray-500)}
    .flex{display:flex}
    .gap2{gap:.5rem}
    .gap3{gap:.75rem}
    .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.25rem}
    .si{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
    .si-g{background:#E8F5E9}.si-y{background:#FFF8E1}.si-b{background:#E3F2FD}.si-r{background:var(--red-pale)}
    .sv{font-size:1.5rem;font-weight:800;color:var(--gray-900);line-height:1}
    .sl{font-size:.72rem;color:var(--gray-500);margin-top:2px}
    .av{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0}
    .scanner-box{background:#1a1a1a;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;position:relative;overflow:hidden;padding:20px}
    .scanner-corner{position:absolute;width:20px;height:20px;border:3px solid var(--red)}
    .sc-tl{top:10px;left:10px;border-right:none;border-bottom:none}
    .sc-tr{top:10px;right:10px;border-left:none;border-bottom:none}
    .sc-bl{bottom:10px;left:10px;border-right:none;border-top:none}
    .sc-br{bottom:10px;right:10px;border-left:none;border-top:none}
    .scanner-line{position:absolute;left:10px;right:10px;height:2px;background:var(--red);top:50%;opacity:0;transition:opacity .3s}
    .scanner-line.active{opacity:1;animation:scanAnim 1.5s ease-in-out infinite}
    @keyframes scanAnim{0%,100%{top:15%}50%{top:85%}}
    .qr-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px}
    .qr-card{background:#fff;border:1px solid var(--gray-200);border-radius:12px;padding:14px;text-align:center}
    .qr-av{width:36px;height:36px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;margin:0 auto 8px}
    .qr-name{font-size:12.5px;font-weight:700;color:var(--gray-900)}
    .qr-sub{font-size:11px;color:var(--gray-500);margin-bottom:8px}
    .qr-nip{font-size:10px;color:var(--gray-400);font-family:monospace;margin-top:6px}
    .badge.bg-g{background:#E8F5E9;color:#2E7D32}
    .badge.bg-y{background:#FFF8E1;color:#F57F17}
    .badge.bg-b{background:#E3F2FD;color:#1565C0}
    .badge.bg-r{background:var(--red-pale);color:var(--red)}
    .btn-r{background:linear-gradient(135deg,var(--red),var(--red-light));color:#fff}
    .btn-r:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(192,57,43,.35);color:#fff}
    .btn-s{background:var(--gray-100);color:var(--gray-700)}
    .btn-sm{padding:.4rem .7rem;font-size:.78rem}
    .empty{text-align:center;padding:2rem;color:var(--gray-400)}
    .ei{font-size:2.5rem;margin-bottom:.5rem}
    .prog{height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden}
    .prog-b{height:100%;border-radius:3px}
    .pb-g{background:#2ecc71}.pb-b{background:#3498db}.pb-r{background:#e74c3c}
    .tg{color:#2E7D32}.tb2{color:#1565C0}.tr{color:var(--red)}
    .card-sub{font-size:.78rem;color:var(--gray-500)}
    @media(max-width:768px){.stat-grid{grid-template-columns:repeat(2,1fr)}.g2{grid-template-columns:1fr}}
    @media print{.no-print{display:none!important}}
</style>

<script>
function openModal(id){
    var el = document.getElementById(id);
    if(el){ el.style.display = 'flex'; }
}
function closeModal(id){
    var el = document.getElementById(id);
    if(el){ el.style.display = 'none'; }
}
function showToast(msg){
    alert(msg);
}

document.addEventListener('mousedown', function(e){
    if(e.target && e.target.classList.contains('mo')){
        e.target.style.display = 'none';
    }
});
</script>
@endpush