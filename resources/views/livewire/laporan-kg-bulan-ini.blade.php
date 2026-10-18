<div class="p-6 max-w-4xl mx-auto bg-white rounded-xl shadow-md space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Total Kg Terkirim Bulan Ini per Kategori</h2>
    <div class="flex items-center space-x-4 mt-4">
        <label class="text-sm font-medium text-gray-600" for="monthSelect">Bulan:</label>
        <select wire:model.live="month" id="monthSelect" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
            @endfor
        </select>
        <label class="text-sm font-medium text-gray-600" for="yearSelect">Tahun:</label>
        <select wire:model.live="year" id="yearSelect" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

    <div class="mt-6">
        <table class="w-full text-left border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Kategori</th>
                    <th class="px-4 py-2 text-right">Total Kg</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium">ESB</td>
                    <td class="px-4 py-2 text-right">{{ number_format($totals['ESB'] ?? 0, 2) }} kg</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium">ELP</td>
                    <td class="px-4 py-2 text-right">{{ number_format($totals['ELP'] ?? 0, 2) }} kg</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
