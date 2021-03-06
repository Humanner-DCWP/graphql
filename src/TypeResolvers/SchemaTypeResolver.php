<?php

declare(strict_types=1);

namespace PoP\GraphQL\TypeResolvers;

use PoP\GraphQL\TypeDataLoaders\SchemaTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\GraphQL\TypeResolvers\AbstractIntrospectionTypeResolver;

class SchemaTypeResolver extends AbstractIntrospectionTypeResolver
{
    public const NAME = '__Schema';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Schema type, to implement the introspection fields', 'graphql');
    }

    public function getID($resultItem)
    {
        $schema = $resultItem;
        return $schema->getID();
    }

    public function getTypeDataLoaderClass(): string
    {
        return SchemaTypeDataLoader::class;
    }
}
