@php
    $endpoints = json_decode(file_get_contents(resource_path($file)), true);
@endphp

@foreach ($endpoints as $endpoint)
    <div class="mb-12">
        <h2 class="text-xl font-semibold {{ $endpoint['method'] == 'GET' ? 'text-red-700' : 'text-green-700' }} mb-1">
            {{ $endpoint['method'] }} <code
                class="{{ $endpoint['method'] == 'GET' ? 'text-red-800 bg-red-100' : 'text-green-800 bg-green-100' }} px-1 rounded">{{ $endpoint['path'] }}</code>
        </h2>
        <p class="text-sm text-gray-600 mb-4">{{ $endpoint['title'] }} — {{ $endpoint['description'] }}</p>

        {{-- Request --}}
        @if (!empty($endpoint['request']))
            @php
                $reqId = 'req_' . ($endpoint['id'] ?? uniqid());
            @endphp
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-5 rounded-xl shadow mb-6 relative">
                <h3 class="text-sm font-semibold text-yellow-800 mb-2">📤 Request Body</h3>

                {{-- Tombol Copy --}}
                <button onclick="copyRequest('{{ $reqId }}')"
                    class="absolute top-4 right-4 text-xs bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded shadow">
                    Copy
                </button>

                <div class="bg-white border rounded p-4 overflow-x-auto text-sm relative">
                    <pre id="req-{{ $reqId }}">{{ json_encode($endpoint['request'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        @endif


        {{-- Response --}}
        <div class="bg-green-50 border-l-4 border-green-500 p-5 rounded-xl shadow mb-6">
            <h3 class="text-sm font-semibold text-green-800 mb-2">📥 Response</h3>
            <div class="bg-white border rounded p-4 overflow-x-auto text-sm">
                <pre>{{ json_encode($endpoint['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>
@endforeach
{{-- @push('script') --}}
<script>
    function copyRequest(id) {
        const pre = document.getElementById('req-' + id);
        console.log(pre)
        if (!pre) return;

        // Salin text dari <pre>
        const text = pre.innerText;
        navigator.clipboard.writeText(text)
            .then(() => {
                alert('Request body berhasil disalin!');
            })
            .catch(err => console.error('Gagal menyalin: ', err));
    }
</script>
{{-- @endpush --}}
