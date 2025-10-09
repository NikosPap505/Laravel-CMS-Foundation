<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Integration\IntegrationManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IntegrationController extends Controller
{
    protected IntegrationManager $integrationManager;

    public function __construct(IntegrationManager $integrationManager)
    {
        $this->integrationManager = $integrationManager;
    }

    /**
     * Display the integration dashboard
     */
    public function index()
    {
        $data = $this->integrationManager->getDashboardData();

        return view('admin.integrations.index', compact('data'));
    }

    /**
     * Show integration details
     */
    public function show(string $integration)
    {
        $integrationInstance = $this->integrationManager->getIntegration($integration);

        if (!$integrationInstance) {
            abort(404, 'Integration not found');
        }

        $dbIntegration = \App\Models\Integration::where('name', $integration)->first();

        return view('admin.integrations.show', [
            'integration' => $integrationInstance,
            'dbIntegration' => $dbIntegration
        ]);
    }

    /**
     * Connect to an integration
     */
    public function connect(Request $request, string $integration): JsonResponse
    {
        $request->validate([
            'config' => 'required|array'
        ]);

        try {
            $result = $this->integrationManager->connect($integration, $request->input('config'));

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Disconnect from an integration
     */
    public function disconnect(string $integration): JsonResponse
    {
        try {
            $result = $this->integrationManager->disconnect($integration);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Test integration connection
     */
    public function test(Request $request, string $integration): JsonResponse
    {
        $config = $request->input('config', []);

        try {
            $result = $this->integrationManager->testConnection($integration, $config);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Sync data from integration
     */
    public function sync(Request $request, string $integration): JsonResponse
    {
        $options = $request->input('options', []);

        try {
            $result = $this->integrationManager->sync($integration, $options);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Execute simple integration operation
     */
    public function execute(Request $request, string $integration): JsonResponse
    {
        $request->validate([
            'operation' => 'required|string'
        ]);

        try {
            $integrationInstance = $this->integrationManager->getIntegration($integration);

            if (!$integrationInstance) {
                throw new \Exception('Integration not found');
            }

            // Only allow basic operations
            $allowedOperations = ['sync', 'test', 'disconnect'];
            $operation = $request->input('operation');

            if (!in_array($operation, $allowedOperations)) {
                throw new \Exception('Operation not allowed');
            }

            $result = $integrationInstance->executeOperation($operation);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get integration configuration form
     */
    public function config(string $integration)
    {
        $integrationInstance = $this->integrationManager->getIntegration($integration);

        if (!$integrationInstance) {
            abort(404, 'Integration not found');
        }

        return view('admin.integrations.config', [
            'integration' => $integrationInstance,
            'requiredFields' => $integrationInstance->getRequiredFields()
        ]);
    }

    /**
     * Get integration status
     */
    public function status(string $integration): JsonResponse
    {
        try {
            $integrationInstance = $this->integrationManager->getIntegration($integration);

            if (!$integrationInstance) {
                throw new \Exception('Integration not found');
            }

            $dbIntegration = \App\Models\Integration::where('name', $integration)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'connected' => $dbIntegration ? $dbIntegration->is_connected : false,
                    'status' => $dbIntegration ? $dbIntegration->status : 'disconnected',
                    'last_sync' => $dbIntegration ? $dbIntegration->last_sync : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
