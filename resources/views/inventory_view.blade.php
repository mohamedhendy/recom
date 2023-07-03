<?php
use Carbon\Carbon;
?>

<h1>Inventory</h1>

<p>
    Created: <?php echo Carbon::now(); ?>
</p>

<table width="100%">
    <thead>
        <tr>
            <th align="center">Produktname</th>
            <th align="center">Anzahl</th>
            <th align="center">Preis</th>
            <th align="center">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalAmount = 0;

        @endphp
        @foreach($inventoryProducts as $item)
            @php
                $totalAmount += $item->unit_cost * $item->available_qty;
            @endphp
            <tr>
                <td >{{ $item->product->name }}</td>
                <td align="center">{{ $item->available_qty }}</td>
                <td align="center">@money($item->unit_cost)</td>
                <td align="center">@money($item->unit_cost * $item->available_qty)</td>
            </tr>
        @endforeach

        <tr>
            <th align="center"></th>
            <th align="center"></th>
            <th align="center"></th>
            <th align="center" style="font-size:25px">@money($totalAmount)</th>
        </tr>
    </tbody>
</table>
