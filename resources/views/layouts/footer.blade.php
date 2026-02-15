<footer class="footer mt-auto border-top"
  style="padding-top: 0.875rem; padding-bottom: 0.875rem; border-color: var(--nf-sidebar-border) !important; background-color: var(--nf-sidebar-bg);">
  <div
    class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">

    {{-- Left Side: Copyright --}}
    <p class="mb-0 text-secondary fw-medium small">
      {{ __('common.copyright') }} &copy; <span class="fw-bold text-primary">{{ date('Y') }}</span> NetFusion
      <span class="opacity-25 mx-1">|</span>
      <span class="opacity-75">Tajawaz Solutions.</span>
    </p>

    {{-- Right Side: Links & Version --}}
    <div class="d-flex align-items-center justify-content-center gap-3 mt-2 mt-md-0">

      <a href="{{ route('support') }}"
        class="text-secondary text-decoration-none small hover-primary transition-all fw-medium">
        {{ __('common.support') }}
      </a>

      <span class="text-secondary opacity-25 small">|</span>

      <a href="{{ route('documentation') }}"
        class="text-secondary text-decoration-none small hover-primary transition-all fw-medium">
        {{ __('common.documentation') }}
      </a>

      <div class="vr mx-1 opacity-25 text-secondary d-none d-md-block" style="height: 14px;"></div>

      <span
        class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 rounded-pill px-3 py-1 fw-bold font-monospace"
        style="font-size: 0.7em;">
        v2.5.0
      </span>
    </div>
  </div>
</footer>
<!--end footer-->