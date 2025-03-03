<x-slot name="header">
    <div class="flex justify-between">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cake Day Schedule
            </h2>
        </div>
        <div>
            @if (!Auth::check())
                <a class="underline text-sm text-gray-600 hover:text-gray-900"
                   href="{{ route('login') }}">
                    <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Login
                    </button>
                </a>
            @else
                <a class="underline text-sm text-gray-600 hover:text-gray-900"
                   href="{{ route('logout') }}">
                    <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Log Out
                    </button>
                </a>
            @endif
        </div>
    </div>
</x-slot>
<div class="py-12 clear-both">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                     role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if (Auth::check())
                <button wire:click="upload()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Upload
                    Developer's
                    Birth Date
                </button>
                @if($isOpen)
                    @include('livewire.upload')
                @endif
            @endif

            <table class="table-fixed w-full">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 w-48 text-center">Date</th>
                    <th class="px-4 py-2 text-center">Small Cakes</th>
                    <th class="px-4 py-2 text-center">Large Cakes</th>
                    <th class="px-4 py-2 text-center">Developers</th>
                </tr>
                </thead>
                <tbody>
                @if($cakeEvents)
                    @foreach ($cakeEvents as $event)
                        <tr>
                            <td class="border px-4 py-2 w-48 text-center">{{  $event['cake_date'] }}</td>
                            <td class="border px-4 py-2 text-center">{{$event['small_cakes'] }}</td>
                            <td class="border px-4 py-2 text-center">{{ $event['large_cakes'] }}</td>
                            <td class="border px-4 py-2 text-center"></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
