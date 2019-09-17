<?php
namespace App\Services;

use Exception;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\Exception\GoogleException;

/*
 * @package GoogleStorage
 */

class GoogleStorage
{
    const GCS_BASE_URL = 'https://storage.googleapis.com/';

    /**
     * Gets the Google cloud bucket instance
     *
     * @return object
     */
    public static function getBucketInstance()
    {
        $storageServiceKeys = __DIR__ . '/image-upload-service-credentials.json';
        $googleStorageBucketName = env('GOOGLE_STORAGE_BUCKET');
        $storage = new StorageClient([ 'keyFilePath' => $storageServiceKeys ]);
        $bucket = $storage->bucket($googleStorageBucketName);
        return $bucket;
    }

    /**
     * Upload an image to the Google cloud storage bucket
     *
     * @param $imageName
     * @param string $image
     * @return string - image URL
     */
    public static function uploadImage($imageName, $image)
    {
        $bucket = self::getBucketInstance();
        try {
            $bucket->upload(file_get_contents($image), [
        'name' => $imageName,
        'predefinedAcl' => 'PUBLICREAD'
      ]);
            $imageUrl = self::GCS_BASE_URL . env('GOOGLE_STORAGE_BUCKET') . '/' . $imageName;
            return $imageUrl;
        } catch (GoogleException $e) {
            throw new Exception('There was an issue uploading image');
        }
    }


    /**
     * Edits an image on the Google cloud storage bucket
     *
     * @param $imageName
     */
    public static function deleteImage($imageName)
    {
        $bucket = self::getBucketInstance();
        $object = $bucket->object($imageName);
        $object->delete();
    }
}
