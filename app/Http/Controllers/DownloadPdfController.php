<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Classes\CustomSeller;
use App\Models\Invoice as Facture;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use Carbon\Carbon;

class DownloadPdfController extends Controller
{
    public function telecharger(Facture $record)
    {
        
        
        $seller = new Seller("Code Bar", "Fidjrosse-plage", "23456780998");

        $customer = new Buyer ([

            'name' => $record->order->customer->name,
            'custom_fields' => [
                'IFU' => $record->order->customer->ifu,
                'email' => $record->order->customer->name,
                'contact' => $record->order->customer->contact,
            ],

        ]);

        $item = (new InvoiceItem()) ->title('Service 1')->pricePerUnit(2);

        $invoice = Invoice::make()
        ->buyer($customer)
        ->seller($seller)
        ->taxRate(18)
        ->addItem($item);

        return $invoice->stream();
    }


    public function download(Facture $record)
    {

        $order = Order::with('orderProducts')->find($record->order->id);

        
        $client = new Party([
            'name'          => 'Code Bar',
            'phone'         => '(229) 98 90 97 87',
            'custom_fields' => [
                'IFU'        => '12345678990',
            ],
        ]);
        
        $customer = new Party ([

            'name' => $record->order->customer->name,
            'custom_fields' => [
                'email' => $record->order->customer->name,
                'contact' => $record->order->customer->contact,
                'IFU' => $record->order->customer->ifu,
            ],

        ]);
        

        

        $items = [];

        foreach($record->order->orderProducts as $orderproduct){
            $items[] =  InvoiceItem::make($orderproduct->product->name)->pricePerUnit($orderproduct->product->unit_price)->quantity($orderproduct->quantity_detail);
        }

        $notes = [
            'Toutes vos commandes',
        ];
        $notes = implode("<br>", $notes);

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $record->date);

        $number_fac = (int) preg_replace('/\D/', '', $record->num_facture);

        $invoice = Invoice::make('FACTURE')
    ->series('NVE')
    // ability to include translated invoice status
    // in case it was paid
    ->status(__('invoices::invoice.paid'))
    ->sequence($number_fac)
    ->serialNumberFormat('{SERIES}{SEQUENCE}')
    ->seller($client)
    ->buyer($customer)
    ->date($date)
    ->dateFormat('d/m/Y')
    ->payUntilDays(1)
    ->currencySymbol('FCFA')
    ->currencyCode('CFA')
    ->currencyFormat('{VALUE} {SYMBOL}')
    ->currencyThousandsSeparator('.')
    ->currencyDecimalPoint(',')
    ->filename($client->name . ' ' . $customer->name)
    ->addItems($items)
    ->notes($notes)
    // You can additionally save generated invoice to configured disk
    ->save('public');

    $link = $invoice->url();
// Then send email to party with link

// And return invoice itself to browser or have a different view
return $invoice->stream();
    }
}


