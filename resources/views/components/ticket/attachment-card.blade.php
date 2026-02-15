@props([
    'file' => '',
    'bgColor' => 'bg-white',
    'textColor' => 'text-dark',
])

<a href="{{ asset('storage/' . $file) }}" target="_blank"
    class="text-decoration-none">
    <div class="card border rounded p-2 d-flex flex-row align-items-center gap-2 small {{ $bgColor }}">
        <span class="material-icons-outlined {{ $textColor }}">description</span>
        <span class="text-truncate" style="max-width: 150px;">{{ basename($file) }}</span>
    </div>
</a>
