@extends('layouts.app')



@section('content')
    <x-page-header title="Queue Optimizer"
        subtitle="Script to reorder Simple Queues (e.g. move dynamic Hotspot queues to bottom/top).">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-sort-numeric-down me-1"></i> Auto-Sort
            </div>
        </x-slot>
    </x-page-header>

    <x-card class="p-5 text-center">
        <div class="py-4">
            <h4 class="fw-bold text-dark mb-3">Simple Queue Sorter</h4>
            <p class="text-secondary opacity-75 mb-4">This tool provides a RouterOS script to automatically sort queues.</p>

            <div class="mt-3 p-4 bg-dark bg-opacity-100 rounded text-start font-monospace text-warning shadow-sm"
                style="max-width: 800px; margin: 0 auto;">
                <span class="text-white-50"># Sort Simple Queues Script</span><br>
                <span class="text-white-50"># Place this in System -> Scripts and run via Scheduler</span><br><br>
                <span class="text-primary">:foreach</span> i <span class="text-primary">in</span>=[/queue simple find] <span
                    class="text-primary">do</span>={<br>
                &nbsp;&nbsp;<span class="text-info">:local</span> name [/queue simple get $i name];<br>
                &nbsp;&nbsp;<span class="text-white-50"># Example logic: Move specific queues to top</span><br>
                &nbsp;&nbsp;<span class="text-primary">:if</span> ($name ~ <span class="text-success">"VIP"</span>) <span
                    class="text-primary">do</span>={<br>
                &nbsp;&nbsp;&nbsp;&nbsp;/queue simple move $i 0;<br>
                &nbsp;&nbsp;}<br>
                }
            </div>
        </div>
    </x-card>
@endsection
