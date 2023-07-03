{{ $product->name }}

@foreach ($deployments as $deployment)
@php
    $deployment = collect($deployment);
@endphp
@if(trim($deployment->get('a_number'))) A-Nr.: {{trim($deployment->get('a_number'))}} - @endif @if(trim($deployment->get('serial_number')))S/N: {{trim($deployment->get('serial_number'))}} - @endif @if(trim($deployment->get('description'))) Notiz: {{trim($deployment->get('description'))}} @endif
@endforeach
{{--{{trim($deployment->get('id',null))}} ---}}
