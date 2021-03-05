<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyAccessExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyDescriptionExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInfoCacheExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInitializableExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyReadInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyWriteInfoExtractorInterface;
use Symfony\Component\PropertyInfo\TargetClassResolver;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('property_info', PropertyInfoExtractor::class)
            ->args([[], [], [], [], []])

        ->alias(PropertyAccessExtractorInterface::class, 'property_info')
        ->alias(PropertyDescriptionExtractorInterface::class, 'property_info')
        ->alias(PropertyInfoExtractorInterface::class, 'property_info')
        ->alias(PropertyTypeExtractorInterface::class, 'property_info')
        ->alias(PropertyListExtractorInterface::class, 'property_info')
        ->alias(PropertyInitializableExtractorInterface::class, 'property_info')

        ->set('property_info.cache', PropertyInfoCacheExtractor::class)
            ->decorate('property_info')
            ->args([service('property_info.cache.inner'), service('cache.property_info')])

        // Extractor
        ->set('property_info.reflection_extractor', ReflectionExtractor::class)
            ->args([
                null,
                null,
                null,
                true,
                ReflectionExtractor::ALLOW_PUBLIC,
                null,
                ReflectionExtractor::ALLOW_MAGIC_GET | ReflectionExtractor::ALLOW_MAGIC_SET,
                service('property_info.target_class_resolver'),
            ])
            ->arg(7, service('property_info.target_class_resolver'))
            ->tag('property_info.list_extractor', ['priority' => -1000])
            ->tag('property_info.type_extractor', ['priority' => -1002])
            ->tag('property_info.access_extractor', ['priority' => -1000])
            ->tag('property_info.initializable_extractor', ['priority' => -1000])

        ->alias(PropertyReadInfoExtractorInterface::class, 'property_info.reflection_extractor')
        ->alias(PropertyWriteInfoExtractorInterface::class, 'property_info.reflection_extractor')

        ->set('property_info.target_class_resolver', TargetClassResolver::class)
    ;
};
