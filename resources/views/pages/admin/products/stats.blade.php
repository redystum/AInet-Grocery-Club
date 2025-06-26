@extends('pages.layouts.admin')

@section('title', 'Estatísticas dos Produtos')

@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-6">Estatísticas dos Produtos</h1>
    <ul class="list-disc pl-6 space-y-2">
        <li><strong>Total de produtos:</strong> {{ $totalProducts }}</li>
        <li><strong>Produto mais vendido:</strong> {{ $topProductName }} ({{ $topProductSold }} unidades)</li>
        <li><strong>Stock médio:</strong> {{ $avgStock }}</li>
        <li><strong>Total de unidades vendidas:</strong> {{ $totalSold }}</li>
    </ul>
</div>
@endsection