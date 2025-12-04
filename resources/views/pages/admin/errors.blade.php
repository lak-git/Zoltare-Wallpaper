@section('title', 'Admin - Error Logs')
<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-10 space-y-8">
        <div>
            <h1 class="text-3xl font-bold">Error log</h1>
            <p class="text-slate-500">Latest application issues captured from uploads, payments, and downloads.</p>
        </div>

        <div class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 shadow divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($logEntries as $error)
                <article class="p-6">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span class="font-semibold text-red-500">{{ strtoupper($error->level ?? 'error') }}</span>
                        <span>{{ $error->created_at?->toDayDateTimeString() }}</span>
                    </div>
                    <p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $error->message }}</p>
                    @if ($error->context)
                        <pre class="mt-3 rounded-2xl bg-slate-100 dark:bg-slate-950/60 text-xs p-3 overflow-auto">{{ json_encode($error->context, JSON_PRETTY_PRINT) }}</pre>
                    @endif
                </article>
            @empty
                <p class="p-6 text-sm text-slate-500">No errors recorded.</p>
            @endforelse
        </div>

        {{ $logEntries->links() }}
    </div>
</x-app-layout>

