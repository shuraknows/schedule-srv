<?php

declare(strict_types=1);

namespace App\UserInterface\Http;

use App\Application\Schedule\Dto\CreateSchedule;
use App\Application\Schedule\Dto\UpdateEuriborRate;
use App\Application\Schedule\Exception\ScheduleNotFound;
use App\Application\Schedule\Service\CreateScheduleService;
use App\Application\Schedule\Service\UpdateEuriborRateService;
use App\Infrastructure\Laravel\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    public function create(Request $request, CreateScheduleService $service): JsonResponse
    {
        try {
            $command = new CreateSchedule(
                $request->get('amount'),
                $request->get('term'),
                $request->get('interestRate'),
                $request->get('euriborRate'),
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $viewScheduleDto = $service->execute($command);

            return response()->json($viewScheduleDto, Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateEuriborRate(Request $request, string $scheduleId, UpdateEuriborRateService $service): JsonResponse
    {
        try {
            $command = new UpdateEuriborRate(
                $scheduleId,
                $request->get('segment'),
                $request->get('euriborRate'),
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $viewScheduleDto = $service->execute($command);

            return response()->json($viewScheduleDto, Response::HTTP_OK);
        } catch (ScheduleNotFound $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
