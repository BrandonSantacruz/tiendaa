<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    /**
     * Directorio para almacenar imágenes
     */
    const PRODUCT_PATH = 'products';
    const CATEGORY_PATH = 'categories';
    const VARIANT_PATH = 'variants';

    /**
     * Subir imagen de producto
     */
    public function uploadProductImage(UploadedFile $file): string
    {
        return $this->uploadImage($file, self::PRODUCT_PATH);
    }

    /**
     * Subir imagen de categoría
     */
    public function uploadCategoryImage(UploadedFile $file): string
    {
        return $this->uploadImage($file, self::CATEGORY_PATH);
    }

    /**
     * Subir imagen de variante
     */
    public function uploadVariantImage(UploadedFile $file): string
    {
        return $this->uploadImage($file, self::VARIANT_PATH);
    }

    /**
     * Eliminar imagen
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Obtener URL pública de imagen
     */
    public function getImageUrl(string $path): string
    {
        return asset('storage/' . $path);
    }

    /**
     * Procesar y redimensionar imagen (opcional)
     */
    private function uploadImage(UploadedFile $file, string $directory): string
    {
        // Generar nombre único
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Almacenar en disco público
        $path = Storage::disk('public')->putFileAs(
            $directory,
            $file,
            $filename
        );

        return $path;
    }

    /**
     * Validar que sea una imagen
     */
    public function isValidImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        return in_array($file->getMimeType(), $allowedMimes);
    }

    /**
     * Obtener dimensiones de imagen
     */
    public function getImageDimensions(UploadedFile $file): array
    {
        $size = getimagesize($file->getPathname());
        return [
            'width' => $size[0] ?? 0,
            'height' => $size[1] ?? 0,
        ];
    }
}
