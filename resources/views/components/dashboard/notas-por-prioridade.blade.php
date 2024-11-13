<div class="bg-gray-800 p-4 rounded-lg">
    <h3 class="text-lg font-semibold mb-4">Notas por Prioridade</h3>
    <div class="space-y-2">
        @foreach($notasPorPrioridade as $item)
            <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $item->prioridade->cor }}"></div>
                    <span class="text-gray-200">{{ $item->prioridade->nome }}</span>
                </div>
                <span class="bg-blue-600 px-2 py-1 rounded-full text-sm text-white">
                    {{ $item->total }}
                </span>
            </div>
        @endforeach
    </div>
</div> 
