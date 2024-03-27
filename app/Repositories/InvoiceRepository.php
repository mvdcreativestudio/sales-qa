<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceRepository
{
  public function create()
  {
    return Invoice::create();
  }

  public function update(Invoice $invoice, array $data): Invoice
  {
    return $invoice->update($data);
  }

  public function delete(Invoice $invoice): void
  {
    $invoice->delete();
  }

  public function getAll()
  {
    return Invoice::all();
  }

  public function findById($id): ?Invoice
  {
    return Invoice::find($id);
  }

  
}
