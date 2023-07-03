<?php

namespace App\Jobs\SaleOrder;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StoreSaleOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Model
     */
    private $identity;
    private $products;
    /**
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param  $identity
     * @param array    $products
     * @param array    $data
     */
    public function __construct($identity, $products = [], $data = [])
    {
        //
        $this->identity = $identity;
        $this->products = collect($products);
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->products) {
            $saleData = array_merge([
                'number' => Carbon::now()->toDateTimeString(),
                'created_by_id' => Auth::id()
            ], $this->data);
            $saleOrder = $this->identity->saleOrders()->create($saleData);
            StoreSaleOrderProductsJob::dispatchNow($saleOrder, $this->products);
            return $saleOrder;
        }
    }
}
