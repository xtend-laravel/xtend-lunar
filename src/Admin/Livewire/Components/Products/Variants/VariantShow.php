<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Products\Variants;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\FileUploadConfiguration;
use Livewire\TemporaryUploadedFile;
use Lunar\Hub\Http\Livewire\Components\Products\Variants\VariantShow as LunarVariantShow;
use Spatie\Activitylog\Facades\LogBatch;

class VariantShow extends LunarVariantShow
{
    public function updateImages()
    {
        DB::transaction(function () {
            LogBatch::startBatch();

            $owner = $this->variant->product;

            $imagesToSync = [];

            $variants = $owner->variants->load('images');

            foreach ($this->images as $key => $image) {
                $newImage = false;
                $file = null;
                $imageEdited = false;
                $previousMediaId = false;
                $previousMedia = null;

                // edited image
                if ($image['file'] ?? false && $image['file'] instanceof TemporaryUploadedFile) {
                    /** @var TemporaryUploadedFile $file */
                    $file = $image['file'];

                    if (isset($image['id'])) {
                        $previousMediaId = $image['id'];
                    }

                    unset($this->images[$key]['file']);

                    $imageEdited = true;
                }

                if (empty($image['id']) || $imageEdited) {
                    if (! $imageEdited) {
                        $file = TemporaryUploadedFile::createFromLivewire(
                            $image['filename']
                        );
                    }

                    // after editing few times the name will get longer and eventually failed to upload
                    $filename = Str::of($file->getFilename())
                        ->beforeLast('.')
                        ->substr(0, 128)
                        ->append('.', $file->getClientOriginalExtension());

                    if (FileUploadConfiguration::isUsingS3()) {
                        $media = $owner->addMediaFromDisk($file->getRealPath())
                            ->usingFileName($filename)
                            ->toMediaCollection('images');
                    } else {
                        $media = $owner->addMedia($file->getRealPath())
                            ->usingFileName($filename)
                            ->toMediaCollection('images');
                    }

                    activity()
                        ->performedOn($this->variant)
                        ->withProperties(['media' => $media->toArray()])
                        ->event('added_image')
                        ->useLog('lunar')
                        ->log('added_image');

                    // Add ID for future and processing now.
                    $this->images[$key]['id'] = $media->id;

                    // reset image thumbnail
                    if ($imageEdited) {
                        $this->images[$key]['thumbnail'] = $media->getFullUrl('medium');
                        $this->images[$key]['original'] = $media->getFullUrl();

                        // link other variants image to the new media
                        if ($previousMediaId) {
                            $variants->each(function ($variant) use ($previousMediaId, $media) {
                                if ($this->variant->id == $variant->id) {
                                    return;
                                }

                                $variantMedia = $variant->images->where('id', $previousMediaId)->first();
                                if ($variantMedia) {
                                    $variant->images()->attach($media, [
                                        'primary' => $variantMedia->pivot->primary,
                                    ]);
                                }
                            });

                            $previousMedia = $owner->media()->find($previousMediaId);
                            $previousMedia->delete();
                        }
                    }

                    $image['id'] = $media->id;

                    $newImage = true;
                } else {
                    $media = app(config('media-library.media_model'))::find($image['id']);
                }

                if ($newImage) {
                    if ($imageEdited) {
                        $media->setCustomProperty('caption', $previousMedia->getCustomProperty('caption', $image['caption']));
                        $media->setCustomProperty('primary', $previousMedia->getCustomProperty('primary', false));
                        $media->setCustomProperty('position', $previousMedia->getCustomProperty('position', $owner->media()->count() + 1));
                    } else {
                        $media->setCustomProperty('caption', $image['caption']);
                        $media->setCustomProperty('primary', false);
                        $media->setCustomProperty('position', $owner->media()->count() + 1);
                    }
                    $media->save();
                }

                $imagesToSync[$media->id] = [
                    'primary' => $image['primary'],
                    'position' => $image['position'],
                ];
            }

            $this->variant->images()->sync($imagesToSync);

            LogBatch::endBatch();
        });
    }
}
