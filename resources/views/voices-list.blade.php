<div class="space-y-2">
    @foreach ($voices as $voice)
        <div class="flex items-center p-2 rounded-xl shadow-sm bg-white dark:bg-gray-800">
            {{-- Avatar --}}
            <img src="{{ $voice['gender'] === 'Male' ? asset('img/male.png') : asset('img/female.png') }}"
                 alt="Avatar" class="w-10 h-10 rounded-full"/>

            {{-- Thông tin --}}
            <div class="ml-3 flex-1">
                <div class="flex text-gray-900 dark:text-white">
                    <div class="">
                        {{ $voice['name'] }}
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $voice['countryName'] }}</p>
            </div>

            <div class="px-3 py-1 text-xs rounded-full
                        {{ $voice['gender'] === 'Male' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'bg-pink-100 text-pink-600 dark:bg-pink-900 dark:text-pink-300' }}">
                {{ $voice['gender'] }}
            </div>

            {{-- Nút Play --}}

            <button type="button"
                    onclick="playVoice('{{ $voice['id'] }}')"
                    class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-600"
            >
                ▶ Play
            </button>


        </div>
    @endforeach

</div>

<script>
    function playVoice(voiceId) {

    }
</script>
