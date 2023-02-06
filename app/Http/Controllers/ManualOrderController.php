<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use Validator;
use App\Model\Tax;
use Carbon\Carbon;
use App\Model\Order;
use App\Model\State;
use App\Model\Country;
use App\Model\Line_item;
use App\Http\Traits\CsvTrait;
use App\Services\ViewService;
use App\Http\Requests\getTaxRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use App\Http\Requests\CsvFormatRequest;
use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Support\Facades\Response;

class ManualOrderController extends Controller
{
    protected OrderRepository $orderRepo;
    protected ViewService $viewService;
    use CsvTrait;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
        $view = 'pages.manual_order';
        $this->viewService = new ViewService($view);
    }

    public function index()
    {
        $countries = Country::where('status', 1)
            ->orderBy('name->value', 'desc')
            ->get();

        $collectiondata = json_decode($countries, true);

        $collection = collect($collectiondata);

        $sorted = $collection->sortBy(function ($product, $key) {
            return $product['name']['en'];
        });

        $sortedcountries = [];

        $index = 0;
        foreach ($sorted as $sort) {
            $sortedcountries[$index] = $sort;

            $index++;
        }

        //dd($countries);
        if (env('MANUAL_ORDER_FINISHED')) {
            return view('pages.manual_order')->with('countries', $sortedcountries);
        }
        return view('pages.coming_soon');
    }

    public function place_order(PlaceOrderRequest $request)
    {
        //DB::beginTransaction();
        try {
            $user = $this->getUserInfo($request->userEmail);
            $order = $this->storeOrder($request);
            $payload = $this->formatOrder($request->all(), $order);
            if ($payload != false) {
                $response = $this->orderRepo->placeManualOrder($user, $payload);
                return response()->json($response);
            }
            //DB::commit();
        } catch (\Exception $e) {
            //DB::rollback();
            return response()->json($e);
        }
        return response()->json(true);
    }

    public function getUserInfo($email = '')
    {
        //this field will need to change to accommodate the function call in $this->placeOrder(...)
        //the new field will be part of the new table before address information
        return $this->orderRepo->details($email);
    }

    public function getProductPrice($isbn)
    {
        return $this->orderRepo->price($isbn);
    }

    public function getTax(getTaxRequest $request)
    {
        $tax = Tax::where([
            'cat_type' => $request->category_type,
            'country_id' => $request->bill_country,
            'city_id' => $request->bill_city,
        ])->first();
        if (isset($tax)) {
            return response()->json($tax);
        }

        $tax = Tax::where(['cat_type' => $request->category_type, 'country_id' => $request->bill_country])
            ->select('amount')
            ->first();
        if (isset($tax)) {
            return response()->json($tax);
        }
        $tax = 0;
        return response()->json($tax);
    }

    private function formatOrder($data, $order)
    {
        $country = Country::find($data['bill_country']);
        $stateCode = 'XX';
        $shipingstype = 'PREMIUM';
        $order_status = 'complete';
        $lineItems = [];
        if (isset($data['bill_state'])) {
            $state = State::find($data['bill_state']);
            $stateCode = $state->code;
        }
        for ($list = 0; $list < count($order['productLists']); $list++) {
            if ($order['productLists'][$list]->selected_product_type == 'ebook') {
                $status = 'complete';
            } else {
                $status = 'processing';
                $order_status = 'processing';
            }
            $lineItems[$list] = [
                'productId' => $order['productLists'][$list]->isbn,
                'primaryProductId' => $order['productLists'][$list]->isbn,
                'name' => $order['productLists'][$list]->title,
                'quantity' => $order['productLists'][$list]->quantity,
                'pricePaid' => $order['productLists'][$list]->total_price,
                'discount' => 0,
                'tax' => 0,
                'preOrder' => $order['productLists'][$list]->released == 'Coming Soon' ? true : false,
                'shippingTrackingId' => 'INV_' . $order['order']['invoiceNumber'],
                'productType' => $order['productLists'][$list]->selected_product_type,
                'status' => $status,
                'subscriptionName' => ' ',
                'listPrice' => $order['productLists'][$list]->price,
                'taxRate' => 0,
                'bookBack' => 'PB',
            ];
        }

        try {
            if ($data['billAsShip'] == '1') {
                $shippingAddress = [
                    'contactName' => $data['userFname'] . ' ' . $data['userLname'],
                    'line1' => $data['bill_line1'],
                    'line2' => $data['bill_line2'],
                    'city' => $data['bill_city'],
                    'state' => isset($data['bill_state']) ? $data['bill_state'] : '',
                    'postalCode' => $data['bill_postalCode'],
                    'country' => trim($country['code_alpha3']),
                    'stateCode' => trim($stateCode),
                    'telephone' => $data['bill_telephone'],
                ];
            } else {
                $country = Country::find($data['ship_country']);
                if (isset($data['ship_state'])) {
                    $state = State::find($data['ship_state']);
                    $shipstateCode = $state->code;
                }

                $shippingAddress = [
                    'contactName' => $data['userFname'] . ' ' . $data['userLname'],
                    'line1' => $data['ship_line1'],
                    'line2' => $data['ship_line2'],
                    'city' => $data['ship_city'],
                    'state' => isset($data['ship_state']) ? $data['ship_state'] : '',
                    'postalCode' => $data['ship_postalCode'],
                    'country' => trim($country['code_alpha3']),
                    'stateCode' => trim($shipstateCode),
                    'telephone' => $data['ship_telephone'],
                ];
            }
            $discounts = [
                'discountCode' => 'none',
                'discountValue' => $data['Discount'],
            ];
            if (trim($country['code_alpha3']) == 'USA') {
                $shipingstype = 'DHL4PRI';
            }

            return [
                'orderRef' => $order['order']['order_ref'],
                'originalOrderId' => md5($order['order']['id']),
                'currency' => $data['userCurrency'],
                'pricePaid' => $order['order']['price_paid'],
                'transactionId' => '0000',
                'orderDate' => $order['order']['created_at'],
                'shippingCost' => $data['Shipping'],
                'guest' => false,
                'notes' => ' ',
                'orderSource' => ' ',
                'status' => $order_status,
                'invoiceNumber' => 'INV_' . $order['order']['invoiceNumber'],
                'vatId' => 'VAT_' . $order['order']['userVat'],
                'orderType' => $data['product_type'],
                'discount' => $discounts,
                'shippingType' => $shipingstype,
                'cancelBackOrderDate' => null,
                'printingHouse' => $order['order']['printingHouse'],
                'billingAddress' => [
                    'contactName' => $data['userFname'] . ' ' . $data['userLname'],
                    'line1' => $data['bill_line1'],
                    'line2' => $data['bill_line2'],
                    'city' => $data['bill_city'],
                    'state' => isset($data['bill_state']) ? $data['bill_state'] : '',
                    'postalCode' => $data['bill_postalCode'],
                    'country' => trim($country['code_alpha3']),
                    'stateCode' => trim($stateCode),
                    'telephone' => $data['bill_telephone'],
                ],
                'shippingAddress' => $shippingAddress,
                'lineItems' => $lineItems,
            ];
        } catch (Exception $ex) {
            return false;
        }
    }

    public function storeOrder($request)
    {
        $response = [];
        $invoiceNumber = 300000;
        $order_id = 1;
        $order = Order::orderBy('id', 'desc')->first();
        if (isset($order)) {
            $order_id = $order->id + 1;
            $invoiceNumber = $order->invoiceNumber + 1;
        }

        $userid = Auth::user()->userId;
        $created_by = Auth::user()->userId;
        $customerGroup = 1;
        //   $customerGroup= $request->customerGroup
        $order = new Order();
        $order->order_ref = 'T-' . Carbon::now()->format('y') . '-000000-' . $order_id;
        $order->user_id = $userid;
        $order->created_by = $created_by;
        $order->invoiceNumber = $invoiceNumber;
        $order->printingHouse = 'PCKTUK';
        $order->customerGroup = $customerGroup;
        $order->price_paid = $request->grandtotal;
        $order->save();

        $productLists = json_decode($request->productList);
        for ($item = 0; $item < count($productLists); $item++) {
            $lineitem = new Line_item();
            $lineitem->order_id = $order->id;
            $lineitem->product_id = $productLists[$item]->isbn;
            $lineitem->primary_product_id = $productLists[$item]->isbn;
            $lineitem->quantity = $productLists[$item]->quantity;
            $lineitem->product_type = $request->product_type;
            if ($request->product_type == 'ebook') {
                $lineitem->status = 'complete';
            } else {
                $lineitem->status = 'processing';
            }
            // $lineitem->pre_order = $request->pre_order;
            // $lineitem->status = $request->status;
            $lineitem->product_type = $productLists[$item]->selected_product_type;
            $lineitem->pre_order = $productLists[$item]->available == 'false' ? true : false;
            // $lineitem->line_item_id = $request->line_item_id;
            $lineitem->price_paid = $productLists[$item]->total_price;
            $lineitem->save();
        }
        $response = [
            'order' => $order,
            'productLists' => $productLists,
        ];

        return $response;
    }

    public function getProductAvailability($isbn)
    {
        return $this->orderRepo->getProductAvailability($isbn);
    }

    public function product_csv(CsvFormatRequest $request)
    {
        $row = $this->csvToArray($request->productCsv);

        return response()->json($row);
    }

    public function getDownload()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file = public_path() . '/sample_bulk_product.csv';

        $headers = ['Content-Type: application/csv'];

        return Response::download($file, 'SampleBulkProduct.csv', $headers);
    }
}
