<?php

namespace App\Http\Controllers;

use App\Http\Services\DataManipulationService;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PurchaseOrderController extends Controller
{
    public function __construct(protected DataManipulationService $dataManipulationService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrders = $this->dataManipulationService->filterSortAndPaginate($purchaseOrder, $request);
        return response()->json($purchaseOrders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //DOES NOT APPLY TO JSON API
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|in:Open,Paid,Canceled',
            'quantity' => 'required|integer|min:1|max:20',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);

        //If validation fails, returns JSON with error
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $purchaseOrder = PurchaseOrder::query()->create($request->all());

        return response()->json($purchaseOrder, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //DOES NOT APPLY TO JSON API
    }

    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $validationRules = [
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|in:Open,Paid,Canceled',
            'quantity' => 'required|integer|min:1|max:20',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $purchaseOrder->update($validator->validated());

        return response()->json($purchaseOrder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->delete();
        return response()->json(null, 204);
    }
}
