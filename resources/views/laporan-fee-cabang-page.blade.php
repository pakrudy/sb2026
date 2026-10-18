<?php
/**
 * Blade layout for the Total Fee per Cabang report.
 * This page wraps the Livewire component and provides the standard app layout.
 */
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Total Fee per Cabang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('laporan-fee-cabang')
        </div>
    </div>
</x-app-layout>
