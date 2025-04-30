<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestão de Chamados</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <x-layout>
            <x-header />

            <main class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <x-stats-card
                        title="Chamados Abertos"
                        :value="$openTickets"
                        color="indigo"
                    >
                        <x-icons.user-group />
                    </x-stats-card>

                    <x-stats-card
                        title="Chamados Resolvidos"
                        :value="$resolvedTickets"
                        color="green"
                    >
                        <x-icons.check-circle />
                    </x-stats-card>

                    <x-stats-card
                        title="Em Andamento"
                        :value="$inProgressTickets"
                        color="yellow"
                    >
                        <x-icons.clock />
                    </x-stats-card>
                                </div>

                <x-ticket-list :tickets="$recentTickets" />
                    </main>

            <footer class="mt-8 bg-white">
                <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <p class="text-sm text-center text-gray-500">
                        &copy; {{ date('Y') }} Gestão de Chamados. Todos os direitos reservados.
                    </p>
                </div>
            </footer>
        </x-layout>
    </body>
</html>
