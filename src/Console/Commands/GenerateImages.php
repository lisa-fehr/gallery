<?php

namespace LisaFehr\Gallery\Console\Commands;

use LisaFehr\Gallery\Models\UberGallery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateImages extends Command
{
    const MISSING_IMAGE_COLOR = '#ffb52e';

    const THUMBNAIL_WIDTH = 125;
    const THUMBNAIL_HEIGHT = 175;
    const THUMBNAIL_QUALITY = 75;

    const MAX_IMAGE_WIDTH = 800;
    const MAX_IMAGE_HEIGHT = 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate a gallery from a folder and a database';

    private $imageDestination = null;
    private $thumbnailDestination = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $originalImages = Storage::disk("gallery-originals")->allFiles();

        foreach ($originalImages as $originalImage) {
            $path = pathinfo($originalImage, PATHINFO_DIRNAME);
            $this->imageDestination = Storage::disk('gallery')->path($path);
            $this->thumbnailDestination = $this->imageDestination . '/t';

            if (! File::exists($this->thumbnailDestination)) {
                $this->info('Created directories for: ' . $path);

                File::makeDirectory($this->thumbnailDestination, 0755, true, true);
            }

            $this->resizeImage($originalImage);
        }

        $this->createMissingThumbnail();
        $this->createMissingImage();

        return 0;
    }

    /**
     * @param $originalImage
     */
    private function resizeImage($originalImage)
    {
        $originalPath = Storage::disk('gallery-originals')->path($originalImage);

        $name = pathinfo($originalImage, PATHINFO_FILENAME);
        $gallery = UberGallery::firstWhere('img', $name);

        if ($gallery) {
            $thumbPath = $this->thumbnailDestination . '/' . $gallery->thumb;
            $imagePath = $this->imageDestination . '/' . $gallery->img . '.' . $gallery->type;

            $thumbExtension = pathinfo($gallery->thumb, PATHINFO_EXTENSION);

            Image::make($originalPath)
                ->resize(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT)
                ->sharpen(5)
                ->limitColors(25, '#ff9900')
                ->save($thumbPath, self::THUMBNAIL_QUALITY, $thumbExtension);

            $image = Image::make($originalPath);
            [$width, $height] = $this->calculateWidthHeight($image);
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imagePath);

            $this->info('Created an image and thumbnail: ' . $originalImage);
        } else {
            $this->info('Could not find: ' . $originalImage);
        }
    }

    /**
     * @param \Intervention\Image\Image $image
     * @return array
     */
    private function calculateWidthHeight(\Intervention\Image\Image $image) : array
    {
        $width = self::MAX_IMAGE_WIDTH;
        $height = self::MAX_IMAGE_HEIGHT;

        if ($image->height() < $height || $image->width() < $width) {
            return [$image->width(), $image->height()];
        }
        if ($image->height() > $image->width()) {
            return [null, $height];
        }
        return [$width, null];
    }

    private function createMissingThumbnail()
    {
        $img = Image::canvas(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT, '#ffa500');
        $img
        ->line(0, 0, self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT, function ($draw) {
            $draw->color(self::MISSING_IMAGE_COLOR);
        })
        ->line(self::THUMBNAIL_WIDTH, 0, 0, self::THUMBNAIL_HEIGHT, function ($draw) {
            $draw->color(self::MISSING_IMAGE_COLOR);
        })
        ->text('Missing Image', round(self::THUMBNAIL_WIDTH / 2 - 34), 20, function ($font) {
            $font->color('#000');
        });
        $img->save(Storage::disk('gallery')->path('missing-thumbnail.gif'), 90, 'gif');
        $this->info('Created missing thumbnail: ' . Storage::disk('gallery')->path('missing-thumbnail.gif'));
    }

    private function createMissingImage()
    {
        $img = Image::canvas(self::MAX_IMAGE_WIDTH, self::MAX_IMAGE_HEIGHT, '#ffa500');
        $img
            ->line(0, 0, self::MAX_IMAGE_WIDTH, self::MAX_IMAGE_HEIGHT, function ($draw) {
                $draw->color(self::MISSING_IMAGE_COLOR);
            })
            ->line(self::MAX_IMAGE_WIDTH, 0, 0, self::MAX_IMAGE_HEIGHT, function ($draw) {
                $draw->color(self::MISSING_IMAGE_COLOR);
            })
            ->text('Missing Image', round(self::MAX_IMAGE_WIDTH / 2 - 34), 20, function ($font) {
                $font->color('#000');
            });
        $img->save(Storage::disk('gallery')->path('missing-image.gif'), 90, 'gif');
        $this->info('Created missing image: ' . Storage::disk('gallery')->path('missing-image.gif'));
    }
}
