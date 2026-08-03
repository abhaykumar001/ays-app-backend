<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AYS Developer APP') }}</title>

    <link rel="icon" href="{{ asset('assets/dashboard/images/favicon.png') }}" type="image/x-icon">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

</head>

<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
    <div class="flex" x-data="{ sidebarOpen: window.innerWidth >= 768, sidebarShrink: false, hoverExpand: false }">
        @include('dashboard.layouts.sidebar')
        <!-- Main content -->
        <div class="md:ml-72 flex-1 flex flex-col overflow-hidden transition-all duration-300"
            :class="{
                'md:ml-72': sidebarOpen && !sidebarShrink,
                'md:ml-20': sidebarOpen && sidebarShrink,
                'md:ml-0': !
                    sidebarOpen
            }"
            >
            <!-- Top nav -->
            @include('dashboard.layouts.navigation')
            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @php
        $toastType = session('status') ?? ''; // default to success
        $toastMessage = session('message') ?? '';
        $toastColors = [
            'success' => 'bg-green-500 text-white',
            'error' => 'bg-red-500 text-white',
            'warning' => 'bg-yellow-500 text-gray-900',
            'info' => 'bg-blue-500 text-white',
        ];
    @endphp

    @if ($toastType)
        <div id="toast"
            class="fixed top-5 right-5 z-50 flex items-center p-4 mb-4 w-96 rounded-lg shadow-lg opacity-0 translate-x-10 transition-all duration-500 {{ $toastColors[$toastType] }}"
            role="alert">
            <div class="ml-3 text-sm font-normal">
                {{ $toastMessage }}
            </div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-white p-1.5 hover:opacity-90"
                onclick="document.getElementById('toast').classList.add('hidden')">
                <span class="sr-only">Close</span>
                &times;
            </button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toast = document.getElementById('toast');
                toast.classList.remove('opacity-0', 'translate-x-10');
                toast.classList.add('opacity-100', 'translate-x-0');

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-x-10');
                    toast.classList.remove('opacity-100', 'translate-x-0');
                    {{ session()->forget('status') }}
                    {{ session()->forget('message') }}
                }, 3000);
            });
        </script>
        {{-- Remove session so it doesn’t show again --}}
    @endif

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.editor').forEach(editorContainer => {
                const inputId = editorContainer.dataset.target;
                const hiddenInput = document.getElementById(inputId);
                if (!hiddenInput) return;

                // Capture any pre-rendered HTML placed inside the div (edit forms)
                const preloaded = editorContainer.innerHTML.trim();

                const quill = new Quill(editorContainer, {
                    theme: 'snow',
                    placeholder: 'Write your content here…',
                    modules: {
                        toolbar: [
                            [{ header: [1, 2, 3, 4, 5, 6, false] }],
                            [{ size: ['small', false, 'large', 'huge'] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ color: [] }, { background: [] }],
                            [{ align: [] }],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            [{ indent: '-1' }, { indent: '+1' }],
                            ['blockquote', 'code-block'],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });

                // Load existing content: prefer pre-rendered HTML in the div,
                // fall back to the hidden input value (e.g. news edit form).
                if (preloaded && preloaded !== '<p><br></p>') {
                    quill.clipboard.dangerouslyPasteHTML(preloaded);
                } else if (hiddenInput.value) {
                    quill.clipboard.dangerouslyPasteHTML(hiddenInput.value);
                }

                hiddenInput.value = quill.root.innerHTML;

                quill.on('text-change', () => {
                    hiddenInput.value = quill.root.innerHTML;
                });
            });
        });
    </script>
</body>

</html>
