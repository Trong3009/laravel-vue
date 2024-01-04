<?php
namespace App\Traits;
use Illuminate\Support\Facades\File;

trait uploadFIle {
    /**
     * @throws \Exception
     */
    public function uploadFileTraits ($file): string
    {
        list($type, $data) = explode(';', $file);
        list(, $data) = explode(',', $data);
        $extension = explode('/', $type)[1];
        $avatarFileName = 'avatar_' . time() . '.' . $extension;

        $directoryPath = public_path('upload/avatars');

        if (!File::exists($directoryPath)) {
            if (!File::makeDirectory($directoryPath, 0777, true, true)) {
                throw new \Exception('Có lỗi xảy ra trong quá trình upload file');
            }
        }
        $avatarPath = $directoryPath . '/' . $avatarFileName;
        file_put_contents($avatarPath, base64_decode($data));

        return 'upload/avatars/'.$avatarFileName;
    }
}
