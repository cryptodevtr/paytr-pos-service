<?php

namespace App\Http\Controllers;

use App\Contracts\PosSelectionServiceInterface;
use App\Http\Requests\PosSelectionRequest;
use App\Jobs\SyncPosRatesJob;
use Illuminate\Http\JsonResponse;

class PosController extends Controller
{
    private PosSelectionServiceInterface $selectionService;

    /**
     * @param PosSelectionServiceInterface $selectionService
     */
    public function __construct(PosSelectionServiceInterface $selectionService)
    {
        $this->selectionService = $selectionService;
    }

    /**
     * @param PosSelectionRequest $request
     * @return JsonResponse
     */
    public function selectBestPos(PosSelectionRequest $request): JsonResponse
    {
        try {
            $paymentDetails = $request->validated();

            $result = $this->selectionService->selectBestPos($paymentDetails);

            return response()->json([
                'success' => true,
                'data' => [
                    'filters' => [
                        'installment' => $paymentDetails['installment'],
                        'currency' => $paymentDetails['currency'],
                        'card_type' => $paymentDetails['card_type'],
                        'card_brand' => $paymentDetails['card_brand'] ?? null
                    ],
                    'selected_pos' => $result
                ]
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);

        } catch (\App\Exceptions\NoPosFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncRates(): JsonResponse
    {
        try {
            SyncPosRatesJob::dispatchSync();

            return response()->json([
                'success' => true,
                'message' => 'POS rates synced successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function triggerSync(): JsonResponse
    {
        SyncPosRatesJob::dispatch();

        return response()->json([
            'success' => true,
            'message' => 'POS rates sync job queued'
        ]);
    }
}
