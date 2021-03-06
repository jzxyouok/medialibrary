<?php

namespace CipeMotion\Medialibrary\Generators;

use CipeMotion\Medialibrary\FileTypes;
use CipeMotion\Medialibrary\Entities\File;
use CipeMotion\Medialibrary\Entities\Transformation;

class AzureUrlGenerator implements IUrlGenerator
{
    /**
     * The config.
     *
     * @var array
     */
    protected $config;

    /**
     * Instantiate the URL generator.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get a URL to the resource.
     *
     * @param \CipeMotion\Medialibrary\Entities\File                $file
     * @param \CipeMotion\Medialibrary\Entities\Transformation|null $tranformation
     * @param bool                                                  $fullPreview
     * @param bool                                                  $download
     *
     * @return string
     * @throws \Exception
     */
    public function getUrlForTransformation(
        File $file,
        Transformation $tranformation = null,
        $fullPreview = false,
        $download = false
    ) {
        if ($download) {
            throw new \Exception(
                'The Azure url generator does not support forced download urls.'
            );
        }

        $account   = array_get($this->config, 'account');
        $container = array_get($this->config, 'container');

        if (empty($tranformation)) {
            $tranformation = 'upload';
            $extension     = $file->extension;

            if ($fullPreview && $file->type !== FileTypes::TYPE_IMAGE) {
                $tranformation = 'preview';
                $extension     = 'jpg';
            }
        } else {
            $tranformation = $tranformation->name;
            $extension     = $tranformation->extension;
        }

        return "https://{$account}.blob.core.windows.net/{$container}/{$file->id}/{$tranformation}.{$extension}";
    }
}
