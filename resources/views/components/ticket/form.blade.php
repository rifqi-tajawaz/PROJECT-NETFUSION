@props([
    'action' => '',
    'method' => 'POST',
])

<form action="{{ $action }}" method="{{ $method }}" enctype="multipart/form-data">
    @csrf
    {{ $slot }}
</form>
