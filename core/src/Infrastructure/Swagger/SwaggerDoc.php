<?php

namespace App\Infrastructure\Swagger;

use Nelmio\ApiDocBundle\Describer\ExternalDocDescriber;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

final class SwaggerDoc extends ExternalDocDescriber
{
    private const string DOCUMENTATION_FOLDER = '/Documentation';
    private const string SEARCH_YAML_FILES_PATTERN = '*.yaml';
    //
    private const array MERGE_ITEMS = ['paths', 'components'];
    private const string PATHS_DOC_ITEM = 'paths';
    private const string COMPONENTS_DOC_ITEM = 'components';

    public function __construct(string $area)
    {
        $docData = self::searchDocumentationFiles($area);
        parent::__construct($docData, true);
    }

    private static function searchDocumentationFiles(string $area): array
    {
        $docData = [
            self::PATHS_DOC_ITEM => [],
            self::COMPONENTS_DOC_ITEM => [],
        ];

        $directory = __DIR__. self::DOCUMENTATION_FOLDER;
        $finder = new Finder();
        $finder->name(self::SEARCH_YAML_FILES_PATTERN)->in($directory);
        $yamlArea = [];
        $yamlGeneric = [];
        foreach ($finder as $file) {
            $relativePath = $file->getRelativePath();
            if (empty($relativePath)) {
                $yamlGeneric[$file->getFilename()] = $file->getPathname();
            } else if ($area == $relativePath) {
                $yamlArea[$file->getFilename()] = $file->getPathname();
            }
        }
        ksort($yamlArea);
        $yamlList = array_merge($yamlArea, $yamlGeneric);
        foreach ($yamlList as $filePath) {
            $currentDoc = Yaml::parseFile($filePath);
            self::merge($docData, $currentDoc);
        }

        return $docData;
    }

    private static function merge(array &$docData, array $currentDoc): void
    {
        foreach (self::MERGE_ITEMS as $item) {
            if (!array_key_exists($item, $currentDoc)) {
                continue;
            }

            foreach ($currentDoc[$item] as $key => $value) {
                if ($item == self::COMPONENTS_DOC_ITEM) {
                    foreach ($value as $name => $component) {
                        $docData[$item][$key][$name] = $component;
                    }
                    break;
                }
                $docData[$item][$key] = $value;
            }
        }
    }
}
