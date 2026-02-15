@props([
    'sectionId' => '',
    'faq' => null,
])

<div class="accordion-item border shadow-sm rounded-3 overflow-hidden bg-white mb-3">
    <h2 class="accordion-header" id="heading-{{ $faq->id }}">
        <button class="accordion-button collapsed fw-bold bg-white p-4" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}"
            aria-expanded="false">
            {{ $faq->question }}
        </button>
    </h2>
    <div id="collapse-{{ $faq->id }}" class="accordion-collapse collapse"
        data-bs-parent="#accordion-{{ $sectionId }}">
        <div class="accordion-body bg-white border-top p-4 text-secondary pt-0 lh-lg">
            {!! nl2br(e($faq->answer)) !!}
        </div>
    </div>
</div>
