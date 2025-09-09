<?php

namespace Wiz\Helper\Controllers;

use App\Http\Controllers\Controller;
use App\Models\System\ZiFileModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function full($file): void
    {
        if (request('x')) {
            dump($file);
        }
        $this->thumb($file, 0);
    }

    public function thumb($file, $width = 480): void
    {


        try {
            $fileContent = file_get_contents(Storage::disk('r2_share')->url($file));
        } catch (\Exception $e) {
            throw new \Exception("Unable to read file");
            // Nếu không lấy được nội dung file từ R2, redirect sang URL dự phòng
          /*  $fallbackUrl = Storage::disk('r2_share')->url($file);
            header("Location: {$fallbackUrl}", false, 302);
            exit;*/
        }

        if (!$fileContent) {
            throw new \Exception("Unable to read file");
        }

        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $mimeType  = match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream', // MIME mặc định cho tệp không xác định
        };
        $isImage   = match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => null
        };

        if ($isImage) {
            if ($width) {
                $image = ImageManager::gd()->read($fileContent)->scale($width);
            } else {
                $image = ImageManager::gd()->read($fileContent)->scale();
            }

            $fileContent = (string)$image->encode(); // Chuyển ảnh thành chuỗi
        }
        $pathSave = str_replace('storage/', '', request()->path());
        Storage::disk('public')->put($pathSave, $fileContent);

        // Trả về file gốc
        header("Content-Type: {$mimeType}");
        echo $fileContent;
    }

    public function medium($file): void
    {
        $this->thumb($file, 680);
    }

    public function thumb_as_path($file, $extension): void
    {
        $path = $file . '.' . $extension;
        /**
         * @var ZiFileModel $file
         */
        $file         = ZiFileModel::wherePath($path)->first();
        $imageManager = ImageManager::gd()->read(Storage::disk($file->disk)->get($path));
        //$encoded      = $imageManager->scaleDown(256)->toPng();
        $encoded = $imageManager->cover(256, 256)->toPng();
        Storage::disk('thumb')->put($path, $encoded);
        header("Content-Type: image/png");
        echo $encoded;
    }

    /**
     * preview file doc, docx,xls,xlsx with microsoft embed or google sheet
     * stream view without original
     * $params:
     * sid : sid of file
     * model: base64encode json {model,id}
     * @url: file/preview/?sid=xxxx&model=xxxxxooo
     */
    public function private_preview($path): void
    {
        $file          = null;
        $validateRules = [
            't'  => ['required', 'string', function ($attr, $value, $fail) {
                try {
                    if (Carbon::parse(date('Y-m-d H:i:s', $value))->lte(now()->subHours(6))) {
                        return $fail("Expired");
                    }
                } catch (\Exception $exception) {
                    return $fail($exception->getMessage());
                }
            }],
            '_t' => ['required', 'string', function ($attr, $value, $fail) use (&$file, $path) {
                $file = ZiFileModel::wherePath($path)->first();
                if (empty($file->id)) {
                    return $fail('Not found');
                }
                $checksum = md5(request('t') . $file->id);
                if ($checksum !== $value) {
                    return $fail('Checksum error!');
                }
            }],
        ];

        $validator = Validator::make(request()->all(), $validateRules);
        if ($validator->fails()) {
            $contentImage = file_get_contents(public_path('img/placeholder.png'));
        } else {
            $contentImage = Storage::disk($file->disk)->get($path);
        }
        header("Content-Type: image/png");
        echo $contentImage;
    }

    public function download()
    {
        /**
         * @var ZiFileModel $file
         */
        $id        = ZiFileModel::getIdFromSSID(request('ssid'));
        $file      = ZiFileModel::find($id);
        $file_path = Storage::disk($file->disk)->path($file->path);
        if (file_exists($file_path)) {
            // Set headers to force a download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            // Read the file and output it to the browser
            readfile($file_path);
            $model = json_decode(base64_decode(request('model')));
            if (!empty($model->model) && !empty($model->id)) {
                try {
                    $object = app($model->model)->find($model->id);
                    if (method_exists($object, 'updateDownloadCount')) {
                        $object->updateDownloadCount();
                    }
                } catch (\Exception $exception) {

                }
            }
            exit;
        } else {
            header('HTTP/1.1 404 Not Found');
            echo 'File Not Found';
        }
    }
}
