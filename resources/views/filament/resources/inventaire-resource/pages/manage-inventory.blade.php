<x-filament-panels::page>

<form wire:submit.prevent="save">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 bg-gray-50">
                        Produit
                    </th>
                    <th scope="col" class="px-6 py-3 bg-gray-50">
                        Stock Logiciel
                    </th>
                    <th scope="col" class="px-6 py-3 bg-gray-50">
                        Stock Physique Réel
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produits as $produit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $produit->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $produit->available_quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" name="stock_physique[{{ $produit->id }}]" class="border-gray-300 rounded-md shadow-sm" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Valider
            </button>
        </div>
    </form>
</x-filament-panels::page>
