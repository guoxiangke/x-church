<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bind') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <p class="p-4">请复制发送以下内容给牧师/AI机器人<br/>
                {{$socialId}}</p>
            </div>
        </div>
    </div>
</x-app-layout>
