<?php

namespace App\Jobs\SaleOrder;

use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSaleOrderProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;
    private $data;
    /**
     * @var null
     */
    private $identity;
    /**
     * @var null
     */
    private $identityType;

    /**
     * Create a new job instance.
     *
     * @param SaleOrderProduct $saleOrderProduct
     * @param $data
     * @param null $identity
     * @param null $identityType
     */
    public function __construct(SaleOrderProduct $saleOrderProduct, $data, $identity = null,$identityType = null)
    {
        //
        $this->saleOrderProduct = $saleOrderProduct;
        $this->data = $data;
        $this->identity = $identity;
        $this->identityType = $identityType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->saleOrderProduct->update($this->data);
        if ($this->identity) {
            $this->saleOrderProduct->saleOrder->update([
                'identity_type' => $this->identityType,
                'identity_id' => $this->identity->id
            ]);
        }
    }
}
