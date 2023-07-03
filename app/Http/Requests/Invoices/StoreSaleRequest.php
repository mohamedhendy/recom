<?php

namespace App\Http\Requests\Invoices;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Throwable;

class StoreSaleRequest extends FormRequest
{
    use SaleRuleValidation;

    public function rules(): array
    {
        $invoiceRules = $this->invoiceRules();

        $additionalRules = [];

        if ($this->getInvoiceType() == 'invoice') {
            $additionalRules = [
                // 'issue_date' => 'required|date', /// match = id
                // 'due_date' => 'required|date', /// match = id
                // 'supplier_id' => 'required|integer|exists:suppliers,id',
            ];
        }
        return array_merge($invoiceRules, $additionalRules);
        # code...
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return RedirectResponse|Redirector
     * @throws Throwable
     */
    public function store()
    {

        DB::beginTransaction();

        try {
            $invoice = $this->createInvoice();
            $this->createArticlesAndGetTotalAmount($invoice);
            DB::commit();


            if ($this->has('redirect_to') && $this->filled('redirect_to')) {
                $redirectTo = $this->input('redirect_to');

                if ($redirectTo === 'qrcodes') return redirect('/articles/' . $invoice->id . '/qrcodes');
            }

            if ($this->has('redirect_to') && $this->filled('redirect_to')) {
                $redirectTo = $this->input('redirect_to');

                if ($redirectTo === 'qrcodes') return redirect('/articles/' . $invoice->id . '/qrcodes');
            }

            return redirect('/sales');

        } catch (QueryException $ex) {
            DB::rollBack();
            throw $ex;
        } catch (Throwable $e) {
            DB::rollBack();
            throw  $e;
        }
    }

    /**
     * @return mixed
     */
    private function createInvoice()
    {
        $data = $this->only(
            'supplier_id',
            'due_date',
            'issue_date'
        );
        $data['type'] = $this->getInvoiceType();
        $creationDate = $this->getInvoiceCreationDate();

        $data['number'] = $creationDate->format("Y") . '-' . Invoice::nextInvoiceNumber($this->getInvoiceCreationDate());
        $data['creation_year'] = $creationDate;
        $data['internal_id'] = $this->has('internal_id') && $this->filled('internal_id') ? $this->input('internal_id') : $data['number'];
        return $this->user()->createdInvoices()->create($data);
    }

    private function createArticlesAndGetTotalAmount(Invoice $invoice)
    {
        foreach ((array)$this->input('articles') as $key => $article) {
            $this->createArticle($invoice, $key, $article);
        }
    }

    /**
     * @return Carbon
     */
    public function getInvoiceCreationDate(): Carbon
    {
        $invoiceYear = $this->input('invoice_year');

        if ($invoiceYear && $invoiceYear != "undefined" && $date = Carbon::createFromDate($invoiceYear)) {
            return $date;
        }


        return Carbon::now();
    }
}
