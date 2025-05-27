<?php

namespace App\Http\Controllers;

use App\Exceptions\Code\InvalidCodeException;
use App\Http\Requests\CodeRequest;
use App\Models\CodeDownloadLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CodeController extends Controller
{
    /**
     * @throws InvalidCodeException
     */
    public function checkCode(CodeRequest $request): BinaryFileResponse|StreamedResponse
    {
        $adminEmail = Config::get('code.admin_email');
        $code = $request->string('code')->value();
        $configCode = Config::get('code.code');
        $releaseDate = Carbon::parse('2025-05-27 13:00:00');

        if ($code !== $configCode) {
            throw new InvalidCodeException;
        }

        if ($request->user()->email === $adminEmail) {
            return $this->downloadEasterEgg($request);
        }

        if (Carbon::now()->isBefore($releaseDate)) {
            throw new InvalidCodeException;
        }

        return $this->downloadEasterEgg($request);
    }

    private function downloadEasterEgg(CodeRequest $request): BinaryFileResponse|StreamedResponse
    {
        $filePath = Config::get('code.file_path');

        $codeLog = new CodeDownloadLog;
        $codeLog->email = $request->user()->email;
        $codeLog->ip_address = $request->ip();

        $codeLog->save();

        return Storage::disk('easter_egg')->download($filePath);
    }
}
