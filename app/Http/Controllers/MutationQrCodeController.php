<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MutationQrCodeController extends Controller
{
    public function image(Request $request): Response
    {
        $room = $this->resolveRoom($request);
        $targetUrl = $this->targetUrl($room);

        $result = (new Builder(
            writer: new PngWriter,
            writerOptions: [],
            validateResult: false,
            data: $targetUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 512,
            margin: 24,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        ))->build();

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => sprintf('%s; filename="%s"', $disposition, $this->filename($room)),
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function print(Request $request): View
    {
        $room = $this->resolveRoom($request);

        return view('mutations.qr-print', [
            'room' => $room,
            'title' => $room ? "QR Mutasi {$room->name}" : 'QR Input Mutasi',
            'targetUrl' => $this->targetUrl($room),
            'imageUrl' => route('mutations.qr.image', array_filter([
                'room' => $room?->id,
            ])),
        ]);
    }

    private function resolveRoom(Request $request): ?Room
    {
        if (! $request->query->has('room')) {
            return null;
        }

        $roomId = filter_var($request->query('room'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        abort_if($roomId === false, 404);

        return Room::findOrFail($roomId);
    }

    private function targetUrl(?Room $room): string
    {
        return route('mutations.create', array_filter([
            'room' => $room?->id,
        ]));
    }

    private function filename(?Room $room): string
    {
        return $room
            ? "qr-mutasi-kamar-{$room->id}.png"
            : 'qr-input-mutasi.png';
    }
}
