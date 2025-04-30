@props(['tickets'])

<div class="mt-6 sm:mt-8">
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-4 py-4 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                Chamados Recentes
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start sm:items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($ticket->status === 'aberto') bg-red-100 text-red-800
                                        @elseif($ticket->status === 'em_progresso') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $ticket->title }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500 sm:mt-0">
                                        {{ $ticket->category->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-500 sm:mt-0">
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6">
                        <div class="text-center text-gray-500">
                            Nenhum chamado encontrado
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
