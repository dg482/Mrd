<?php

namespace Dg482\Mrd\Files;

use Dg482\Mrd\Model;

/**
 * Class Storage
 * @package App\Models\Files
 */
class Storage extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    const OWNER_ID = 'owner_id';

    /** @var string */
    const OWNER_TYPE = 'owner_type';

    /** @var string */
    const PATH = 'path';

    /** @var string */
    const STORAGE = 'storage';

    /** @var string */
    const FILE = 'file';

    /** @var string */
    protected $table = 'file_storage';

    /** @var string[] */
    protected $fillable = [
        self::OWNER_ID, self::OWNER_TYPE, self::PATH,
        self::STORAGE, self::FILE,
    ];

    /**
     * @return array
     */
    public function getFileData(): array
    {
        return [
            'id' => $this->id,
            'uid' => $this->id,
            'url' => $this->{self::PATH},
            'status' => 'done',
            'name' => $this->{self::FILE},
        ];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return public_path($this->{self::FILE});
    }

    /**
     * @param  int  $id
     * @return bool
     * @throws \Exception
     */
    public static function deleteFile(int $id): bool
    {
        /** @var self $file */
        $file = self::where(['id' => $id])->first();

        if ($file) {
            if (unlink($file->getPath())) {
                $file->delete();
            } else {
                throw new \Exception('file not deleted');
            }
        }

        return false;
    }
}
