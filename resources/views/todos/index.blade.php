@extends('layouts.base')

@section('title', 'Todos')

@section('content')
    <div x-data="{ openCreate: false, openEdit: false, editTodo: null, openDelete: false, deleteTodo: null }" class="flex">
        <div class="w-3/4 p-6">

            <div class="flex justify-end mb-6">
                <button @click="openCreate = true" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Create New</button>
            </div>

            <div class="mb-10">
                <h2 class="text-xl font-bold mb-4">Huidige taken</h2>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($currentTodo as $todo)
                        <div class="bg-blue-200 p-4 rounded-lg shadow hover:shadow-lg relative">
                            <h3 class="text-lg font-bold">{{ $todo->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $todo->description }}</p>
                            <p class="text-xs text-gray-500 mt-2">Bijgewerkt: {{ $todo->updated_at->format('d-m-Y') }}</p>

                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button @click="openEdit = true; editTodo = {{ $todo }}" class="text-yellow-600 hover:text-yellow-800">
                                    ✏️
                                </button>
                                <button @click="openDelete = true; deleteTodo = {{ $todo }}" class="text-red-600 hover:text-red-800">
                                    🗑️
                                </button>
                                <form action="{{ route('todos.status', $todo) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="text-green-600 hover:text-green-800">
                                        ✅
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold mb-4">Afgemaakte taken</h2>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($completedTodo as $todo)
                        <div class="bg-green-200 p-4 rounded-lg shadow hover:shadow-lg">
                            <h3 class="text-lg font-bold">{{ $todo->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $todo->description }}</p>
                            <p class="text-xs text-gray-500 mt-2">Voltooid op: {{ $todo->updated_at->format('d-m-Y') }}</p>

                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button @click="openEdit = true; editTodo = {{ $todo }}" class="text-yellow-600 hover:text-yellow-800">
                                    ✏️
                                </button>
                                <button @click="openDelete = true; deleteTodo = {{ $todo }}" class="text-red-600 hover:text-red-800">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div x-data="pomodoroTimer" class="w-1/4 h-screen p-6 border-l border-gray-200 bg-gray-100 flex flex-col items-center sticky top-0">
            <h2 class="text-lg font-bold mb-4" x-text="phaseText"></h2>
            <div class="text-4xl font-mono mb-6" x-text="formattedTime"></div>

            <div class="space-y-4 w-full">
                <button @click="start" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 w-1/2">Start</button>
                <button @click="stop" class="bg-yellow-500 text-white px-4 py-2 rounded shadow hover:bg-yellow-600 w-1/2">Stop</button>
                <button @click="reset" class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 w-1/2">Reset</button>
                <button @click="skipPhase" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 w-1/2">Skip</button>

            </div>
        </div>

       <div x-show="openCreate" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center" x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-bold mb-4">Nieuwe taak</h3>
            <form action="{{ route('todos.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <input type="text" name="title" placeholder="Title" class="border border-gray-300 p-2 w-full rounded" required>
                </div>
                <div class="mb-4">
                    <input type="text" name="description" placeholder="Description" class="border border-gray-300 p-2 w-full rounded" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openCreate = false" class="bg-gray-500 text-white px-4 py-2 rounded">Annuleren</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Voeg toe</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEdit" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center" x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-bold mb-4">Taak aanpassen</h3>
            <form :action="`/todos/${editTodo.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <input type="text" name="title" x-model="editTodo.title" class="border border-gray-300 p-2 w-full rounded" required>
                </div>
                <div class="mb-4">
                    <input type="text" name="description" x-model="editTodo.description" class="border border-gray-300 p-2 w-full rounded" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openEdit = false" class="bg-gray-500 text-white px-4 py-2 rounded">Annuleren</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Taak opslaan</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openDelete" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center" x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-bold mb-4">Taak verwijderen</h3>
            <p class="mb-4">Weet je zeker dat je deze taak wilt verwijderen?</p>
            <form :action="`/todos/${deleteTodo.id}`" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openDelete = false" class="bg-gray-500 text-white px-4 py-2 rounded">Annuleren</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Verwijderen</button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pomodoroTimer', () => ({
                time: 1500, 
                breakTime: 300, 
                interval: null,
                isWorking: true, 

                get phaseText() {
                    return this.isWorking ? 'Aan het werk!' : 'Pauze!';
                },

                get formattedTime() {
                    const minutes = Math.floor(this.time / 60);
                    const seconds = this.time % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                start() {
                    if (this.interval) return; 
                    this.interval = setInterval(() => {
                        if (this.time > 0) {
                            this.time--;
                        } else {
                            this.switchPhase();
                        }
                    }, 1000);
                },

                stop() {
                    clearInterval(this.interval);
                    this.interval = null;
                },

                reset() {
                    this.stop();
                    this.time = this.isWorking ? 1500 : 300; 
                },

                switchPhase() {
                    this.isWorking = !this.isWorking;
                    this.time = this.isWorking ? 1500 : 300; 
                },

                skipPhase() {
                    this.switchPhase(); 
                }
            }));
        });
    </script>
@endsection
